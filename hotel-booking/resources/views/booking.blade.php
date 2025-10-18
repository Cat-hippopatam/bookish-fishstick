@extends('layouts.app')

@section('title', 'Бронирование номера - Отель Сладкие Сны')

@section('content')
<!-- Успешное сообщение (будет показываться после отправки) -->
@if(session('success'))
    <div class="alert alert-success text-center" role="alert">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger text-center" role="alert">
        {{ session('error') }}
    </div>
@endif

<!-- Информация о номере -->
<div class="card mb-4">
    <div class="card-body">
        <h3>Вы бронируете: {{ $room['category']['name'] }}</h3>
        <p><strong>Цена:</strong> {{ number_format($room['price'], 0, ',', ' ') }} ₽ / ночь</p>
        <p><strong>Вместимость:</strong> {{ $room['capacity'] }} человек</p>
        <p><strong>Номер:</strong> {{ $room['room_number'] }}</p>
        
        <!-- Удобства -->
        <div class="mt-3">
            <strong>Удобства:</strong>
            <div class="mt-1">
                @foreach($room['amenities_list'] as $amenity)
                <span class="badge bg-light text-dark border me-1 mb-1">{{ $amenity['name'] }}</span>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!--Форма бронирования-->
<div class="d-flex justify-content-between flex-wrap align-items-center">
    <h1>Бронирование номера</h1>
</div>

<form class="row g-3 needs-validation my-2" method="POST" action="{{ route('booking.store') }}" novalidate>
    @csrf
    
    <input type="hidden" name="room_id" value="{{ $room['id'] }}">
    
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

    <!-- Информация о бронировании -->
    <div class="col-12">
        <div class="alert alert-info">
            <h6>📅 Информация о бронировании:</h6>
            <p class="mb-1"><strong>Минимальное бронирование:</strong> 1 ночь</p>
            <p class="mb-1"><strong>Заезд:</strong> с 14:00</p>
            <p class="mb-0"><strong>Выезд:</strong> до 12:00</p>
        </div>
    </div>

    
    
    <!-- <div class="d-grid gap-2 col-12"> -->
        <!-- <button class="btn btn-primary btn-lg" type="submit"> -->
            <!-- В карточке номера проверяем ссылку: -->
            <!-- <a href="{{ route('booking.show', $room['id']) }}" class="btn btn-success btn-lg"> -->
                <!-- 🏨 Забронировать номер {{ $room['room_number'] }} -->
            <!-- </a> -->
        <!-- </button> -->
    <!-- </div> -->

    <div class="d-grid gap-2 col-12">
        <button class="btn btn-primary btn-lg" type="submit">
            🏨 Забронировать номер {{ $room['room_number'] }}
        </button>
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
        if (this.value) {
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            checkOut.min = nextDay.toISOString().split('T')[0];
            
            // Если дата выезда раньше новой минимальной даты - сбрасываем
            if (checkOut.value && checkOut.value < checkOut.min) {
                checkOut.value = '';
            }
        }
    });

    // При изменении даты выезда, проверяем что она после даты заезда
    checkOut.addEventListener('change', function() {
        if (checkIn.value && this.value) {
            const checkInDate = new Date(checkIn.value);
            const checkOutDate = new Date(this.value);
            
            if (checkOutDate <= checkInDate) {
                alert('Дата выезда должна быть после даты заезда!');
                this.value = '';
            }
        }
    });
});
</script>
@endsection