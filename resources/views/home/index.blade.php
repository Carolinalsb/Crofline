@extends('layouts.app')

@section('content')
    <style>
        .carousel {
            background-color: #49034D;
            height: 88vh;

        }

        .carousel_item {
            border-radius: 50px;
            background-color: red;
            height: 50px;


        }
    </style>
    @if (isset($carrossel))
        <div class="carousel">
            <h1>{{ $carrossel[1]->carrossel_item }}</h1>
            @for ($i = 0; $i < count($carrossel); $i++)
                <div class="carousel_item">

                    <img src="{{ asset('img/' . $carrossel[1]->carrossel_item) }}" height="650px;" width="100%">

                    <div class="carousel_circle">


                        <div class="circle_item">

                        </div>


                    </div>

                </div>
            @endfor
        </div>
    @else
        <div class="carousel">
            <h1></h1>
        </div>
    @endif
@endsection
