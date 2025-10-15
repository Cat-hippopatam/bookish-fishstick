@extends('layouts.app')

@section('title', 'Панель администратора - Отель Сладкие Сны')

@section('content')
<div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
    <h1>Панель администратора</h1>
    <a href="{{ route('home') }}" class="btn btn-outline-primary">На главную</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    @forelse($bookings as $booking)
    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h5 class="mb-0">Бронирование #{{ $booking->id }}</h5>
            </div>
            <div class="card-body">
                <h5 class="text-primary">Номер {{ $booking->room->room_number }} - {{ $booking->room->category }}</h5>
                <div class="mb-3">
                    <strong>Клиент:</strong> {{ $booking->client_name }}<br>
                    <strong>Телефон:</strong> {{ $booking->client_phone }}<br>
                    <strong>Email:</strong> {{ $booking->client_email }}
                </div>
                
                <div class="mb-3">
                    <strong>Дата заезда:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->format('d.m.Y') }}<br>
                    <strong>Дата выезда:</strong> {{ \Carbon\Carbon::parse($booking->check_out)->format('d.m.Y') }}<br>
                    <strong>Кол-во ночей:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->diffInDays($booking->check_out) }}
                </div>
                
                <div class="mb-3">
                    <strong>Статус:</strong> 
                    <span class="badge 
                        @if($booking->status == 'confirmed') bg-success
                        @elseif($booking->status == 'cancelled') bg-danger
                        @else bg-warning @endif">
                        @if($booking->status == 'pending') Ожидание
                        @elseif($booking->status == 'confirmed') Подтверждено
                        @elseif($booking->status == 'cancelled') Отменено
                        @endif
                    </span>
                </div>
            </div>
            <div class="card-footer">
                @if($booking->status == 'pending')
                <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}" class="d-grid gap-2">
                    @csrf
                    <button type="submit" name="status" value="confirmed" class="btn btn-success" onclick="return confirm('Вы уверены, что хотите подтвердить это бронирование?')">
                        ✅ Одобрить бронирование
                    </button>
                    <button type="submit" name="status" value="cancelled" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите отменить это бронирование?')">
                        ❌ Отклонить бронирование
                    </button>
                </form>
                @else
                <div class="text-center">
                    <span class="text-muted">Действие выполнено</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            <h4>Бронирований нет</h4>
            <p>На данный момент активных бронирований нет.</p>
        </div>
    </div>
    @endforelse
</div>
@endsection