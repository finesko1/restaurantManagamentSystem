<template>
    <div class="waiter-dashboard">
        <OrderList>
        </OrderList>
    </div>
</template>

<script>
import { ref, computed } from 'vue';
import OrderList from '../Orders/OrderList.vue';
import CreateOrder from '../Orders/CreateOrder.vue';
import OrderDetail from '../Orders/OrderDetail.vue';
import store from "@/stores/store.js";

export default {
    name: 'WaiterDashboard',
    components: {
        OrderList,
        CreateOrder,
        OrderDetail
    },
    setup() {
        const currentView = ref('OrderList');
        const selectedOrder = ref(null);

        // Обработчик создания заказа
        const handleOrderCreated = () => {
            currentView.value = 'OrderList';
        };

        return {
            currentView,
            selectedOrder,
            handleOrderCreated,
        };
    },
    beforeRouteEnter(to, from, next) {
        if (store.getters['auth/role'] !== 'waiter') {
            next('/');
        } else {
            next();
        }
    }
};
</script>
