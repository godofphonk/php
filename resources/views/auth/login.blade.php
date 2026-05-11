@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="row row--nogutter top-line">
    <div class="line"></div>
</div>
<div class="main">
    <div class="row">
        <div class="row--small">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <h2>Форма авторизации</h2>
                @if($errors->any())
                    <div style="color: red;">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <button class="btn">Войти</button>
                </div>
            </form>
            <p>Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
        </div>
    </div>
</div>
<div class="row row--nogutter">
    <div class="line"></div>
</div>
@endsection
