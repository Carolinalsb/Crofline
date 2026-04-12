@extends('layouts.app')

@section('title', 'Crofline | Produtos')

@section('content')

<style>
    .products-section {
        background-color: #321150;
        padding: 40px 40px 60px;
        min-height: 100vh;
    }

    .product-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 32px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .product-card {
        background-color: #321150;
        display: flex;
        flex-direction: column;
        color: #ffffff;
    }

    .product-image-wrapper {
        position: relative;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 10px;
    }

    .product-image {
        width: 100%;
        height: 30rem;
        display: block;
        object-fit: cover;
        transition: transform 0.4s ease, opacity 0.25s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.03);
    }

    .product-buy-btn {
        position: absolute;
        left: 50%;
        bottom: 18px;
        transform: translateX(-50%);
        padding: 10px 40px;
        border-radius: 0;
        border: none;
        font-weight: 600;
        background: linear-gradient(90deg, #3b0764, #5b21b6, #6d28d9);
        color: #fff;
        text-transform: uppercase;
        font-size: 14px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease;
    }

    .product-card:hover .product-buy-btn {
        opacity: 1;
        pointer-events: auto;
    }

    .product-info {
        padding-top: 12px;
    }

    .product-title {
        font-size: 14px;
        margin: 0;
    }

    .product-price {
        font-size: 15px;
        margin: 4px 0 0 0;
        font-weight: 600;
        color: #f5ddff;
    }

    .products-empty {
        max-width: 1400px;
        margin: 0 auto;
        color: #ffffff;
        text-align: center;
        padding: 80px 20px;
        opacity: 0.85;
    }

    @media (max-width: 576px) {
        .products-section {
            padding: 20px 12px 40px;
        }

        .product-buy-btn {
            width: 80%;
            padding: 8px 0;
        }

        .product-image {
            height: 24rem;
        }
    }
</style>

<section class="products-section">
    @if($dados->count())
        <div class="product-container">
            @foreach ($dados as $produto)
                @php
                    $detalhesBrutos = json_decode($produto->detalhes ?? '[]', true);
                    $detalhesBrutos = is_array($detalhesBrutos) ? $detalhesBrutos : [];

                    $variacoes = [];

                    foreach ($detalhesBrutos as $detalhe) {
                        if (!is_array($detalhe)) {
                            continue;
                        }

                        $valor = isset($detalhe['valor']) ? (float) $detalhe['valor'] : 0;

                        $imagens = [];
                        if (isset($detalhe['imagens']) && is_array($detalhe['imagens'])) {
                            foreach (['imagem1', 'imagem2', 'imagem3', 'imagem4'] as $chaveImagem) {
                                if (!empty($detalhe['imagens'][$chaveImagem])) {
                                    $imagens[] = $detalhe['imagens'][$chaveImagem];
                                }
                            }
                        }

                        $variacoes[] = [
                            'cor' => $detalhe['cor'] ?? '',
                            'tamanho' => $detalhe['tamanho'] ?? '',
                            'valor' => $valor,
                            'imagens' => $imagens,
                        ];
                    }

                    usort($variacoes, function ($a, $b) {
                        return $a['valor'] <=> $b['valor'];
                    });

                    $variacaoMaisBarata = $variacoes[0] ?? null;
                    $variacaoMaisCara = !empty($variacoes) ? $variacoes[count($variacoes) - 1] : null;

                    $precoExibicao = $variacaoMaisBarata['valor'] ?? 0;
                    $imagemPrincipal = $variacaoMaisCara['imagens'][0] ?? null;
                    $imagemSecundaria = $variacaoMaisBarata['imagens'][0] ?? null;
                @endphp

                <div class="product-card">
                    <div class="product-image-wrapper">
                        <img
                            class="product-image"
                            src="{{ $imagemPrincipal ? asset('img/' . $imagemPrincipal) : asset('img/sem-imagem.png') }}"
                            alt="{{ $produto->titulo }}"
                            data-image-primary="{{ $imagemPrincipal ? asset('img/' . $imagemPrincipal) : asset('img/sem-imagem.png') }}"
                            data-image-secondary="{{ $imagemSecundaria ? asset('img/' . $imagemSecundaria) : '' }}"
                        >

                        <a href="{{ route('product.show', ['id' => $produto->id]) }}">
                            <button
                                type="button"
                                class="product-buy-btn"
                                data-product-id="{{ $produto->id }}"
                            >
                                Comprar
                            </button>
                        </a>
                    </div>

                    <div class="product-info">
                        <p class="product-title">
                            {{ $produto->titulo }}
                        </p>
                        <p class="product-price">
                            R$ {{ number_format($precoExibicao, 2, ',', '.') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="products-empty">
            <h3>Nenhum produto encontrado nessa categoria no momento.</h3>
        </div>
    @endif
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.product-card');

        cards.forEach(function (card) {
            const img = card.querySelector('.product-image');
            const primary = img.dataset.imagePrimary;
            const secondary = img.dataset.imageSecondary;

            card.addEventListener('mouseenter', function () {
                if (secondary) {
                    img.src = secondary;
                }
            });

            card.addEventListener('mouseleave', function () {
                img.src = primary;
            });

            const buyBtn = card.querySelector('.product-buy-btn');

            buyBtn.addEventListener('click', function () {
                const productId = this.dataset.productId;
                console.log('ID do produto clicado:', productId);
            });
        });
    });
</script>

@endsection