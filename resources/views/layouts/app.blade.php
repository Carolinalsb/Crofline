<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Crofline')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <style>
        body {
            margin: 0 auto;

        }

        .menu-nav {
            justify-content: space-between;
            display: flex;
            background-color: #24034d;
            height: 5.5rem;


        }

        .logo {
            display: flex;
            align-items: center;
        }

        .botoes-nav {

            display: flex;
            align-items: center;

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

        .login {

            display: flex;
            align-items: center;
            justify-content: end;
            gap: 10px;
        }

        .login i {
            color: #f2f2f2;

        }
    </style>
    {{-- MENU DE NAVEGAÇÃO --}}
    <header>
        <nav class="menu-nav">
            {{-- LOGOTIPO --}}
            <div class="logo">
                <img src="/img/logoCrof.png" alt="Logo do Site" height="100px;" />
            </div>

            {{-- BOTÕES --}}
            <div class="botoes-nav">
                <ul>
                    <li>CALÇAS</li>
                    <li>BODYS</li>
                    <li>CROPPED</li>
                    <li>CAMISETA</li>
                    <li>CONJUNTOS</li>
                    <li>VESTIDOS</li>
                    <li>ACESSÓRIOS</li>
                </ul>
            </div>

            {{-- ICONE --}}
            <div class="login">
                <i class="bi bi-search-heart-fill fs-3 me-2"></i>
                <i class="bi bi-bag-heart-fill fs-3 me-2"></i>
                <i class="bi bi-person-fill fs-3 me-2"></i>

            </div>
        </nav>
    </header>

    {{-- CONTEÚDO --}}
    @yield('content')

    {{-- RODAPÉ --}}
    <footer>

    </footer>
</body>

</html>
