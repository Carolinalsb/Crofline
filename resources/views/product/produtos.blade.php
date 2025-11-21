@extends('layouts.app')

@section('title', 'Crofline | Produtos')

@section('content')

    <style>
        .product-container {
            display: grid;
            grid-template-columns: repeat(4, 320px);
            width; 1600px;
            max-width: 100%;
            background-color: #321150ff;
            padding: 0px 40px;
            margin: 0 auto;
            justify-self: center;
        }
        .product{
            display: flex;
            width: 8rem;
            heigth: 16rem;
            
        }
     
    </style>

    <div class="product-container">
        @foreach ($dados as $produto)
            <div class="product">
                <img src="{{ asset('/img/' . $produto->imagem) }}" alt="{{ $produto->titulo }}" style="width: 301px; height: 452px;" />
            </div>
        @endforeach

    </div>

@endsection
