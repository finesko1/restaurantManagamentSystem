<template>
    <div class="home">
        <component :is="currentDashboard" />
    </div>
</template>

<script>
import CookDashboard from '@/components/Cook/CookDashboard.vue';
import WaiterDashboard from '@/components/Waiter/WaiterDashboard.vue';
import Welcome from "@/components/Welcome.vue";

import {computed, onMounted, watch} from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';

export default {
    name: 'Home',
    components: {
        CookDashboard,
        WaiterDashboard,
        Welcome
    },
    setup() {
        const store = useStore();
        const route = useRoute();

        // Реактивные геттеры
        const user = computed(() => store.state.auth.user);
        const role = computed(() => store.state.auth.role);

        // Определяем полотно
        const currentDashboard = computed(() => {
            if (role.value === null)
                return Welcome;
            return role.value === 'cook' ? CookDashboard : WaiterDashboard;
        });

        // Получение информации о пользователе
        const fetchUser = async () => {
            await store.dispatch('fetchUser');
        };

        // Обновляем данные при изменении маршрута
        onMounted(async () => {
            await fetchUser();
        });

        // Следим за изменениями пути
        // watch(() => route.path, (newPath) => {
        //     if (newPath === '/') {
        //         fetchUser();
        //     }
        // });

        return {
            currentDashboard,
            user,
            role
        };
    }
};
</script>
