<template>
    <div class="chat-list-container">
        <div v-if="chats.length === 0" class="no-chats mt-10 text-center text-gray-400 flex flex-col items-center">
            <i class="fas fa-comment-slash text-5xl mb-4 text-gray-600"></i>
            <p class="text-lg">Нет доступных чатов</p>
            <p class="text-sm text-gray-500">Хм... никого нет. Но всё может начаться с одного лайка.</p>
        </div>
        <div v-for="chat in chats" :key="chat.id" class="chat-item">
            <router-link :to="'/chat/' + chat.id" class="chat-link">
                <div class="chat-content">
                    <img :src="chat.image" alt="Аватар" class="chat-avatar" />
                    <div class="chat-info">
                        <p class="chat-name">{{ chat.name }}</p>
                        <p class="last-message" v-if="chat.last_message">{{ chat.last_message }}</p>
                    </div>
                </div>
            </router-link>
        </div>
    </div>
    <navbar></navbar>
</template>

<script>
import Navbar from "./Navbar.vue";

export default {
    components: {Navbar},
    data() {
        return {
            chats: [],
            userId: null,
            hasRequest: false,
        };
    },
    methods: {
        getUserId(){
          axios.get('/api/user-id')
              .then((response) => {
                  this.userId = response.data.user_id;
              })
        },
        loadChats() {
            axios
                .get('/api/chats')
                .then((response) => {
                    this.chats = response.data.chats;
                    this.hasRequest = response.data.hasRequest;
                })
                .catch((error) => {
                    console.error('Ошибка при загрузке чатов:', error);
                });
            console.log(this.chats);
        }
    },
    created() {
        this.getUserId()
        this.loadChats();
    },
};
</script>

<style scoped>
.chat-list-container {
    background-color: #121212;
    color: #ffffff;
    min-height: 100vh;
    padding: 20px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

.no-chats {
    text-align: center;
    color: #888888;
    font-size: 18px;
}

.red-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    background-color: red;
    border-radius: 50%;
    margin-left: 8px;
    vertical-align: middle;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.3); opacity: 0.7; }
    100% { transform: scale(1); opacity: 1; }
}

.top-bar {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 10px;
}

.requests-button {
    background-color: #2a2a2a;
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s;
}

.requests-button:hover {
    background-color: #3a3a3a;
}

.chat-item {
    background-color: #1e1e1e;
    margin-bottom: 15px;
    border-radius: 10px;
    overflow: hidden;
    transition: background-color 0.3s;
}

.chat-item:hover {
    background-color: #2a2a2a;
}

.chat-link {
    text-decoration: none;
    color: #ffffff;
    display: flex;
    align-items: center;
    padding: 15px;
}

.chat-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 15px;
}

.chat-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.chat-info {
    flex: 1;
}

.chat-name {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 2px; /* маленький отступ снизу */
}

.last-message {
    font-size: 14px;
    color: #bbbbbb;
    margin-top: 0; /* убрать отступ сверху */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

</style>
