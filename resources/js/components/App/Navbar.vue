<template>
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <router-link class="text-xl font-semibold text-gray-800" to="/">
                    {{ appName }}
                </router-link>
                <div :class="{'hidden': !isMenuOpen, 'block': isMenuOpen}" class="lg:block">
                    <ul class="flex items-center space-x-4">
                        <li v-if="user" class="text-gray-700">Добро пожаловать, {{ user.name }}!</li>
                        <li v-if="user">
                            <a class="text-gray-700 hover:text-gray-900 transition duration-200" href="#" @click.prevent="logout">Logout</a>
                        </li>
                        <li v-else>
                            <router-link class="text-gray-700 hover:text-gray-900 transition duration-200" to="/login">Login</router-link>
                        </li>
                        <li v-if="!user">
                            <router-link class="text-gray-700 hover:text-gray-900 transition duration-200" to="/register">Register</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import {ref, onMounted, computed, watch} from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';
import axios from 'axios';

export default {
    name: 'Navbar',
    setup() {
        const store = useStore();
        const isMenuOpen = ref(false);
        const appName = 'Restaurant Management System';
        const route = useRoute();

        // Реактивные геттеры
        const user = computed(() => store.state.auth.user);
        const role = computed(() => store.state.auth.role);

        // Получение информации о пользователе
        const fetchUser = async () => {
            await store.dispatch('fetchUser');
        };

        watch(() => route.path, (newPath) => {
            if (newPath === '/') {
                fetchUser();
            }
        });

        // Логика выхода
        const logout = async () => {
            try {
                await axios.post(`/${role.value}/logout`);
                await store.dispatch('logout');
                window.location.reload()
            } catch (error) {
                console.error('Ошибка при выходе:', error);
            }
        };

        onMounted(async () => {
            await fetchUser();
        });

        const toggleMenu = () => {
            isMenuOpen.value = !isMenuOpen.value;
        };

        return {
            isMenuOpen,
            appName,
            toggleMenu,
            logout,
            user,
            role
        };
    },
};
</script>
