# Zinder

Zinder is a modern dating platform inspired by Tinder, built with Laravel (backend) and Vue.js (frontend).  
It includes real-time chat, match notifications, user authentication, and an elegant UI with Tailwind CSS.

![Zinder Screenshot 1](127.0.0.1_8000_(Samsung Galaxy S8+).png)
![Zinder Screenshot 2](127.0.0.1_8000_(Samsung Galaxy S8+).png)

## Features

- 🔥 Swipe-like user interface
- 💬 Real-time chat with WebSockets
- 💖 Match system with animated feedback
- 🧾 Queue handling for background jobs
- 👤 User profiles and authentication
- 📱 Responsive design with Tailwind CSS

## Tech Stack

- **Laravel** – Powerful PHP framework for backend and API
- **Vue.js** – Reactive frontend framework
- **Tailwind CSS** – Utility-first CSS framework
- **Pusher** – Real-time event broadcasting

## Setup Instructions

```bash
git clone https://github.com/zikrullo0625/zinder.git
cd zinder
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
npm install
npm run dev
php artisan serve
php artisan queue:work
```
