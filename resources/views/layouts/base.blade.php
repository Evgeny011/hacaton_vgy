<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="/css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="wrap">
        <header>
            <div class="container">
                <div class="headerInner">
                    <div>
                        <a href="{{ route('index') }}"><h1 class="logo unselectable textShadow">FileKeeper</h1></a>
                    </div>
                    <div>
                        <ul class="headerLink">
                            @if(Auth::check() && Auth::user()->login == 'copp')
                                <li>
                                    <a href="{{ route('admin') }}"><button class="btn logBtn">Админ панель</button></a>
                                </li>
                            @endif
                            @guest
                                <li>
                                    <a href="{{ route('reg') }}"><button class="btn regBtn">Регистрация</button></a>
                                </li>
                            @endguest
                            @auth
                                <li>
                                    <a href="{{ route('profile') }}"><button class="btn logBtn">Профиль</button></a>
                                </li>
                            @endauth
                            @guest
                                <li>
                                    <a href="{{ route('log') }}"><button class="btn authBtn">Вход</button></a>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
                <hr noshade="true">
            </div>
        </header>
        <main>
            <div class="container">
                <div class="mainInner">
                    @yield('content')
                </div>
            </div>
        </main>
        <footer>
            <div class="container">
                <hr noshade="">
                <div class="footerInner">
                </div>
            </div>
        </footer>
    </div>
    @yield('javascript')
</body>
</html>
