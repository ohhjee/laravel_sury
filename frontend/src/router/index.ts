import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import Login from "../views/auth/login.vue"
import Defaultlayout from '../components/Defaultlayout.vue'
import Dashboard from '../views/Dashboard.vue'
import Survey from '../views/Survey.vue'
import Register from '../views/auth/Register.vue'
import SurveyPublic from '../views/SurveyPublic.vue'
import Authlayout from '../components/Authlayout.vue'
import SurveyView from '../views/SurveyView.vue'
import store from '../store'

const routes: Array<RouteRecordRaw> = [
    {
        path: '/',
        redirect: '/dashboard',
        component: Defaultlayout,
        meta: { requireAuth: true },
        children: [
            { path: '/dashboard', name: 'Dashboard', component: Dashboard },
            { path: '/surveys', name: 'Surveys', component: Survey },
            { path: '/surveys/create', name: 'SurveyCreate', component: SurveyView },
            { path: '/surveys/:id', name: 'SurveyView', component: SurveyView }
        ]
    },
    {
        path: '/view/survey/:slug',
        name: "SurveyPublic",
        component: SurveyPublic
    },
    {
        path: '/auth',
        redirect: '/login',
        name: 'Auth',
        meta: { isGuest: true },
        component: Authlayout,
        children: [
            {
                path: '/login',
                name: 'Login',
                component: Login,
            }, {
                path: '/register',
                name: 'register',
                component: Register,
            }
        ]
    },
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
})
router.beforeEach((to, from, next) => {
    if (to.meta.requireAuth && !store.state.user.token) {
        next({ name: 'Login' })
    } else if (store.state.user.token && (to.meta.isGuest)) {
        next({ name: 'Dashboard' })
    }
    else {
        next()
    }
})

export default router
