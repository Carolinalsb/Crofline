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
        background-color: rgba(128, 0, 128, 0.9);
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
                    $imagensDecodificadas = json_decode($produto->imagem, true);
                    $listaImagens = [];

                    if (is_array($imagensDecodificadas)) {
                        foreach ($imagensDecodificadas as $itemImagem) {
                            if (is_array($itemImagem) && isset($itemImagem['imagem']) && !empty($itemImagem['imagem'])) {
                                $listaImagens[] = $itemImagem['imagem'];
                            } elseif (is_string($itemImagem) && !empty($itemImagem)) {
                                $listaImagens[] = $itemImagem;
                            }
                        }
                    }

                    if (empty($listaImagens) && !empty($produto->imagem)) {
                        $listaImagens[] = $produto->imagem;
                    }

                    $imagemPrincipal = $listaImagens[0] ?? null;
                    $imagemSecundaria = $listaImagens[1] ?? null;
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
                            R$ {{ number_format($produto->valor, 2, ',', '.') }}
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