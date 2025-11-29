<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Crofline')</title>

    {{-- CSRF para Ajax --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        /* POPUP */

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
            text-align: center;
        }

        .form-login {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* CADASTRO 2 ETAPAS */

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

        /* TOAST / OVERLAY DE SUCESSO */

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

            .grid-cadastro {
                grid-template-columns: 1fr;
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
            <div class="logo">
                <img src="/img/logoCrof.png" alt="Logo do Site" height="100px;" />
            </div>

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

            <div class="login">
                <i class="bi bi-search-heart-fill fs-3 me-2"></i>
                <i class="bi bi-bag-heart-fill fs-3 me-2"></i>
                <i class="bi bi-person-fill fs-3 me-2" id="btn-login"></i>
            </div>
        </nav>
    </header>

    {{-- CONTEÚDO --}}
    <div id="conteudo" class="conteudo-principal @if(session('success')) blur @endif">
        @yield('content')
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
                        <input type="email" id="login-email" placeholder="Digite seu e-mail" required>
                    </div>

                    <div class="campo-input">
                        <i class="bi bi-lock"></i>
                        <input type="password" id="login-senha" placeholder="Digite sua senha" required>
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

    {{-- OVERLAY DE SUCESSO (via sessão ou Ajax) --}}
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

        <script>
        // flag vindo do backend pra saber se está logado
        const CROFLINE_IS_AUTH = @json(auth()->check());

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
                toggle.checked = false;            // volta pro texto de cadastro
                containerPopup.classList.remove('step-2'); // garante etapa 1 e esconde botão voltar
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
                containerPopup.classList.remove('step-2'); // volta pra etapa 1
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
                } else {
                    textoContador.textContent = `Fechando em ${contador}...`;
                }
            }, 1000);
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

            if (loginIcon) {
                loginIcon.addEventListener('click', function () {
                    if (CROFLINE_IS_AUTH) {
                        alert('Você já está logado. Aqui depois vamos abrir a área do cliente 😉');
                    } else {
                        abrirPopupCadastro();
                    }
                });
            }

            if (toggle && titulo && texto && botao) {
                toggle.addEventListener('change', () => {
                    // Sempre que trocar entre login/cadastro, volta pra etapa 1 e esconde botão voltar
                    if (containerPopup) {
                        containerPopup.classList.remove('step-2');
                    }

                    if (toggle.checked) {
                        // Modo LOGIN
                        titulo.textContent = 'Já é cadastrado?';
                        texto.textContent = 'Realize o login';
                        botao.textContent = 'Login';
                    } else {
                        // Modo CADASTRO
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

            // LOGIN (ainda fake)
            const formLogin = document.getElementById('form-login');
            if (formLogin) {
                formLogin.addEventListener('submit', function (e) {
                    if (!formLogin.checkValidity()) {
                        e.preventDefault();
                        alert('Por favor, preencha todos os campos corretamente.');
                    } else {
                        e.preventDefault();
                        alert('Login: aqui entra a lógica do backend.');
                    }
                });
            }

            // ==== CADASTRO COM AJAX ====
            const formCadastro = document.getElementById('form-cadastro');
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

                    // Monta os dados
                    const formData = new FormData(formCadastro);
                    // Campos da etapa 2 (não têm "name", então vamos pegar manualmente)
                    formData.append('sobrenome', document.getElementById('cadastro-sobrenome-2')?.value || '');
                    formData.append('telefone', document.getElementById('cadastro-telefone-2')?.value || '');
                    formData.append('data_nascimento', document.getElementById('cadastro-nascimento-2')?.value || '');

                    // CSRF token
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
                            // sucesso
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
        });
        // ... deixa o resto do script como está

    document.addEventListener('DOMContentLoaded', function() {
        // já existem essas consts aí em cima, deixa
        const formCadastro   = document.getElementById('form-cadastro');
        const containerPopup = document.getElementById('container-popup');
        const btnContinuar   = document.getElementById('btn-continuar');
        const btnVoltarEtapa = document.getElementById('btn-voltar-etapa');

        // === CONTINUAR / VOLTAR já estavam OK, mantenho ===
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

        // === SUBMIT DO CADASTRO COM AJAX ===
        if (formCadastro) {
            formCadastro.addEventListener('submit', async function (e) {
                e.preventDefault();

                if (!formCadastro.checkValidity()) {
                    alert('Por favor, preencha todos os campos corretamente.');
                    return;
                }

                const senha     = document.getElementById('cadastro-senha')?.value || '';
                const confirmar = document.getElementById('cadastro-confirmar-senha-2')?.value || '';

                if (senha !== confirmar) {
                    alert('As senhas não conferem.');
                    return;
                }

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const url   = '{{ route('account.register') }}';

                const formData = new FormData(formCadastro);
                // garante que o back receba o campo de confirmação
                formData.set('confirmar_senha', confirmar);

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    let data;
                    try {
                        data = await response.json();
                    } catch (err) {
                        console.error('Erro ao ler JSON:', err);
                        alert('Ocorreu um erro ao cadastrar. Tente novamente.');
                        return;
                    }

                    if (response.ok && data.success) {
                        // mostra overlay de sucesso (3s) – usando a div que já criamos
                        mostrarSucessoCadastro(); // essa função você já tem no código anterior
                    } else {
                        console.error('Erros de validação:', data);
                        if (data.errors) {
                            // mostra primeira mensagem de erro
                            const firstField = Object.keys(data.errors)[0];
                            alert(data.errors[firstField][0]);
                        } else if (data.message) {
                            alert(data.message);
                        } else {
                            alert('Ocorreu um erro ao cadastrar. Tente novamente.');
                        }
                    }
                } catch (error) {
                    console.error('Erro na requisição AJAX:', error);
                    alert('Ocorreu um erro ao cadastrar. Tente novamente.');
                }
            });
        }
    });
    </script>
</body>

</html>
