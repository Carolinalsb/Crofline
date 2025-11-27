@extends('layouts.app')

@section('title', 'Crofline | ' . $produto->titulo)

@section('content')

@php
    // Monta array com todas as imagens existentes
    $imagens = array_filter([
        $produto->imagem ? asset('img/' . $produto->imagem) : null,
        $produto->imagem2 ? asset('img/' . $produto->imagem2) : null,
        $produto->imagem3 ? asset('img/' . $produto->imagem3) : null,
    ]);

    // Decodifica cores do banco (ex.: ["preto","cinza","marrom","azul"])
    $coresBrutas = json_decode($produto->cores ?? '[]', true) ?: [];

    // Mapeia nomes de cores em PT pra cores CSS
    $mapaCores = [
        'preto'  => '#000000',
        'cinza'  => '#808080',
        'marrom' => '#8B4513',
        'azul'   => '#1E90FF',
        'rosa'   => '#FF6FB5',
        'roxo'   => '#7B2CBF',
        'branco' => '#FFFFFF',
        'bege'   => '#F5DEB3',
        'verde'  => '#32CD32',
    ];

    $cores = [];
    foreach ($coresBrutas as $c) {
        $nome = trim(mb_strtolower($c));
        $cores[] = [
            'nome'  => $c,
            'cor'   => $mapaCores[$nome] ?? '#CCCCCC',
        ];
    }
@endphp

<style>
    .product-page {
        background-color: #321150;
        min-height: 100vh;
        padding: 40px 6vw 60px;
        color: #ffffff;
        display: flex;
        justify-content: center;
    }

    .product-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
        gap: 40px;
        width: 100%;
        max-width: 1200px;
    }

    /* COLUNA ESQUERDA – GALERIA */
    .product-gallery {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 16px;
    }

    .product-main-image-wrapper {
        background-color: #1f0934;
        padding: 16px;
        border-radius: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .product-main-image {
        max-width: 100%;
        max-height: 540px;
        object-fit: cover;
        border-radius: 6px;
    }

    .product-thumbs {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .product-thumb {
        width: 80px;
        height: 100px;
        border-radius: 6px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: transform 0.2s ease, border-color 0.2s ease;
        background-color: #1f0934;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-thumb:hover {
        transform: translateY(-2px);
    }

    .product-thumb.active {
        border-color: #a855f7;
    }

    /* COLUNA DIREITA – INFO */
    .product-info-panel {
        background-color: #1f0934;
        border-radius: 10px;
        padding: 24px 26px 28px;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .product-title-main {
        font-size: 22px;
        font-weight: 600;
        margin: 0;
    }

    .product-subtitle-category {
        font-size: 13px;
        opacity: 0.8;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .product-price-main {
        font-size: 26px;
        font-weight: 700;
        margin: 4px 0 0 0;
    }

    .product-size-select label,
    .product-colors-block span,
    .product-description-block h4,
    .product-sales span {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        opacity: 0.9;
    }

    .product-size-select .form-select {
        background-color: #321150;
        border: 1px solid #5f2491;
        color: #fff;
        font-size: 14px;
    }

    .product-size-select .form-select:focus {
        box-shadow: 0 0 0 0.1rem rgba(168, 85, 247, 0.6);
        border-color: #a855f7;
    }

    .product-colors-list {
        display: flex;
        gap: 10px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .product-color-dot {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        border: 2px solid #ffffff;
        box-shadow: 0 0 0 2px #1f0934;
        cursor: pointer;
        position: relative;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .product-color-dot:hover {
        transform: scale(1.06);
        box-shadow: 0 0 0 2px #a855f7;
    }

    .product-color-dot::after {
        content: attr(data-color-name);
        position: absolute;
        bottom: -18px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 10px;
        white-space: nowrap;
    }

    .product-description-text {
        font-size: 14px;
        line-height: 1.5;
        opacity: 0.95;
        white-space: pre-line;
    }

    .product-sales strong {
        font-size: 14px;
        font-weight: 600;
    }

    .product-buy-main-btn {
        width: 100%;
        border: none;
        border-radius: 999px;
        padding: 12px 20px;
        background: linear-gradient(90deg, #7b2cbf, #a855f7);
        color: #ffffff;
        font-weight: 700;
        font-size: 15px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-top: 4px;
        transition: transform 0.12s ease, box-shadow 0.12s ease, opacity 0.12s ease;
    }

    .product-buy-main-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.35);
        opacity: 0.95;
    }

    .product-buy-main-btn:active {
        transform: translateY(0);
        box-shadow: none;
        opacity: 0.9;
    }

    .product-freight-block {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
    }

    .product-freight-block label {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        opacity: 0.9;
    }

    .product-freight-row {
        display: flex;
        gap: 10px;
        margin-top: 8px;
    }

    .product-freight-row input {
        background-color: #321150;
        border: 1px solid #5f2491;
        color: #fff;
        font-size: 14px;
    }

    .product-freight-row input:focus {
        box-shadow: 0 0 0 0.1rem rgba(168, 85, 247, 0.6);
        border-color: #a855f7;
    }

    .product-freight-row button {
        white-space: nowrap;
        border-radius: 999px;
        padding: 8px 18px;
        border: none;
        background-color: #7b2cbf;
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        transition: background-color 0.12s ease, transform 0.12s ease;
    }

    .product-freight-row button:hover {
        background-color: #a855f7;
        transform: translateY(-1px);
    }

    /* RESPONSIVO */
    @media (max-width: 900px) {
        .product-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (max-width: 576px) {
        .product-page {
            padding: 20px 14px 40px;
        }

        .product-info-panel {
            padding: 18px 18px 22px;
        }
    }
</style>

<section class="product-page">
    <div class="product-grid">

        {{-- COLUNA ESQUERDA – GALERIA --}}
        <div class="product-gallery">
            <div class="product-main-image-wrapper">
                <img
                    id="product-main-image"
                    class="product-main-image"
                    src="{{ $imagens[0] ?? '' }}"
                    alt="{{ $produto->titulo }}"
                >
            </div>

            @if(count($imagens) > 1)
                <div class="product-thumbs">
                    @foreach($imagens as $idx => $img)
                        <div
                            class="product-thumb {{ $idx === 0 ? 'active' : '' }}"
                            data-image="{{ $img }}"
                        >
                            <img src="{{ $img }}" alt="Imagem {{ $idx + 1 }} de {{ $produto->titulo }}">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- COLUNA DIREITA – INFORMAÇÕES --}}
        <div class="product-info-panel">

            <div>
                <p class="product-subtitle-category">
                    {{ $produto->categorias }}
                </p>
                <h1 class="product-title-main">
                    {{ $produto->titulo }}
                </h1>
            </div>

            <div>
                <p class="product-price-main">
                    R$ {{ number_format($produto->valor, 2, ',', '.') }}
                </p>
            </div>

            <div class="product-size-select">
                <label for="select-tamanho">Tamanho</label>
                <select id="select-tamanho" class="form-select mt-1">
                    {{-- Aqui por enquanto só o tamanho vindo do banco. Depois dá pra evoluir pra vários --}}
                    <option value="{{ $produto->tamanho }}" selected>
                        {{ $produto->tamanho }}
                    </option>
                </select>
            </div>

            @if(count($cores) > 0)
                <div class="product-colors-block">
                    <span>Cores</span>
                    <div class="product-colors-list">
                        @foreach($cores as $c)
                            <div
                                class="product-color-dot"
                                style="background-color: {{ $c['cor'] }};"
                                data-color-name="{{ $c['nome'] }}"
                            ></div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="product-description-block">
                <h4>Descrição</h4>
                <p class="product-description-text">
                    {{ $produto->descricao }}
                </p>
            </div>

            <div class="product-sales">
                <span>Vendas</span>
                <br>
                <strong>{{ $produto->qtd_vendas }} {{ $produto->qtd_vendas == 1 ? 'unidade' : 'unidades' }} vendidas</strong>
            </div>

            <div>
                <button
                    type="button"
                    class="product-buy-main-btn"
                    data-product-id="{{ $produto->id }}"
                >
                    Comprar
                </button>
            </div>

            {{-- BLOCO FRETE (placeholder pra futura integração com API de frete) --}}
            <div class="product-freight-block">
                <label for="cep">Calcular frete</label>
                <div class="product-freight-row">
                    <input
                        type="text"
                        id="cep"
                        class="form-control"
                        placeholder="Digite o CEP"
                    >
                    <button type="button" id="btn-calcular-frete">
                        Calcular
                    </button>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Troca imagem principal ao clicar nas miniaturas
        const mainImg = document.getElementById('product-main-image');
        const thumbs = document.querySelectorAll('.product-thumb');

        thumbs.forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                const newSrc = this.getAttribute('data-image');
                if (!newSrc) return;

                mainImg.src = newSrc;

                thumbs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Botão principal comprar (só log pra vocês plugarem depois)
        const buyBtn = document.querySelector('.product-buy-main-btn');
        if (buyBtn) {
            buyBtn.addEventListener('click', function () {
                const id = this.dataset.productId;
                console.log('Comprar produto ID:', id);
                // Aqui depois vocês redirecionam ou mandam pro carrinho / checkout
            });
        }

        // Botão calcular frete (placeholder)
        const freteBtn = document.getElementById('btn-calcular-frete');
        if (freteBtn) {
            freteBtn.addEventListener('click', function () {
                const cep = document.getElementById('cep').value;
                console.log('Calcular frete para CEP:', cep);
                // Depois vocês chamam a API de frete aqui (Correios / outra)
            });
        }
    });
</script>

@endsection
