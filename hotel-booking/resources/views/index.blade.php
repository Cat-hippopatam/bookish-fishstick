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
        <button class="btn btn-danger my-1">Сбросить фильтр</button>
    </div>
</div>

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

<div class="d-flex justify-content-around flex-wrap align-items-center">
    @foreach($rooms as $room)
    <div class="card my-3">
        <img src="{{ asset('img/' . $room->image) }}" class="card-img-top" alt="{{ $room->category }}">
        <div class="card-body">
            <h3>Категория: {{ $room->category }}</h3>
            <h5>Цена: {{ number_format($room->price, 0, ',', ' ') }} ₽ / ночь</h5>
            <h5>Характеристики:</h5>
            <ul class="list-group">
                @foreach(explode(',', $room->amenities) as $amenity)
                    <li class="list-group-item">{{ trim($amenity) }}</li>
                @endforeach
            </ul>
            <p class="mt-2"><strong>Вместимость:</strong> {{ $room->capacity }} чел.</p>
        </div>
        <div class="d-grid gap-2">
            @if($room->is_available)
                <a href="{{ route('booking.show', $room) }}" class="btn btn-success">Забронировать</a>
            @else
                <button class="btn btn-secondary" disabled>Недоступно</button>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection