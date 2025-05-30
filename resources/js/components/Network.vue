<template>
    <div class="app-container">
        <!-- Story List -->
        <div class="story-list">
            <div class="navbar">
            <h2 class="story-list-title">Wall</h2>
            <h2 class="profile-text">Профиль</h2>
            </div>
            <div class="story-scroll">
                <div v-for="story in stories" :key="story.id" class="story-container">
                    <img :src="story.image" alt="Story Avatar" class="story-image" />
                    <p class="story-name">{{ story.name }}</p>
                </div>
            </div>
        </div>

        <!-- Message List -->
        <div class="message-list">
            <div v-for="message in messages" :key="message.id" class="message-card">
                <div class="message-header">
                    <img :src="message.avatar" alt="Sender Avatar" class="avatar" />
                    <p class="sender-name">{{ message.sender }}</p>
                </div>
                <div class="message-body">
                    <div v-if="message.image" class="message-image-container">
                        <img :src="message.image" alt="Message Image" class="message-image" />
                    </div>
                    <p class="message-text">{{ message.text }}</p>
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
            stories: [
                { id: 1, name: 'Jane', image: 'https://www.bootdey.com/img/Content/avatar/avatar2.png' },
                { id: 2, name: 'John', image: 'https://www.bootdey.com/img/Content/avatar/avatar3.png' },
                { id: 3, name: 'Katie', image: 'https://www.bootdey.com/img/Content/avatar/avatar4.png' },
                { id: 4, name: 'Michael', image: 'https://www.bootdey.com/img/Content/avatar/avatar5.png' },
                { id: 5, name: 'Sarah', image: 'https://www.bootdey.com/img/Content/avatar/avatar6.png' },
                { id: 5, name: 'Sarah', image: 'https://www.bootdey.com/img/Content/avatar/avatar6.png' },
                { id: 5, name: 'Sarah', image: 'https://www.bootdey.com/img/Content/avatar/avatar6.png' },
                { id: 5, name: 'Sarah', image: 'https://www.bootdey.com/img/Content/avatar/avatar6.png' },
                { id: 5, name: 'Sarah', image: 'https://www.bootdey.com/img/Content/avatar/avatar6.png' },
            ],
            messages: [
                {
                    id: 1,
                    sender: 'John',
                    text: 'Hey, how are you?',
                    avatar: 'https://www.bootdey.com/img/Content/avatar/avatar1.png',
                    image: 'https://www.bootdey.com/image/580x580/00BFFF/000000',
                },
                {
                    id: 2,
                    sender: 'Jane',
                    text: "I'm doing well, thanks for asking. How about you?",
                    avatar: 'https://www.bootdey.com/img/Content/avatar/avatar2.png',
                    image: 'https://www.bootdey.com/image/580x580/FF00FF/000000',
                },
                {
                    id: 3,
                    sender: 'John',
                    text: "I'm doing well too. Do you want to grab a coffee later?",
                    avatar: 'https://www.bootdey.com/img/Content/avatar/avatar3.png',
                    image: 'https://www.bootdey.com/image/580x580/008000/000000',
                },
            ],
        }
    },
}
</script>

<style scoped>
.navbar {
    display: flex;
}
.app-container {
    background-color: #121212;
    color: #ffffff;
    min-height: 100vh;
    padding: 20px;
    display: flex;
    flex-direction: column;
}

.story-list {
    margin-bottom: 20px;
}
.profile-text{
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
}
.story-list-title  {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
}

.story-scroll {
    display: flex;
    overflow-x: scroll;
    padding-bottom: 10px;
}

.story-container {
    margin-right: 15px;
    text-align: center;
}

.story-image {
    min-width: 70px;
    min-height: 70px;
    border-radius: 50%;
    margin-bottom: 5px;
}

.story-name {
    font-size: 13px;
    font-weight: bold;
}

.message-list {
    margin-top: 20px;
}

.message-card {
    background-color: #1e1e1e;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
}

.message-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.sender-name {
    font-weight: bold;
}

.message-body {
    flex: 1;
}

.message-image-container {
    margin-top: 10px;
}

.message-image {
    width: 100%;
    height: 150px;
    border-radius: 10px;
}

.message-text {
    margin-top: 10px;
}
</style>
