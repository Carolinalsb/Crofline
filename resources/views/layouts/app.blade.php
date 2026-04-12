<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Crofline')</title>

    {{-- Bootstrap + Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --crofline-roxo-escuro: #24034d;
            --crofline-roxo-fundo: #321150;
            --crofline-rosa: #ff3b9d;
            --crofline-texto: #f2f2f2;
            --crofline-header-altura: 4.45rem;
            --crofline-header-bg-scroll: rgba(36, 3, 77, 0.78);
            --crofline-header-borda: rgba(255, 255, 255, 0.06);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding-top: var(--crofline-header-altura);
            background-color: var(--crofline-roxo-fundo);
            color: var(--crofline-texto);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        /* ===== HEADER FIXO / NAV TRANSPARENTE ===== */

        .crofline-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 2000;
        }

        .menu-nav-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.2rem;
            height: var(--crofline-header-altura);
            background: transparent;
            border-bottom: 1px solid transparent;
            transition:
                background-color 0.28s ease,
                backdrop-filter 0.28s ease,
                border-color 0.28s ease,
                height 0.28s ease,
                box-shadow 0.28s ease;
        }

        .menu-nav-main.nav-scrolled {
            background: var(--crofline-header-bg-scroll);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--crofline-header-borda);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.16);
        }

        .nav-left,
        .nav-center,
        .nav-right {
            display: flex;
            align-items: center;
        }

        .nav-center {
            flex: 1;
            justify-content: center;
        }

        .nav-right {
            justify-content: flex-end;
        }

        .nav-left {
            gap: .75rem;
        }

        .btn-menu-mobile {
            border: none;
            outline: none;
            background: transparent;
            color: var(--crofline-texto);
            font-size: 1.55rem;
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 0;
        }

        .btn-menu-mobile span {
            font-size: 0.72rem;
            letter-spacing: 0.18em;
            margin-left: 0.25rem;
        }

        .brand-crofline {
            letter-spacing: 0.38em;
            font-size: 0.92rem;
            color: var(--crofline-texto);
            text-transform: uppercase;
            text-align: center;
            white-space: nowrap;
        }

        /* LOGO DESKTOP */

        .nav-logo-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .brand-logo-desktop {
            height: 4.85rem;
            width: auto;
            display: block;
            transition: transform 0.2s ease, filter 0.2s ease, opacity 0.2s ease;
        }

        .brand-logo-desktop:hover {
            transform: scale(1.03);
            filter: drop-shadow(0 0 6px rgba(255, 255, 255, 0.25));
        }

        .nav-icons {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-icon-btn {
            border: none;
            outline: none;
            background: transparent;
            color: var(--crofline-texto);
            font-size: 1.28rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.15s ease, color 0.15s ease, opacity 0.15s ease;
            padding: 0;
        }

        .nav-icon-btn:hover {
            transform: translateY(-1px);
            color: #ffb3ff;
        }

        /* ===== NAV CATEGORIAS DESKTOP DENTRO DA BARRA ===== */

        .menu-categorias-desktop {
            list-style: none;
            display: flex;
            gap: 2.25rem;
            margin: 0;
            padding: 0;
            font-size: 0.80rem;
            letter-spacing: 0.12em;
        }

        .menu-categorias-desktop li {
            position: relative;
            cursor: pointer;
            text-transform: uppercase;
            color: var(--crofline-texto);
            padding: 0.18rem 0 0.42rem 0;
            transition: color 0.22s ease, opacity 0.22s ease;
        }

        .menu-categorias-desktop li::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 2px;
            width: 16px;
            background: var(--crofline-rosa);
            transition: width 0.22s ease;
        }

        .menu-categorias-desktop li:hover {
            color: #ffffff;
        }

        .menu-categorias-desktop li:hover::after {
            width: 28px;
        }

        /* ===== MENU MOBILE (SLIDE LEFT) ===== */

        .menu-mobile-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            z-index: 1900;
        }

        .menu-mobile-overlay.aberto {
            opacity: 1;
            visibility: visible;
        }

        .menu-mobile {
            position: fixed;
            top: 0;
            left: 0;
            width: 270px;
            max-width: 80%;
            height: 100%;
            background: #1b0437;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 2001;
            padding: 1.5rem 1.25rem;
            display: flex;
            flex-direction: column;
        }

        .menu-mobile.aberto {
            transform: translateX(0);
        }

        .menu-mobile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .menu-mobile-title {
            font-size: 0.9rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .btn-close-menu {
            border: none;
            outline: none;
            background: transparent;
            color: var(--crofline-texto);
            font-size: 1.4rem;
            cursor: pointer;
        }

        .menu-mobile ul {
            list-style: none;
            padding-left: 0;
            margin: 0 0 1.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .menu-mobile li {
            color: #f7e9ff;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .menu-mobile li span {
            border-bottom: 1px solid transparent;
        }

        .menu-mobile li:hover span {
            border-color: var(--crofline-rosa);
        }

        .btn-login-mobile {
            margin-top: auto;
            border-radius: 999px;
            border: 1px solid var(--crofline-rosa);
            padding: 0.55rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: transparent;
            color: var(--crofline-texto);
            font-size: 0.9rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .btn-login-mobile i {
            font-size: 1.2rem;
        }

        .btn-login-mobile:hover {
            background: rgba(255, 59, 157, 0.18);
        }

        /* ===== CONTEÚDO ===== */

        #conteudo {
            width: 1600px;
            max-width: 100%;
            margin: 0 auto;
        }

        .conteudo-principal.blur {
            filter: blur(3px);
            pointer-events: none;
            user-select: none;
        }

        /* ===== POPUP LOGIN/CADASTRO ===== */

        .popup-cadastro {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 820px;
            max-width: 95vw;
            height: 420px;
            background: #f4e8ff;
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 3000;
            border-radius: 20px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.45);
        }

        .popup-cadastro.ativo {
            display: flex;
        }

        .popup-cadastro .container-popup {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            border-radius: 20px;
            overflow: hidden;
            background-color: #f4e8ff;
            transition: all 0.4s ease;
        }

        #toggle {
            display: none;
        }

        .left {
            flex: 1;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #f4e8ff;
            transition: all 0.6s ease;
        }

        .right {
            flex: 1;
            padding: 30px 34px 30px 34px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #f4e8ff;
            transition: all 0.6s ease;
        }

        .middle {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            background: linear-gradient(135deg, #7b2cbf, #a855f7);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: all 0.6s ease;
            z-index: 10;
            color: #fff;
            text-align: center;
            padding: 0 20px;
        }

        #toggle:checked~.container-popup .middle {
            left: 0;
            background: linear-gradient(135deg, #24034d, #7b2cbf);
        }

        .botao-cadastrar-label {
            background: #fff;
            color: #24034d;
            border: none;
            padding: 10px 24px;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: bold;
            font-size: 0.95rem;
            margin-top: 18px;
        }

        .botao-cadastrar-label:hover {
            background: #24034d;
            color: #fff;
        }

        .fechar-popup {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
            z-index: 20;
        }

        .fechar-popup i {
            font-size: 22px;
            color: #24034d;
            transition: color 0.3s ease, transform 0.2s ease;
        }

        .fechar-popup:hover i {
            color: #431280;
            transform: scale(1.08);
        }

        .botao-voltar {
            position: absolute;
            top: 12px;
            left: 14px;
            background: none;
            border: none;
            cursor: pointer;
            z-index: 20;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .botao-voltar i {
            font-size: 26px;
            color: #ffb3ff;
            transition: color 0.3s ease, transform 0.2s ease;
        }

        .container-popup.step-2 .botao-voltar {
            display: flex;
        }

        .botao-voltar:hover i {
            color: #fff;
            transform: translateX(-2px);
        }

        .campo-input {
            display: flex;
            align-items: center;
            width: 100%;
            min-width: 0;
            overflow: hidden;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 15px;
        }

        .campo-input i {
            flex: 0 0 auto;
            margin-right: 10px;
            color: #543062;
            font-size: 1.2rem;
        }

        .campo-input input {
            flex: 1 1 auto;
            width: 100%;
            min-width: 0;
            max-width: 100%;
            border: none;
            outline: none;
            font-size: 0.95rem;
            background: transparent;
        }

        .campo-input input:focus {
            border: none;
            outline: none;
        }

        input:focus {
            border: 1.45px solid #a855f7;
            box-shadow: 0 0 5px rgba(168, 85, 247, 0.5);
        }

        .form-login button,
        .form-cadastro button {
            background-color: #24034d;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.15s ease;
        }

        .form-login button:hover,
        .form-cadastro button:hover {
            background-color: #a855f7;
            transform: translateY(-1px);
        }

        .form-login h2,
        .form-cadastro h2 {
            color: #24034d;
            margin-bottom: 20px;
            font-size: 1.8rem;
            text-align: center;
        }

        .form-login {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .form-cadastro {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .cadastro-wrapper {
            position: relative;
            flex: 1;
            overflow: hidden;
            margin-top: 5px;
        }

        .cadastro-step {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            transition: transform 0.5s ease;
        }

        .cadastro-step1 {
            transform: translateX(0);
        }

        .cadastro-step2 {
            transform: translateX(100%);
        }

        .container-popup.step-2 .cadastro-step1 {
            transform: translateX(-100%);
        }

        .container-popup.step-2 .cadastro-step2 {
            transform: translateX(0);
        }

        .grid-cadastro {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px 20px;
        }

        #btn-continuar-cadastro {
            margin-top: 16px;
            width: 100%;
        }

        .container-popup.step-2 #btn-continuar-cadastro {
            display: none;
        }

        #btn-cadastrar-final {
            margin-top: 16px;
            width: 100%;
        }

        .cadastro-sucesso-overlay {
            position: fixed;
            inset: 0;
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 4000;
            pointer-events: none;
        }

        .cadastro-sucesso-overlay.ativo {
            display: flex;
        }

        .cadastro-sucesso-card {
            background: #e6ffed;
            border-radius: 16px;
            padding: 18px 26px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            gap: 14px;
            border: 1px solid #22c55e;
        }

        .cadastro-sucesso-card i {
            font-size: 28px;
            color: #16a34a;
        }

        .cadastro-sucesso-card p {
            margin: 0;
            font-weight: 600;
            color: #14532d;
        }

        .cadastro-sucesso-card span {
            display: block;
            font-size: 13px;
            color: #166534;
        }

        /* ===== CARRINHO POPUP GLOBAL (DRAWER) ===== */

        .cart-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            display: flex;
            justify-content: flex-end;
            align-items: stretch;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        .cart-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .cart-drawer {
            width: 360px;
            max-width: 100%;
            background: #1a0730;
            box-shadow: -6px 0 20px rgba(0, 0, 0, 0.6);
            transform: translateX(100%);
            transition: transform 0.25s ease;
            display: flex;
            flex-direction: column;
        }

        .cart-overlay.active .cart-drawer {
            transform: translateX(0);
        }

        .cart-header {
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cart-header h3 {
            margin: 0;
            font-size: 18px;
        }

        .cart-close-btn {
            border: none;
            background: transparent;
            color: #fff;
            font-size: 22px;
            cursor: pointer;
        }

        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 16px 18px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 24px 64px 1fr;
            gap: 10px;
            background: #2a0a46;
            border-radius: 8px;
            padding: 8px;
            align-items: center;
        }

        .cart-item img {
            width: 64px;
            height: 80px;
            border-radius: 6px;
            object-fit: cover;
        }

        .cart-item-title {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .cart-item-meta {
            font-size: 11px;
            opacity: 0.9;
            margin-bottom: 2px;
        }

        .cart-item-price {
            font-size: 13px;
            font-weight: 600;
        }

        .cart-footer {
            padding: 14px 18px 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .cart-total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .cart-primary-btn,
        .cart-secondary-btn {
            width: 100%;
            border-radius: 999px;
            padding: 9px 16px;
            border: none;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
            margin-top: 6px;
            cursor: pointer;
        }

        .cart-primary-btn {
            background: linear-gradient(90deg, #7b2cbf, #a855f7);
            color: #fff;
        }

        .cart-primary-btn:hover {
            opacity: 0.95;
        }

        .cart-secondary-btn {
            background: transparent;
            color: #fff;
            border: 1px solid #5f2491;
        }

        .cart-secondary-btn:hover {
            background-color: #321150;
        }

        /* ===== POPUP CONTA DO USUÁRIO (LOGADO) ===== */

        .user-popup-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 3500;
        }

        .user-popup-overlay.ativo {
            display: flex;
        }

        .user-popup-card {
            background: radial-gradient(circle at top, #4b1b7a, #24034d 60%);
            border-radius: 20px;
            padding: 24px 26px;
            min-width: 280px;
            max-width: 360px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.6);
            color: #f9f5ff;
            position: relative;
            overflow: hidden;
        }

        .user-popup-card::before {
            content: '';
            position: absolute;
            inset: -40%;
            background:
                radial-gradient(circle at 10% 0, rgba(255, 59, 157, 0.25), transparent 55%),
                radial-gradient(circle at 100% 100%, rgba(111, 66, 193, 0.35), transparent 55%);
            opacity: 0.55;
            pointer-events: none;
        }

        .user-popup-header {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .user-popup-avatar {
            width: 46px;
            height: 46px;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
        }

        .user-popup-header h3 {
            margin: 0;
            font-size: 1.1rem;
        }

        .user-popup-header span {
            font-size: 0.83rem;
            opacity: 0.86;
        }

        .user-popup-close-btn {
            position: absolute;
            top: 0;
            right: 0;
            border: none;
            background: transparent;
            color: #f9f5ff;
            cursor: pointer;
            font-size: 1.1rem;
        }

        .user-popup-actions {
            position: relative;
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .user-popup-btn {
            width: 100%;
            border-radius: 999px;
            border: 1px solid rgba(248, 250, 252, 0.2);
            background: rgba(15, 23, 42, 0.35);
            color: #f9f5ff;
            padding: 9px 14px;
            font-size: 0.86rem;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
        }

        .user-popup-btn.primary {
            border-color: transparent;
            background: linear-gradient(90deg, #ff3b9d, #a855f7);
        }

        .user-popup-btn:hover {
            filter: brightness(1.05);
        }

        .user-popup-btn i {
            font-size: 1rem;
        }

        @media (max-width: 992px) {
            .brand-crofline {
                font-size: 0.95rem;
                letter-spacing: 0.28em;
            }

            .menu-categorias-desktop {
                display: none;
            }
        }

        @media (min-width: 993px) {
            .btn-menu-mobile span {
                display: inline;
            }

            .btn-menu-mobile {
                display: none;
            }
        }

        @media (max-width: 768px) {
            :root {
                --crofline-header-altura: 4rem;
            }

            .popup-cadastro {
                height: auto;
            }

            .popup-cadastro .container-popup {
                flex-direction: column;
            }

            .middle {
                display: none;
            }

            .grid-cadastro {
                grid-template-columns: 1fr;
            }

            .brand-logo-desktop {
                height: 4.2rem;
            }
        }
    </style>
</head>

<body>
    @php
        $cartItems = session('cart', []);
        $cartTotal = 0;
        foreach ($cartItems as $item) {
            $cartTotal += $item['total_value'];
        }

        $userId = session('user_id');
        $userName = session('user_name');
        $userEmail = session('user_email');
    @endphp

    {{-- FORM PARA ENVIAR CATEGORIA --}}
    <form method="POST" action="{{ route('product.produtos') }}" id="formulario">
        @csrf
        <input type="hidden" name="categoria" id="categoria" />
    </form>

    {{-- HEADER / NAV --}}
    <header class="crofline-header">
        <nav class="menu-nav-main" id="menu-nav-main">
            {{-- ESQUERDA: logo (desktop) + sanduíche (mobile) --}}
            <div class="nav-left">
                <a href="{{ url('/') }}" class="nav-logo-link d-none d-lg-inline-block">
                    <img src="{{ asset('img/logoCrof.png') }}"
                         alt="Crofline"
                         class="brand-logo-desktop">
                </a>

                <button type="button" class="btn-menu-mobile d-lg-none" id="btn-menu-mobile" aria-label="Abrir menu">
                    <i class="bi bi-list"></i>
                    <span class="d-none d-md-inline">MENU</span>
                </button>
            </div>

            {{-- CENTRO: CROFLINE (mobile) + categorias (desktop) --}}
            <div class="nav-center">
                <span class="brand-crofline d-lg-none">CROFLINE</span>

                <ul class="menu-categorias-desktop d-none d-lg-flex">
                    <li onclick="acessarProdutos('Calça')">CALÇAS</li>
                    <li onclick="acessarProdutos('Body')">BODYS</li>
                    <li onclick="acessarProdutos('Cropped')">CROPPED</li>
                    <li onclick="acessarProdutos('Camiseta')">CAMISETAS</li>
                    <li onclick="acessarProdutos('Conjunto')">CONJUNTOS</li>
                    <li onclick="acessarProdutos('Vestido')">VESTIDOS</li>
                    <li onclick="acessarProdutos('Acessório')">ACESSÓRIOS</li>
                </ul>
            </div>

            {{-- DIREITA: ícones --}}
            <div class="nav-right">
                <div class="nav-icons">
                    <button type="button" class="nav-icon-btn" aria-label="Buscar">
                        <i class="bi bi-search-heart-fill"></i>
                    </button>
                    <button type="button" class="nav-icon-btn" aria-label="Login">
                        <i class="bi bi-person-fill" id="btn-login"></i>
                    </button>
                    <button
                        type="button"
                        class="nav-icon-btn"
                        aria-label="Carrinho"
                        id="btn-cart"
                    >
                        <i class="bi bi-bag-heart-fill"></i>
                    </button>
                </div>
            </div>
        </nav>
    </header>

    {{-- MENU MOBILE SLIDE --}}
    <div class="menu-mobile-overlay" id="menu-mobile-overlay"></div>
    <aside class="menu-mobile" id="menu-mobile">
        <div class="menu-mobile-header">
            <span class="menu-mobile-title">Menu</span>
            <button class="btn-close-menu" id="btn-close-menu" aria-label="Fechar menu">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <ul>
            <li onclick="acessarProdutos('Calça')"><span>Calças</span></li>
            <li onclick="acessarProdutos('Body')"><span>Bodys</span></li>
            <li onclick="acessarProdutos('Cropped')"><span>Cropped</span></li>
            <li onclick="acessarProdutos('Camiseta')"><span>Camisetas</span></li>
            <li onclick="acessarProdutos('Conjunto')"><span>Conjuntos</span></li>
            <li onclick="acessarProdutos('Vestido')"><span>Vestidos</span></li>
            <li onclick="acessarProdutos('Acessório')"><span>Acessórios</span></li>
        </ul>

        <button type="button" class="btn-login-mobile" id="btn-login-mobile">
            <i class="bi bi-person-fill"></i>
            <span>Login / Cadastro</span>
        </button>
    </aside>

    {{-- CONTEÚDO --}}
    <div id="conteudo" class="conteudo-principal @if(session('success')) blur @endif">
        @yield('content')
    </div>

    {{-- FORM GLOBAL PARA ENVIAR ITENS SELECIONADOS PRO RESUMO --}}
    <form id="cart-resumo-form" method="POST" action="{{ route('product.resumo') }}">
        @csrf
    </form>

    {{-- CARRINHO GLOBAL (DRAWER) --}}
    <div id="cart-overlay" class="cart-overlay">
        <div class="cart-drawer">
            <div class="cart-header">
                <h3>Carrinho</h3>
                <button type="button" class="cart-close-btn" id="cart-close-btn">&times;</button>
            </div>

            <div class="cart-items" id="cart-items">
                @if(count($cartItems) === 0)
                    <p style="font-size:13px; opacity:0.8;">Seu carrinho está vazio.</p>
                @else
                    @foreach($cartItems as $key => $item)
                        <div class="cart-item">
                            <div class="d-flex justify-content-center">
                                <input type="checkbox"
                                       class="form-check-input"
                                       name="selected_items[]"
                                       value="{{ $key }}"
                                       form="cart-resumo-form">
                            </div>

                            <img src="{{ asset('img/' . $item['image']) }}" alt="{{ $item['title'] }}">

                            <div>
                                <div class="cart-item-title">{{ $item['title'] }}</div>
                                <div class="cart-item-meta">
                                    Tamanho: {{ $item['size'] }} &nbsp;|&nbsp;
                                    Cor: {{ $item['color'] }}
                                </div>
                                <div class="cart-item-meta">
                                    Quantidade: {{ $item['quantity'] }}
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <div class="cart-item-price">
                                        R$ {{ number_format($item['total_value'], 2, ',', '.') }}
                                    </div>

                                    <button type="submit"
                                            form="cart-remove-{{ $key }}"
                                            class="btn btn-link p-0"
                                            style="font-size:11px;color:#ff9b9b;text-decoration:underline;">
                                        remover
                                    </button>
                                </div>
                            </div>
                        </div>

                        <form id="cart-remove-{{ $key }}" method="POST" action="{{ route('cart.remove') }}" style="display:none;">
                            @csrf
                            <input type="hidden" name="key" value="{{ $key }}">
                        </form>
                    @endforeach
                @endif
            </div>

            <div class="cart-footer">
                <div class="cart-total-row">
                    <span>Total</span>
                    <span id="cart-total">
                        R$ {{ number_format($cartTotal, 2, ',', '.') }}
                    </span>
                </div>
                <button type="submit"
                        class="cart-primary-btn"
                        id="cart-checkout"
                        form="cart-resumo-form">
                    Finalizar compra
                </button>
                <button type="button" class="cart-secondary-btn" id="cart-continue">
                    Continuar comprando
                </button>
            </div>
        </div>
    </div>

    {{-- POPUP CONTA DO USUÁRIO (QUANDO LOGADO) --}}
    <div id="user-popup-overlay" class="user-popup-overlay">
        <div class="user-popup-card">
            <div class="user-popup-header">
                <div class="user-popup-avatar">
                    <i class="bi bi-person-heart"></i>
                </div>
                <div>
                    <h3>{{ $userName ?? 'Usuário Crofline' }}</h3>
                    <span>{{ $userEmail ?? '' }}</span>
                </div>

                <button type="button" id="user-popup-close" class="user-popup-close-btn" aria-label="Fechar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="user-popup-actions">
                <button type="button" class="user-popup-btn primary" id="user-popup-manage">
                    <i class="bi bi-gear-fill"></i>
                    GERENCIAR CONTA
                </button>

                <a href="{{ route('account.logout') }}"><button type="button" class="user-popup-btn" id="user-popup-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    SAIR
                </button></a>
            </div>
        </div>
    </div>

    {{-- POP-UP LOGIN/CADASTRO --}}
    <div id="popup-cadastro" class="popup-cadastro">
        <input type="checkbox" id="toggle">

        <div class="container-popup" id="container-popup">

            <button type="button" class="botao-voltar" id="btn-voltar-etapa">
                <i class="bi bi-arrow-left-circle"></i>
            </button>

            <div class="left">
                <form class="form-login" id="form-login">
                    <h2>Login</h2>

                    <div class="campo-input">
                        <i class="bi bi-envelope"></i>
                        <input type="email"
                               id="login-email"
                               name="email"
                               placeholder="Digite seu e-mail"
                               required>
                    </div>

                    <div class="campo-input">
                        <i class="bi bi-lock"></i>
                        <input type="password"
                               id="login-senha"
                               name="senha"
                               placeholder="Digite sua senha"
                               required>
                    </div>

                    <button type="submit">Entrar</button>
                </form>
            </div>

            <div class="right">
                <form class="form-cadastro" id="form-cadastro">
                    <h2>Cadastro</h2>

                    <div class="cadastro-wrapper">

                        <div class="cadastro-step cadastro-step1">
                            <div class="grid-cadastro">
                                <div class="campo-input">
                                    <i class="bi bi-person"></i>
                                    <input type="text" id="cadastro-nome" name="nome" placeholder="Digite seu nome" required>
                                </div>

                                <div class="campo-input">
                                    <i class="bi bi-envelope"></i>
                                    <input type="email" id="cadastro-email" name="email" placeholder="Digite seu e-mail" required>
                                </div>

                                <div class="campo-input">
                                    <i class="bi bi-lock"></i>
                                    <input type="password" id="cadastro-senha" name="senha" placeholder="Crie uma senha" required>
                                </div>

                                <div class="campo-input">
                                    <i class="bi bi-lock-fill"></i>
                                    <input type="password" name="confirmar_senha" placeholder="Confirme sua senha" id="cadastro-confirmar-senha-2" required>
                                </div>
                            </div>
                        </div>

                        <div class="cadastro-step cadastro-step2">
                            <div class="grid-cadastro">
                                <div class="campo-input">
                                    <i class="bi bi-person"></i>
                                    <input type="text" name="sobrenome" placeholder="Digite o sobrenome" id="cadastro-sobrenome-2">
                                </div>

                                <div class="campo-input">
                                    <i class="bi bi-telephone"></i>
                                    <input type="text" name="telefone" placeholder="Digite o telefone" id="cadastro-telefone-2">
                                </div>

                                <div class="campo-input">
                                    <i class="bi bi-calendar-event"></i>
                                    <input type="date" name="data_nascimento" placeholder="Data de nascimento" id="cadastro-nascimento-2">
                                </div>

                                <div class="campo-input">
                                    <i class="bi bi-person-vcard"></i>
                                    <input type="text" id="cadastro-cpf" name="cpf" placeholder="Digite seu CPF">
                                </div>
                            </div>
                        </div>

                    </div>

                    <button type="button" id="btn-continuar-cadastro">Continuar</button>

                    <button type="submit" id="btn-cadastrar-final">Cadastrar</button>
                </form>
            </div>

            <div class="middle">
                <h2 id="middle-titulo">Bem-vindo à Crofline</h2>
                <p id="middle-texto">Cadastre-se para começar</p>
                <button id="middle-botao" class="botao-cadastrar-label"
                        onclick="document.getElementById('toggle').click()">
                    Cadastrar
                </button>
            </div>
        </div>

        <button type="button" class="fechar-popup" aria-label="Fechar">
            <i class="bi bi-x-circle"></i>
        </button>
    </div>

    <div id="cadastro-sucesso"
         class="cadastro-sucesso-overlay @if(session('success')) ativo @endif">
        <div class="cadastro-sucesso-card">
            <i class="bi bi-check-circle-fill"></i>
            <div>
                <p>{{ session('success') ?? 'Cadastro realizado com sucesso!' }}</p>
                <span id="cadastro-sucesso-contador">Fechando em 3...</span>
            </div>
        </div>
    </div>

    <footer></footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const CROFLINE_IS_AUTH = @json(auth()->check());

        window.CROFLINE_CART_OPEN = @json(session('cart_open') || session('cart_success'));
        window.CROFLINE_CART_SUCCESS_MESSAGE = @json(session('cart_success'));
        window.CROFLINE_CART_ERROR_MESSAGE   = @json(session('cart_error'));

        const CROFLINE_USER_ID    = @json($userId);
        const CROFLINE_USER_NAME  = @json($userName);
        const CROFLINE_USER_EMAIL = @json($userEmail);
    </script>

    <script src="{{ asset('js/cart.js') }}"></script>

    <script>
        const categoria = document.getElementById('categoria');
        const formulario = document.getElementById('formulario');

        function acessarProdutos(valor) {
            categoria.value = valor;
            formulario.submit();
        }

        function abrirPopupCadastro() {
            const popup = document.getElementById('popup-cadastro');
            const conteudo = document.querySelector('.conteudo-principal');
            const toggle = document.getElementById('toggle');
            const containerPopup = document.getElementById('container-popup');

            if (popup && conteudo && toggle && containerPopup) {
                popup.classList.add('ativo');
                conteudo.classList.add('blur');
                toggle.checked = false;
                containerPopup.classList.remove('step-2');
            }
        }

        function limparCamposPopup() {
            const popup = document.getElementById('popup-cadastro');
            if (!popup) return;

            popup.querySelectorAll('input').forEach(input => {
                if (input.type === 'checkbox') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });
        }

        function fecharPopupCadastro() {
            const popup = document.getElementById('popup-cadastro');
            const conteudo = document.querySelector('.conteudo-principal');
            const containerPopup = document.getElementById('container-popup');
            const toggle = document.getElementById('toggle');

            if (popup && conteudo) {
                popup.classList.remove('ativo');
                conteudo.classList.remove('blur');
            }

            if (containerPopup) {
                containerPopup.classList.remove('step-2');
            }

            if (toggle) {
                toggle.checked = false;
            }

            limparCamposPopup();
        }

        function mostrarSucessoCadastro() {
            const overlay = document.getElementById('cadastro-sucesso');
            const textoContador = document.getElementById('cadastro-sucesso-contador');

            if (!overlay || !textoContador) return;

            let contador = 3;
            overlay.classList.add('ativo');
            textoContador.textContent = `Fechando em ${contador}...`;

            const intervalo = setInterval(() => {
                contador--;
                if (contador <= 0) {
                    clearInterval(intervalo);
                    overlay.classList.remove('ativo');
                    fecharPopupCadastro();
                    window.location.reload();
                } else {
                    textoContador.textContent = `Fechando em ${contador}...`;
                }
            }, 1000);
        }

        function abrirPopupConta() {
            const overlay = document.getElementById('user-popup-overlay');
            const conteudo = document.querySelector('.conteudo-principal');

            if (overlay && conteudo) {
                overlay.classList.add('ativo');
                conteudo.classList.add('blur');
            }
        }

        function fecharPopupConta() {
            const overlay = document.getElementById('user-popup-overlay');
            const conteudo = document.querySelector('.conteudo-principal');

            if (overlay && conteudo) {
                overlay.classList.remove('ativo');
                conteudo.classList.remove('blur');
            }
        }

        function atualizarHeaderScroll() {
            const nav = document.getElementById('menu-nav-main');
            if (!nav) return;

            if (window.scrollY > 16) {
                nav.classList.add('nav-scrolled');
            } else {
                nav.classList.remove('nav-scrolled');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const loginIcon       = document.getElementById('btn-login');
            const toggle          = document.getElementById('toggle');
            const titulo          = document.getElementById('middle-titulo');
            const texto           = document.getElementById('middle-texto');
            const botao           = document.getElementById('middle-botao');
            const botaoFechar     = document.querySelector('.fechar-popup');
            const containerPopup  = document.getElementById('container-popup');
            const btnContinuar    = document.getElementById('btn-continuar-cadastro');
            const btnVoltarEtapa  = document.getElementById('btn-voltar-etapa');
            const formLogin       = document.getElementById('form-login');
            const formCadastro    = document.getElementById('form-cadastro');

            const userPopupOverlay = document.getElementById('user-popup-overlay');
            const userPopupClose   = document.getElementById('user-popup-close');
            const userPopupLogout  = document.getElementById('user-popup-logout');
            const userPopupManage  = document.getElementById('user-popup-manage');

            atualizarHeaderScroll();
            window.addEventListener('scroll', atualizarHeaderScroll);

            if (loginIcon) {
                loginIcon.addEventListener('click', function () {
                    if (CROFLINE_USER_ID) {
                        abrirPopupConta();
                    } else {
                        abrirPopupCadastro();
                    }
                });
            }

            if (userPopupOverlay) {
                userPopupOverlay.addEventListener('click', function (e) {
                    if (e.target === userPopupOverlay) {
                        fecharPopupConta();
                    }
                });
            }

            if (userPopupClose) {
                userPopupClose.addEventListener('click', fecharPopupConta);
            }

            if (userPopupLogout) {
                userPopupLogout.addEventListener('click', function () {
                    fecharPopupConta();
                });
            }

            if (userPopupManage) {
                userPopupManage.addEventListener('click', function () {
                    // futura página "minha conta"
                });
            }

            if (toggle && titulo && texto && botao) {
                toggle.addEventListener('change', () => {
                    if (containerPopup) {
                        containerPopup.classList.remove('step-2');
                    }

                    if (toggle.checked) {
                        titulo.textContent = 'Já é cadastrado?';
                        texto.textContent = 'Realize o login';
                        botao.textContent = 'Login';
                    } else {
                        titulo.textContent = 'Bem-vindo à Crofline';
                        texto.textContent = 'Cadastre-se para começar';
                        botao.textContent = 'Cadastrar';
                    }
                });
            }

            if (botaoFechar) {
                botaoFechar.addEventListener('click', fecharPopupCadastro);
            }

            if (btnContinuar && containerPopup) {
                btnContinuar.addEventListener('click', function () {
                    containerPopup.classList.add('step-2');
                });
            }

            if (btnVoltarEtapa && containerPopup) {
                btnVoltarEtapa.addEventListener('click', function () {
                    containerPopup.classList.remove('step-2');
                });
            }

            if (formLogin) {
                formLogin.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    if (!formLogin.checkValidity()) {
                        alert('Por favor, preencha todos os campos corretamente.');
                        return;
                    }

                    const formData = new FormData(formLogin);
                    const token = document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content');

                    formData.append('_token', token);

                    try {
                        const response = await fetch("{{ route('account.login') }}", {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });

                        const data = await response.json().catch(() => ({}));

                        if (response.status === 422) {
                            if (data.errors) {
                                let msg = 'Erro ao fazer login:\n';
                                for (const field in data.errors) {
                                    msg += '- ' + data.errors[field].join('\n- ') + '\n';
                                }
                                alert(msg);
                            } else if (data.message) {
                                alert(data.message);
                            } else {
                                alert('Dados de login inválidos.');
                            }
                            return;
                        }

                        if (!response.ok || !data.success) {
                            alert(data.message || 'Ocorreu um erro ao fazer login. Tente novamente.');
                            return;
                        }

                        alert(data.message || 'Login realizado com sucesso!');
                        window.location.reload();

                    } catch (error) {
                        console.error(error);
                        alert('Ocorreu um erro ao fazer login. Tente novamente.');
                    }
                });
            }

            if (formCadastro) {
                formCadastro.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    if (!formCadastro.checkValidity()) {
                        alert('Por favor, preencha todos os campos corretamente.');
                        return;
                    }

                    const senha = document.getElementById('cadastro-senha')?.value || '';
                    const confirmar = document.getElementById('cadastro-confirmar-senha-2')?.value || '';

                    if (senha !== confirmar) {
                        alert('As senhas não conferem.');
                        return;
                    }

                    const formData = new FormData(formCadastro);
                    formData.append('sobrenome', document.getElementById('cadastro-sobrenome-2')?.value || '');
                    formData.append('telefone', document.getElementById('cadastro-telefone-2')?.value || '');
                    formData.append('data_nascimento', document.getElementById('cadastro-nascimento-2')?.value || '');

                    const token = document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content');

                    formData.append('_token', token);

                    try {
                        const response = await fetch("{{ route('account.register') }}", {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });

                        if (response.status === 422) {
                            const data = await response.json();
                            let msg = 'Erro de validação:\n';
                            for (const field in data.errors) {
                                msg += '- ' + data.errors[field].join('\n- ') + '\n';
                            }
                            alert(msg);
                            return;
                        }

                        if (!response.ok) {
                            alert('Ocorreu um erro ao cadastrar. Tente novamente.');
                            return;
                        }

                        const data = await response.json();

                        if (data.success) {
                            mostrarSucessoCadastro();
                        } else {
                            alert(data.message || 'Ocorreu um erro ao cadastrar. Tente novamente.');
                        }

                    } catch (error) {
                        console.error(error);
                        alert('Ocorreu um erro ao cadastrar. Tente novamente.');
                    }
                });
            }

            const btnMenuMobile   = document.getElementById('btn-menu-mobile');
            const menuMobile      = document.getElementById('menu-mobile');
            const overlayMenu     = document.getElementById('menu-mobile-overlay');
            const btnCloseMenu    = document.getElementById('btn-close-menu');
            const btnLoginMobile  = document.getElementById('btn-login-mobile');

            function abrirMenuMobile() {
                if (menuMobile) menuMobile.classList.add('aberto');
                if (overlayMenu) overlayMenu.classList.add('aberto');
            }

            function fecharMenuMobile() {
                if (menuMobile) menuMobile.classList.remove('aberto');
                if (overlayMenu) overlayMenu.classList.remove('aberto');
            }

            if (btnMenuMobile) {
                btnMenuMobile.addEventListener('click', abrirMenuMobile);
            }
            if (btnCloseMenu) {
                btnCloseMenu.addEventListener('click', fecharMenuMobile);
            }
            if (overlayMenu) {
                overlayMenu.addEventListener('click', fecharMenuMobile);
            }

            if (btnLoginMobile) {
                btnLoginMobile.addEventListener('click', function () {
                    fecharMenuMobile();
                    if (CROFLINE_USER_ID) {
                        abrirPopupConta();
                    } else {
                        abrirPopupCadastro();
                    }
                });
            }
        });
    </script>
</body>

</html>