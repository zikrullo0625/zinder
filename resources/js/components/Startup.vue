<template>
    <div>
        <div v-if="page === 1" class="welcome-container">
            <div class="content">
                <i class="fa fa-heart heart-icon"></i>
                <h1 class="fade-in">Добро пожаловать в Zinder</h1>
                <p class="fade-in">Находите новых друзей, любовь и приключения прямо здесь.</p>
                <button class="start-button bounce" @click="goToNextPage">Начать</button>
            </div>
        </div>
        <div v-if="page === 2" class="profile-container">
            <!-- Карточка профиля -->
            <div class="card slide-in">
                <div class="card-img-container">
                    <label for="image-upload" class="image-label">
                        <img v-if="image_changed" :src="user.image" class="card-img" alt="Фото пользователя" />
                        <div v-if="user.image" class="placeholder">
                            Нажмите, чтобы выбрать файл
                        </div>
                    </label>
                    <input
                        id="image-upload"
                        type="file"
                        class="image-input"
                        accept="image/*"
                        @change="onImageChange"
                    />
                </div>
                <div class="card-body">
                    <h5 class="card-title">
                        <p>Имя</p>
                        <input v-model="user.name" type="text" class="profile-input" />
                        <p>Возраст</p>
                        <input v-model="user.age" type="number" class="profile-input" />
                        <p>Пол</p>
                        <select v-model="user.gender" class="form-select profile-input">
                            <option value="male">Мужчина</option>
                            <option value="female">Женщина</option>
                        </select>
                    </h5>
                    <p>Описание</p>
                    <textarea v-model="user.description" class="profile-input" placeholder="Описание..."></textarea>
                </div>
                <button class="btn  w-100 save_button" @click="save()">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            image_changed: false,
            page: 1,
            user: {
                image: 'https://i.pinimg.com/736x/7e/ff/48/7eff486e273bd6d1b314b909cd65ae84.jpg',
                name: 'Анна',
                age: 25,
                gender: 'female',
                description: 'Я люблю путешествовать и искать новых друзей.'
            }
        };
    },
    methods: {
        save() {
            const formData = new FormData();
            const imageInput = document.querySelector('#image-upload');
            if (imageInput && imageInput.files[0]) {
                formData.append('image', imageInput.files[0]); // Передаем файл
            } else {
                formData.append('image', this.user.image); // Если файл не выбран, передаем существующий URL
            }

            // Добавляем текстовые данные
            formData.append('name', this.user.name);
            formData.append('age', this.user.age);
            formData.append('gender', this.user.gender);
            formData.append('description', this.user.description);

            // Отправляем запрос
            axios.post('/api/card', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {
                    console.log('Данные сохранены:', response.data);
                    console.log('Профиль успешно сохранен!');
                    this.$router.push('/');
                })
                .catch(error => {
                    console.error('Ошибка при сохранении профиля:', error);
                    console.log('Ошибка при сохранении данных.');
                });
        },
        goToNextPage() {
            this.page = 2;
        },
        onImageChange(event) {
            this.image_changed = true;
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.user.image = e.target.result; // Устанавливаем base64 в объект user
                };
                reader.readAsDataURL(file);
            }
        }
    }
};
</script>

<style scoped>

.save_button{
    color: white;
    font-size: 18px;
    font-weight: normal;
    background: linear-gradient(135deg, #ff6f61, #d3453b) !important;
}
.welcome-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: linear-gradient(135deg, #1e1e1e, #121212);
    color: white;
    text-align: center;
    overflow: hidden;
}

.content {
    animation: fadeIn 1.5s ease-in-out;
}

.heart-icon {
    font-size: 5rem;
    color: #ff6f61;
    margin-bottom: 20px;
    animation: pulse 1.5s infinite;
}

h1 {
    font-size: 2.5rem;
    margin-bottom: 20px;
    animation: fadeInDown 1s ease-in-out;
}

p {
    font-size: 1.2rem;
    margin-bottom: 30px;
    animation: fadeInUp 1s ease-in-out;
}

.start-button {
    background: linear-gradient(135deg, #ff6f61, #d3453b);
    color: white;
    border: none;
    border-radius: 25px;
    padding: 15px 30px;
    font-size: 1rem;
    cursor: pointer;
    transition: transform 0.3s ease, background-color 0.3s ease;
}

.start-button:hover {
    transform: scale(1.1);
    background: linear-gradient(135deg, #d3453b, #ff6f61);
}

.start-button:active {
    transform: scale(0.95);
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

.bounce {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.profile-container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    min-height: 100vh;
    background: linear-gradient(135deg, #121212, #1e1e1e);
    color: white;
    padding: 20px;
    overflow-y: auto;
}

.card {
    width: 100%;
    max-width: 400px;
    background-color: #1e1e1e;
    border-radius: 15px;
    box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.5);
    margin-top: 15px;
    overflow: hidden;
    animation: slideIn 0.8s ease-in-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-img-container {
    width: 100%;
    height: 200px;
    position: relative;
}

.card-img {
    object-fit: cover;
    width: 100%;
    height: 100%;
}

.placeholder {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #2c2c2c;
    color: white;
    font-size: 16px;
    text-align: center;
}

.image-label {
    display: block;
    width: 100%;
    height: 100%;
    cursor: pointer;
    position: relative;
}

.image-input {
    display: none;
}

.card-body {
    padding: 15px;
}

.card-body h5,
.card-body p {
    color: white;
    margin-bottom: 10px;
}

.profile-input {
    background-color: #2c2c2c;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 10px;
    width: 100%;
    margin-bottom: 15px;
}

.profile-input::placeholder {
    color: #aaa;
}

button.btn-success {
    margin-top: 10px;
    padding: 10px;
    font-size: 16px;
}
</style>
