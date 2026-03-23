@extends('layouts.app')

@section('content')
    <style>
        /* HERO / CARROSSEL INDEX */

        #sec-carousel {
            position: relative;
            width: 100%;
            height: 100vh;
            margin-top: calc(var(--crofline-header-altura) * -1);
            overflow: hidden;
            background:
                radial-gradient(circle at center, rgba(122, 44, 191, 0.18), transparent 45%),
                linear-gradient(90deg, rgba(36, 3, 77, 0.25), rgba(36, 3, 77, 0.08), rgba(36, 3, 77, 0.25));
        }

        #sec-carousel::after {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                linear-gradient(to bottom, rgba(10, 5, 20, 0.18), rgba(10, 5, 20, 0.04) 18%, rgba(10, 5, 20, 0.18)),
                linear-gradient(to right, rgba(10, 5, 20, 0.18), transparent 18%, transparent 82%, rgba(10, 5, 20, 0.18));
            z-index: 2;
        }

        #sec-carousel .carousel,
        #sec-carousel .carousel-inner,
        #sec-carousel .carousel-item {
            width: 100%;
            height: 100%;
        }

        #sec-carousel .carousel-inner {
            overflow: hidden;
        }

        #sec-carousel .carousel-item {
            transition: transform 1.1s ease, opacity 1.1s ease;
            transform: scale(1);
        }

        #sec-carousel .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            display: block;
            transform: scale(1.02);
            transition: transform 6s ease, filter 0.9s ease, opacity 0.9s ease;
            will-change: transform, opacity;
        }

        #sec-carousel .carousel-item.active img {
            transform: scale(1.08);
        }

        #sec-carousel .carousel-item.carousel-item-start img,
        #sec-carousel .carousel-item.carousel-item-end img {
            transform: scale(0.92);
            filter: brightness(0.92);
            opacity: 0.88;
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
            z-index: 4;
        }

        .hero-carousel .carousel-indicators [data-bs-target] {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 1px solid #d8dcff;
            background-color: transparent;
            opacity: 1;
            margin: 0;
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hero-carousel .carousel-indicators .active {
            background-color: #ffffff;
            box-shadow: 0 0 0 2px rgba(255, 59, 157, 0.45);
            transform: scale(1.08);
        }

        /* CONTROLES ANTERIOR / PRÓXIMO */

        .hero-carousel .carousel-control-prev,
        .hero-carousel .carousel-control-next {
            width: auto;
            top: auto;
            bottom: 22px;
            transform: none;
            background: transparent;
            opacity: 1;
            z-index: 4;
        }

        .hero-carousel .carousel-control-prev {
            left: 42px;
        }

        .hero-carousel .carousel-control-next {
            right: 42px;
        }

        .hero-carousel .carousel-control-prev span,
        .hero-carousel .carousel-control-next span {
            font-size: 0.78rem;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: #ffffff;
            text-shadow: 0 1px 10px rgba(0, 0, 0, 0.45);
        }

        .hero-carousel .carousel-control-prev-icon,
        .hero-carousel .carousel-control-next-icon {
            display: none;
        }

        @media (max-width: 768px) {
            #sec-carousel {
                height: 72vh;
            }

            .hero-carousel .carousel-control-prev {
                left: 16px;
            }

            .hero-carousel .carousel-control-next {
                right: 16px;
            }

            .hero-carousel .carousel-control-prev span,
            .hero-carousel .carousel-control-next span {
                font-size: 0.68rem;
            }

            .hero-carousel .carousel-indicators {
                bottom: 18px;
            }
        }
    </style>

    @if (isset($carrossel) && count($carrossel))
        <section id="sec-carousel">
            <div id="croflineCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel" data-bs-interval="4500">

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
    @else
        <div class="d-flex align-items-center justify-content-center" style="height: 60vh;">
            <h2 class="text-white-50">Em breve novidades na Crofline ✨</h2>
        </div>
    @endif

    <script>
        function acessarProdutoCarousel(idProduto) {
            window.location.href = "{{ url('/product/show') }}/" + idProduto;
        }
    </script>
@endsection