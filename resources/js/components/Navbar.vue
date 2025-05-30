<template>
    <div>
        <!-- Затемняющий overlay -->
        <div
            v-if="showOverlay"
            class="fixed inset-0 bg-black transition-opacity duration-600 ease-out"
            :class="{ 'opacity-70': showOverlay, 'opacity-0': !showOverlay }"
            style="z-index: 45;"
        ></div>
        <!-- Добавь это ПЕРЕД .bottom-nav -->
        <div
            v-if="showFloatingHeart"
            class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none"
        >
            <div class="relative flex flex-col items-center">
                <i
                    class="fa-solid fa-heart text-red-500 text-6xl animate-pop"
                    style="text-shadow: 0 0 15px red;"
                ></i>
                <div
                    class="text-red-500 font-bold text-2xl match-text mt-2"
                    style="font-family: 'Kalam', cursive;"
                >
                    It's a Match!
                </div>
            </div>
        </div>

        <div class="bottom-nav" ref="nav">
            <router-link to="/" class="text-light"><i class="fas fa-home"></i></router-link>

            <div ref="matchIconWrapper" class="relative w-6 h-6 flex flex-col items-center justify-center">
                <router-link to="/requests" class="text-light">
                    <i
                        ref="matchIcon"
                        class="fa-solid fa-heart transition-all duration-300 text-xl"
                        :class="{ 'text-white': !isAnimating, 'text-red-500': isAnimating }"
                    ></i>
                </router-link>

                <!-- Текст Match с рукописным шрифтом -->
                <div
                    v-if="showMatchText"
                    class="absolute bottom-full mb-2 text-red-500 font-bold text-xl match-text"
                    style="z-index: 60; white-space: nowrap;"
                >
                    It's a Match!
                </div>
            </div>

            <router-link to="/messenger" class="text-light"><i class="fas fa-comment"></i></router-link>
            <router-link to="/profile" class="text-light"><i class="fas fa-user"></i></router-link>
        </div>
    </div>
</template>

<script>
import { gsap } from "gsap";
import Pusher from "pusher-js";

export default {
    name: 'BottomNav',
    data() {
        return {
            showMatchText: false,
            showOverlay: false,
            isAnimating: false,
            userId: null,
            showFloatingHeart: false,
        };
    },
    mounted() {
        this.getUserId().then(() => {
            this.initializePusher();
        });
    },

    methods: {
        async getUserId() {
            const response = await axios.get('/api/user-id');
            this.userId = response.data.user_id;
        },

        initializePusher() {
            if (!this.userId) {
                console.error('User ID is not set yet');
                return;
            }
            console.log('initializing Pusher for user:', this.userId);

            const pusher = new Pusher('7a56b3c7a3a2b892f2e0', {
                cluster: 'us3',
            });

            const channel = pusher.subscribe('user-' + this.userId);

            channel.bind('match-found', (data) => {
                console.log('Match found event received:', data);
                this.animateMatch();
            });
        },
        animateMatch() {
            this.showFloatingHeart = true;
            this.showOverlay = true;

            // Таймлайн на исчезновение через 2.5 сек
            gsap.timeline()
                .to({}, { duration: 2 }) // просто пауза
                .call(() => {
                    this.showFloatingHeart = false;
                    this.showOverlay = false;
                });
        },
    },
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Kalam:wght@400;700&display=swap');

.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: #1e1e1e;
    padding: 10px 0;
    display: flex;
    justify-content: space-around;
    align-items: center;
    z-index: 50;
}

.bottom-nav a {
    color: white;
    font-size: 20px;
}
@keyframes pop {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
        opacity: 1;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.animate-pop {
    animation: pop 0.5s ease-out;
}

.match-text {
    font-family: 'Kalam', cursive;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    animation: pulse 0.5s ease-in-out;
}

@keyframes pulse {
    0% { transform: scale(0.8); opacity: 0; }
    50% { transform: scale(1.1); opacity: 1; }
    100% { transform: scale(1); opacity: 1; }
}
</style>
