@extends('layouts.app')

@section('title', 'Resumo da Compra - Crofline')

@section('content')
<style>
    .resumo-compra-wrapper {
        max-width: 1320px;
        margin: 34px auto 70px;
        padding: 0 20px;
        color: var(--crofline-texto);
    }

    .resumo-topo {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .resumo-titulo-box h2 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: #ffffff;
    }

    .resumo-titulo-box p {
        margin: 8px 0 0;
        color: rgba(255,255,255,.72);
        font-size: .92rem;
    }

    .resumo-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        background: linear-gradient(90deg, rgba(76, 29, 149, .26), rgba(109, 40, 217, .22));
        border: 1px solid rgba(255,255,255,.08);
        color: #f7ecff;
        font-size: .88rem;
        letter-spacing: .05em;
        box-shadow: 0 8px 20px rgba(0,0,0,.18);
    }

    .resumo-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.8fr) minmax(320px, .95fr);
        gap: 28px;
        align-items: start;
    }

    .resumo-card-produtos,
    .resumo-card-resumo {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        padding: 24px;
        background:
            radial-gradient(circle at top right, rgba(76, 29, 149, 0.16), transparent 24%),
            radial-gradient(circle at bottom left, rgba(109, 40, 217, 0.18), transparent 28%),
            linear-gradient(135deg, rgba(22, 6, 43, 0.96), rgba(42, 11, 79, 0.96));
        border: 1px solid rgba(255,255,255,0.07);
        box-shadow: 0 18px 40px rgba(0,0,0,.32);
        backdrop-filter: blur(10px);
    }

    .resumo-lista {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .resumo-item {
        display: grid;
        grid-template-columns: 145px 1fr;
        gap: 18px;
        align-items: stretch;
        padding: 16px;
        border-radius: 20px;
        background: rgba(255,255,255,.035);
        border: 1px solid rgba(255,255,255,.05);
    }

    .resumo-item-imagem-box {
        border-radius: 18px;
        overflow: hidden;
        min-height: 210px;
        background:
            linear-gradient(135deg, rgba(255,255,255,.08), rgba(255,255,255,.02));
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .resumo-item-imagem-box img {
        width: 100%;
        height: 100%;
        min-height: 210px;
        object-fit: cover;
        display: block;
    }

    .resumo-item-titulo {
        font-weight: 700;
        margin-bottom: 8px;
        font-size: 1.05rem;
        color: #ffffff;
        line-height: 1.35;
    }

    .resumo-item-descricao {
        font-size: .9rem;
        color: rgba(255,255,255,.68);
        margin-bottom: 12px;
        line-height: 1.45;
    }

    .resumo-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 14px;
    }

    .resumo-tag {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.08);
        font-size: .83rem;
        color: #f8efff;
        white-space: nowrap;
    }

    .resumo-item-valor {
        font-weight: 700;
        font-size: 1.15rem;
        color: #efe1ff;
    }

    .resumo-linha,
    .resumo-total {
        display: flex;
        justify-content: space-between;
        gap: 16px;
    }

    .resumo-linha {
        margin-bottom: 12px;
        color: rgba(255,255,255,.86);
    }

    .resumo-total {
        margin-top: 18px;
        padding-top: 16px;
        border-top: 1px solid rgba(255,255,255,.10);
        align-items: center;
        font-size: 1.12rem;
        font-weight: 700;
    }

    .resumo-total span:last-child {
        color: #efe1ff;
        font-size: 1.28rem;
    }

    .resumo-info-extra {
        margin-top: 16px;
        padding: 14px 16px;
        border-radius: 18px;
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.06);
        color: rgba(255,255,255,.80);
        font-size: .88rem;
        line-height: 1.5;
    }

    .btn-crofline {
        width: 100%;
        border-radius: 999px;
        padding: 13px 16px;
        border: none;
        margin-top: 14px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 700;
        font-size: 0.88rem;
        cursor: pointer;
        transition: transform .18s ease, filter .18s ease;
    }

    .btn-crofline:hover {
        transform: translateY(-1px);
    }

    .btn-crofline-primary {
        background: #751597;
        color: #fff;
    }

    .btn-crofline-secondary {
        background: transparent;
        color: #fff;
        border: 1px solid rgba(255,255,255,.12);
    }

    @media (max-width: 980px) {
        .resumo-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .resumo-compra-wrapper {
            margin: 24px auto 50px;
            padding: 0 12px;
        }

        .resumo-item {
            grid-template-columns: 1fr;
        }
    }
</style>

@php
    $subtotal = 0;
    foreach ($items as $item) {
        $subtotal += $item['total_value'];
    }
@endphp

<div class="resumo-compra-wrapper">
    <div class="resumo-topo">
        <div class="resumo-titulo-box">
            <h2>Resumo da compra</h2>
            <p>Confira seus itens antes de seguir para o pagamento.</p>
        </div>

        <div class="resumo-badge">
            <i class="bi bi-bag-heart-fill"></i>
            <span>{{ count($items) }} item(ns) selecionado(s)</span>
        </div>
    </div>

    <div class="resumo-grid">

        <div class="resumo-card-produtos">
            <h3><i class="bi bi-stars"></i> Produtos selecionados</h3>

            <div class="resumo-lista">
                @foreach($items as $item)
                    <div class="resumo-item">
                        <div class="resumo-item-imagem-box">
                            <img
                                src="{{ !empty($item['image']) ? asset('img/' . $item['image']) : asset('img/sem-imagem.png') }}"
                                alt="{{ $item['title'] }}"
                            >
                        </div>

                        <div>
                            <div class="resumo-item-titulo">{{ $item['title'] }}</div>

                            @if(!empty($item['description']))
                                <div class="resumo-item-descricao">{{ $item['description'] }}</div>
                            @endif

                            <div class="resumo-tags">
                                <div class="resumo-tag">
                                    <i class="bi bi-palette-fill"></i>
                                    <span>Cor: {{ $item['color'] ?? 'Não informada' }}</span>
                                </div>

                                <div class="resumo-tag">
                                    <i class="bi bi-aspect-ratio-fill"></i>
                                    <span>Tamanho: {{ $item['size'] ?? 'Não informado' }}</span>
                                </div>

                                <div class="resumo-tag">
                                    <i class="bi bi-box-seam"></i>
                                    <span>Quantidade: {{ $item['quantity'] }}</span>
                                </div>
                            </div>

                            <div class="resumo-item-valor">
                                R$ {{ number_format($item['total_value'], 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="resumo-card-resumo">
            <h3><i class="bi bi-receipt-cutoff"></i> Resumo</h3>

            <form action="{{ route('pagamento.pagar') }}" method="POST">
                @csrf

                <input type="hidden" name="id_usuario" value="{{ session('id_usuario') ?? session('user_id') }}">
                <input type="hidden" name="total" value="{{ $subtotal }}">

                @foreach($items as $index => $item)
                    <input type="hidden" name="produtos[{{ $index }}][id_produto]" value="{{ $item['product_id'] ?? '' }}">
                    <input type="hidden" name="produtos[{{ $index }}][tamanho]" value="{{ $item['size'] ?? '' }}">
                    <input type="hidden" name="produtos[{{ $index }}][cor]" value="{{ $item['color'] ?? '' }}">
                    <input type="hidden" name="produtos[{{ $index }}][quantidade]" value="{{ $item['quantity'] }}">
                    <input type="hidden" name="produtos[{{ $index }}][valor]" value="{{ $item['total_value'] }}">
                @endforeach

                <div class="resumo-linhas">
                    <div class="resumo-linha">
                        <span>Subtotal</span>
                        <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                    </div>

                    <div class="resumo-linha">
                        <span>Frete</span>
                        <span>Calcular depois</span>
                    </div>

                    <div class="resumo-linha">
                        <span>Pagamento</span>
                        <span>Mercado Pago</span>
                    </div>
                </div>

                <div class="resumo-total">
                    <span>Total</span>
                    <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                </div>

                <div class="resumo-info-extra">
                    Ao finalizar, você será redirecionada para a etapa de pagamento. Revise cor, tamanho e quantidade antes de continuar.
                </div>

                <button type="submit" class="btn-crofline btn-crofline-primary">
                    <i class="bi bi-credit-card-2-front-fill"></i>
                    Finalizar compra
                </button>

                <button type="button" class="btn-crofline btn-crofline-secondary" onclick="window.history.back();">
                    <i class="bi bi-arrow-left-circle-fill"></i>
                    Continuar comprando
                </button>
            </form>
        </div>

    </div>
</div>
@endsection