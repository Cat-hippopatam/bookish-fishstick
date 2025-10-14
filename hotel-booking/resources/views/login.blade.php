@extends('layouts.app')

@section('title', 'Вход в панель администратора')

@section('content')
<!--Форма авторизации-->
<div class="d-flex justify-content-between flex-wrap align-items-center">
    <h1>Вход в панель администратора</h1>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form class="my-2" method="POST" action="{{ route('admin.login') }}">
    @csrf
    <div class="my-2">
        <label for="password" class="form-label">Пароль администратора *</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" 
               id="password" name="password" required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Пожалуйста, введите пароль</div>
        @enderror
    </div>
    <div class="d-grid gap-2">
        <button class="btn btn-primary">Войти</button>
    </div>
</form>
@endsection