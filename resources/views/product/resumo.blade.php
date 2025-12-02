@extends('layouts.app')

@section('title', 'Resumo da Compra - Crofline')

@section('content')
<style>
    .resumo-compra-wrapper {
        max-width: 1200px;
        margin: 40px auto 60px;
        padding: 0 20px; 
        color: var(--crofline-texto);
    }

    .resumo-grid {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
        gap: 30px;
    }

    .resumo-card-produtos,
    .resumo-card-resumo {
        background: #1d0735;
        border-radius: 18px;
        padding: 22px 24px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.45);
    }

    .resumo-card-produtos h3,
    .resumo-card-resumo h3 {
        margin-bottom: 18px;
        font-size: 1.3rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .resumo-item {
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 16px;
        padding: 14px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .resumo-item:last-child {
        border-bottom: none;
    }

    .resumo-item img {
        width: 120px;
        height: 150px;
        object-fit: cover;
        border-radius: 12px;
    }

    .resumo-item-titulo {
        font-weight: 600;
        margin-bottom: 4px;
        font-size: 0.98rem;
    }

    .resumo-item-meta {
        font-size: 0.85rem;
        opacity: 0.9;
        margin-bottom: 2px;
    }

    .resumo-item-valor {
        margin-top: 6px;
        font-weight: 600;
        font-size: 0.95rem;
        color: #ffd4ff;
    }

    .resumo-linhas {
        font-size: 0.95rem;
    }

    .resumo-linha {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
    }

    .resumo-linha span:first-child {
        opacity: 0.9;
    }

    .resumo-linha span:last-child {
        font-weight: 500;
    }

    .resumo-total {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        justify-content: space-between;
        font-size: 1.05rem;
        font-weight: 700;
    }

    .resumo-total span:last-child {
        color: #ffb3ff;
        font-size: 1.15rem;
    }

    .btn-crofline {
        width: 100%;
        border-radius: 999px;
        padding: 11px 16px;
        border: none;
        margin-top: 12px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-crofline-primary {
        background: linear-gradient(90deg, #7b2cbf, #a855f7);
        color: #fff;
    }

    .btn-crofline-primary:hover {
        filter: brightness(1.05);
    }

    .btn-crofline-secondary {
        background: transparent;
        color: #fff;
        border: 1px solid #5f2491;
    }

    .btn-crofline-secondary:hover {
        background: #321150;
    }

    @media (max-width: 900px) {
        .resumo-grid {
            grid-template-columns: 1fr;
        }

        .resumo-item {
            grid-template-columns: 90px 1fr;
        }

        .resumo-item img {
            width: 90px;
            height: 120px;
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
    <h2 style="margin-bottom:18px;letter-spacing:.12em;text-transform:uppercase;font-size:1.1rem;">
        RESUMO DA COMPRA
    </h2>

    <div class="resumo-grid">

        {{-- LADO ESQUERDO: PRODUTOS SELECIONADOS --}}
        <div class="resumo-card-produtos">
            <h3>Produtos selecionados</h3>

            @foreach($items as $item)
                <div class="resumo-item">
                    <img src="{{ asset('img/' . $item['image']) }}" alt="{{ $item['title'] }}">

                    <div>
                        <div class="resumo-item-titulo">{{ $item['title'] }}</div>
                        <div class="resumo-item-meta">Tamanho: {{ $item['size'] }}</div>
                        <div class="resumo-item-meta">Cor: {{ $item['color'] }}</div>
                        <div class="resumo-item-meta">Quantidade: {{ $item['quantity'] }}</div>
                        <div class="resumo-item-valor">
                            R$ {{ number_format($item['total_value'], 2, ',', '.') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- LADO DIREITO: RESUMO + FORM DE PAGAMENTO --}}
        <div class="resumo-card-resumo">
            <h3>Resumo</h3>

            {{-- FORM QUE ENVIA PARA PagamentoController@pagar --}}
            <form action="{{ route('pagamento.pagar') }}" method="POST">
                @csrf

                {{-- id do usuário logado vindo da sessão --}}
                <input type="hidden" name="id_usuario"
                       value="{{ session('id_usuario') ?? session('user_id') }}">

                {{-- total geral (subtotal) --}}
                <input type="hidden" name="total" value="{{ $subtotal }}">

                {{-- dados de cada produto em formato de array --}}
                @foreach($items as $index => $item)
                    <input type="hidden" name="produtos[{{ $index }}][id_produto]"
                           value="{{ $item['product_id'] ?? $item['id'] ?? '' }}">

                    <input type="hidden" name="produtos[{{ $index }}][tamanho]"
                           value="{{ $item['size'] }}">

                    <input type="hidden" name="produtos[{{ $index }}][cor]"
                           value="{{ $item['color'] }}">

                    <input type="hidden" name="produtos[{{ $index }}][quantidade]"
                           value="{{ $item['quantity'] }}">

                    {{-- valor do registro (preço * quantidade) --}}
                    <input type="hidden" name="produtos[{{ $index }}][valor]"
                           value="{{ $item['total_value'] }}">
                @endforeach

                <div class="resumo-linhas">
                    <div class="resumo-linha">
                        <span>Subtotal</span>
                        <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="resumo-linha">
                        <span>Frete</span>
                        <span>Calcular</span>
                    </div>
                </div>

                <div class="resumo-total">
                    <span>Total</span>
                    <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                </div>

                <button type="submit" class="btn-crofline btn-crofline-primary">
                    Finalizar compra
                </button>

                {{-- Continuar comprando: volta pra página anterior --}}
                <button type="button"
                        class="btn-crofline btn-crofline-secondary"
                        onclick="window.history.back();">
                    Continuar comprando
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
