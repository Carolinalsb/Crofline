@extends('layouts.app')

@section('title', 'Minhas Compras - Crofline')

@section('content')
    <style>
        .mc-wrapper {
            max-width: 1240px;
            margin: 40px auto 70px;
            padding: 0 20px;
            color: var(--crofline-texto);
        }

        .mc-topo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .mc-title {
            margin: 0;
            letter-spacing: .12em;
            text-transform: uppercase;
            font-size: 1.1rem;
            font-weight: 800;
        }

        .mc-subtitle {
            margin: 6px 0 0;
            color: rgba(255, 255, 255, .72);
            font-size: .92rem;
        }

        .mc-alert {
            border-radius: 14px;
            padding: 12px 16px;
            margin-bottom: 16px;
            font-size: .9rem;
        }

        .mc-alert-success {
            background: rgba(34, 197, 94, .12);
            border: 1px solid rgba(34, 197, 94, .25);
            color: #86efac;
        }

        .mc-alert-error {
            background: rgba(239, 68, 68, .12);
            border: 1px solid rgba(239, 68, 68, .25);
            color: #fca5a5;
        }

        .mc-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 22px;
            flex-wrap: wrap;
        }

        .mc-tab-btn {
            border-radius: 999px;
            padding: 9px 18px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border: 1px solid #5f2491;
            background: transparent;
            color: #fff;
            cursor: pointer;
            transition: 0.2s;
            font-weight: 700;
        }

        .mc-tab-btn.is-active {
            background: #751597;
            border-color: transparent;
            box-shadow: 0 10px 24px rgba(117, 21, 151, .28);
        }

        .mc-tab-panel {
            display: none;
        }

        .mc-tab-panel.is-active {
            display: block;
        }

        .mc-card {
            background:
                radial-gradient(circle at top right, rgba(117, 21, 151, 0.14), transparent 20%),
                linear-gradient(135deg, #1d0735, #2a0a46 62%, #21083a);
            border-radius: 22px;
            padding: 20px 22px;
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.35);
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, .06);
        }

        .mc-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            gap: 12px;
            flex-wrap: wrap;
            font-size: 0.9rem;
        }

        .mc-buycode {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #ffffff;
        }

        .mc-status {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 800;
        }

        .mc-status-pendente {
            background: rgba(234, 179, 8, 0.18);
            color: #facc15;
            border: 1px solid rgba(250, 204, 21, .22);
        }

        .mc-status-pago {
            background: rgba(34, 197, 94, 0.18);
            color: #4ade80;
            border: 1px solid rgba(74, 222, 128, .22);
        }

        .mc-status-enviado {
            background: rgba(59, 130, 246, 0.18);
            color: #60a5fa;
            border: 1px solid rgba(96, 165, 250, .22);
        }

        .mc-status-finalizado {
            background: rgba(168, 85, 247, 0.18);
            color: #c084fc;
            border: 1px solid rgba(192, 132, 252, .22);
        }

        .mc-card-body {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 12px;
            margin-top: 8px;
        }

        .mc-item {
            display: grid;
            grid-template-columns: 96px 1fr;
            gap: 14px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            font-size: 0.88rem;
            align-items: center;
        }

        .mc-item:last-child {
            border-bottom: none;
        }

        .mc-item img {
            width: 96px;
            height: 118px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 8px 18px rgba(0, 0, 0, .25);
            border: 1px solid rgba(255, 255, 255, .06);
        }

        .mc-item-title {
            font-weight: 700;
            margin-bottom: 6px;
            font-size: 1rem;
            color: #fff;
        }

        .mc-item-meta {
            opacity: 0.92;
            margin-bottom: 3px;
            color: rgba(255, 255, 255, .86);
        }

        .mc-item-valor {
            margin-top: 7px;
            font-weight: 800;
            color: #ffd4ff;
            font-size: .98rem;
        }

        .mc-pix-box {
            margin-top: 18px;
            border-radius: 20px;
            padding: 18px;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, .04), rgba(255, 255, 255, .02));
            border: 1px solid rgba(255, 255, 255, .08);
        }

        .mc-pix-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .mc-pix-title {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 800;
            color: #fff;
        }

        .mc-pix-subtitle {
            margin: 6px 0 0;
            color: rgba(255, 255, 255, .76);
            font-size: .92rem;
        }

        .mc-pix-grid {
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 22px;
            align-items: start;
        }

        .mc-pix-qr-card {
            background: rgba(255, 255, 255, .03);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 18px;
            padding: 14px;
            text-align: center;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .03);
        }

        .mc-pix-qr-card img {
            width: 100%;
            max-width: 210px;
            display: block;
            margin: 0 auto 12px;
            border-radius: 16px;
            background: #fff;
            padding: 10px;
            box-shadow: 0 14px 30px rgba(0, 0, 0, .18);
        }

        .mc-pix-qr-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(117, 21, 151, .24);
            color: #f3d8ff;
        }

        .mc-pix-copy-area {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .mc-pix-instruction {
            color: rgba(255, 255, 255, .84);
            font-size: .93rem;
            line-height: 1.55;
        }

        .mc-pix-code-wrap {
            position: relative;
        }

        .mc-pix-code {
            width: 100%;
            min-height: 110px;
            border-radius: 14px;
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .08);
            color: #fff;
            padding: 14px 14px 52px;
            font-size: .85rem;
            line-height: 1.5;
            resize: vertical;
        }

        .mc-copy-btn {
            position: absolute;
            right: 12px;
            bottom: 12px;
            border: none;
            border-radius: 999px;
            padding: 10px 14px;
            background: #751597;
            color: #fff;
            font-size: .78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            cursor: pointer;
            transition: .2s;
        }

        .mc-copy-btn:hover {
            filter: brightness(1.07);
            transform: translateY(-1px);
        }

        .mc-copy-feedback {
            font-size: .82rem;
            color: #d8b4fe;
            min-height: 18px;
        }

        .mc-empty {
            opacity: 0.8;
            font-size: 0.92rem;
            padding: 12px 2px;
        }

        @media (max-width: 980px) {
            .mc-pix-grid {
                grid-template-columns: 1fr;
            }

            .mc-pix-qr-card {
                max-width: 260px;
            }
        }

        @media (max-width: 900px) {
            .mc-item {
                grid-template-columns: 78px 1fr;
            }

            .mc-item img {
                width: 78px;
                height: 98px;
            }
        }

        @media (max-width: 640px) {
            .mc-wrapper {
                padding: 0 12px;
            }

            .mc-card {
                padding: 16px 16px;
                border-radius: 18px;
            }

            .mc-pix-title {
                font-size: 1.12rem;
            }

            .mc-tabs {
                gap: 8px;
            }

            .mc-tab-btn {
                width: 100%;
                text-align: center;
            }

            .mc-pix-code {
                min-height: 130px;
                padding-bottom: 58px;
            }

            .mc-pix-qr-card {
                max-width: 100%;
            }
        }
    </style>

    <div class="mc-wrapper">
        <div class="mc-topo">
            <div>
                <h2 class="mc-title">MINHAS COMPRAS</h2>
                <p class="mc-subtitle">Acompanhe seus pedidos, pagamentos e entregas da Crofline.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="mc-alert mc-alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="mc-alert mc-alert-error">{{ session('error') }}</div>
        @endif

        <div class="mc-tabs">
            <button class="mc-tab-btn is-active" data-tab-button="pendentes">Pendentes</button>
            <button class="mc-tab-btn" data-tab-button="pagas">Pagas</button>
            <button class="mc-tab-btn" data-tab-button="enviadas">Enviado</button>
            <button class="mc-tab-btn" data-tab-button="finalizadas">Finalizadas</button>
        </div>

        <div class="mc-tab-panel is-active" data-tab-panel="pendentes">
            @if (empty($pendentes))
                <div class="mc-empty">Você não possui compras pendentes.</div>
            @else
                @foreach ($pendentes as $compra)
                    <div class="mc-card">
                        <div class="mc-card-header">
                            <div class="mc-buycode">Compra #{{ $compra['buyCode'] }}</div>
                            <div class="mc-status mc-status-pendente">
                                PENDENTE
                            </div>
                        </div>

                        <div class="mc-card-body">
                            @foreach ($compra['itens'] as $item)
                                <div class="mc-item">
                                    <img src="{{ !empty($item->produto_imagem) ? asset('img/' . $item->produto_imagem) : asset('img/sem-imagem.png') }}"
                                        alt="{{ $item->produto_titulo }}">

                                    <div>
                                        <div class="mc-item-title">{{ $item->produto_titulo }}</div>
                                        <div class="mc-item-meta">Tamanho: {{ $item->tamanho }}</div>
                                        <div class="mc-item-meta">Cor: {{ $item->cor }}</div>
                                        <div class="mc-item-meta">Quantidade: {{ $item->qtd }}</div>
                                        <div class="mc-item-valor">
                                            R$ {{ number_format($item->valor, 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if (($compra['tipo_pagamento'] ?? '') === 'pix' && !empty($compra['qr_code']))
                                <div class="mc-pix-box">
                                    <div class="mc-pix-top">
                                        <div>
                                            <h5 class="mc-pix-title">Pagamento via Pix</h5>
                                            <p class="mc-pix-subtitle">
                                                Escaneie o QR Code no app do seu banco ou copie o código Pix abaixo.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mc-pix-grid">
                                        <div class="mc-pix-qr-card">
                                            @if (!empty($compra['qr_code_base64']))
                                                <img src="data:image/png;base64,{{ $compra['qr_code_base64'] }}"
                                                    alt="QR Code Pix">
                                            @endif

                                            <div class="mc-pix-qr-badge">
                                                <i class="bi bi-qr-code-scan"></i>
                                                QR Code Pix
                                            </div>
                                        </div>

                                        <div class="mc-pix-copy-area">
                                            <div class="mc-pix-instruction">
                                                Depois de pagar, o status da compra será atualizado automaticamente.
                                                Caso o pagamento ainda não apareça, aguarde alguns instantes e atualize a
                                                página.
                                            </div>

                                            <div class="mc-pix-code-wrap">
                                                <textarea class="mc-pix-code" readonly>{{ $compra['qr_code'] }}</textarea>
                                                <button type="button" class="mc-copy-btn"
                                                    data-copy="{{ $compra['qr_code'] }}">
                                                    <i class="bi bi-copy"></i> Copiar Pix
                                                </button>
                                            </div>

                                            <div class="mc-copy-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="mc-tab-panel" data-tab-panel="pagas">
            @if (empty($pagas))
                <div class="mc-empty">Você ainda não possui compras pagas.</div>
            @else
                @foreach ($pagas as $compra)
                    <div class="mc-card">
                        <div class="mc-card-header">
                            <div class="mc-buycode">Compra #{{ $compra['buyCode'] }}</div>
                            <div class="mc-status mc-status-pago">
                                APROVADO
                            </div>
                        </div>

                        <div class="mc-card-body">
                            @foreach ($compra['itens'] as $item)
                                <div class="mc-item">
                                    <img src="{{ !empty($item->produto_imagem) ? asset('img/' . $item->produto_imagem) : asset('img/sem-imagem.png') }}"
                                        alt="{{ $item->produto_titulo }}">

                                    <div>
                                        <div class="mc-item-title">{{ $item->produto_titulo }}</div>
                                        <div class="mc-item-meta">Tamanho: {{ $item->tamanho }}</div>
                                        <div class="mc-item-meta">Cor: {{ $item->cor }}</div>
                                        <div class="mc-item-meta">Quantidade: {{ $item->qtd }}</div>
                                        <div class="mc-item-valor">
                                            R$ {{ number_format($item->valor, 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="mc-tab-panel" data-tab-panel="enviadas">
            @if (empty($enviadas))
                <div class="mc-empty">Nenhuma compra enviada no momento.</div>
            @else
                @foreach ($enviadas as $compra)
                    <div class="mc-card">
                        <div class="mc-card-header">
                            <div class="mc-buycode">Compra #{{ $compra['buyCode'] }}</div>
                            <div class="mc-status mc-status-enviado">ENVIADO</div>
                        </div>

                        <div class="mc-card-body">
                            @foreach ($compra['itens'] as $item)
                                <div class="mc-item">
                                    <img src="{{ !empty($item->produto_imagem) ? asset('img/' . $item->produto_imagem) : asset('img/sem-imagem.png') }}"
                                        alt="{{ $item->produto_titulo }}">

                                    <div>
                                        <div class="mc-item-title">{{ $item->produto_titulo }}</div>
                                        <div class="mc-item-meta">Tamanho: {{ $item->tamanho }}</div>
                                        <div class="mc-item-meta">Cor: {{ $item->cor }}</div>
                                        <div class="mc-item-meta">Quantidade: {{ $item->qtd }}</div>
                                        <div class="mc-item-valor">
                                            R$ {{ number_format($item->valor, 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="mc-tab-panel" data-tab-panel="finalizadas">
            @if (empty($finalizadas))
                <div class="mc-empty">Nenhuma compra finalizada no momento.</div>
            @else
                @foreach ($finalizadas as $compra)
                    <div class="mc-card">
                        <div class="mc-card-header">
                            <div class="mc-buycode">Compra #{{ $compra['buyCode'] }}</div>
                            <div class="mc-status mc-status-finalizado">FINALIZADA</div>
                        </div>

                        <div class="mc-card-body">
                            @foreach ($compra['itens'] as $item)
                                <div class="mc-item">
                                    <img src="{{ !empty($item->produto_imagem) ? asset('img/' . $item->produto_imagem) : asset('img/sem-imagem.png') }}"
                                        alt="{{ $item->produto_titulo }}">

                                    <div>
                                        <div class="mc-item-title">{{ $item->produto_titulo }}</div>
                                        <div class="mc-item-meta">Tamanho: {{ $item->tamanho }}</div>
                                        <div class="mc-item-meta">Cor: {{ $item->cor }}</div>
                                        <div class="mc-item-meta">Quantidade: {{ $item->qtd }}</div>
                                        <div class="mc-item-valor">
                                            R$ {{ number_format($item->valor, 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('[data-tab-button]');
            const panels = document.querySelectorAll('[data-tab-panel]');

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const target = button.getAttribute('data-tab-button');

                    buttons.forEach(b => b.classList.remove('is-active'));
                    panels.forEach(p => p.classList.remove('is-active'));

                    button.classList.add('is-active');
                    document.querySelector('[data-tab-panel="' + target + '"]').classList.add(
                        'is-active');
                });
            });

            const copyButtons = document.querySelectorAll('.mc-copy-btn');

            copyButtons.forEach(button => {
                button.addEventListener('click', async function() {
                    const text = this.getAttribute('data-copy') || '';
                    const feedback = this.closest('.mc-pix-copy-area')?.querySelector(
                        '.mc-copy-feedback');

                    try {
                        await navigator.clipboard.writeText(text);
                        if (feedback) {
                            feedback.textContent = 'Código Pix copiado com sucesso.';
                        }
                    } catch (error) {
                        if (feedback) {
                            feedback.textContent =
                                'Não foi possível copiar automaticamente. Copie manualmente.';
                        }
                    }
                });
            });
        });
    </script>
@endsection
