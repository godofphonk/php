@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="row row--nogutter top-line">
    <div class="line"></div>
</div>
<div class="main">
    <div class="row">
        <div class="row--small">
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <h2>Форма регистрации</h2>
                @if($errors->any())
                    <div style="color: red;">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="form-group">
                    <label>ФИО</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Подтверждение пароля</label>
                    <input type="password" name="password_confirmation" required>
                </div>
                <div class="form-group">
                    <label>Номер телефона</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+79991234567" required>
                </div>
                <div class="form-group">
                    <button class="btn">Отправить</button>
                </div>
            </form>
            <p>Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
        </div>
    </div>
</div>
<div class="row row--nogutter">
    <div class="line"></div>
</div>
@endsection
