<template>
    <div class="p-4">
        <h2 class="text-2xl mb-4">Блюда</h2>
        <div class="mb-4">
            <div v-for="dish in menu" :key="dish.id" class="mb-4">
                <div class="grid grid-cols-4 gap-4 items-center border-b pb-2">
                    <div class="col-span-1">
                        <span class="font-medium">{{ dish.name }}</span>
                    </div>
                    <div class="col-span-1">
                        <span class="text-gray-700">{{ dish.price }}</span>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium mb-1">Количество</label>
                        <input v-model="count[dish.id]" type="number" required class="input-field border border-gray-300 rounded-md p-2 w-full">
                    </div>
                    <div class="col-span-1 flex justify-end">
                        <button
                            @click="addItem(dish.id, count[dish.id])"
                            class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                            Добавить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {onMounted, ref} from 'vue';
import {useStore} from 'vuex';
import {useRoute} from 'vue-router';

export default {
    props: ['order'],
    setup(props, {emit}) {
        const store = useStore();
        const route = useRoute();
        const order = ref(props.order)
        const menu = ref({})
        const count = ref([])

        onMounted(async() => {
            await fetchMenuItems()
        })

        const fetchMenuItems = async () => {
            try {
                let response = await axios.get('/waiter/menu')
                menu.value = response.data
            } catch (error) {
                console.error('Error fetching order details:', error);
            }
        };

        const addItem = async (menu_id, count) => {
            try
            {
                let itemForm = new FormData
                itemForm.append('order_id', order.value.id)
                itemForm.append('menu_id', menu_id)
                itemForm.append('count', count)
                let response = await axios.post(`/waiter/order/items`, itemForm)

                emit('updateOrder')
            }
            catch(e)
            {
                console.error(e.message)
            }
        }

        return {
            addItem,
            menu,
            order,
            count
        };
    },
};
</script>
