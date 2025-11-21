@extends('layouts.app')

@section('content')
    <style>
        /*Section: .carousel .carousel_item */
        #sec-carousel {
            position: relative;
            width: 100%;
            height: 100vh;

        }


        .carousel {
            display: flex;
            transition: transform 0.4s ease;
            width: 100%;
            height: 100%;

        }

        .carousel_item {
            position: absolute;
            user-select: none;
            inset: 0;
            min-width: 100%;
        }

        .carousel_item img {
            width: 100%;
            display: block;
            cursor: pointer;
        }
    </style>
    @if (isset($carrossel))
        <form method="POST" action="{{ route('product.produtos') }}" id="formulario">
            @csrf
            <input type="hidden" name="id" id="id-carousel" />

            <section id="sec-carousel">

                <div class="carousel" id = "carousel">

                    @foreach ($carrossel as $c)
                        <div class="carousel_item">

                            <img src="{{ asset('img/' . $c->carrossel_item) }}" height="650px;" width="100%"
                                onclick="acessarProdutos({{ $c->id }})">

                        </div>
                    @endforeach


                </div>
                <button class = "btn prev" id = "prev">&#10094;</button>
                <button class = "btn next" id = "next">&#10095;</button>
            </section>
        </form>
    @else
        <div class="carousel">
            <h1></h1>
        </div>
    @endif
    <script>
        //Funcionamento do Mecanismo do ID do Carrossel
        idCarousel = document.getElementById('id-carousel');
        formulario = document.getElementById('formulario');

        function acessarProdutos(valor) {
            idCarousel.value = valor;
            formulario.submit();

        }

        /*Funcionamento do Carrossel 
        document.addEventListener("DOMContentLoaded", function() {
            const carousel = document.getElementById('carousel');
            const item = Array.from(carousel.children);
            const prev = document.getElementById('prev');
            const next = document.getElementById('next');
            let index = 0;

            function updateCarousel() {
                const width =
                    item[0].getBoundingClientRect().width;
                carousel.style.transform = `translateX(-$ {index*width}px)`;

            }
            next.addEventListener('click', () => {
                index = (index + 1) % item.length;
                updateCarousel();
            });
            prev.addEventListener('click', () => {
                index = (index - 1 + item.length) % item.length;
                updateCarousel();
            });
            setInterval(() => {
                next.click();
            }, 6000);
            

        });
        */
    </script>
@endsection
