@extends('layouts.app')

@section('title', 'Панель администратора - Отель Сладкие Сны')

@section('content')
<div class="d-flex justify-content-between flex-wrap align-items-center">
    <h1>Панель администратора</h1>
    <a href="{{ route('home') }}" class="btn btn-outline-primary">На главную</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="d-flex justify-content-around flex-wrap align-items-center">
    @forelse($bookings as $booking)
    <div class="card my-3">
        <div class="card-body">
            <h5>Номер: {{ $booking->room->category }}</h5>
            <h5>Имя: {{ $booking->client_name }}</h5>
            <h5>Телефон: {{ $booking->client_phone }}</h5>
            <h5>Email: {{ $booking->client_email }}</h5>
            <h5>Статус: 
                <span class="badge 
                    @if($booking->status == 'confirmed') bg-success
                    @elseif($booking->status == 'cancelled') bg-danger
                    @else bg-warning @endif">
                    @if($booking->status == 'pending') Ожидание
                    @elseif($booking->status == 'confirmed') Подтверждено
                    @elseif($booking->status == 'cancelled') Отменено
                    @endif
                </span>
            </h5>
            <ul class="list-group">
                <li class="list-group-item">Дата заезда: {{ \Carbon\Carbon::parse($booking->check_in)->format('d.m.Y') }}</li>
                <li class="list-group-item">Дата выезда: {{ \Carbon\Carbon::parse($booking->check_out)->format('d.m.Y') }}</li>
            </ul>
        </div>
        <div class="d-grid gap-2">
            <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">
                @csrf
                <button type="submit" name="status" value="confirmed" class="btn btn-success w-100">Одобрить</button>
                <button type="submit" name="status" value="cancelled" class="btn btn-danger w-100">Отклонить</button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            На данный момент бронирований нет.
        </div>
    </div>
    @endforelse
</div>
@endsection