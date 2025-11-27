<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Crofline')</title>

    {{-- Bootstrap + Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0 auto;
            background-color: #321150;
        }

        .menu-nav {
            justify-content: space-between;
            display: flex;
            background: #24034d;
            height: 5.5rem;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .botoes-nav {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .botoes-nav ul {
            list-style: none;
            display: flex;
            gap: 70px;
            padding: 20px;
            justify-content: space-between;
            color: #f2f2f2;
            padding-bottom: 5px;
        }

        .botoes-nav li:hover {
            text-decoration: underline;
        }

        .login {
            display: flex;
            align-items: center;
            justify-content: end;
            gap: 10px;
            padding-right: 16px;
        }

        .login i {
            color: #f2f2f2;
            cursor: pointer;
            transition: transform 0.15s ease, color 0.15s ease;
        }

        .login i:hover {
            transform: translateY(-1px);
            color: #ffb3ff;
        }

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

        /* === POP-UP LOGIN/CADASTRO CROFLINE === */

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
        }

        #toggle {
            display: none;
        }

        .left,
        .right {
            flex: 1;
            padding: 30px;
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
            color: #ffffff;
            transform: scale(1.08);
        }

        .campo-input {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .campo-input i {
            margin-right: 10px;
            color: #543062;
            font-size: 1.2rem;
        }

        .campo-input input {
            border: none;
            outline: none;
            flex: 1;
            font-size: 0.95rem;
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
        }

        .form-login,
        .form-cadastro {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .popup-cadastro {
                height: auto;
            }

            .popup-cadastro .container-popup {
                flex-direction: column;
            }

            .middle {
                display: none;
            }
        }
    </style>
</head>

<body>
    {{-- FORM PARA ENVIAR CATEGORIA --}}
    <form method="POST" action="{{ route('product.produtos') }}" id="formulario">
        @csrf
        <input type="hidden" name="categoria" id="categoria" />
    </form>

    <header>
        <nav class="menu-nav">
            {{-- LOGOTIPO --}}
            <div class="logo">
                <img src="/img/logoCrof.png" alt="Logo do Site" height="100px;" />
            </div>

            {{-- BOTÕES --}}
            <div class="botoes-nav">
                <ul>
                    <li onclick="acessarProdutos('Calça')">CALÇAS</li>
                    <li onclick="acessarProdutos('Body')">BODYS</li>
                    <li onclick="acessarProdutos('Cropped')">CROPPED</li>
                    <li onclick="acessarProdutos('Camiseta')">CAMISETA</li>
                    <li onclick="acessarProdutos('Conjunto')">CONJUNTOS</li>
                    <li onclick="acessarProdutos('Vestido')">VESTIDOS</li>
                    <li onclick="acessarProdutos('Acessório')">ACESSÓRIOS</li>
                </ul>
            </div>

            {{-- ÍCONES --}}
            <div class="login">
                <i class="bi bi-search-heart-fill fs-3 me-2"></i>
                <i class="bi bi-bag-heart-fill fs-3 me-2"></i>

                {{-- ÍCONE LOGIN --}}
                <i class="bi bi-person-fill fs-3 me-2" id="btn-login"></i>
            </div>
        </nav>
    </header>

    {{-- CONTEÚDO --}}
    <div id="conteudo" class="conteudo-principal">
        @yield('content')
    </div>

    {{-- POP-UP LOGIN/CADASTRO --}}
    <div id="popup-cadastro" class="popup-cadastro">
        <input type="checkbox" id="toggle">

        <div class="container-popup">
            {{-- Login --}}
            <div class="left">
                <form class="form-login">
                    <h2>Login</h2>

                    <div class="campo-input">
                        <i class="bi bi-envelope"></i>
                        <input type="email" id="login-email" placeholder="Digite seu e-mail" required>
                    </div>

                    <div class="campo-input">
                        <i class="bi bi-lock"></i>
                        <input type="password" id="login-senha" placeholder="Digite sua senha" required>
                    </div>

                    <button type="submit">Entrar</button>
                </form>
            </div>

            {{-- Cadastro --}}
            <div class="right">
                <form class="form-cadastro">
                    <h2>Cadastro</h2>

                    <div class="campo-input">
                        <i class="bi bi-person"></i>
                        <input type="text" id="cadastro-nome" placeholder="Digite seu nome" required>
                    </div>

                    <div class="campo-input">
                        <i class="bi bi-envelope"></i>
                        <input type="email" id="cadastro-email" placeholder="Digite seu e-mail" required>
                    </div>

                    <div class="campo-input">
                        <i class="bi bi-lock"></i>
                        <input type="password" id="cadastro-senha" placeholder="Crie uma senha" required>
                    </div>

                    <button type="submit">Cadastrar</button>
                </form>
            </div>

            {{-- Painel lateral --}}
            <div class="middle">
                <h2 id="middle-titulo">Bem-vindo à Crofline</h2>
                <p id="middle-texto">Cadastre-se para começar</p>
                <button id="middle-botao" class="botao-cadastrar-label"
                        onclick="document.getElementById('toggle').click()">
                    Cadastrar
                </button>
            </div>
        </div>

        {{-- Botão fechar --}}
        <button type="button" class="fechar-popup" aria-label="Fechar">
            <i class="bi bi-x-circle"></i>
        </button>
    </div>

    {{-- RODAPÉ --}}
    <footer>
    </footer>

    <script>
        // flag vindo do backend pra saber se está logado
        const CROFLINE_IS_AUTH = @json(auth()->check());

        // categoria -> produtos
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

            if (popup && conteudo && toggle) {
                popup.classList.add('ativo');
                conteudo.classList.add('blur');
                toggle.checked = false; // sempre começa no login
            }
        }

        function fecharPopupCadastro() {
            const popup = document.getElementById('popup-cadastro');
            const conteudo = document.querySelector('.conteudo-principal');

            if (popup && conteudo) {
                popup.classList.remove('ativo');
                conteudo.classList.remove('blur');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const loginIcon = document.getElementById('btn-login');
            const toggle = document.getElementById('toggle');
            const titulo = document.getElementById('middle-titulo');
            const texto = document.getElementById('middle-texto');
            const botao = document.getElementById('middle-botao');
            const botaoFechar = document.querySelector('.fechar-popup');

            if (loginIcon) {
                loginIcon.addEventListener('click', function () {
                    if (CROFLINE_IS_AUTH) {
                        // Usuário já logado → futuro: direcionar para área do cliente
                        alert('Você já está logado. Aqui depois vamos abrir a área do cliente 😉');
                    } else {
                        abrirPopupCadastro();
                    }
                });
            }

            if (toggle && titulo && texto && botao) {
                toggle.addEventListener('change', () => {
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

            // Validação básica dos formulários (por enquanto só front)
            document.querySelectorAll('.popup-cadastro form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        alert('Por favor, preencha todos os campos corretamente.');
                    } else {
                        e.preventDefault();
                        alert('Aqui depois entra a lógica real de login/cadastro no backend.');
                    }
                });
            });
        });
    </script>
</body>

</html>
