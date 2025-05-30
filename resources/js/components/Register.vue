<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-900">
        <div class="w-full max-w-md px-6">
            <div class="bg-gray-800 text-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-semibold text-center mb-6">Регистрация в Zinder</h2>
                <div>
                    <form @submit.prevent="register">
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium mb-1">Электронная почта</label>
                            <input
                                type="email"
                                id="email"
                                v-model="email"
                                required
                                placeholder="Введите вашу почту"
                                class="w-full px-4 py-2 rounded-md bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500"
                            />
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium mb-1">Пароль</label>
                            <input
                                type="password"
                                id="password"
                                v-model="password"
                                required
                                placeholder="Введите ваш пароль"
                                class="w-full px-4 py-2 rounded-md bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500"
                            />
                        </div>
                        <button type="submit" class="w-full py-2 px-4 bg-purple-700 hover:bg-purple-800 rounded-md text-white font-semibold">
                            Зарегистрироваться
                        </button>
                    </form>
                    <div class="mt-4 text-center">
                        <router-link to="/login" class="text-sm text-gray-300 hover:text-white">Уже есть аккаунт? Войти</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            email: '',
            password: '',
        };
    },
    methods: {
        async register() {
            try {
                const response = await axios.post('/api/register', {
                    email: this.email,
                    password: this.password,
                });

                if (response.status === 200) {
                    this.$router.push('/startup');
                }
            } catch (error) {
                console.error(error);
            }
        },
    },
};
</script>
