<template>
    <div class="flex justify-center items-center flex-col h-screen bg-[#121210] text-white">
        <!-- Карточка пользователя -->
        <div
            class="w-full max-w-[95%] h-3/4 bg-gray-800 rounded-2xl shadow-2xl overflow-hidden relative -mt-44 select-none"
            v-if="visibleCards.length > 0"
            @touchstart="handleTouchStart"
            @touchmove="handleTouchMove"
            @touchend="handleTouchEnd"
            @mousedown="handleMouseStart"
            @mousemove="handleMouseMove"
            @mouseup="handleMouseEnd"
            @mouseleave="handleMouseEnd"
            ref="cardContainer"
        >
            <div
                v-for="(card) in visibleCards"
                :key="card.id"
                class="w-full h-full relative transition-transform duration-300 ease-out"
                :style="{ transform: `translateX(${swipeOffset}px) rotate(${swipeRotation}deg)` }"
            >
                <img
                    :src="'http://127.0.0.1:8000/storage/' + card.image"
                    alt="Фото пользователя"
                    class="absolute top-0 left-0 w-full h-full object-cover pointer-events-none"
                />

                <!-- Индикаторы лайка/дизлайка -->
                <div
                    v-if="Math.abs(swipeOffset) > 50"
                    class="absolute inset-0 flex items-center justify-center"
                >
                    <div
                        v-if="swipeOffset > 50"
                        class="bg-green-500 z-50 text-white px-8 py-4 rounded-lg text-2xl font-bold border-4 border-green-400 transform rotate-12"
                        :style="{ opacity: Math.min(Math.abs(swipeOffset) / 150, 1) }"
                    >
                        ЛАЙК
                    </div>
                    <div
                        v-if="swipeOffset < -50"
                        class="bg-red-500 z-50 text-white px-8 py-4 rounded-lg text-2xl font-bold border-4 border-red-400 transform -rotate-12"
                        :style="{ opacity: Math.min(Math.abs(swipeOffset) / 150, 1) }"
                    >
                        НЕТ
                    </div>
                </div>

                <div class="p-4 absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent">
                    <p class="text-white text-4xl font-bold mb-2">{{ card.name }}, {{ card.age }} лет</p>
                    <p class="text-white text-xl font-normal">
    <span v-if="!expandedCards[card.id]">
      {{ truncatedDescription(card.description) }}
      <span class="text-blue-400 cursor-pointer underline" @click="expandCard(card.id)">ещё</span>
    </span>
                        <span v-else>
      {{ card.description }}
    </span>
                    </p>
                </div>

            </div>
        </div>

        <div v-if="endOfCards" class="flex flex-col justify-center items-center h-[60%] text-center px-4">
            <i class="fa-regular fa-face-sad-tear text-6xl text-gray-500 mb-4"></i>
            <p class="text-3xl text-gray-300 font-semibold mb-2">Карточки закончились</p>
            <p class="text-lg text-gray-400">Попробуй зайти позже или обнови страницу.</p>
        </div>

        <!-- Кнопки с иконками, расположенные снаружи -->
        <div class="absolute bottom-[10%] flex justify-around w-full" v-if="visibleCards.length > 0">
            <button @click="dislikeCard(visibleCards[0].id)"
                    class="bg-red-600 hover:bg-red-700 border-none rounded-full w-20 h-20 flex justify-center items-center shadow-lg text-white text-5xl cursor-pointer transition-colors duration-300">
                <i class="fa-solid fa-thumbs-down"></i>
            </button>
            <button @click="likeCard(visibleCards[0].id)"
                    class="bg-green-600 hover:bg-green-700 border-none rounded-full w-20 h-20 flex justify-center items-center shadow-lg text-white text-5xl cursor-pointer transition-colors duration-300">
                <i class="fa-sharp fa-solid fa-heart"></i>
            </button>
        </div>
    </div>
    <navbar></navbar>
</template>

<script>
export default {
    data() {
        return {
            cards: [],
            currentIndex: 0,
            endOfCards: false,
            // Свайп данные
            startX: 0,
            startY: 0,
            currentX: 0,
            currentY: 0,
            swipeOffset: 0,
            swipeRotation: 0,
            isDragging: false,
            isMouseDown: false,
            expandedCards: {},
        };
    },
    computed: {
        visibleCards() {
            return this.cards.length > 0 ? [this.cards[this.currentIndex]] : [];
        },
    },
    methods: {
        truncatedDescription(description) {
            const maxLength = 99;
            return description.length > maxLength
                ? description.slice(0, maxLength) + '... '
                : description;
        },

        expandCard(cardId) {
            this.expandedCards[cardId] = true;
        },
        // Touch события
        handleTouchStart(e) {
            this.startX = e.touches[0].clientX;
            this.startY = e.touches[0].clientY;
            this.isDragging = true;
        },

        handleTouchMove(e) {
            if (!this.isDragging) return;

            e.preventDefault();
            this.currentX = e.touches[0].clientX;
            this.currentY = e.touches[0].clientY;

            this.updateSwipeOffset();
        },

        handleTouchEnd(e) {
            if (!this.isDragging) return;

            this.handleSwipeEnd();
            this.isDragging = false;
        },

        // Mouse события (для десктопа)
        handleMouseStart(e) {
            this.startX = e.clientX;
            this.startY = e.clientY;
            this.isMouseDown = true;
            this.isDragging = true;
        },

        handleMouseMove(e) {
            if (!this.isDragging || !this.isMouseDown) return;

            e.preventDefault();
            this.currentX = e.clientX;
            this.currentY = e.clientY;

            this.updateSwipeOffset();
        },

        handleMouseEnd(e) {
            if (!this.isDragging) return;

            this.handleSwipeEnd();
            this.isDragging = false;
            this.isMouseDown = false;
        },

        // Обновление смещения
        updateSwipeOffset() {
            const deltaX = this.currentX - this.startX;
            this.swipeOffset = deltaX;
            this.swipeRotation = deltaX * 0.1; // Поворот карточки
        },

        // Обработка завершения свайпа
        handleSwipeEnd() {
            const threshold = 100; // Минимальное расстояние для срабатывания

            if (Math.abs(this.swipeOffset) > threshold) {
                if (this.swipeOffset > 0) {
                    // Свайп вправо - лайк
                    this.likeCard(this.visibleCards[0].id);
                } else {
                    // Свайп влево - дизлайк
                    this.dislikeCard(this.visibleCards[0].id);
                }
            } else {
                // Возврат карточки на место
                this.resetCardPosition();
            }
        },

        // Сброс позиции карточки
        resetCardPosition() {
            this.swipeOffset = 0;
            this.swipeRotation = 0;
        },

        // Лайк карточки
        likeCard(user_id) {
            this.like(user_id);
        },

        // Дизлайк карточки
        dislikeCard(user_id) {
            this.nextCard(user_id);
        },

        async fetchCurrentUserId() {
            try {
                const response = await axios.get('/api/user-id');
                console.log('Ответ от API:', response.data);

                if (response.data && response.data.user_id !== undefined) {
                    localStorage.setItem('user_id', response.data.user_id);
                    console.log('user_id сохранен в localStorage:', response.data.user_id);
                } else {
                    console.error('Поле user_id отсутствует в ответе');
                }
            } catch (error) {
                console.error('Ошибка при получении ID пользователя:', error);
            }
        },

        like(user_id) {
            axios.post('/api/like', {'user_id': user_id})
                .then(response => {
                    console.log('Лайк отправлен:', response.data);
                    this.nextCard(user_id);
                })
                .catch(error => {
                    console.error('Ошибка при отправке лайка:', error);
                });
        },

        nextCard(cardId) {
            this.swipeOffset = this.swipeOffset > 0 ? window.innerWidth : -window.innerWidth;

            setTimeout(() => {
                this.cards = this.cards.filter(card => card.id !== cardId);
                this.resetCardPosition();

                if (this.cards.length === 0) {
                    this.endOfCards = true;
                    this.currentIndex = 0;
                } else if (this.currentIndex >= this.cards.length) {
                    this.currentIndex = 0;
                }
            }, 300);
        },

        loadCards() {
            axios.get('/api/cards')
                .then(response => {
                    this.cards = response.data;
                    if (this.cards.length === 0) {
                        this.endOfCards = true;
                    }
                })
                .catch(error => {
                    console.error('Ошибка при загрузке карт:', error);
                });
        }
    },

    mounted() {
        this.fetchCurrentUserId();

        // Предотвращение скролла при свайпе
        document.addEventListener('touchmove', (e) => {
            if (this.isDragging) {
                e.preventDefault();
            }
        }, {passive: false});
    },

    created() {
        this.loadCards();
    }
};
</script>

<style scoped>
/* Анимация плавного исчезновения и появления */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s ease-in-out;
}

.fade-enter, .fade-leave-to {
    opacity: 0;
}

/* Отключение выделения текста */
.select-none {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
</style>
