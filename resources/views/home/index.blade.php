@extends('layouts.app')

@section('content')
    <style>
        /* HERO / CARROSSEL INDEX */

        #sec-carousel {
            position: relative;
            width: 100%;
            height: 100vh;
            margin-top: -5.5rem;
            overflow: hidden;
        }

        #sec-carousel .carousel,
        #sec-carousel .carousel-inner,
        #sec-carousel .carousel-item {
            width: 100%;
            height: 100%;
        }

        #sec-carousel .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            display: block;
        }

        /* INDICATORS (BOLINHAS) */

        .hero-carousel .carousel-indicators {
            position: absolute;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%);
            margin: 0;
            padding: 0;
            justify-content: center;
            gap: 10px;
        }

        .hero-carousel .carousel-indicators [data-bs-target] {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 1px solid #cfd5ff;
            background-color: transparent;
            opacity: 1;
            margin: 0;
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hero-carousel .carousel-indicators .active {
            background-color: #ffffff;
            box-shadow: 0 0 0 2px rgba(255, 59, 157, 0.4);
            transform: scale(1.1);
        }

        /* CONTROLES ANTERIOR / PRÓXIMO */

        .hero-carousel .carousel-control-prev,
        .hero-carousel .carousel-control-next {
            width: auto;
            top: auto;
            bottom: 26px;
            transform: none;
            background: transparent;
            opacity: 1;
        }

        .hero-carousel .carousel-control-prev {
            left: 40px;
        }

        .hero-carousel .carousel-control-next {
            right: 40px;
        }

        .hero-carousel .carousel-control-prev span,
        .hero-carousel .carousel-control-next span {
            font-size: 0.8rem;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: #ffffff; /* AGORA BRANCO */
        }

        .hero-carousel .carousel-control-prev-icon,
        .hero-carousel .carousel-control-next-icon {
            display: none;
        }

        @media (max-width: 768px) {
            #sec-carousel {
                height: 70vh;
            }

            .hero-carousel .carousel-control-prev {
                left: 16px;
            }

            .hero-carousel .carousel-control-next {
                right: 16px;
            }

            .hero-carousel .carousel-control-prev span,
            .hero-carousel .carousel-control-next span {
                font-size: 0.7rem;
            }

            .hero-carousel .carousel-indicators {
                bottom: 18px;
            }
        }
    </style>

    @if (isset($carrossel) && count($carrossel))
        <form method="POST" action="{{ route('product.produtos') }}" id="formulario-index">
            @csrf
            <input type="hidden" name="categoria" id="id-carousel" />

            <section id="sec-carousel">
                <div id="croflineCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel"
                     data-bs-interval="4000">

                    {{-- INDICADORES --}}
                    <div class="carousel-indicators">
                        @foreach ($carrossel as $c)
                            <button type="button"
                                    data-bs-target="#croflineCarousel"
                                    data-bs-slide-to="{{ $loop->index }}"
                                    class="{{ $loop->first ? 'active' : '' }}"
                                    @if($loop->first) aria-current="true" @endif
                                    aria-label="Slide {{ $loop->iteration }}">
                            </button>
                        @endforeach
                    </div>

                    {{-- SLIDES --}}
                    <div class="carousel-inner">
                        @foreach ($carrossel as $c)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <img src="{{ asset('img/' . $c->carrossel_item) }}"
                                     alt="{{ $c->titulo ?? 'Produto Crofline' }}"
                                     onclick="acessarProdutoCarousel({{ $c->id }})">
                            </div>
                        @endforeach
                    </div>

                    {{-- CONTROLES TEXTO --}}
                    <button class="carousel-control-prev" type="button"
                            data-bs-target="#croflineCarousel" data-bs-slide="prev">
                        <span>ANTERIOR</span>
                    </button>
                    <button class="carousel-control-next" type="button"
                            data-bs-target="#croflineCarousel" data-bs-slide="next">
                        <span>PRÓXIMO</span>
                    </button>
                </div>
            </section>
        </form>
    @else
        <div class="d-flex align-items-center justify-content-center" style="height: 60vh;">
            <h2 class="text-white-50">Em breve novidades na Crofline ✨</h2>
        </div>
    @endif

    <script>
        const idCarouselInput = document.getElementById('id-carousel');
        const formularioIndex = document.getElementById('formulario-index');

        function acessarProdutos(idProduto) {
            if (!idCarouselInput || !formularioIndex) return;
            idCarouselInput.value = idProduto;
            formularioIndex.submit();
        }
    </script>
@endsection
