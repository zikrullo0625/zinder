<template>
    <div class="max-w-3xl mx-auto p-4 bg-[#121212] min-h-screen text-white">
        <div class="flex items-center mb-6">
            <router-link to="/" class="text-white text-2xl mr-4">
                <i class="fas fa-arrow-left"></i>
            </router-link>
            <h1 class="text-3xl font-bold">Запросы</h1>
        </div>

        <div v-if="loading" class="text-center py-10 text-gray-400">
            Загрузка...
        </div>

        <div v-if="requests.length === 0 && !loading" class="text-center text-gray-500">
            Нет новых запросов.
        </div>

        <div
            v-for="request in requests"
            :key="request.id"
            class="bg-gray-800 rounded-xl overflow-hidden mb-6 shadow-lg"
        >
            <img
                :src="'/storage/' + request.user.image"
                alt="Фото пользователя"
                class="w-full h-64 object-cover"
            />
            <div class="p-4">
                <h2 class="text-xl font-bold mb-1">{{ request.user.name }}, {{ request.user.age }}</h2>
                <p class="text-gray-300 whitespace-pre-wrap">{{ request.user.description || 'Без описания' }}</p>
                <div class="flex justify-center mt-4 space-x-4">
                    <button
                        @click="handleAccept(request.id)"
                        class="w-12 h-12 bg-green-600 hover:bg-green-700 rounded-full flex items-center justify-center text-white text-xl"
                        aria-label="Принять"
                    >
                        <i class="fas fa-thumbs-up"></i>
                    </button>
                    <button
                        @click="handleReject(request.id)"
                        class="w-12 h-12 bg-red-600 hover:bg-red-700 rounded-full flex items-center justify-center text-white text-xl"
                        aria-label="Отклонить"
                    >
                        <i class="fas fa-thumbs-down"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <navbar></navbar>
</template>


<script>
export default {
    data() {
        return {
            requests: [],
            loading: false,
        };
    },
    methods: {
        fetchRequests() {
            this.loading = true;
            axios
                .get('/api/requests')
                .then((response) => {
                    this.requests = response.data.requests || [];
                })
                .catch((error) => {
                    console.error('Ошибка при загрузке запросов:', error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        handleAccept(id) {
            axios
                .post(`/api/requests/${id}/accept`)
                .then((response) => {
                    if(response.data.success) {
                        this.requests = this.requests.filter((r) => r.id !== id);
                    }
                })
                .catch(() => {
                    alert('Ошибка при принятии запроса');
                });
        },
        handleReject(id) {
            axios
                .post(`/api/requests/${id}/reject`)
                .then((response) => {
                    if(response.data.success) {
                        this.requests = this.requests.filter((r) => r.id !== id);
                    }
                })
                .catch(() => {
                    alert('Ошибка при отклонении запроса');
                });
        },
    },
    mounted() {
        this.fetchRequests();
    },
};
</script>
