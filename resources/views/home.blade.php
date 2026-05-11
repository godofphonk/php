@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title">Добро пожаловать</div>
        <div class="row--small grid between">
            <div class="content">
                <p>Клуб любителей творчества «ОчУмелые ручки» приглашает вас на увлекательные мастер-классы по различным видам творчества.</p>
                <p>У нас вы сможете:</p>
                <ul>
                    <li>Освоить новые навыки под руководством опытных инструкторов</li>
                    <li>Создать уникальные изделия своими руками</li>
                    <li>Найти единомышленников и обрести новое хобби</li>
                </ul>
                @auth
                    @if(auth()->user()->isVisitor())
                        <h3>Ваши записи:</h3>
                        @if(auth()->user()->registeredMasterclasses()->count() > 0)
                            <ul>
                                @foreach(auth()->user()->registeredMasterclasses as $mc)
                                    <li>{{ $mc->title }} - {{ $mc->date->format('d.m.Y') }} {{ $mc->time }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>Вы еще не записались ни на один мастер-класс.</p>
                        @endif
                    @endif
                @endauth
            </div>
            <ul class="menu">
                @foreach($categories as $category)
                    <li><a href="{{ route('category.show', $category->id) }}">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>	
</div>
@endsection
