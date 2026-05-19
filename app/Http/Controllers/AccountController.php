<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'nome'            => 'required|string|max:255',
                'sobrenome'       => 'required|string|max:255',
                'cpf'             => 'required|string|max:20|unique:usuarios,cpf',
                'email'           => 'required|email|max:255|unique:usuarios,email',
                'senha'           => [
                    'required',
                    'string',
                    'min:8',
                    'max:50',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&._\-]/'
                ],
                'telefone'        => 'required|string|max:20',
                'data_nascimento' => 'required|date',
                'confirmar_senha' => 'required|string|min:8|max:50',
            ], [
                'senha.min'       => 'A senha deve ter no mínimo 8 caracteres.',
                'senha.regex'     => 'A senha deve conter letra maiúscula, letra minúscula, número e caractere especial.',
                'confirmar_senha.min' => 'A confirmação de senha deve ter no mínimo 8 caracteres.',
            ]);

            if ($validated['senha'] !== $validated['confirmar_senha']) {
                return response()->json([
                    'success' => false,
                    'errors'  => ['senha' => ['As senhas não conferem.']],
                ], 422);
            }

            $userId = DB::table('usuarios')->insertGetId([
                'nome'            => $validated['nome'],
                'sobrenome'       => $validated['sobrenome'],
                'cpf'             => $validated['cpf'],
                'email'           => $validated['email'],
                'senha'           => Hash::make($validated['senha']),
                'telefone'        => $validated['telefone'],
                'data_nascimento' => $validated['data_nascimento'],
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

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
            $validated = $request->validate([
                'email' => 'required|email',
                'senha' => 'required|string',
            ]);

            $user = DB::table('usuarios')
                ->where('email', $validated['email'])
                ->first();

            if (!$user || !Hash::check($validated['senha'], $user->senha)) {
                return response()->json([
                    'success' => false,
                    'message' => 'E-mail ou senha inválidos.',
                ], 422);
            }

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

    public function gerenciarConta()
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect('/')->with('error', 'Faça login para acessar sua conta.');
        }

        $usuario = DB::table('usuarios')
            ->where('id', $userId)
            ->first();

        if (!$usuario) {
            Session::flush();
            return redirect('/')->with('error', 'Usuário não encontrado.');
        }

        return view('account.gerenciarConta', compact('usuario'));
    }

    public function excluirConta(Request $request)
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect('/')->with('error', 'Faça login para excluir sua conta.');
        }

        $usuario = DB::table('usuarios')
            ->where('id', $userId)
            ->first();

        if (!$usuario) {
            Session::flush();
            return redirect('/')->with('error', 'Usuário não encontrado.');
        }

        DB::table('usuarios')
            ->where('id', $userId)
            ->delete();

        Session::flush();

        return redirect('/')->with('success', 'Sua conta foi excluída com sucesso.');
    }
}