import {createRouter, createWebHistory} from 'vue-router'
// @ts-ignore
import Login from "../views/LoginView.vue"

const routes = [
    {
        path: '/auth/login', name: 'Login',component:Login
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
    
})

export default router