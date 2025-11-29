<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    public function register(Request $request)
    {
        try {
            // validação
            $validated = $request->validate([
                'nome'            => 'required|string|max:255',
                'sobrenome'       => 'required|string|max:255',
                'cpf'             => 'required|string|max:20|unique:usuarios,cpf',
                'email'           => 'required|email|max:255|unique:usuarios,email',
                'senha'           => 'required|string|min:6',
                'telefone'        => 'required|string|max:20',
                'data_nascimento' => 'required|date',
                'confirmar_senha' => 'required|string|min:6',
            ]);

            // confere senha = confirmar_senha
            if ($validated['senha'] !== $validated['confirmar_senha']) {
                return response()->json([
                    'success' => false,
                    'errors'  => ['senha' => ['As senhas não conferem.']]
                ], 422);
            }

            // grava no banco
            DB::table('usuarios')->insert([
                'nome'            => $validated['nome'],
                'sobrenome'       => $validated['sobrenome'],
                'cpf'             => $validated['cpf'],
                'email'           => $validated['email'],
                // ideal: Hash::make($validated['senha'])
                'senha'           => $validated['senha'],
                'telefone'        => $validated['telefone'],
                'data_nascimento' => $validated['data_nascimento'],
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cadastro realizado com sucesso.'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Erro no register: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro interno no servidor.'
            ], 500);
        }
    }
}
