import {createRouter, createWebHistory} from 'vue-router'
import Login from "../views/Auth/LoginView.vue"
import Register from "../views/Auth/RegisterView.vue"
import UserView from "../views/Users/UserView.vue"
import UsersList from "../views/Users/UsersList.vue"
import DepartmentList from "../views/Department/DepartmentList.vue"
import SendResetLink from "../views/Auth/SendResetLink.vue"
import UserProfile from "../views/UserProfile/UserProfile.vue"
import UserProfileEdit from "../views/UserProfile/UserProfileEdit.vue"
import Admin from "../views/Admin/AdminPage.vue"
import ResetPassword from '../views/Auth/ResetPassword.vue'
import NotFoundPage from '../views/NotFoundPage.vue'

const routes = [
    {
        path: '/auth/login', name: 'Login',component:Login        
    },
    {
        path: '/auth/register', name: 'Register',component:Register        
    },
    {
        path: '/users/user', name: 'UserView',component:UserView       
    },
    {
        path: '/users', name: 'UsersList',component:UsersList        
    },
    {
        path: '/departments', name: 'DepartmentList',component:DepartmentList        
    },
    {
        path: '/auth/restore', name: 'SendResetLink',component:SendResetLink        
    },
    {
        path: '/auth/restore/confirm', name: 'ResetPassword',component:ResetPassword        
    },
    {
        path: '/profile', name: 'UserProfile',component:UserProfile
    },
    {
        path: '/profile/edit/:id', name: 'UserProfileEdit', component: UserProfileEdit
    },
    {
        path: '/admin', name: 'Admin',component:Admin
    },
    { path: '/:catchAll(.*)', component: NotFoundPage }, // Обработчик для 404
]

const router = createRouter({
    history: createWebHistory(),
    routes
    
})

router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('token'); // ваша логика проверки

    if (to.path !== '/auth/login' && !isAuthenticated) {
        next('/auth/login'); // перенаправление на страницу авторизации
    } else {
        next(); // продолжить навигацию
    }
});

export default router