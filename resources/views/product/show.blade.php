@extends('layouts.app')

@section('title', 'Crofline | ' . $produto->titulo)

@section('content')

@php
    // Imagens
    $imagens = array_filter([
        $produto->imagem  ? asset('img/' . $produto->imagem)  : null,
        $produto->imagem2 ? asset('img/' . $produto->imagem2) : null,
        $produto->imagem3 ? asset('img/' . $produto->imagem3) : null,
    ]);

    // Decodifica cores no formato:
    // [
    //   {"cor":"marrom","quantidade":5},
    //   {"cor":"azul","quantidade":10}
    // ]
    $coresBrutas = json_decode($produto->cores ?? '[]', true) ?: [];

    $mapaCores = [
        'preto'          => '#000000',
        'cinza'          => '#808080',
        'marrom'         => '#8B4513',
        'azul'           => '#1E90FF',
        'rosa'           => '#FF6FB5',
        'roxo'           => '#7B2CBF',
        'branco'         => '#FFFFFF',
        'bege'           => '#F5DEB3',
        'verde'          => '#32CD32',
        'nude'           => '#D2B48C',
        'marsala'        => '#964F4C',
        'marçala'        => '#964F4C',
        'off white'      => '#F8F5F0',
        'rosa claro'     => '#FFC0CB',
        'rosa choque'    => '#FF1493',
        'lilás'          => '#C8A2C8',
        'laranja'        => '#FFA500',
        'mostarda'       => '#FFDB58',
        'terracota'      => '#E2725B',
        'verde musgo'    => '#556B2F',
        'verde bandeira' => '#008000',
        'azul marinho'   => '#001F3F',
        'azul royal'     => '#4169E1',
        'jeans claro'    => '#7EA0B7',
        'vinho'          => '#722F37',
        'caramelo'       => '#AF6E4D',
    ];

    $cores = [];

    foreach ($coresBrutas as $c) {
        if (is_string($c)) {
            $nomeCor = trim($c);
            $nomeKey = mb_strtolower($nomeCor);
            $cores[] = [
                'nome'       => $nomeCor,
                'cor'        => $mapaCores[$nomeKey] ?? '#CCCCCC',
                'quantidade' => null,
            ];
        } elseif (is_array($c) && isset($c['cor'])) {
            $nomeCor   = trim($c['cor']);
            $nomeKey   = mb_strtolower($nomeCor);
            $quantidade = isset($c['quantidade']) ? (int) $c['quantidade'] : null;

            $cores[] = [
                'nome'       => $nomeCor,
                'cor'        => $mapaCores[$nomeKey] ?? '#CCCCCC',
                'quantidade' => $quantidade,
            ];
        }
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
    .product-gallery {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .product-gallery-main {
        display: flex;
        gap: 16px;
        align-items: stretch;
    }
    .product-thumbs {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 80px;
    }
    .product-thumb {
        width: 80px;
        height: 90px;
        border-radius: 6px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        background-color: #1f0934;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .product-thumb:hover { transform: translateY(-2px); }
    .product-thumb.active { border-color: #a855f7; }

    .product-main-image-wrapper {
        flex: 1;
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

    .product-info-panel {
        background-color: #1f0934;
        border-radius: 10px;
        padding: 24px 26px 28px;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .product-title-main { font-size: 22px; font-weight: 600; margin: 0; }
    .product-subtitle-category {
        font-size: 13px;
        opacity: 0.8;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .product-price-main { font-size: 26px; font-weight: 700; margin: 4px 0 0 0; }

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
        transition: transform 0.15s ease, box-shadow 0.15s ease, opacity 0.15s ease;
    }
    .product-color-dot:hover {
        transform: scale(1.06);
        box-shadow: 0 0 0 2px #a855f7;
    }
    .product-color-dot.selected {
        transform: scale(1.1);
        box-shadow: 0 0 0 2px #a855f7, 0 0 0 4px #321150;
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

    .product-color-stock {
        margin-top: 12px;
        padding: 10px 14px;
        border-radius: 8px;
        background-color: rgba(50, 17, 80, 0.85);
        border: 1px solid rgba(168, 85, 247, 0.5);
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .product-color-stock.hidden { display: none; }
    .product-color-stock-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 18px;
        min-height: 18px;
        padding: 0 8px;
        border-radius: 999px;
        background: linear-gradient(90deg, #7b2cbf, #a855f7);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .product-color-stock-label { opacity: 0.95; }

    .product-quantity-block { margin-top: 16px; }
    .product-quantity-block span.label {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        opacity: 0.9;
        display: block;
        margin-bottom: 6px;
    }
    .product-quantity-controls {
        display: inline-flex;
        align-items: center;
        background-color: #321150;
        border-radius: 999px;
        border: 1px solid #5f2491;
        overflow: hidden;
    }
    .product-quantity-controls button {
        border: none;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        color: #fff;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    .product-quantity-controls button:hover { background-color: #5f2491; }
    .product-quantity-controls input {
        width: 50px;
        text-align: center;
        border: none;
        background: transparent;
        color: #fff;
        font-size: 14px;
    }
    .product-quantity-controls input:focus { outline: none; }
    .product-quantity-hint {
        display: block;
        margin-top: 4px;
        font-size: 11px;
        opacity: 0.8;
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
    .product-buy-main-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
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

    @media (max-width: 900px) {
        .product-grid { grid-template-columns: minmax(0, 1fr); }
    }
    @media (max-width: 576px) {
        .product-page { padding: 20px 14px 40px; }
        .product-info-panel { padding: 18px 18px 22px; }
        .product-gallery-main { flex-direction: column; }
        .product-thumbs { flex-direction: row; width: auto; }
    }
</style>

<section class="product-page">
    <div class="product-grid">

        {{-- COLUNA ESQUERDA – GALERIA --}}
        <div class="product-gallery">
            <div class="product-gallery-main">
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

                <div class="product-main-image-wrapper">
                    <img
                        id="product-main-image"
                        class="product-main-image"
                        src="{{ $imagens[0] ?? '' }}"
                        alt="{{ $produto->titulo }}"
                    >
                </div>
            </div>
        </div>

        {{-- COLUNA DIREITA – INFORMAÇÕES --}}
        <div class="product-info-panel">

            <div>
                <p class="product-subtitle-category">{{ $produto->categorias }}</p>
                <h1 class="product-title-main">{{ $produto->titulo }}</h1>
            </div>

            <div>
                <p class="product-price-main">
                    R$ {{ number_format($produto->valor, 2, ',', '.') }}
                </p>
            </div>

            <div class="product-size-select">
                <label for="select-tamanho">Tamanho</label>
                <select id="select-tamanho" class="form-select mt-1">
                    <option value="{{ $produto->tamanho }}" selected>
                        {{ $produto->tamanho }}
                    </option>
                </select>
            </div>

            @if(count($cores) > 0)
                <div class="product-colors-block">
                    <span>Cores</span>
                    <div class="product-colors-list" id="product-colors-list">
                        @foreach($cores as $index => $c)
                            <div
                                class="product-color-dot"
                                style="background-color: {{ $c['cor'] }};"
                                data-color-name="{{ $c['nome'] }}"
                                data-quantity="{{ $c['quantidade'] ?? '' }}"
                                data-index="{{ $index }}"
                            ></div>
                        @endforeach
                    </div>

                    <div id="product-color-stock" class="product-color-stock hidden">
                        <span class="product-color-stock-pill" id="product-color-stock-pill">0</span>
                        <span class="product-color-stock-label" id="product-color-stock-label">
                            Selecione uma cor para ver a quantidade disponível.
                        </span>
                    </div>
                </div>
            @endif

            {{-- Quantidade --}}
            <div class="product-quantity-block">
                <span class="label">Quantidade</span>
                <div class="product-quantity-controls">
                    <button type="button" id="qty-minus">−</button>
                    <input type="number" id="product-quantity" value="1" min="1">
                    <button type="button" id="qty-plus">+</button>
                </div>
                <small class="product-quantity-hint" id="product-quantity-hint">
                    Selecione uma cor para ver o máximo disponível.
                </small>
            </div>

            <div class="product-description-block">
                <h4>Descrição</h4>
                <p class="product-description-text">{{ $produto->descricao }}</p>
            </div>

            <div class="product-sales">
                <span>Vendas</span><br>
                <strong>
                    {{ $produto->qtd_vendas }}
                    {{ $produto->qtd_vendas == 1 ? 'unidade' : 'unidades' }} vendidas
                </strong>
            </div>

            {{-- FORM PRA ADD TO CART --}}
            <form id="form-add-cart" method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $produto->id }}">
                <input type="hidden" name="color" id="cart-color">
                <input type="hidden" name="quantity" id="cart-quantity">

                <button
                    type="button"
                    class="product-buy-main-btn"
                    id="btn-buy"
                    data-product-id="{{ $produto->id }}"
                >
                    Comprar
                </button>
            </form>

            {{-- BLOCO FRETE / CEP (placeholder) --}}
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
    // ===== GALERIA =====
    const mainImg = document.getElementById('product-main-image');
    const thumbs  = document.querySelectorAll('.product-thumb');

    thumbs.forEach(thumb => {
        thumb.addEventListener('click', function () {
            const newSrc = this.getAttribute('data-image');
            if (!newSrc) return;

            mainImg.src = newSrc;
            thumbs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });

    const freteBtn = document.getElementById('btn-calcular-frete');
    if (freteBtn) {
        freteBtn.addEventListener('click', function () {
            const cep = document.getElementById('cep').value;
            console.log('Calcular frete para CEP:', cep);
        });
    }

    // ===== CORES + QUANTIDADE =====
    const colorDots   = document.querySelectorAll('.product-color-dot');
    const stockBox    = document.getElementById('product-color-stock');
    const stockPill   = document.getElementById('product-color-stock-pill');
    const stockLabel  = document.getElementById('product-color-stock-label');

    const qtyInput    = document.getElementById('product-quantity');
    const qtyMinus    = document.getElementById('qty-minus');
    const qtyPlus     = document.getElementById('qty-plus');
    const qtyHint     = document.getElementById('product-quantity-hint');

    const buyBtn      = document.getElementById('btn-buy');
    const formAddCart = document.getElementById('form-add-cart');
    const cartColorInput    = document.getElementById('cart-color');
    const cartQuantityInput = document.getElementById('cart-quantity');

    let selectedColorName  = null;
    let selectedColorStock = null;

    function updateQtyLimits() {
        let min = 1;
        let max = selectedColorStock !== null ? selectedColorStock : Infinity;

        if (selectedColorStock === null) {
            qtyHint.textContent = 'Selecione uma cor para ver o máximo disponível.';
        } else if (selectedColorStock <= 0) {
            qtyHint.textContent = `Cor ${selectedColorName}: esgotado.`;
        } else {
            qtyHint.textContent = `Cor ${selectedColorName}: máximo ${selectedColorStock} unidade(s) por compra.`;
        }

        qtyInput.min = 1;
        if (selectedColorStock !== null) {
            qtyInput.max = Math.max(1, selectedColorStock);
        } else {
            qtyInput.removeAttribute('max');
        }

        let current = parseInt(qtyInput.value || '1', 10);
        if (isNaN(current) || current < min) current = min;
        if (selectedColorStock !== null && current > max) current = max;
        qtyInput.value = current;
    }

    if (qtyMinus && qtyPlus && qtyInput) {
        qtyMinus.addEventListener('click', () => {
            let val = parseInt(qtyInput.value || '1', 10);
            if (isNaN(val)) val = 1;
            if (val > 1) val--;
            qtyInput.value = val;
        });

        qtyPlus.addEventListener('click', () => {
            let val = parseInt(qtyInput.value || '1', 10);
            if (isNaN(val)) val = 1;
            let max = selectedColorStock !== null ? selectedColorStock : Infinity;
            if (val < max) val++;
            qtyInput.value = val;
        });

        qtyInput.addEventListener('input', () => {
            let val = parseInt(qtyInput.value || '1', 10);
            if (isNaN(val) || val < 1) val = 1;
            let max = selectedColorStock !== null ? selectedColorStock : Infinity;
            if (val > max) val = max;
            qtyInput.value = val;
        });
    }

    if (colorDots.length > 0 && stockBox && stockPill && stockLabel) {
        colorDots.forEach(dot => {
            dot.addEventListener('click', () => {
                const nome       = dot.getAttribute('data-color-name') || '';
                const quantidade = dot.getAttribute('data-quantity');

                selectedColorName = nome;
                const qtdNum = quantidade === '' || quantidade === null
                    ? null
                    : parseInt(quantidade, 10);
                selectedColorStock = (qtdNum !== null && !isNaN(qtdNum)) ? qtdNum : null;

                colorDots.forEach(d => d.classList.remove('selected'));
                dot.classList.add('selected');

                stockBox.classList.remove('hidden');

                if (qtdNum === null || isNaN(qtdNum)) {
                    stockPill.textContent = '-';
                    stockLabel.textContent = `Estoque não informado para a cor ${nome}.`;
                } else if (qtdNum <= 0) {
                    stockPill.textContent = '0';
                    stockLabel.textContent = `Cor ${nome}: produto esgotado nesta cor.`;
                } else {
                    stockPill.textContent = qtdNum;
                    stockLabel.textContent =
                        `Cor ${nome}: ${qtdNum} ${qtdNum === 1 ? 'unidade disponível' : 'unidades disponíveis'}.`;
                }

                updateQtyLimits();
            });
        });
    }

    // ===== COMPRAR -> ENVIA PRO BACK =====
    if (buyBtn && formAddCart && cartColorInput && cartQuantityInput) {
        buyBtn.addEventListener('click', () => {
            const quantity = parseInt(qtyInput.value || '1', 10);

            if (!selectedColorName) {
                alert('Selecione uma cor antes de adicionar ao carrinho.');
                return;
            }

            if (isNaN(quantity) || quantity <= 0) {
                alert('Informe uma quantidade válida.');
                return;
            }

            if (selectedColorStock !== null && quantity > selectedColorStock) {
                alert('Quantidade selecionada maior que o estoque disponível.');
                return;
            }

            cartColorInput.value    = selectedColorName;
            cartQuantityInput.value = quantity;

            formAddCart.submit();
        });
    }
});
</script>

@endsection
