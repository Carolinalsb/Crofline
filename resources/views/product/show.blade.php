@extends('layouts.app')

@section('title', 'Crofline | ' . $produto->titulo)

@section('content')

@php
    $mapaCores = [
        'preto'            => '#000000',
        'cinza'            => '#808080',
        'marrom'           => '#8B4513',
        'azul'             => '#1E90FF',
        'rosa'             => '#FF6FB5',
        'roxo'             => '#7B2CBF',
        'branco'           => '#FFFFFF',
        'bege'             => '#F5DEB3',
        'verde'            => '#32CD32',
        'nude'             => '#D2B48C',
        'marsala'          => '#964F4C',
        'marçala'          => '#964F4C',
        'off white'        => '#F8F5F0',
        'rosa claro'       => '#FFC0CB',
        'rosa choque'      => '#FF1493',
        'lilás'            => '#C8A2C8',
        'laranja'          => '#FFA500',
        'mostarda'         => '#FFDB58',
        'terracota'        => '#E2725B',
        'verde musgo'      => '#556B2F',
        'verde bandeira'   => '#008000',
        'verde oliva'      => '#6B8E23',
        'verde esmeralda'  => '#50C878',
        'azul marinho'     => '#001F3F',
        'azul royal'       => '#4169E1',
        'jeans claro'      => '#7EA0B7',
        'vinho'            => '#722F37',
        'caramelo'         => '#AF6E4D',
        'lavanda'          => '#B57EDC',
        'grafite'          => '#41424C',
        'amarelo manteiga' => '#F3E5AB',
        'vermelho'         => '#C1121F',
    ];

    $detalhesBrutos = json_decode($produto->detalhes ?? '[]', true);
    $detalhesBrutos = is_array($detalhesBrutos) ? $detalhesBrutos : [];

    $variacoes = [];
    $tamanhosUnicos = [];
    $coresUnicas = [];

    foreach ($detalhesBrutos as $indice => $detalhe) {
        if (!is_array($detalhe)) {
            continue;
        }

        $cor = trim($detalhe['cor'] ?? '');
        $tamanho = trim($detalhe['tamanho'] ?? '');
        $valor = isset($detalhe['valor']) ? (float) $detalhe['valor'] : 0;
        $qtd = isset($detalhe['qtd']) ? (int) $detalhe['qtd'] : 0;

        $imagens = [];
        if (isset($detalhe['imagens']) && is_array($detalhe['imagens'])) {
            foreach (['imagem1', 'imagem2', 'imagem3', 'imagem4'] as $chaveImagem) {
                if (!empty($detalhe['imagens'][$chaveImagem])) {
                    $imagens[] = $detalhe['imagens'][$chaveImagem];
                }
            }
        }

        $variacoes[] = [
            'index' => $indice,
            'cor' => $cor,
            'cor_key' => mb_strtolower($cor),
            'tamanho' => $tamanho,
            'valor' => $valor,
            'qtd' => $qtd,
            'imagens' => $imagens,
        ];

        if ($tamanho !== '' && !in_array($tamanho, $tamanhosUnicos, true)) {
            $tamanhosUnicos[] = $tamanho;
        }

        if ($cor !== '' && !isset($coresUnicas[mb_strtolower($cor)])) {
            $coresUnicas[mb_strtolower($cor)] = [
                'nome' => $cor,
                'hex' => $mapaCores[mb_strtolower($cor)] ?? '#CCCCCC',
            ];
        }
    }

    $primeiraVariacao = $variacoes[0] ?? [
        'index' => 0,
        'cor' => '',
        'cor_key' => '',
        'tamanho' => '',
        'valor' => 0,
        'qtd' => 0,
        'imagens' => [],
    ];

    $coresLista = array_values($coresUnicas);
@endphp

<style>
    .product-page {
        background:
            radial-gradient(circle at top left, rgba(123, 44, 191, 0.16), transparent 25%),
            radial-gradient(circle at bottom right, rgba(88, 28, 135, 0.14), transparent 22%),
            linear-gradient(180deg, #2b0d47 0%, #321150 42%, #2a0d43 100%);
        min-height: 100vh;
        padding: 28px 5vw 64px;
        color: #ffffff;
        display: flex;
        justify-content: center;
    }

    .product-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.18fr) minmax(360px, 0.92fr);
        gap: 34px;
        width: 100%;
        max-width: 1320px;
        align-items: start;
    }

    .product-gallery,
    .product-info-panel,
    .product-description-left {
        position: relative;
        border-radius: 28px;
        overflow: hidden;
        background:
            linear-gradient(145deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
        border: 1px solid rgba(255,255,255,0.07);
        box-shadow: 0 18px 46px rgba(0, 0, 0, 0.34);
        backdrop-filter: blur(10px);
    }

    .product-gallery {
        padding: 18px;
    }

    .product-gallery-column {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .product-gallery-main {
        display: grid;
        grid-template-columns: 92px 1fr;
        gap: 18px;
        align-items: stretch;
    }

    .product-thumbs {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .product-thumb {
        width: 92px;
        height: 108px;
        border-radius: 16px;
        overflow: hidden;
        cursor: pointer;
        border: 1px solid rgba(255,255,255,0.08);
        background: rgba(255,255,255,0.03);
        transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        opacity: 0.82;
    }

    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .product-thumb:hover {
        transform: translateY(-2px);
        opacity: 1;
    }

    .product-thumb.active {
        border-color: rgba(196, 161, 255, 0.95);
        box-shadow: 0 0 0 2px rgba(123, 44, 191, 0.38);
        opacity: 1;
    }

    .product-main-image-wrapper {
        position: relative;
        height: min(78vh, 760px);
        max-height: 760px;
        border-radius: 24px;
        overflow: hidden;
        background:
            linear-gradient(145deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02));
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-main-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
        background: rgba(255,255,255,0.015);
    }

    .product-description-left {
        padding: 18px 20px;
    }

    .product-description-title {
        display: block;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: rgba(255,255,255,0.82);
        margin-bottom: 10px;
    }

    .product-description-text {
        font-size: 0.95rem;
        line-height: 1.65;
        color: rgba(255,255,255,0.94);
        white-space: pre-line;
        margin: 0;
    }

    .product-info-panel {
        padding: 28px 28px 30px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .product-title-main {
        font-size: 2rem;
        line-height: 1.16;
        font-weight: 700;
        margin: 0;
        color: #ffffff;
    }

    .product-price-main {
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        color: #efe1ff;
        text-shadow: 0 3px 18px rgba(123, 44, 191, 0.18);
    }

    .product-section-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: rgba(255,255,255,0.80);
        display: block;
        margin-bottom: 10px;
    }

    .product-size-select .form-select {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.10);
        color: #fff;
        font-size: 0.95rem;
        border-radius: 14px;
        padding: 12px 14px;
    }

    .product-size-select .form-select:focus,
    .product-freight-row input:focus {
        box-shadow: 0 0 0 0.14rem rgba(123, 44, 191, 0.42);
        border-color: #9d63e8;
    }

    .product-size-select .form-select option {
        color: #111;
    }

    .product-colors-list {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .product-color-dot {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.85);
        box-shadow: 0 0 0 3px rgba(31, 9, 52, 0.70);
        cursor: pointer;
        position: relative;
        transition: transform 0.18s ease, box-shadow 0.18s ease, opacity 0.18s ease;
    }

    .product-color-dot:hover {
        transform: scale(1.08);
        box-shadow: 0 0 0 3px rgba(123, 44, 191, 0.55);
    }

    .product-color-dot.selected {
        transform: scale(1.12);
        box-shadow: 0 0 0 3px rgba(123, 44, 191, 0.85), 0 0 0 6px rgba(255,255,255,0.06);
    }

    .product-color-dot::after {
        content: attr(data-color-name);
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        bottom: -20px;
        font-size: 10px;
        white-space: nowrap;
        color: rgba(255,255,255,0.84);
    }

    .product-selection-box {
        padding: 16px 18px;
        border-radius: 18px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.07);
    }

    .product-color-stock {
        margin-top: 18px;
        padding: 12px 14px;
        border-radius: 14px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.08);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .product-color-stock-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 26px;
        min-height: 26px;
        padding: 0 10px;
        border-radius: 999px;
        background: #751597;
        font-size: 12px;
        font-weight: 800;
        color: #fff;
        letter-spacing: 0.06em;
    }

    .product-color-stock-label {
        color: rgba(255,255,255,0.92);
    }

    .product-quantity-controls {
        display: inline-flex;
        align-items: center;
        background: rgba(255,255,255,0.05);
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.10);
        overflow: hidden;
    }

    .product-quantity-controls button {
        border: none;
        width: 40px;
        height: 40px;
        background: transparent;
        color: #fff;
        font-size: 1.15rem;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }

    .product-quantity-controls button:hover {
        background-color: rgba(123, 44, 191, 0.34);
    }

    .product-quantity-controls input {
        width: 64px;
        text-align: center;
        border: none;
        background: transparent;
        color: #fff;
        font-size: 0.95rem;
    }

    .product-quantity-controls input:focus {
        outline: none;
    }

    .product-quantity-hint {
        display: block;
        margin-top: 10px;
        font-size: 0.8rem;
        color: rgba(255,255,255,0.74);
    }

    .product-freight-block {
        padding: 16px 18px;
        border-radius: 18px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.07);
    }

    .product-action-buttons {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .product-cart-btn,
    .product-buy-main-btn,
    .product-freight-row button {
        width: 100%;
        border: none;
        border-radius: 999px;
        padding: 14px 20px;
        background: #751597;
        color: #ffffff;
        font-weight: 800;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.10em;
        transition: transform 0.15s ease, opacity 0.15s ease, filter 0.15s ease;
    }

    .product-cart-btn:hover,
    .product-buy-main-btn:hover,
    .product-freight-row button:hover {
        transform: translateY(-1px);
        filter: brightness(1.06);
    }

    .product-freight-row {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .product-freight-row input {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.10);
        color: #fff;
        border-radius: 14px;
        padding: 12px 14px;
    }

    @media (max-width: 1020px) {
        .product-grid {
            grid-template-columns: 1fr;
        }

        .product-main-image-wrapper {
            height: auto;
            max-height: none;
            min-height: 520px;
        }

        .product-main-image {
            min-height: 520px;
            max-height: 520px;
        }
    }

    @media (max-width: 700px) {
        .product-page {
            padding: 18px 12px 42px;
        }

        .product-gallery {
            padding: 14px;
            border-radius: 22px;
        }

        .product-gallery-main {
            grid-template-columns: 1fr;
        }

        .product-thumbs {
            order: 2;
            flex-direction: row;
            width: 100%;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .product-thumb {
            min-width: 78px;
            width: 78px;
            height: 94px;
        }

        .product-main-image-wrapper {
            height: auto;
            min-height: 380px;
            max-height: none;
        }

        .product-main-image {
            min-height: 380px;
            max-height: 380px;
        }

        .product-info-panel {
            padding: 20px 18px 22px;
            border-radius: 22px;
        }

        .product-title-main {
            font-size: 1.5rem;
        }

        .product-price-main {
            font-size: 1.6rem;
        }

        .product-freight-row {
            flex-direction: column;
        }
    }
</style>

<section class="product-page">
    <div class="product-grid">

        <div class="product-gallery-column">
            <div class="product-gallery">
                <div class="product-gallery-main">
                    <div class="product-thumbs" id="product-thumbs"></div>

                    <div class="product-main-image-wrapper">
                        <img
                            id="product-main-image"
                            class="product-main-image"
                            src="{{ !empty($primeiraVariacao['imagens'][0]) ? asset('img/' . $primeiraVariacao['imagens'][0]) : asset('img/sem-imagem.png') }}"
                            alt="{{ $produto->titulo }}"
                        >
                    </div>
                </div>
            </div>

            <div class="product-description-left">
                <span class="product-description-title">Descrição</span>
                <p class="product-description-text">{{ $produto->descricao }}</p>
            </div>
        </div>

        <div class="product-info-panel">

            <div>
                <h1 class="product-title-main">{{ $produto->titulo }}</h1>
            </div>

            <div>
                <p class="product-price-main" id="product-price-main">
                    R$ {{ number_format($primeiraVariacao['valor'] ?? 0, 2, ',', '.') }}
                </p>
            </div>

            <div class="product-selection-box">
                <div class="product-size-select">
                    <label for="select-tamanho" class="product-section-label">Tamanho</label>
                    <select id="select-tamanho" class="form-select">
                        <option value="">Selecione um tamanho</option>
                        @foreach($tamanhosUnicos as $tamanho)
                            <option value="{{ $tamanho }}" {{ ($primeiraVariacao['tamanho'] ?? '') === $tamanho ? 'selected' : '' }}>
                                {{ $tamanho }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if(count($coresLista) > 0)
                    <div class="product-colors-block mt-4">
                        <span class="product-section-label">Cores</span>

                        <div class="product-colors-list" id="product-colors-list">
                            @foreach($coresLista as $index => $c)
                                <div
                                    class="product-color-dot {{ ($primeiraVariacao['cor_key'] ?? '') === mb_strtolower($c['nome']) ? 'selected' : '' }}"
                                    style="background-color: {{ $c['hex'] }};"
                                    data-color-name="{{ $c['nome'] }}"
                                    data-color-key="{{ mb_strtolower($c['nome']) }}"
                                    data-index="{{ $index }}"
                                    title="{{ $c['nome'] }}"
                                ></div>
                            @endforeach
                        </div>

                        <div id="product-color-stock" class="product-color-stock">
                            <span class="product-color-stock-pill" id="product-color-stock-pill">-</span>
                            <span class="product-color-stock-label" id="product-color-stock-label">
                                Selecione tamanho e cor para ver a quantidade disponível.
                            </span>
                        </div>
                    </div>
                @endif

                <div class="product-quantity-block mt-4">
                    <span class="product-section-label">Quantidade</span>

                    <div class="product-quantity-controls">
                        <button type="button" id="qty-minus">−</button>
                        <input type="number" id="product-quantity" value="1" min="1">
                        <button type="button" id="qty-plus">+</button>
                    </div>

                    <small class="product-quantity-hint" id="product-quantity-hint">
                        Selecione tamanho e cor para ver o estoque disponível.
                    </small>
                </div>
            </div>

            <form id="form-add-cart" method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $produto->id }}">
                <input type="hidden" name="color" id="cart-color">
                <input type="hidden" name="size" id="cart-size">
                <input type="hidden" name="quantity" id="cart-quantity">
                <input type="hidden" name="mode" id="cart-mode" value="cart">

                <div class="product-action-buttons">
                    <button
                        type="button"
                        class="product-cart-btn"
                        id="btn-add-cart"
                    >
                        Adicionar ao carrinho
                    </button>

                    <button
                        type="button"
                        class="product-buy-main-btn"
                        id="btn-buy-now"
                    >
                        Comprar agora
                    </button>
                </div>
            </form>

            <div class="product-freight-block">
                <label for="cep" class="product-section-label" style="margin-bottom:0;">Calcular frete</label>
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
    const variacoes = @json($variacoes);

    const mainImg = document.getElementById('product-main-image');
    const thumbsContainer = document.getElementById('product-thumbs');
    const priceEl = document.getElementById('product-price-main');

    const selectTamanho = document.getElementById('select-tamanho');
    const colorDots = document.querySelectorAll('.product-color-dot');

    const stockPill = document.getElementById('product-color-stock-pill');
    const stockLabel = document.getElementById('product-color-stock-label');

    const qtyInput = document.getElementById('product-quantity');
    const qtyMinus = document.getElementById('qty-minus');
    const qtyPlus = document.getElementById('qty-plus');
    const qtyHint = document.getElementById('product-quantity-hint');

    const formAddCart = document.getElementById('form-add-cart');
    const cartColorInput = document.getElementById('cart-color');
    const cartSizeInput = document.getElementById('cart-size');
    const cartQuantityInput = document.getElementById('cart-quantity');
    const cartModeInput = document.getElementById('cart-mode');

    const btnAddCart = document.getElementById('btn-add-cart');
    const btnBuyNow = document.getElementById('btn-buy-now');

    const freteBtn = document.getElementById('btn-calcular-frete');

    let selectedColorKey = @json($primeiraVariacao['cor_key'] ?? '');
    let selectedColorName = @json($primeiraVariacao['cor'] ?? '');
    let selectedSize = @json($primeiraVariacao['tamanho'] ?? '');
    let currentVariation = variacoes.length ? variacoes[0] : null;
    let selectedStock = parseInt(@json($primeiraVariacao['qtd'] ?? 0), 10) || 0;

    function formatCurrency(value) {
        return Number(value || 0).toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        });
    }

    function getVariationByColorAndSize(corKey, tamanho) {
        return variacoes.find(function (variacao) {
            return variacao.cor_key === corKey && variacao.tamanho === tamanho;
        }) || null;
    }

    function getFirstVariationByColor(corKey) {
        return variacoes.find(function (variacao) {
            return variacao.cor_key === corKey;
        }) || null;
    }

    function renderThumbs(images) {
        if (!thumbsContainer) return;

        thumbsContainer.innerHTML = '';

        const fallbackImage = '{{ asset('img/sem-imagem.png') }}';
        const lista = Array.isArray(images) && images.length ? images : [fallbackImage];

        lista.forEach(function (imgSrc, index) {
            const finalSrc = imgSrc.startsWith('http') ? imgSrc : ('{{ asset('img') }}/' + imgSrc);

            const thumb = document.createElement('div');
            thumb.className = 'product-thumb' + (index === 0 ? ' active' : '');
            thumb.setAttribute('data-image', finalSrc);

            const img = document.createElement('img');
            img.src = finalSrc;
            img.alt = 'Imagem ' + (index + 1) + ' de {{ $produto->titulo }}';

            thumb.appendChild(img);
            thumbsContainer.appendChild(thumb);

            thumb.addEventListener('click', function () {
                mainImg.src = finalSrc;
                thumbsContainer.querySelectorAll('.product-thumb').forEach(function (t) {
                    t.classList.remove('active');
                });
                thumb.classList.add('active');
            });
        });

        const firstSrc = lista[0].startsWith('http') ? lista[0] : ('{{ asset('img') }}/' + lista[0]);
        mainImg.src = firstSrc;
    }

    function updateStockInfo() {
        if (!currentVariation) {
            selectedStock = 0;
            stockPill.textContent = '0';
            stockLabel.textContent = 'Variação indisponível.';
            qtyHint.textContent = 'Variação indisponível.';
            qtyInput.value = 1;
            qtyInput.min = 1;
            qtyInput.max = 1;
            return;
        }

        selectedStock = parseInt(currentVariation.qtd || 0, 10);

        if (selectedStock <= 0) {
            stockPill.textContent = '0';
            stockLabel.textContent = `Cor ${selectedColorName} no tamanho ${selectedSize}: esgotado.`;
            qtyHint.textContent = `Combinação ${selectedColorName} / ${selectedSize} indisponível.`;
            qtyInput.value = 1;
            qtyInput.min = 1;
            qtyInput.max = 1;
            return;
        }

        stockPill.textContent = selectedStock;
        stockLabel.textContent =
            `Cor ${selectedColorName} no tamanho ${selectedSize}: ${selectedStock} ` +
            `${selectedStock === 1 ? 'unidade disponível' : 'unidades disponíveis'}.`;

        qtyHint.textContent =
            `Você pode comprar até ${selectedStock} ${selectedStock === 1 ? 'unidade' : 'unidades'} dessa combinação.`;

        qtyInput.min = 1;
        qtyInput.max = selectedStock;

        let current = parseInt(qtyInput.value || '1', 10);
        if (isNaN(current) || current < 1) current = 1;
        if (current > selectedStock) current = selectedStock;
        qtyInput.value = current;
    }

    function updateVariationUI() {
        let variation = getVariationByColorAndSize(selectedColorKey, selectedSize);

        if (!variation && selectedColorKey) {
            variation = getFirstVariationByColor(selectedColorKey);
            if (variation) {
                selectedSize = variation.tamanho;
                if (selectTamanho) selectTamanho.value = variation.tamanho;
            }
        }

        currentVariation = variation;

        colorDots.forEach(function (dot) {
            dot.classList.toggle('selected', dot.getAttribute('data-color-key') === selectedColorKey);
        });

        if (currentVariation) {
            selectedColorName = currentVariation.cor;
            selectedSize = currentVariation.tamanho;
            priceEl.textContent = formatCurrency(currentVariation.valor);
            renderThumbs(currentVariation.imagens || []);
        }

        updateStockInfo();
    }

    colorDots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            selectedColorKey = this.getAttribute('data-color-key') || '';
            selectedColorName = this.getAttribute('data-color-name') || '';
            updateVariationUI();
        });
    });

    if (selectTamanho) {
        selectTamanho.addEventListener('change', function () {
            selectedSize = this.value || '';
            updateVariationUI();
        });
    }

    qtyMinus.addEventListener('click', function () {
        let val = parseInt(qtyInput.value || '1', 10);
        if (isNaN(val) || val <= 1) {
            qtyInput.value = 1;
            return;
        }
        qtyInput.value = val - 1;
    });

    qtyPlus.addEventListener('click', function () {
        let val = parseInt(qtyInput.value || '1', 10);
        if (isNaN(val) || val < 1) val = 1;
        if (val < selectedStock) {
            qtyInput.value = val + 1;
        }
    });

    qtyInput.addEventListener('input', function () {
        let val = parseInt(qtyInput.value || '1', 10);
        if (isNaN(val) || val < 1) val = 1;
        if (selectedStock > 0 && val > selectedStock) val = selectedStock;
        qtyInput.value = val;
    });

    function submitCart(mode) {
        const quantity = parseInt(qtyInput.value || '1', 10);

        if (!selectedSize) {
            alert('Selecione um tamanho antes de continuar.');
            return;
        }

        if (!selectedColorName) {
            alert('Selecione uma cor antes de continuar.');
            return;
        }

        if (!currentVariation || selectedStock <= 0) {
            alert('Essa combinação está sem estoque.');
            return;
        }

        if (isNaN(quantity) || quantity <= 0) {
            alert('Informe uma quantidade válida.');
            return;
        }

        if (quantity > selectedStock) {
            alert('Quantidade selecionada maior que o estoque disponível.');
            return;
        }

        cartColorInput.value = selectedColorName;
        cartSizeInput.value = selectedSize;
        cartQuantityInput.value = quantity;
        cartModeInput.value = mode;

        formAddCart.submit();
    }

    btnAddCart.addEventListener('click', function () {
        submitCart('cart');
    });

    btnBuyNow.addEventListener('click', function () {
        submitCart('buy_now');
    });

    if (freteBtn) {
        freteBtn.addEventListener('click', function () {
            const cep = document.getElementById('cep').value;
            console.log('Calcular frete para CEP:', cep);
        });
    }

    updateVariationUI();
});
</script>

@endsection