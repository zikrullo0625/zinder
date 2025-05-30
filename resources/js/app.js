import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import Navbar from './components/Navbar.vue';
import router from './router';
import 'bootstrap/dist/css/bootstrap.css'
import { library } from "@fortawesome/fontawesome-svg-core";
import { faPhone } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const app = createApp(App);
app.use(router);
app.component('navbar', Navbar)
library.add(faPhone);
app.mount('#app');
