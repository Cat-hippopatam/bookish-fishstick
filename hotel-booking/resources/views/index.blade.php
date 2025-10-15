@extends('layouts.app')

@section('title', 'Каталог номеров - Отель Сладкие Сны')

@section('content')
<!--описание номеров-->
<div class="d-flex justify-content-between flex-wrap">
    <h1>Каталог номеров</h1>
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
            Категории
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Стандартный</a></li>
            <li><a class="dropdown-item" href="#">Студия</a></li>
            <li><a class="dropdown-item" href="#">Люкс</a></li>
        </ul>
        <button class="btn btn-primary my-1">Применить</button>
        <a href="{{ route('home') }}?reset=1" class="btn btn-danger my-1">Сбросить фильтр</a>
    </div>
</div>

<!-- Информация о случайном порядке -->
@if($isRandomOrder)
<div class="alert alert-info alert-dismissible fade show" role="alert">
    🎲 <strong>Случайный порядок!</strong> Первые 4 номера отображаются в случайном порядке. 
    Нажмите "Сбросить фильтр" для нового случайного набора.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Сообщения об успехе/ошибке -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    @foreach($rooms as $index => $room)
    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="card h-100 room-card">
            @if($isRandomOrder && $index < 4)
            <div class="position-absolute top-0 start-0 m-2">
                <span class="badge bg-warning text-dark">🎲 Случайный выбор</span>
            </div>
            @endif
            <img src="{{ asset('img/' . $room->image) }}" class="card-img-top" alt="{{ $room->category }}" style="height: 250px; object-fit: cover;">
            <div class="card-body">
                <h3 class="card-title">Номер {{ $room->room_number }}</h3>
                <h4 class="text-primary">{{ $room->category }}</h4>
                <h5 class="text-success">Цена: {{ number_format($room->price, 0, ',', ' ') }} ₽ / ночь</h5>
                
                <h6>Характеристики:</h6>
                <ul class="list-group mb-3">
                    @foreach(explode(',', $room->amenities) as $amenity)
                        <li class="list-group-item">{{ trim($amenity) }}</li>
                    @endforeach
                </ul>
                
                <p class="card-text"><strong>Вместимость:</strong> {{ $room->capacity }} чел.</p>
                <p class="card-text"><strong>Описание:</strong> {{ $room->description }}</p>
            </div>
            <div class="card-footer">
                <div class="d-grid gap-2">
                    @if($room->is_available)
                        <a href="{{ route('booking.show', $room) }}" class="btn btn-success">Забронировать номер {{ $room->room_number }}</a>
                    @else
                        <button class="btn btn-secondary" disabled>Недоступно</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($rooms->isEmpty())
<div class="col-12">
    <div class="alert alert-warning text-center">
        <h4>Номера не найдены!</h4>
        <p>В базе данных нет номеров для отображения.</p>
    </div>
</div>
@endif
@endsection