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
    }

    .product-image {
        width: 100%;
        height: 30rem;
        display: block;
        object-fit: cover;
        transition: transform 0.4s ease;
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
        background-color: rgba(128, 0, 128, 0.9); /* roxo botão */
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

    /* Responsividade extra (pode ajustar depois) */
    @media (max-width: 576px) {
        .products-section {
            padding: 20px 12px 40px;
        }

        .product-buy-btn {
            width: 80%;
            padding: 8px 0;
        }
    }
</style>

<section class="products-section">
    <div class="product-container">
        @foreach ($dados as $produto)
            <div class="product-card">
                <div class="product-image-wrapper">
                      <img
                        class="product-image"
                        src="{{ asset('img/' . $produto->imagem) }}"
                        alt="{{ $produto->titulo }}"
                        data-image-primary="{{ asset('img/' . $produto->imagem) }}"
                        data-image-secondary="{{ $produto->imagem2 ? asset('img/' . $produto->imagem2) : '' }}"
                    >
                      
                    <a href="{{route('product.show', ['id'=>$produto->id])}}"><button
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
                // aqui depois vocês colocam a lógica de ir pro carrinho / detalhe / etc
            });
        });
    });
</script>

@endsection
