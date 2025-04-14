import './bootstrap';

import { createApp } from 'vue';
import { createStore } from 'vuex';
import App from './components/App/App.vue';
import router from "./router/index.js";
import { createPinia } from 'pinia';
const pinia = createPinia();
import store from './stores/store.js';
// Создание хранилища Vuex

createApp(App).use(router).use(store).use(pinia).mount('#app');
