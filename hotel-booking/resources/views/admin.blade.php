@extends('layouts.app')

@section('title', 'Панель администратора - Отель Сладкие Сны')

@section('content')
<div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
    <h1>Панель администратора</h1>
    <div>
        <a href="{{ route('home') }}" class="btn btn-outline-primary">На главную</a>

        <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Выйти</button>
        </form>
        
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    @forelse($bookings as $booking)
    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h5 class="mb-0">Бронирование #{{ $booking->id }}</h5>
            </div>
            <div class="card-body">
                <h5 class="text-primary">Номер {{ $booking->room_id }}</h5>
                <p><strong>Клиент:</strong> {{ $booking->client_name }}</p>
                <p><strong>Телефон:</strong> {{ $booking->client_phone }}</p>
                <p><strong>Даты:</strong> {{ $booking->check_in->format('d.m.Y') }} - {{ $booking->check_out->format('d.m.Y') }}</p>
                
                <p><strong>Статус:</strong> 
                    <span class="badge 
                        @if($booking->status == 'confirmed') bg-success
                        @elseif($booking->status == 'cancelled') bg-danger
                        @else bg-warning @endif">
                        {{ $booking->status }}
                    </span>
                </p>
            </div>
            
            @if($booking->status == 'pending')
            <div class="card-footer">
                <form method="POST" action="{{ route('admin.bookings.confirm', $booking) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">✅ Подтвердить</button>
                </form>
                <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">❌ Отменить</button>
                </form>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            <h4>Бронирований нет</h4>
        </div>
    </div>
    @endforelse
</div>
@endsection