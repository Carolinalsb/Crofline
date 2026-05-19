@extends('layouts.app')

@section('title', 'Gerenciar Conta - Crofline')

@section('content')
    <style>
        .conta-wrapper {
            max-width: 1100px;
            margin: 40px auto 70px;
            padding: 0 20px;
            color: var(--crofline-texto);
        }

        .conta-card {
            border-radius: 24px;
            padding: 28px;
            background:
                radial-gradient(circle at top right, rgba(76, 29, 149, 0.16), transparent 24%),
                radial-gradient(circle at bottom left, rgba(109, 40, 217, 0.18), transparent 28%),
                linear-gradient(135deg, rgba(22, 6, 43, 0.96), rgba(42, 11, 79, 0.96));
            border: 1px solid rgba(255, 255, 255, 0.07);
            box-shadow: 0 18px 40px rgba(0, 0, 0, .32);
        }

        .conta-topo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .conta-titulo {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #fff;
        }

        .conta-subtitulo {
            margin: 6px 0 0;
            color: rgba(255, 255, 255, .72);
            font-size: .92rem;
        }

        .conta-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .conta-box {
            border-radius: 18px;
            padding: 16px 18px;
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .07);
        }

        .conta-label {
            display: block;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .10em;
            color: rgba(255, 255, 255, .65);
            margin-bottom: 8px;
        }

        .conta-valor {
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            word-break: break-word;
        }

        .conta-acoes {
            margin-top: 28px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-conta {
            border: none;
            border-radius: 999px;
            padding: 13px 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            cursor: pointer;
            transition: .2s;
        }

        .btn-conta-voltar {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .12);
        }

        .btn-conta-voltar:hover {
            background: rgba(255, 255, 255, .05);
        }

        .btn-conta-excluir {
            background: #a11b3f;
            color: #fff;
        }

        .btn-conta-excluir:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
        }

        .modal-excluir-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .65);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 5000;
            padding: 20px;
        }

        .modal-excluir-overlay.ativo {
            display: flex;
        }

        .modal-excluir-card {
            width: 100%;
            max-width: 460px;
            border-radius: 22px;
            padding: 24px;
            background: linear-gradient(135deg, #1a062f, #2a0b46);
            border: 1px solid rgba(255, 255, 255, .08);
            box-shadow: 0 20px 50px rgba(0, 0, 0, .45);
            text-align: center;
        }

        .modal-excluir-card h3 {
            margin: 0 0 10px;
            color: #fff;
            font-size: 1.2rem;
        }

        .modal-excluir-card p {
            margin: 0 0 20px;
            color: rgba(255, 255, 255, .78);
            line-height: 1.5;
        }

        .modal-excluir-acoes {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-modal {
            border: none;
            border-radius: 999px;
            padding: 12px 18px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            cursor: pointer;
            transition: .2s;
        }

        .btn-modal-cancelar {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .12);
        }

        .btn-modal-confirmar {
            background: #a11b3f;
            color: #fff;
        }

        @media (max-width: 768px) {
            .conta-wrapper {
                padding: 0 12px;
            }

            .conta-grid {
                grid-template-columns: 1fr;
            }

            .conta-card {
                padding: 20px;
            }
        }
    </style>

    <div class="conta-wrapper">
        <div class="conta-card">
            <div class="conta-topo">
                <div>
                    <h1 class="conta-titulo">Gerenciar conta</h1>
                    <p class="conta-subtitulo">Confira os dados cadastrados da sua conta Crofline.</p>
                </div>
            </div>

            <div class="conta-grid">
                <div class="conta-box">
                    <span class="conta-label">Nome</span>
                    <div class="conta-valor">{{ $usuario->nome }}</div>
                </div>

                <div class="conta-box">
                    <span class="conta-label">Sobrenome</span>
                    <div class="conta-valor">{{ $usuario->sobrenome }}</div>
                </div>

                <div class="conta-box">
                    <span class="conta-label">E-mail</span>
                    <div class="conta-valor">{{ $usuario->email }}</div>
                </div>

                <div class="conta-box">
                    <span class="conta-label">CPF</span>
                    <div class="conta-valor">{{ $usuario->cpf }}</div>
                </div>

                <div class="conta-box">
                    <span class="conta-label">Telefone</span>
                    <div class="conta-valor">{{ $usuario->telefone }}</div>
                </div>

                <div class="conta-box">
                    <span class="conta-label">Data de nascimento</span>
                    <div class="conta-valor">
                        {{ \Carbon\Carbon::parse($usuario->data_nascimento)->format('d/m/Y') }}
                    </div>
                </div>
            </div>

            <div class="conta-acoes">
                <button type="button" class="btn-conta btn-conta-voltar" onclick="window.history.back();">
                    Voltar
                </button>

                <button type="button" class="btn-conta btn-conta-excluir" id="btn-abrir-modal-excluir">
                    Excluir conta
                </button>
            </div>
        </div>
    </div>

    <div class="modal-excluir-overlay" id="modal-excluir-overlay">
        <div class="modal-excluir-card">
            <h3>Tem certeza?</h3>
            <p>
                Essa ação excluirá sua conta permanentemente do sistema.
                Depois disso, não será possível recuperar seus dados.
            </p>

            <div class="modal-excluir-acoes">
                <button type="button" class="btn-modal btn-modal-cancelar" id="btn-cancelar-exclusao">
                    Cancelar
                </button>

                <form method="POST" action="{{ route('account.excluirConta') }}">
                    @csrf
                    <button type="submit" class="btn-modal btn-modal-confirmar">
                        Sim, excluir
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnAbrir = document.getElementById('btn-abrir-modal-excluir');
            const btnCancelar = document.getElementById('btn-cancelar-exclusao');
            const overlay = document.getElementById('modal-excluir-overlay');

            if (btnAbrir && overlay) {
                btnAbrir.addEventListener('click', function() {
                    overlay.classList.add('ativo');
                });
            }

            if (btnCancelar && overlay) {
                btnCancelar.addEventListener('click', function() {
                    overlay.classList.remove('ativo');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function(e) {
                    if (e.target === overlay) {
                        overlay.classList.remove('ativo');
                    }
                });
            }
        });
    </script>
@endsection
