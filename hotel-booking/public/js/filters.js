// Улучшенная упрощенная версия с работающими ссылками и случайным порядком
document.addEventListener('DOMContentLoaded', function() {
    const categoryButtons = document.querySelectorAll('.btn-group [data-category]');
    const resetButton = document.getElementById('reset-filters');
    
    let currentCategory = 'all';

    // Обработчики для кнопок категорий
    categoryButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const category = button.getAttribute('data-category');
            filterByCategory(category);
            
            // Добавляем параметр категории в URL без перезагрузки
            updateUrlParameter('category', category);
        });
    });

    // Обработчик для кнопки сброса
    if (resetButton) {
        resetButton.addEventListener('click', (e) => {
            e.preventDefault();
            // Перезагружаем страницу с параметром reset для случайного порядка
            window.location.href = getBaseUrl() + '?reset=1';
        });
    }

    // Проверяем параметры URL при загрузке
    const urlParams = new URLSearchParams(window.location.search);
    const urlCategory = urlParams.get('category');
    if (urlCategory && urlCategory !== 'all') {
        filterByCategory(urlCategory);
    }

    function filterByCategory(category) {
        currentCategory = category;
        updateActiveButton(category);
        filterRooms();
    }

    function updateActiveButton(category) {
        categoryButtons.forEach(button => {
            const buttonCategory = button.getAttribute('data-category');
            if (buttonCategory === category) {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });
    }

    function filterRooms() {
        const roomCards = document.querySelectorAll('.room-card');
        let hasVisibleRooms = false;

        roomCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            const shouldShow = currentCategory === 'all' || cardCategory === currentCategory;
            
            if (shouldShow) {
                card.style.display = 'block';
                hasVisibleRooms = true;
                
                // Плавное появление
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });

        // Показываем сообщение, если нет результатов
        showNoResults(!hasVisibleRooms);
    }

    function showNoResults(show) {
        let noResults = document.getElementById('no-results');
        const roomsContainer = document.getElementById('rooms-container');
        
        if (show && !noResults) {
            noResults = document.createElement('div');
            noResults.id = 'no-results';
            noResults.className = 'col-12';
            noResults.innerHTML = `
                <div class="alert alert-warning text-center">
                    <h4>Номера не найдены!</h4>
                    <p>По выбранной категории нет доступных номеров.</p>
                    <button class="btn btn-primary" id="reset-from-empty">
                        Показать все номера
                    </button>
                </div>
            `;
            roomsContainer.appendChild(noResults);

            // Обработчик для кнопки в сообщении "нет результатов"
            document.getElementById('reset-from-empty').addEventListener('click', function() {
                window.location.href = getBaseUrl() + '?reset=1';
            });
        } else if (!show && noResults) {
            noResults.remove();
        }
    }

    // Вспомогательные функции для работы с URL
    function updateUrlParameter(key, value) {
        const url = new URL(window.location);
        if (value === 'all') {
            url.searchParams.delete(key);
        } else {
            url.searchParams.set(key, value);
        }
        window.history.replaceState({}, '', url);
    }

    function getBaseUrl() {
        return window.location.origin + window.location.pathname;
    }

    // Инициализация фильтров при загрузке
    filterRooms();
});