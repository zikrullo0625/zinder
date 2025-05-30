<template>
    <div class="min-h-screen bg-[#121210] text-white flex flex-col justify-between">
        <div class="w-full max-w-md mx-auto px-4 py-6 flex-grow">
            <!-- Image Upload Section -->
            <div class="relative w-full h-48 rounded-lg overflow-hidden mb-6">
                <label
                    for="image-upload"
                    class="block w-full h-full cursor-pointer relative group"
                >
                    <img
                        :src="user.image"
                        alt="Фото пользователя"
                        class="w-full h-full object-cover"
                    />
                    <!-- Текст по центру появляется при наведении -->
                    <div
                        class="absolute inset-0 flex items-center justify-center pointer-events-none"
                        style="background: transparent;"
                    >
            <span
                class="text-white text-sm font-medium bg-black bg-opacity-50 px-3 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-auto"
            >
              Нажмите, чтобы выбрать фото
            </span>
                    </div>
                </label>
                <input
                    id="image-upload"
                    type="file"
                    class="hidden"
                    accept="image/*"
                    @change="onImageChange"
                />
            </div>

            <!-- Form Section -->
            <form @submit.prevent="save" class="space-y-4">
                <!-- Name -->
                <div>
                    <label
                        for="name"
                        class="block text-white text-sm font-medium mb-1"
                    >Имя</label
                    >
                    <input
                        id="name"
                        v-model="user.name"
                        type="text"
                        placeholder="Введите имя"
                        class="w-full bg-gray-700 text-white border-none rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                </div>

                <!-- Age -->
                <div>
                    <label
                        for="age"
                        class="block text-white text-sm font-medium mb-1"
                    >Возраст</label
                    >
                    <input
                        id="age"
                        v-model.number="user.age"
                        type="number"
                        min="1"
                        placeholder="Введите возраст"
                        class="w-full bg-gray-700 text-white border-none rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                </div>

                <!-- Gender -->
                <div>
                    <label
                        for="gender"
                        class="block text-white text-sm font-medium mb-1"
                    >Пол</label
                    >
                    <select
                        id="gender"
                        v-model="user.gender"
                        class="w-full bg-gray-700 text-white border-none rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="male">Мужчина</option>
                        <option value="female">Женщина</option>
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label
                        for="description"
                        class="block text-white text-sm font-medium mb-1"
                    >Описание</label
                    >
                    <textarea
                        id="description"
                        v-model="user.description"
                        rows="4"
                        placeholder="Описание..."
                        class="w-full bg-gray-700 text-white border-none rounded-lg px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                    ></textarea>
                </div>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="w-full bg-white text-gray-900 text-base font-semibold py-2 rounded-lg transition-all duration-200 hover:bg-gray-100 active:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 shadow-sm"
                >
                    Сохранить
                </button>
            </form>
        </div>

        <navbar />
    </div>
</template>

<script>
export default {
    data() {
        return {
            image_changed: false,
            user: {
                image:
                    'https://i.pinimg.com/736x/7e/ff/48/7eff486e273bd6d1b314b909cd65ae84.jpg',
                name: 'Анна',
                gender: 'Женщина',
                age: 25,
                description: 'Я люблю путешествовать и искать новых друзей.'
            }
        };
    },
    methods: {
        save() {
            if (!this.image_changed) {
                axios
                    .post('/api/update-card/', {
                        status: this.image_changed,
                        image: this.user.image,
                        name: this.user.name,
                        gender: this.user.gender,
                        age: this.user.age,
                        description: this.user.description
                    })
                    .then(response => {
                        console.log('Данные сохранены:', response.data);
                        alert('Профиль успешно сохранен!');
                    })
                    .catch(error => {
                        console.error('Ошибка при сохранении профиля:', error);
                        alert('Ошибка при сохранении данных.');
                    });
            } else {
                const formData = new FormData();
                const imageInput = document.querySelector('#image-upload');
                if (imageInput && imageInput.files[0]) {
                    formData.append('image', imageInput.files[0]);
                } else {
                    formData.append('image', this.user.image);
                }
                formData.append('name', this.user.name);
                formData.append('age', this.user.age);
                formData.append('gender', this.user.gender);
                formData.append('status', this.image_changed);
                formData.append('description', this.user.description);

                axios
                    .post('/api/update-card/', formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    })
                    .then(response => {
                        console.log('Данные сохранены:', response.data);
                        alert('Профиль успешно сохранен!');
                    })
                    .catch(error => {
                        console.error('Ошибка при сохранении профиля:', error);
                        alert('Ошибка при сохранении данных.');
                    });
            }
        },
        me() {
            axios
                .get('/api/card')
                .then(response => {
                    this.user.image =
                        'http://127.0.0.1:8000/storage/' + response.data.image;
                    this.user.name = response.data.name;
                    this.user.age = response.data.age;
                    this.user.gender = response.data.gender;
                    this.user.description = response.data.description;
                })
                .catch(error => {
                    console.error('Ошибка при загрузке профиля:', error);
                    alert('Ошибка при загрузке данных.');
                });
        },
        onImageChange(event) {
            this.image_changed = true;
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    this.user.image = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    },
    created() {
        this.me();
    }
};
</script>

<style scoped>
/* Если хочешь кастомные стили для scrollbar или что-то специфическое — можно добавить */
</style>
