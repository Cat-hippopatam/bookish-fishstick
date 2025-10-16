// Простая версия без классов
document.addEventListener('DOMContentLoaded', function() {
    const roomsContainer = document.getElementById('rooms-container');
    const categoryButtons = document.querySelectorAll('[data-category]');
    const resetButton = document.getElementById('reset-filters');
    
    let currentCategory = 'all';

    // Обработчики для кнопок категорий
    categoryButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const category = button.getAttribute('data-category');
            filterByCategory(category);
        });
    });

    // Кнопка сброса
    if (resetButton) {
        resetButton.addEventListener('click', (e) => {
            e.preventDefault();
            resetFilters();
        });
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
        let visibleCount = 0;

        roomCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            
            if (currentCategory === 'all' || cardCategory === currentCategory) {
                card.style.display = 'block';
                visibleCount++;
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });

        showNoResults(visibleCount === 0);
    }

    function showNoResults(show) {
        let noResults = document.getElementById('no-results');
        
        if (show && !noResults) {
            noResults = document.createElement('div');
            noResults.id = 'no-results';
            noResults.className = 'col-12';
            noResults.innerHTML = `
                <div class="alert alert-warning text-center">
                    <h4>Номера не найдены!</h4>
                    <p>По выбранной категории нет доступных номеров.</p>
                    <button class="btn btn-primary" onclick="resetFilters()">
                        Показать все номера
                    </button>
                </div>
            `;
            roomsContainer.appendChild(noResults);
        } else if (!show && noResults) {
            noResults.remove();
        }
    }

    function resetFilters() {
        currentCategory = 'all';
        updateActiveButton('all');
        filterRooms();
        showResetMessage();
    }

    function showResetMessage() {
        const existingMessage = document.querySelector('.reset-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        const message = document.createElement('div');
        message.className = 'alert alert-info reset-message text-center';
        message.innerHTML = 'Фильтры сброшены. Показаны все номера.';
        message.style.position = 'fixed';
        message.style.top = '20px';
        message.style.right = '20px';
        message.style.zIndex = '1000';
        
        document.body.appendChild(message);
        
        setTimeout(() => {
            message.remove();
        }, 3000);
    }

    // Сделаем функции глобальными для onclick
    window.resetFilters = resetFilters;
});