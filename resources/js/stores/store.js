import { createStore } from 'vuex';
import axios from 'axios';

const store = createStore({
    state: {
        auth: {
            user: null,
            role: null
        }
    },
    mutations: {
        SET_AUTH(state, payload) {
            state.auth.user = payload.user;
            state.auth.role = payload.role;
        },
        CLEAR_AUTH(state) {
            state.auth.user = null;
            state.auth.role = null;
        }
    },
    actions: {
        async fetchUser({ commit }) {
            try {
                const { data } = await axios.get('/user');
                commit('SET_AUTH', {
                    user: data.user,
                    role: data.role
                });
            } catch (error) {
                commit('CLEAR_AUTH');
            }
        },
        async logout({ commit }) {
            try {
                await axios.post('/logout');
            } finally {
                commit('CLEAR_AUTH');
            }
        }
    },
    getters: {
        isAuthenticated: state => !!state.auth.user,
        role: state => state.auth.role,
        user: state => state.auth.user
    }
});

export default store;
