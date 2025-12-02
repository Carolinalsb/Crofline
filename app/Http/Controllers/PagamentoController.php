<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MercadoPago\Client\Preference\PreferenceClient;

class PagamentoController extends Controller
{
    /**
     * Recebe o POST do resumo, grava na tabela compra
     * e cria a preferência de pagamento no Mercado Pago.
     */
    public function pagar(Request $request)
    {
        // Validação básica do que vem da view resumo
        $request->validate([
            'id_usuario'                 => 'required|integer',
            'total'                      => 'required|numeric|min:0.01',
            'produtos'                   => 'required|array|min:1',
            'produtos.*.id_produto'      => 'required|integer',
            'produtos.*.tamanho'         => 'required|string',
            'produtos.*.cor'             => 'required|string',
            'produtos.*.quantidade'      => 'required|integer|min:1',
            'produtos.*.valor'           => 'required|numeric|min:0.01',
        ]);

        $idUsuario = $request->input('id_usuario');
        $produtos  = $request->input('produtos');

        // Código que identifica TODOS os itens da mesma compra
        $buyCode = strtoupper(Str::random(10));
        $now     = now();

        // 1) Grava tudo na tabela compra
        DB::beginTransaction();

        foreach ($produtos as $p) {
            DB::table('compras')->insert([
                'id_usuario'         => $idUsuario,
                'id_produto'         => $p['id_produto'],
                'paymentid'          => null,
                'tamanho'            => $p['tamanho'],
                'cor'                => $p['cor'],
                'quantidade'         => $p['quantidade'],
                'valor'              => $p['valor'],          // valor do registro (já * quantidade)
                'buyCode'            => $buyCode,
                'pago'               => 0,                    // começa como NÃO pago
                'tipo_pagamento'     => null,
                'status_pagamento'   => 'pending',
                'detalhes_pagamento' => null,
                'created_at'         => $now,
                'updated_at'         => $now,
            ]);
        }

        DB::commit();

        // 2) Cria preferência no Mercado Pago
        $client = new PreferenceClient();

        $mpItems = [];
        foreach ($produtos as $p) {
            $mpItems[] = [
                'title'      => 'Crofline',                     // título genérico
                'quantity'   => (int) $p['quantidade'],
                'unit_price' => (float) $p['valor'],            // valor do registro
            ];
        }

        $preference = $client->create([
            'items' => $mpItems,

            // Vamos usar o buyCode pra rastrear todos os itens dessa compra
            'external_reference' => $buyCode,

            'back_urls' => [
                'success' => route('pagamento.minhasCompras'),
                'failure' => route('pagamento.minhasCompras'),
                'pending' => route('pagamento.minhasCompras'),
            ],
            'auto_return'      => 'approved',

            // URL que o MP chama pra avisar status (notificação)
            'notification_url' => route('pagamento.checkoutResp'),
        ]);

        // Redireciona o cliente para o checkout do Mercado Pago
        return redirect($preference->init_point);
    }

    /**
     * Recebe o retorno/notificação do Mercado Pago
     * e atualiza a tabela compra com base no buyCode (external_reference).
     */
    public function checkoutResp(Request $request)
    {
        $buyCode = $request->input('external_reference');

        if (!$buyCode) {
            return response()->json(['error' => 'external_reference não informado'], 400);
        }

        // Campos que o MP costuma mandar
        $status        = $request->input('status');             // approved, pending, rejected...
        $statusDetail  = $request->input('status_detail');      // detalhes
        $paymentMethod = $request->input('payment_method_id');  // cartão, pix, etc
        $paymentId     = $request->input('payment_id') ?? $request->input('id');

        $pago = $status === 'approved' ? 1 : 0;

        // Atualiza TODOS os registros dessa compra (buyCode)
        DB::table('compras')
            ->where('buyCode', $buyCode)
            ->whereNull('paymentid') // só se ainda não tiver ID de pagamento
            ->update([
                'paymentid'          => $paymentId,
                'pago'               => $pago,
                'tipo_pagamento'     => $paymentMethod,
                'status_pagamento'   => $status,
                'detalhes_pagamento' => $statusDetail,
                'updated_at'         => now(),
            ]);

        // Se é notificação (POST/JSON), responde JSON
        if ($request->expectsJson() || $request->isMethod('post')) {
            return response()->json(['success' => true]);
        }

        // Se veio de redirect (back_urls), manda pra minhas compras
        return redirect()->route('pagamento.minhasCompras');
    }

    /**
     * Lista das compras do usuário (Pendentes, Pagas e Finalizadas).
     */
    public function minhasCompras(Request $request)
    {
        // Ajusta aqui qual chave de sessão você está usando pro usuário
        $idUsuario = session('id_usuario') ?? session('user_id');

        if (!$idUsuario) {
            return redirect('/')->with('error', 'Faça login para ver suas compras.');
        }

        $compras = DB::table('compras')
            ->join('produtos', 'compras.id_produto', '=', 'produtos.id')
            ->select(
                'compras.*',
                'produtos.titulo as produto_titulo',
                'produtos.imagem as produto_imagem'
            )
            ->where('compras.id_usuario', $idUsuario)
            ->orderByDesc('compras.created_at')
            ->get();

        // Agrupa por buyCode (uma compra = vários itens)
        $grouped = $compras->groupBy('buyCode');

        $pendentes   = [];
        $pagas       = [];
        $finalizadas = [];

        foreach ($grouped as $buyCode => $itens) {
            $first = $itens->first();

            $compra = [
                'buyCode'          => $buyCode,
                'created_at'       => $first->created_at,
                'status_pagamento' => $first->status_pagamento,
                'pago'             => $first->pago,
                'itens'            => $itens,
            ];

            if (!$compra['pago']) {
                $pendentes[] = $compra;
            } elseif ($compra['status_pagamento'] === 'finalizada') {
                // se algum dia você marcar como finalizada
                $finalizadas[] = $compra;
            } else {
                // aprovadas / pagas
                $pagas[] = $compra;
            }
        }

        return view('pagamento.minhasCompras', [
            'pendentes'   => $pendentes,
            'pagas'       => $pagas,
            'finalizadas' => $finalizadas,
        ]);
    }
}
