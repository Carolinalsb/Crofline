<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MercadoPago\SDK;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Common\RequestOptions;

class PagamentoController extends Controller
{
    public function __construct()
    {
        SDK::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));
    }

    public function checkout(Request $request)
    {
        $idUsuario = session('id_usuario') ?? session('user_id');

        if (!$idUsuario) {
            return back()->with('cart_error', 'Faça login para continuar.');
        }

        $request->validate([
            'id_usuario'                 => 'nullable|integer',
            'total'                      => 'required|numeric|min:0.01',
            'produtos'                   => 'required|array|min:1',
            'produtos.*.id_produto'      => 'required|integer',
            'produtos.*.tamanho'         => 'required|string',
            'produtos.*.cor'             => 'required|string',
            'produtos.*.quantidade'      => 'required|integer|min:1',
            'produtos.*.valor'           => 'required|numeric|min:0.01',
        ]);

        $payload = [
            'id_usuario' => $idUsuario,
            'total'      => (float) $request->input('total'),
            'produtos'   => $request->input('produtos'),
        ];

        session()->put('checkout_payload', $payload);

        return view('pagamento.pagamento', [
            'checkout'  => $payload,
            'userId'    => $idUsuario,
            'userName'  => session('user_name'),
            'userEmail' => session('user_email'),
        ]);
    }

    public function processarPix(Request $request)
    {
        $payload = session('checkout_payload');

        if (!$payload || empty($payload['produtos'])) {
            return redirect()->route('home.index')->with('cart_error', 'Sessão de checkout expirada.');
        }

        $request->validate([
            'payer_email'   => 'required|email',
            'payer_name'    => 'required|string|max:120',
            'doc_type'      => 'nullable|string|max:10',
            'doc_number'    => 'nullable|string|max:30',
        ]);

        $buyCode = strtoupper(Str::random(10));

        try {
            $client = new PaymentClient();
            $requestOptions = new RequestOptions();
            $requestOptions->setCustomHeaders([
                'X-Idempotency-Key: ' . (string) Str::uuid(),
            ]);

            $payment = $client->create([
                'transaction_amount' => (float) $payload['total'],
                'description'        => 'Crofline #' . $buyCode,
                'payment_method_id'  => 'pix',
                'external_reference' => $buyCode,
                'notification_url'   => route('pagamento.checkoutResp'),
                'payer' => [
                    'email'         => $request->payer_email,
                    'first_name'    => $request->payer_name,
                    'entity_type'   => 'individual',
                    'identification' => [
                        'type'   => $request->doc_type ?: 'CPF',
                        'number' => $request->doc_number ?: '00000000000',
                    ],
                ],
            ], $requestOptions);

            $paymentData = json_decode(json_encode($payment), true);

            $this->gravarCompra(
                $payload,
                $buyCode,
                $paymentData['id'] ?? null,
                'pix',
                $paymentData['status'] ?? 'pending',
                $paymentData['status_detail'] ?? null,
                (int) (($paymentData['status'] ?? '') === 'approved'),
                $paymentData['point_of_interaction']['transaction_data']['qr_code'] ?? null,
                $paymentData['point_of_interaction']['transaction_data']['qr_code_base64'] ?? null,
                null
            );

            session()->forget('checkout_payload');

            return redirect()
                ->route('pagamento.minhasCompras')
                ->with('success', 'Pix gerado com sucesso.');
        } catch (\Throwable $e) {
            $this->gravarCompra(
                $payload,
                $buyCode,
                null,
                'pix',
                'pending',
                $e->getMessage(),
                0,
                null,
                null,
                null
            );

            session()->forget('checkout_payload');

            return redirect()
                ->route('pagamento.minhasCompras')
                ->with('error', 'Não foi possível gerar o Pix agora. A compra ficou como pendente.');
        }
    }

    public function processarCartao(Request $request)
    {
        $payload = session('checkout_payload');

        if (!$payload || empty($payload['produtos'])) {
            return redirect()->route('home.index')->with('cart_error', 'Sessão de checkout expirada.');
        }

        $request->validate([
            'payer_email'             => 'required|email',
            'payment_type_choice'     => 'required|string|in:credito,debito',
            'token'                   => 'required|string',
            'payment_method_id'       => 'required|string',
            'issuer_id'               => 'nullable',
            'installments'            => 'nullable|integer|min:1',
            'identification_type'     => 'required|string',
            'identification_number'   => 'required|string',
        ]);

        $buyCode = strtoupper(Str::random(10));

        try {
            $client = new PaymentClient();
            $requestOptions = new RequestOptions();
            $requestOptions->setCustomHeaders([
                'X-Idempotency-Key: ' . (string) Str::uuid(),
            ]);

            $payment = $client->create([
                'transaction_amount' => (float) $payload['total'],
                'token'              => $request->token,
                'description'        => 'Crofline #' . $buyCode,
                'installments'       => (int) ($request->installments ?: 1),
                'payment_method_id'  => $request->payment_method_id,
                'issuer_id'          => $request->issuer_id ? (int) $request->issuer_id : null,
                'external_reference' => $buyCode,
                'notification_url'   => route('pagamento.checkoutResp'),
                'payer' => [
                    'email' => $request->payer_email,
                    'identification' => [
                        'type'   => $request->identification_type,
                        'number' => $request->identification_number,
                    ],
                ],
            ], $requestOptions);

            $paymentData = json_decode(json_encode($payment), true);

            $status = $paymentData['status'] ?? 'pending';
            $statusDetail = $paymentData['status_detail'] ?? null;
            $pago = $status === 'approved' ? 1 : 0;

            $this->gravarCompra(
                $payload,
                $buyCode,
                $paymentData['id'] ?? null,
                $request->payment_type_choice,
                $status,
                $statusDetail,
                $pago,
                null,
                null,
                $pago ? 'pago' : null
            );

            session()->forget('checkout_payload');

            return redirect()
                ->route('pagamento.minhasCompras')
                ->with(
                    $pago ? 'success' : 'error',
                    $pago
                        ? 'Pagamento processado com sucesso.'
                        : 'O pagamento não foi aprovado agora. A compra ficou pendente.'
                );
        } catch (\Throwable $e) {
            $this->gravarCompra(
                $payload,
                $buyCode,
                null,
                $request->payment_type_choice,
                'pending',
                $e->getMessage(),
                0,
                null,
                null,
                null
            );

            session()->forget('checkout_payload');

            return redirect()
                ->route('pagamento.minhasCompras')
                ->with('error', 'Falha no pagamento por cartão. A compra ficou pendente.');
        }
    }

    public function checkoutResp(Request $request)
    {
        $paymentId = $request->input('data.id') ?? $request->input('payment_id') ?? $request->input('id');

        if (!$paymentId) {
            return response()->json(['ok' => false], 400);
        }

        try {
            $client = new PaymentClient();
            $payment = $client->get((int) $paymentId);
            $paymentData = json_decode(json_encode($payment), true);

            $buyCode = $paymentData['external_reference'] ?? null;
            if (!$buyCode) {
                return response()->json(['ok' => false], 400);
            }

            $status = $paymentData['status'] ?? 'pending';
            $statusDetail = $paymentData['status_detail'] ?? null;
            $pago = $status === 'approved' ? 1 : 0;

            DB::table('compras')
                ->where('buyCode', $buyCode)
                ->update([
                    'paymentid'          => $paymentData['id'] ?? null,
                    'pago'               => $pago,
                    'status_pagamento'   => $status,
                    'detalhes_pagamento' => $statusDetail,
                    'updated_at'         => now(),
                    'status_entrega'     => $pago ? DB::raw("COALESCE(status_entrega, 'pago')") : DB::raw('status_entrega'),
                ]);

            return response()->json(['ok' => true]);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function minhasCompras(Request $request)
    {
        $idUsuario = session('id_usuario') ?? session('user_id');

        if (!$idUsuario) {
            return redirect('/')->with('error', 'Faça login para ver suas compras.');
        }

        $compras = DB::table('compras')
            ->join('produtos', 'compras.id_produto', '=', 'produtos.id')
            ->select(
                'compras.*',
                'produtos.titulo as produto_titulo',
                'produtos.descricao as produto_descricao',
                'produtos.detalhes as produto_detalhes'
            )
            ->where('compras.id_usuario', $idUsuario)
            ->orderByDesc('compras.created_at')
            ->get();

        $compras = $compras->map(function ($item) {
            $item->produto_imagem = null;

            $detalhes = json_decode($item->produto_detalhes ?? '[]', true);
            $detalhes = is_array($detalhes) ? $detalhes : [];

            foreach ($detalhes as $detalhe) {
                $cor = trim($detalhe['cor'] ?? '');
                $tamanho = trim($detalhe['tamanho'] ?? '');

                if (mb_strtolower($cor) === mb_strtolower(trim($item->cor)) && $tamanho === trim($item->tamanho)) {
                    $item->produto_imagem = $detalhe['imagens']['imagem1']
                        ?? $detalhe['imagens']['imagem2']
                        ?? $detalhe['imagens']['imagem3']
                        ?? $detalhe['imagens']['imagem4']
                        ?? null;
                    break;
                }
            }

            return $item;
        });

        $grouped = $compras->groupBy('buyCode');

        $pendentes = [];
        $pagas = [];
        $enviadas = [];
        $finalizadas = [];

        foreach ($grouped as $buyCode => $itens) {
            $first = $itens->first();

            $compra = [
                'buyCode'          => $buyCode,
                'created_at'       => $first->created_at,
                'status_pagamento' => $first->status_pagamento,
                'status_entrega'   => $first->status_entrega,
                'pago'             => $first->pago,
                'tipo_pagamento'   => $first->tipo_pagamento,
                'qr_code'          => $first->qr_code,
                'qr_code_base64'   => $first->qr_code_base64,
                'itens'            => $itens,
            ];

            if (in_array($first->status_entrega, ['finalizado', 'finalizada'])) {
                $finalizadas[] = $compra;
            } elseif (in_array($first->status_entrega, ['enviado', 'a_caminho'])) {
                $enviadas[] = $compra;
            } elseif ((int) $first->pago === 1) {
                $pagas[] = $compra;
            } else {
                $pendentes[] = $compra;
            }
        }

        return view('pagamento.minhasCompras', [
            'pendentes'   => $pendentes,
            'pagas'       => $pagas,
            'enviadas'    => $enviadas,
            'finalizadas' => $finalizadas,
        ]);
    }

    private function gravarCompra(
        array $payload,
        string $buyCode,
        $paymentId,
        ?string $tipoPagamento,
        ?string $statusPagamento,
        ?string $detalhesPagamento,
        int $pago,
        ?string $qrCode,
        ?string $qrCodeBase64,
        ?string $statusEntrega
    ): void {
        DB::beginTransaction();

        foreach ($payload['produtos'] as $p) {
            DB::table('compras')->insert([
                'id_usuario'         => $payload['id_usuario'],
                'id_produto'         => $p['id_produto'],
                'paymentid'          => $paymentId,
                'cor'                => $p['cor'],
                'tamanho'            => $p['tamanho'],
                'valor'              => $p['valor'],
                'qtd'                => $p['quantidade'],
                'buyCode'            => $buyCode,
                'pago'               => $pago,
                'tipo_pagamento'     => $tipoPagamento,
                'status_pagamento'   => $statusPagamento,
                'status_entrega'     => $statusEntrega,
                'detalhes_pagamento' => $detalhesPagamento,
                'qr_code'            => $qrCode,
                'qr_code_base64'     => $qrCodeBase64,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }

        DB::commit();
    }
}