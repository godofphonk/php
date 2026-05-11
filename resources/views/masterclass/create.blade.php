@extends('layouts.app')

@section('title', 'Добавление мастер-класса')

@section('content')
<div class="row row--nogutter top-line">
    <div class="line"></div>
</div>
<div class="main">
    <div class="row">
        <div class="row--small">
            <form action="{{ route('masterclass.store') }}" method="POST">
                @csrf
                <h2>Форма добавления мастер-класса</h2>
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
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Название мастер-класса</label>
                    <input type="text" name="title" value="{{ old('title') }}" required>
                </div>
                <div class="form-group">
                    <label>Описание мастер-класса</label>
                    <textarea name="description" required>{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Дата</label>
                    <input type="date" name="date" id="dateInput" required>
                </div>
                <div class="form-group">
                    <label>Время (жесткая сетка: 9-11, 11-13, 13-15, 15-17)</label>
                    <select name="time" id="timeInput" required>
                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot }}">{{ $slot }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Количество человек в группе</label>
                    <input type="number" name="max_participants" min="1" value="{{ old('max_participants', 10) }}" required>
                </div>
                <div class="form-group">
                    <label>Стоимость (руб.)</label>
                    <input type="number" name="price" min="0" step="0.01" value="{{ old('price', 0) }}" required>
                </div>
                <div class="form-group">
                    <button class="btn">Отправить</button>
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

@push('scripts')
<script>
const occupiedSlots = @json($occupiedSlots);
const timeInput = document.getElementById('timeInput');
const dateInput = document.getElementById('dateInput');

function updateTimeOptions() {
    const selectedDate = dateInput.value;
    const options = timeInput.options;

    for (let i = 0; i < options.length; i++) {
        const slotKey = selectedDate + '_' + options[i].value;
        if (occupiedSlots.includes(slotKey)) {
            options[i].disabled = true;
            options[i].textContent = options[i].value + ' (занято)';
        } else {
            options[i].disabled = false;
            options[i].textContent = options[i].value.replace(' (занято)', '');
        }
    }
}

dateInput.addEventListener('change', updateTimeOptions);
</script>
@endpush
