<template>
    <div class="max-w-md mx-auto mt-10">
        <h2 class="text-2xl font-bold mb-4">Login</h2>
        <form @submit.prevent="login" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" v-model="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                <input type="password" v-model="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-6">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role:</label>
                <select v-model="role" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="" disabled>Select your role</option>
                    <option value="waiter">Официант</option>
                    <option value="cook">Повар</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Login</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
import router from "@/router/index.js";
import {ref} from "vue";

export default {
    setup() {
        const email = ref('');
        const password = ref('');
        const role = ref('');

        const login = async () => {
            try {
                // Отправка запроса на вход
                const response = await axios.post('/login', {
                    email: email.value,
                    password: password.value,
                    role: role.value
                });

                router.push('/')
            } catch (error) {
                console.error('Ошибка при входе:', error);
            }
        };

        return {
            email,
            password,
            role,
            login,
        };
    },
};
</script>
