@extends('layouts.app')

@section('title', 'Minhas Compras - Crofline')

@section('content')
<style>
    .mc-wrapper {
        max-width: 1200px;
        margin: 40px auto 60px;
        padding: 0 20px;
        color: var(--crofline-texto);
    }

    .mc-title {
        margin-bottom: 18px;
        letter-spacing: .12em;
        text-transform: uppercase;
        font-size: 1.1rem;
    }

    .mc-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .mc-tab-btn {
        border-radius: 999px;
        padding: 8px 18px;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border: 1px solid #5f2491;
        background: transparent;
        color: #fff;
        cursor: pointer;
        transition: 0.2s;
    }

    .mc-tab-btn.is-active {
        background: linear-gradient(90deg, #7b2cbf, #a855f7);
        border-color: transparent;
    }

    .mc-tab-panel {
        display: none;
    }

    .mc-tab-panel.is-active {
        display: block;
    }

    .mc-card {
        background: #1d0735;
        border-radius: 18px;
        padding: 18px 20px;
        box-shadow: 0 10px 26px rgba(0, 0, 0, 0.5);
        margin-bottom: 18px;
    }

    .mc-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }

    .mc-buycode {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .mc-status {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .mc-status-pendente {
        background: rgba(234, 179, 8, 0.18);
        color: #facc15;
    }

    .mc-status-pago {
        background: rgba(34, 197, 94, 0.18);
        color: #4ade80;
    }

    .mc-status-finalizado {
        background: rgba(59, 130, 246, 0.18);
        color: #60a5fa;
    }

    .mc-card-body {
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        padding-top: 10px;
        margin-top: 8px;
    }

    .mc-item {
        display: grid;
        grid-template-columns: 90px 1fr;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        font-size: 0.86rem;
    }

    .mc-item:last-child {
        border-bottom: none;
    }

    .mc-item img {
        width: 90px;
        height: 110px;
        object-fit: cover;
        border-radius: 10px;
    }

    .mc-item-title {
        font-weight: 600;
        margin-bottom: 4px;
    }

    .mc-item-meta {
        opacity: 0.9;
        margin-bottom: 2px;
    }

    .mc-item-valor {
        margin-top: 4px;
        font-weight: 600;
        color: #ffd4ff;
    }

    .mc-empty {
        opacity: 0.8;
        font-size: 0.9rem;
        padding: 10px 0;
    }

    @media (max-width: 900px) {
        .mc-item {
            grid-template-columns: 75px 1fr;
        }

        .mc-item img {
            width: 75px;
            height: 95px;
        }
    }
</style>

<div class="mc-wrapper">
    <h2 class="mc-title">MINHAS COMPRAS</h2>

    <div class="mc-tabs">
        <button class="mc-tab-btn is-active" data-tab-button="pendentes">Pendentes</button>
        <button class="mc-tab-btn" data-tab-button="pagas">Pagas</button>
        <button class="mc-tab-btn" data-tab-button="finalizadas">Finalizadas</button>
    </div>

    {{-- PENDENTES --}}
    <div class="mc-tab-panel is-active" data-tab-panel="pendentes">
        @if(empty($pendentes))
            <div class="mc-empty">Você não possui compras pendentes.</div>
        @else
            @foreach($pendentes as $compra)
                <div class="mc-card">
                    <div class="mc-card-header">
                        <div class="mc-buycode">Compra #{{ $compra['buyCode'] }}</div>
                        <div class="mc-status mc-status-pendente">
                            {{ strtoupper($compra['status_pagamento'] ?? 'pendente') }}
                        </div>
                    </div>

                    <div class="mc-card-body">
                        @foreach($compra['itens'] as $item)
                            <div class="mc-item">
                                <img src="{{ asset('img/' . $item->produto_imagem) }}" alt="{{ $item->produto_titulo }}">

                                <div>
                                    <div class="mc-item-title">{{ $item->produto_titulo }}</div>
                                    <div class="mc-item-meta">Tamanho: {{ $item->tamanho }}</div>
                                    <div class="mc-item-meta">Cor: {{ $item->cor }}</div>
                                    <div class="mc-item-meta">Quantidade: {{ $item->quantidade }}</div>
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

    {{-- PAGAS --}}
    <div class="mc-tab-panel" data-tab-panel="pagas">
        @if(empty($pagas))
            <div class="mc-empty">Você ainda não possui compras pagas.</div>
        @else
            @foreach($pagas as $compra)
                <div class="mc-card">
                    <div class="mc-card-header">
                        <div class="mc-buycode">Compra #{{ $compra['buyCode'] }}</div>
                        <div class="mc-status mc-status-pago">
                            {{ strtoupper($compra['status_pagamento'] ?? 'aprovado') }}
                        </div>
                    </div>

                    <div class="mc-card-body">
                        @foreach($compra['itens'] as $item)
                            <div class="mc-item">
                                <img src="{{ asset('img/' . $item->produto_imagem) }}" alt="{{ $item->produto_titulo }}">

                                <div>
                                    <div class="mc-item-title">{{ $item->produto_titulo }}</div>
                                    <div class="mc-item-meta">Tamanho: {{ $item->tamanho }}</div>
                                    <div class="mc-item-meta">Cor: {{ $item->cor }}</div>
                                    <div class="mc-item-meta">Quantidade: {{ $item->quantidade }}</div>
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

    {{-- FINALIZADAS --}}
    <div class="mc-tab-panel" data-tab-panel="finalizadas">
        @if(empty($finalizadas))
            <div class="mc-empty">Nenhuma compra finalizada no momento.</div>
        @else
            @foreach($finalizadas as $compra)
                <div class="mc-card">
                    <div class="mc-card-header">
                        <div class="mc-buycode">Compra #{{ $compra['buyCode'] }}</div>
                        <div class="mc-status mc-status-finalizado">
                            {{ strtoupper($compra['status_pagamento'] ?? 'finalizada') }}
                        </div>
                    </div>

                    <div class="mc-card-body">
                        @foreach($compra['itens'] as $item)
                            <div class="mc-item">
                                <img src="{{ asset('img/' . $item->produto_imagem) }}" alt="{{ $item->produto_titulo }}">

                                <div>
                                    <div class="mc-item-title">{{ $item->produto_titulo }}</div>
                                    <div class="mc-item-meta">Tamanho: {{ $item->tamanho }}</div>
                                    <div class="mc-item-meta">Cor: {{ $item->cor }}</div>
                                    <div class="mc-item-meta">Quantidade: {{ $item->quantidade }}</div>
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
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('[data-tab-button]');
        const panels  = document.querySelectorAll('[data-tab-panel]');

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const target = button.getAttribute('data-tab-button');

                buttons.forEach(b => b.classList.remove('is-active'));
                panels.forEach(p => p.classList.remove('is-active'));

                button.classList.add('is-active');
                document.querySelector('[data-tab-panel="' + target + '"]')
                    .classList.add('is-active');
            });
        });
    });
</script>
@endsection
