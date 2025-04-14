<template>
    <div class="cook-dashboard">
        <OrderList>
        </OrderList>
    </div>
</template>

<script>
import { useStore } from 'vuex';
import { computed } from 'vue';
import OrderList from "@/components/Orders/OrderList.vue";

export default {
    name: 'CookDashboard',
    components: {OrderList},
    setup() {
        const store = useStore();
        const role = computed(() => store.getters['auth/role']);

        return {
            role
        };
    },
    beforeRouteEnter(to, from, next) {
        const store = useStore();
        if (store.getters['auth/role'] !== 'cook') {
            next('/');
        } else {
            next();
        }
    }
};
</script>
