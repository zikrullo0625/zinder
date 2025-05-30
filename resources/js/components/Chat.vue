<template>
    <div class="h-screen flex flex-col bg-[#0b0c0b] text-gray-200">
        <!-- Header -->
        <div class="flex items-center justify-start  px-4 py-3 text-white text-base">
            <button @click="$router.push('/messenger')" class="text-white text-xl">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <div class="flex-grow text-center font-medium">{{ friendName }}</div>
        </div>

        <!-- Messages -->
        <div class="flex-grow overflow-y-auto p-4" ref="messageList">
            <div
                v-for="(message, index) in messages"
                :key="index"
                class="mb-3 flex"
                :class="{ 'justify-end': message.from_me }"
            >
                <div
                    class="max-w-[70%] p-2 break-words whitespace-pre-wrap"
                    :class="[
    message.from_me
      ? 'bg-[#ccd8de] text-[#2d3240] rounded-tl-xl rounded-tr-xl rounded-bl-xl'
      : 'bg-[#1f201f] text-white rounded-tl-lg rounded-tr-lg rounded-br-lg'
  ]"
                >
                    <div>{{ message.content }}</div>
                </div>
            </div>
        </div>

        <!-- Input -->
        <div class="flex gap-2 pb-2 pl-2 pr-2 sticky bottom-0 ">
            <input
                v-model="newMessage"
                @keyup.enter="sendMessage"
                type="text"
                placeholder="Введите сообщение..."
                class="flex-grow px-3 py-2 bg-[#161716] rounded-5 text-sm"
            >
            <button
                @click="sendMessage"
                class="w-10 h-10 flex items-center justify-center rounded-5 border-1 border-[#161716] text-white  hover:bg-blue-600"
            >
                <i class="fa-solid fa-paper-plane text-sm"></i>
            </button>
        </div>
    </div>
</template>

<script>
import Pusher from 'pusher-js';
export default {
    data() {
        return {
            messages: [],
            newMessage: '',
            roomId: this.$route.params.roomId,
            friendName: null,
            currentUserId: null,
        };
    },
    methods: {
        async fetchMessages() {
            try {
                const response = await axios.get(`/api/messages/${this.roomId}`);
                this.messages = response.data.messages;
                this.friendName = response.data.friendName;
                this.scrollToBottom();
            } catch (error) {
                console.error('Ошибка при загрузке сообщений:', error);
            }
        },
        async userId() {
            try {
                const response = await axios.get(`/api/user-id`);
                this.currentUserId = response.data.user_id;
            } catch (error) {
                console.error('Ошибка при загрузке сообщений:', error);
            }
        },
        sendMessage() {
            const newMessage = this.newMessage;
            this.newMessage = '';
            if (newMessage.trim()) {
                axios
                    .post(`/api/messages/${this.roomId}`, { content: newMessage })
                    .catch(error => console.error('Ошибка при отправке сообщения:', error));
            }
        },
        scrollToBottom() {
            this.$nextTick(() => {
                const list = this.$refs.messageList;
                if (list) list.scrollTop = list.scrollHeight;
            });
        },
        initializePusher() {
            console.log('initializing Pusher');
            const pusher = new Pusher('7a56b3c7a3a2b892f2e0', {
                cluster: 'us3',
            });
            const channel = pusher.subscribe('chat-' + this.roomId);

            channel.bind('message-sent', (data) => {
                const currentUserId = this.currentUserId;
console.log('CurrentUserId' + this.currentUserId);
                const message = data.message;
                message.from_me = message.user_id === currentUserId;

                this.messages.push(message);
            });
        }
    },
    mounted() {
        this.initializePusher();
        this.fetchMessages();
        this.userId();
    }
};
</script>
