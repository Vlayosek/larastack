import { defineStore } from 'pinia';
import api from '../plugins/axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('auth_token') || null,
    }),
    getters: {
        isAuthenticated: (state) => !!state.token,
    },
    actions: {
        async login(email, password) {
            const response = await api.post('/login', { email, password });
            this.token = response.data.token;
            this.user = response.data.user;
            localStorage.setItem('auth_token', this.token);
        },
        async fetchUser() {
            if (!this.token) return;
            try {
                const response = await api.get('/me');
                this.user = response.data.user;
            } catch (error) {
                this.logout();
            }
        },
        async logout() {
            try {
                await api.post('/logout');
            } catch (error) {}
            this.token = null;
            this.user = null;
            localStorage.removeItem('auth_token');
        }
    }
});
