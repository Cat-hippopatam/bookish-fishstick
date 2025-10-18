@extends('layouts.app')

@section('title', 'Каталог номеров - Отель Сладкие Сны')

@section('content')
<!-- Заголовок и фильтры -->
<div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
    <h1>Каталог номеров</h1>
    <div class="d-flex flex-wrap gap-2 align-items-center">
        <!-- Кнопки фильтров -->
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary active" data-category="all">
                Все номера
            </button>
            @foreach($categories as $category)
            <button type="button" class="btn btn-outline-primary" data-category="{{ $category['id'] }}">
                {{ $category['name'] }}
            </button>
            @endforeach
        </div>

        <!-- <a href="{{ route('home') }}" class="btn btn-danger" id="reset-filters" onclick="event.preventDefault(); window.resetFilters();">
            Сбросить фильтр
        </a> -->
        <a href="{{ route('home') }}?reset=1" class="btn btn-danger" id="reset-filters">
            Сбросить фильтр
        </a>
        
    </div>
</div>

<!-- Информация о случайном порядке -->
@if($isRandomOrder)
<div class="alert alert-info alert-dismissible fade show" role="alert">
    🎲 <strong>Случайный порядок!</strong> Первые 4 номера отображаются в случайном порядке.
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

<!-- Сетка номеров -->
<div class="row" id="rooms-container">
    @foreach($rooms as $index => $room)
    <div class="col-lg-6 col-md-6 col-sm-12 mb-4 room-card" 
         data-category="{{ $room['category_id'] }}"
         style="transition: all 0.3s ease;">
        
        <div class="card h-100">
            <!-- Бейдж случайного номера -->
            @if($isRandomOrder && $index < 4)
            <div class="position-absolute top-0 start-0 m-2">
                <span class="badge bg-warning text-dark">🎲 Случайный выбор</span>
            </div>
            @endif
            
            <img src="{{ asset('img/rooms/' . $room['image']) }}" class="card-img-top" 
                 alt="Номер {{ $room['room_number'] }}" 
                 style="height: 250px; object-fit: cover;">
            
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h3 class="card-title mb-0">Номер {{ $room['room_number'] }}</h3>
                    <span class="badge bg-primary fs-6">{{ $room['category']['name'] }}</span>
                </div>
                
                <h4 class="text-success mb-3">{{ number_format($room['price'], 0, ',', ' ') }} ₽ / ночь</h4>
                
                <!-- Удобства -->
                <div class="mb-3">
                    <strong>Удобства:</strong>
                    <div class="mt-1">
                        @foreach($room['amenities_list'] as $amenity)
                        <span class="badge bg-light text-dark border me-1 mb-1">{{ $amenity['name'] }}</span>
                        @endforeach
                    </div>
                </div>
                
                <div class="room-features mb-3">
                    <p class="card-text mb-2">
                        <strong>Вместимость:</strong> 
                        <span class="badge bg-secondary">{{ $room['capacity'] }} чел.</span>
                    </p>
                </div>
                
                <p class="card-text"><strong>Описание:</strong> {{ $room['description'] }}</p>
            </div>
            
            <div class="card-footer">
                <div class="d-grid gap-2">
                    @if($room['is_available'])
                        <!-- В карточке номера измените ссылку: -->
                        <a href="{{ route('booking.show', $room['room_number']) }}" class="btn btn-success btn-lg">
                            🏨 Забронировать номер {{ $room['room_number'] }}
                        </a>
                    @else
                        <button class="btn btn-secondary btn-lg" disabled>
                            ❌ Недоступно для бронирования
                        </button>
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
        <p>По вашему запросу не найдено подходящих номеров.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Показать все номера</a>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script src="{{ asset('js/filters.js') }}"></script>
<script>
// Дополнительные скрипты для улучшения UX
document.addEventListener('DOMContentLoaded', function() {
    // Плавная прокрутка к верху при фильтрации
    const filterButtons = document.querySelectorAll('[data-category]');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            setTimeout(() => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }, 100);
        });
    });
    
    // Подсветка карточек при наведении
    const roomCards = document.querySelectorAll('.room-card');
    roomCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });

    // Динамическое обновление минимальных дат
    const today = new Date().toISOString().split('T')[0];
    document.querySelectorAll('input[type="date"]').forEach(input => {
        input.min = today;
    });
});
</script>

<style>
.room-card {
    transition: all 0.3s ease;
}

.btn-group .btn.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.card {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    overflow: hidden;
}

.card:hover {
    border-color: #0d6efd;
}

.card-img-top {
    transition: transform 0.3s ease;
}

.card:hover .card-img-top {
    transform: scale(1.05);
}

.badge {
    font-size: 0.8em;
}

.amenities-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
}

/* Анимации для фильтрации */
.fade-enter {
    opacity: 0;
    transform: translateY(20px);
}

.fade-enter-active {
    opacity: 1;
    transform: translateY(0);
    transition: all 0.3s ease;
}

.fade-exit {
    opacity: 1;
    transform: translateY(0);
}

.fade-exit-active {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
}

/* Гарантируем, что ссылки всегда кликабельны */
.room-card a {
    pointer-events: auto !important;
    text-decoration: none;
}

/* Стили для кнопок фильтров */
.btn-group .btn.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

/* Убедимся, что карточки правильно отображаются */
.room-card {
    transition: all 0.3s ease;
}

/* Ссылки в карточках должны быть полноразмерными */
.card-footer .d-grid a {
    display: block;
    width: 100%;
}
</style>
@endsection