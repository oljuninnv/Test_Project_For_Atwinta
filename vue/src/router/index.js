import {createRouter, createWebHistory} from 'vue-router'
import Login from "../views/LoginView.vue"
import Register from "../views/RegisterView.vue"
import UserView from "../views/UserView.vue"
import UsersList from "../views/UsersList.vue"
import GroupList from "../views/GroupList.vue"
import SendResetLink from "../views/SendResetLink.vue"
import UserProfile from "../views/UserProfile.vue"
import UserProfileEdit from "../views/UserProfileEdit.vue"

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
        path: '/groups', name: 'GroupList',component:GroupList        
    },
    {
        path: '/auth/restore', name: 'SendResetLink',component:SendResetLink        
    },
    {
        path: '/profile', name: 'UserProfile',component:UserProfile
    },
    {
        path: '/profile/edit', name: 'UserProfileEdit',component:UserProfileEdit
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
    
})

export default router