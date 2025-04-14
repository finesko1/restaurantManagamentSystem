<template>
    <div class="p-4">
        <h2 class="text-2xl mb-4">Заказ #{{ orderId }} - {{ totalPrice ?? 0 }} р</h2>

        <!-- Блок статуса заказа для повара -->
        <div v-if="isCook" class="mb-4">
            <label class="block text-sm font-medium mb-1">Статус заказа:</label>
            <select
                v-model="order.status"
                @change="updateOrderStatus(orderId)"
                class="border p-2 rounded bg-white w-full md:w-1/3"
            >
                <option
                    v-for="(label, status) in statuses"
                    :key="status"
                    :value="status"
                >
                    {{ label }}
                </option>
            </select>
        </div>

        <div class="mb-4">
            <h3 class="text-xl mb-2">Состав заказа:</h3>
            <div v-for="item in orderItems" :key="item.id" class="mb-2">
                <div class="flex justify-between items-center">
                    <div class="flex-1">
                        <template v-if="isCook">
                            <select
                                v-model="item.status"
                                @change="updateItemStatus(item)"
                                class="border p-1 rounded bg-white mr-2"
                            >
                                <option
                                    v-for="(label, status) in statuses"
                                    :key="status"
                                    :value="status"
                                >
                                    {{ label }}
                                </option>
                            </select>
                        </template>

                        <!-- Для официанта - текстовое отображение статуса -->
                        <template v-else>
                            <span class="mr-2">({{ statuses[item.status] }})</span>
                        </template>

                        {{ item.menu_name }} x {{ item.count }} - {{ item.price }} р

                        <!-- Кнопка удаления только для официанта -->
                        <button
                            v-if="isWaiter"
                            @click="removeItem(item.id)"
                            class="text-red-500 ml-2 hover:text-red-700"
                        >
                            Удалить
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Кнопка добавления блюд только для официанта -->
        <button
            v-if="isWaiter"
            @click="showMenu = !showMenu"
            class="btn-secondary mb-4"
        >
            Добавить блюда
        </button>

        <Menu
            v-if="showMenu && isWaiter"
            :order="order"
            @updateOrder="fetchOrderItemsData"
            @close="showMenu = false"
        />
    </div>
</template>

<script>
import {computed, onMounted, ref} from 'vue';
import {useStore} from 'vuex';
import {useRoute} from 'vue-router';
import Menu from "@/components/Waiter/Menu.vue";

export default {
    components: {Menu},
    props: ['id', 'order', 'role'],
    setup(props, {emit}) {
        const store = useStore();
        const route = useRoute();
        const orderId = ref(props.id)
        const orderItems = ref({})
        const order = ref(props.order)
        const totalPrice = ref(0)
        const showMenu = ref(false)
        const isWaiter = computed(() => props.role === "waiter");
        const isCook = computed(() => props.role === "cook");
        const statuses = {
            'pending': 'Ожидает',
            'in_progress': 'Принят',
            'completed': 'Завершен',
            'canceled': 'Отклонен'
        };
        const statusOrder = {
            'pending': 1,
            'in_progress': 2,
            'completed': 3,
            'canceled': 4
        };

        onMounted(async() => {
            await fetchOrderItemsData()
        })

        const fetchOrderItemsData = async () => {
            const apiUrl = `/${store.state.auth.role}/order/${props.id}/items`;
            try {
                const response = await axios.get(apiUrl);

                orderItems.value = await Promise.all(
                    response.data.map(async item => {
                        try {
                            const menuResponse = await axios.get(`/${store.state.auth.role}/menu/${item.menu_id}`);
                            return {
                                ...item,
                                menu_name: menuResponse.data.name
                            };
                        } catch (error) {
                            console.error(`Error fetching menu item ${item.menu_id}:`, error);
                            return {
                                ...item,
                                menu_name: 'Неизвестное блюдо'
                            };
                        }
                    })
                );

                totalPrice.value = 0;
                orderItems.value.forEach(orderItem => {
                    totalPrice.value += orderItem.price;
                });
                totalPrice.value = parseFloat(totalPrice.value.toFixed(2));
            } catch (error) {
                console.error('Error fetching order details:', error);
            }
        };

        // Методы для обновления статусов
        const updateOrderStatus = async (id) => {
            try {
                await axios.patch(`/cook/order/${props.id}`, {
                    id: id,
                    status: order.value.status
                });
                await fetchOrderItemsData()
                emit('statusUpdated')
            } catch (error) {
                console.error('Ошибка обновления статуса заказа:', error);
            }
        };

        const updateItemStatus = async (item) => {
            try {
                await axios.patch(`/cook/order/items/${item.id}`, {
                    id: item.id,
                    status: item.status
                });
                await fetchOrderItemsData()
                emit('statusUpdated')
            } catch (error) {
                console.error('Ошибка обновления статуса блюда:', error);
            }
        };

        const removeItem = async (id) => {
            try
            {
                let response = await axios.delete(`/${store.state.auth.role}/order/items/${id}`)
                await fetchOrderItemsData()
            }
            catch(e)
            {
                console.error(e.message)
            }
        }

        return {
            orderItems,
            orderId,
            order,
            statuses,
            removeItem,
            showMenu,
            fetchOrderItemsData,
            totalPrice,
            isWaiter,
            isCook,
            updateOrderStatus,
            updateItemStatus
        };
    },
};
</script>
