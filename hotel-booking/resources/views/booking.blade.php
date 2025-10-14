@extends('layouts.app')

@section('title', 'Бронирование номера - Отель Сладкие Сны')

@section('content')
<!-- Успешное сообщение (будет показываться после отправки) -->
@if(session('success'))
    <div class="alert alert-success text-center" role="alert">
        {{ session('success') }}
    </div>
@endif

<!-- Информация о номере -->
<div class="card mb-4">
    <div class="card-body">
        <h3>Вы бронируете: {{ $room->category }}</h3>
        <p><strong>Цена:</strong> {{ number_format($room->price, 0, ',', ' ') }} ₽ / ночь</p>
        <p><strong>Вместимость:</strong> {{ $room->capacity }} человек</p>
    </div>
</div>

<!--Форма бронирования-->
<div class="d-flex justify-content-between flex-wrap align-items-center">
    <h1>Бронирование номера</h1>
</div>

<form class="row g-3 needs-validation my-2" method="POST" action="{{ route('booking.store') }}" novalidate>
    @csrf
    <input type="hidden" name="room_id" value="{{ $room->id }}">
    
    <div class="col-md-6">
        <label for="client_name" class="form-label">Имя *</label>
        <input type="text" class="form-control @error('client_name') is-invalid @enderror" 
               id="client_name" name="client_name" value="{{ old('client_name') }}" required>
        @error('client_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Пожалуйста, введите имя</div>
        @enderror
    </div>
    
    <div class="col-md-6">
        <label for="client_email" class="form-label">Почта *</label>
        <input type="email" class="form-control @error('client_email') is-invalid @enderror" 
               id="client_email" name="client_email" value="{{ old('client_email') }}" required>
        @error('client_email')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Пожалуйста, введите email</div>
        @enderror
    </div>
    
    <div class="col-md-6">
        <label for="client_phone" class="form-label">Телефон *</label>
        <input type="text" class="form-control @error('client_phone') is-invalid @enderror" 
               id="client_phone" name="client_phone" value="{{ old('client_phone') }}" required>
        @error('client_phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Пожалуйста, введите номер телефона</div>
        @enderror
    </div>
    
    <div class="col-md-3">
        <label for="check_in" class="form-label">Дата заезда *</label>
        <input type="date" class="form-control @error('check_in') is-invalid @enderror" 
               id="check_in" name="check_in" value="{{ old('check_in') }}" required>
        @error('check_in')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Пожалуйста, введите дату заезда</div>
        @enderror
    </div>
    
    <div class="col-md-3">
        <label for="check_out" class="form-label">Дата выезда *</label>
        <input type="date" class="form-control @error('check_out') is-invalid @enderror" 
               id="check_out" name="check_out" value="{{ old('check_out') }}" required>
        @error('check_out')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Пожалуйста, введите дату выезда</div>
        @enderror
    </div>
    
    <div class="d-grid gap-2 col-12">
        <button class="btn btn-primary" type="submit">Отправить заявку</button>
    </div>
</form>
@endsection

@section('scripts')
<script>
// JavaScript для валидации дат
document.addEventListener('DOMContentLoaded', function() {
    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');
    
    // Устанавливаем минимальную дату - сегодня
    const today = new Date().toISOString().split('T')[0];
    checkIn.min = today;
    
    // При изменении даты заезда, обновляем минимальную дату выезда
    checkIn.addEventListener('change', function() {
        checkOut.min = this.value;
    });
});
</script>
@endsection