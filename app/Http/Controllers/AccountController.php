<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

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
                    'errors'  => ['senha' => ['As senhas não conferem.']],
                ], 422);
            }

            // grava no banco e pega o ID
            $userId = DB::table('usuarios')->insertGetId([
                'nome'            => $validated['nome'],
                'sobrenome'       => $validated['sobrenome'],
                'cpf'             => $validated['cpf'],
                'email'           => $validated['email'],
                // em produção: Hash::make($validated['senha'])
                'senha'           => $validated['senha'],
                'telefone'        => $validated['telefone'],
                'data_nascimento' => $validated['data_nascimento'],
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // salva dados básicos na sessão (já considera logado)
            session([
                'user_id'    => $userId,
                'user_email' => $validated['email'],
                'user_name'  => $validated['nome'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cadastro realizado com sucesso.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Erro no register: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro interno no servidor.',
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            // valida campos de login
            $validated = $request->validate([
                'email' => 'required|email',
                'senha' => 'required|string',
            ]);

            // busca usuário pelo email
            $user = DB::table('usuarios')
                ->where('email', $validated['email'])
                ->first();

            // confere se achou e se a senha bate
            if (!$user || $user->senha !== $validated['senha']) {
                return response()->json([
                    'success' => false,
                    'message' => 'E-mail ou senha inválidos.',
                ], 422);
            }

            // salva dados na sessão
            session([
                'user_id'    => $user->id,
                'user_email' => $user->email,
                'user_name'  => $user->nome,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login realizado com sucesso.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Erro no login: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro interno no servidor.',
            ], 500);
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect()->back();
    }
}
