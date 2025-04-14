<template>
    <div class="p-6 relative bg-gray-50">
        <h2 class="text-3xl font-semibold mb-6 text-gray-800">Заказы, роль: {{ role }}</h2>

        <!-- Основной контент -->
        <div class="space-y-6">
            <div v-for="order in orders" :key="order.id" class="p-4 border rounded-lg bg-white shadow-md transition-transform transform ">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-xl text-gray-900">Заказ #{{ order.id }}</h3>
                        <p class="text-gray-700">Стол: {{ order.table_id ?? 0 }}</p>
                        <p class="text-gray-700">Статус: <span class="font-semibold">{{ statuses[order.status] }}</span></p>
                    </div>
                    <button
                        @click="openOrderDetail[order.id] = !openOrderDetail[order.id]"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        Подробнее
                    </button>
                </div>
                <OrderDetail
                    v-if="openOrderDetail[order.id]"
                    :id="order.id"
                    :order="order"
                    :role="role"
                    @statusUpdated="fetchOrdersData"
                    @close="openOrderDetail[order.id] = false"
                />
            </div>
        </div>

        <div v-if="role === 'waiter'">
            <!-- Кнопка открытия модального окна -->
            <div class="mt-8 flex justify-center">
                <button
                    @click="openCreateOrder = true"
                    class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-md"
                >
                    Создать заказ
                </button>
            </div>

            <div v-if="openCreateOrder" class="fixed inset-0 z-50">
                <div
                    class="absolute inset-0 bg-gray-900 bg-opacity-50"
                    @click="openCreateOrder = false"
                ></div>

                <!-- Контент модального окна -->
                <div class="relative h-full flex items-center justify-center p-4">
                    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl p-6 relative z-10">
                        <button
                            @click="openCreateOrder = false"
                            class="absolute -top-2 -right-2 z-20 w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300
                           transition-colors flex items-center justify-center shadow-sm border border-gray-300
                           text-gray-600 hover:text-gray-800"
                        >
                            X
                        </button>

                        <CreateOrder
                            @orderCreated="handleOrderCreated"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import {computed, onMounted, ref} from 'vue';
import { useStore } from 'vuex';
import router from "@/router/index.js";
import OrderDetail from './OrderDetail.vue';
import CreateOrder from "@/components/Waiter/CreateOrder.vue"; // Import OrderDetail

export default {
    name: 'waiter-orders',
    components: {
        CreateOrder,
        OrderDetail // Register OrderDetail
    },
    setup() {
        const store = useStore();
        const orders = ref({})
        const openOrderDetail = ref([])
        const openCreateOrder = ref(false)

        const statuses = {
            'pending': 'Ожидает',
            'in_progress': 'Принят',
            'completed': 'Завершен',
            'canceled': 'Отклонен'
        };

        onMounted(async() => {
            await fetchOrdersData();
        })

        const fetchOrdersData = async () => {
            try {
                const response = await axios.get(`/${store.state.auth.role}/order`);
                orders.value = response.data;
            } catch(e) {
                console.log(e.message)
                if(e.response?.status === 401) {
                    router.push('/')
                }
            }
        };

        const handleOrderCreated = async () => {
            await fetchOrdersData()
            openCreateOrder.value = false
        }


        return {
            orders,
            handleOrderCreated,
            openOrderDetail,
            openCreateOrder,
            statuses,
            role: store.state.auth.role,
            fetchOrdersData
        };
    },
};
</script>
