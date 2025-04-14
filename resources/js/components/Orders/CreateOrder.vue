<template>
    <div class="p-6 max-w-lg mx-auto bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-center">Новый заказ</h2>
        <form @submit.prevent="createOrder">
            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium">Номер стола:</label>
                <input v-model="orderData.table_id" type="number" required class="input-field border border-gray-300 rounded-md p-2 w-full">
            </div>
            <button type="submit" class="btn-primary w-full bg-blue-500 text-white font-bold py-2 rounded hover:bg-blue-600 transition duration-200">Создать заказ</button>
        </form>
    </div>
</template>

<script>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';

export default {
    setup(props, {emit}) {
        const store = useStore();
        const orderData = ref({
            order_id: null,
            table_id: null,
        });
        const selectedItems = ref([]);
        const menu = ref({});

        const createOrder = async function()
        {
            try
            {
                await axios.post('/waiter/order', {...orderData.value})
                emit('orderCreated')
            }
            catch(e)
            {
                console.error(e.message)
            }
        }

        return {
            orderData,
            selectedItems,
            menu,
            createOrder
        };
    }
};
</script>
