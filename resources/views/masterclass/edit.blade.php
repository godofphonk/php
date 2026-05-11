@extends('layouts.app')

@section('title', 'Редактирование мастер-класса')

@section('content')
<div class="row row--nogutter top-line">
    <div class="line"></div>
</div>
<div class="main">
    <div class="row">
        <div class="row--small">
            <form action="{{ route('masterclass.update', $masterclass->id) }}" method="POST">
                @csrf
                @method('PUT')
                <h2>Редактирование мастер-класса</h2>
                @if($errors->any())
                    <div style="color: red;">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="form-group">
                    <label>Вид творчества</label>
                    <select name="category_id" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $masterclass->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Название мастер-класса</label>
                    <input type="text" name="title" value="{{ old('title', $masterclass->title) }}" required>
                </div>
                <div class="form-group">
                    <label>Описание мастер-класса</label>
                    <textarea name="description" required>{{ old('description', $masterclass->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label>Стоимость (руб.)</label>
                    <input type="number" name="price" min="0" step="0.01" value="{{ old('price', $masterclass->price) }}" required>
                </div>
                <div class="form-group">
                    <button class="btn">Сохранить</button>
                </div>
            </form>
            <a href="{{ route('instructor.dashboard') }}" class="btn">Отмена</a>
        </div>
    </div>
</div>
<div class="row row--nogutter">
    <div class="line"></div>
</div>
@endsection
