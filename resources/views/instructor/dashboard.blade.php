@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
<body class="dp">
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title"></div>
        <div class="row--small grid between">
            <div class="content driver-page">
                <div class="driver-page-photo">
                    <img src="{{ asset('img/driver-page.png') }}" alt="{{ $instructor->name }}" onerror="this.style.display='none'">
                </div>	
                <div class="driver-page-name">{{ $instructor->name }}</div>
                <div class="driver-page-text">
                    <div class="driver-page-my">Мои мастер-классы</div>
                    @if($masterclasses->count() > 0)
                        <table class="driver-page-table">
                            <tbody>
                                @foreach($masterclasses as $masterclass)
                                    <tr>
                                        <td>{{ $masterclass->date->format('d.m.Y') }} {{ $masterclass->time }}</td>
                                        <td>
                                            <strong>{{ $masterclass->category->name }}</strong><br>
                                            <strong>{{ $masterclass->title }}</strong><br>
                                            Стоимость: {{ number_format($masterclass->price, 2) }} руб.<br>
                                            <br>
                                            <strong>Участники ({{ $masterclass->registrations->count() }}/{{ $masterclass->max_participants }}):</strong>
                                            @if($masterclass->registrations->count() > 0)
                                                @foreach($masterclass->registrations as $registration)
                                                    <p>
                                                        {{ $loop->iteration }}. {{ $registration->user->name }}<br>
                                                        email: {{ $registration->user->email }}<br>
                                                        tel: {{ $registration->user->phone }}
                                                    </p>
                                                @endforeach
                                            @else
                                                <p>Нет участников</p>
                                            @endif
                                            <br>
                                            <a href="{{ route('masterclass.edit', $masterclass->id) }}" class="btn">Редактировать</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>У вас пока нет мастер-классов.</p>
                    @endif
                </div>
                <div class="driver-page-btn-wrapper">
                    <a href="{{ route('masterclass.create') }}" class="driver-page-btn btn">
                        Добавить мастер-класс
                    </a>
                </div>
            </div>
            <ul class="menu">
                @foreach($masterclasses->pluck('category')->unique() as $category)
                    <li><a href="{{ route('category.show', $category->id) }}">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>	
</div>
<div class="row row--nogutter">
    <div class="line"></div>
</div>
@endsection
