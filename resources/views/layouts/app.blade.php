<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'ОчУмелые ручки')</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}">
</head>
<body>
    <div class="header">
        <div class="row grid middle between">
            <div class="logo">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" onerror="this.style.display='none'">
            </div>
            <div class="title">
                Клуб любителей творчества «ОчУмелые ручки»
            </div>
            <div class="auth">
                @auth
                    @if(auth()->user()->isInstructor())
                        <a href="{{ route('instructor.dashboard') }}">Личный кабинет</a>
                    @else
                        <a href="{{ route('home') }}">Главная</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <a href="#" onclick="this.closest('form').submit(); return false;">Выход</a>
                    </form>
                @else
                    <a href="{{ route('login') }}">Вход</a>
                    <a href="{{ route('register') }}">Регистрация</a>
                @endauth
            </div>
        </div>
    </div>
    <div class="row row--nogutter">
        <div class="menu-burger">
            <div class="burger">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>	
    </div>
    
    @yield('content')
    
    <div class="footer">
        <div class="row">
            <div class="row--small grid between">
                <div class="address">Наш адрес: ВДНХ, 120в</div>
                <div class="tel">Тел: 89123456765</div>
                <div class="copy">(с) Copyright, 2017</div>
            </div>
        </div>
    </div>
    
    @if(session('success'))
        <script>alert('{{ session('success') }}');</script>
    @endif
    @if(session('error'))
        <script>alert('{{ session('error') }}');</script>
    @endif
</body>
</html>
