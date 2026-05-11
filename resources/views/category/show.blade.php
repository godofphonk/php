@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title">{{ $category->name }}</div>
        <div class="row--small grid between">
            <div class="content">
                <p>{{ $category->description }}</p>
            </div>

        </div>

        <div class="row shedule">
            <div class="row--small">
                <h2>Расписание</h2>
                <div class="drivers">
                    @forelse($category->masterclasses as $masterclass)
                        <div class="driver grid">
                            <div class="driver-left grid">
                                <div class="driver-photo">
                                    <img src="{{ asset('img/driver1.png') }}" alt="{{ $masterclass->instructor->name }}" onerror="this.style.display='none'">
                                </div>
                                <div class="driver-text">
                                    <div class="driver-name">{{ $masterclass->instructor->name }}</div>
                                    <div class="driver-desc">
                                        <strong>{{ $masterclass->title }}</strong><br>
                                        {{ $masterclass->description }}<br>
                                        Стоимость: {{ number_format($masterclass->price, 2) }} руб.<br>
                                        Свободных мест: {{ $masterclass->available_spots }} из {{ $masterclass->max_participants }}
                                    </div>
                                </div>
                            </div>
                            <div class="driver-right">
                                @if(auth()->check())
                                    @if($masterclass->hasAvailableSpots())
                                        <a href="{{ route('registration.create', $masterclass->id) }}" class="driver-btn">Записаться</a>
                                    @else
                                        <button class="driver-btn" disabled>Мест нет</button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="driver-btn">Войти для записи</a>
                                @endif
                                <div class="driver-time">{{ $masterclass->date->format('d.m.Y') }} {{ $masterclass->time }}</div>
                            </div>	
                        </div>
                    @empty
                        <p>Мастер-классы пока не запланированы.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>	
</div>
@endsection
