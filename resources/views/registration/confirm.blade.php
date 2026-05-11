@extends('layouts.app')

@section('title', 'Подтверждение записи')

@section('content')
<div class="row row--nogutter top-line">
    <div class="line"></div>
</div>
<div class="main">
    <div class="row">
        <div class="row--small">
            <h2>Подтверждение записи</h2>
            <p><strong>ФИО:</strong> {{ auth()->user()->name }}</p>
            <p><strong>Вид творчества:</strong> {{ $masterclass->category->name }}</p>
            <p><strong>ФИО мастера:</strong> {{ $masterclass->instructor->name }}</p>
            <p><strong>Дата:</strong> {{ $masterclass->date->format('d.m.Y') }}</p>
            <p><strong>Время:</strong> {{ $masterclass->time }}</p>
            <p><strong>Стоимость:</strong> {{ number_format($masterclass->price, 2) }} руб.</p>
            
            <form action="{{ route('registration.store', $masterclass->id) }}" method="POST" style="display: inline;">
                @csrf
                <button class="btn">Подтвердить</button>
            </form>
            
            <a href="{{ route('category.show', $masterclass->category_id) }}" class="btn">Отмена</a>
        </div>
    </div>
</div>
<div class="row row--nogutter">
    <div class="line"></div>
</div>
@endsection
