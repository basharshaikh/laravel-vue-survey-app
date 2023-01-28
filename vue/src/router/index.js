/*
|-------------------------------------------------------
| To declare route.. here is 3 step
| 1. Make a view component
| 2. import it in here
| 3. make routes object
*/



import { createRouter, createWebHistory } from "vue-router";

// import components for rounters
import Dashboard from '../views/Dashboard.vue'
import Surveys from '../views/Surveys.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import SurveyPublicView from '../views/SurveyPublicView.vue'
import DefaultLayout from '../components/DefaultLayout.vue'
import AuthLayout from '../components/AuthLayout.vue'
import SurveyView from '../views/SurveyView.vue'
import SurveyAns from '../views/SurveyAns.vue'
import AnsVurveys from '../views/AnsVurveys.vue'

// check store data for user token
import store from '../store'
const routes = [
    {
        path: '/',
        redirect: '/dashboard',
        component: DefaultLayout,
        meta: {rquiresAuth: true},
        children: [
            {
                path: '/dashboard',
                name: 'Dashboard',
                component: Dashboard
            },
            {
                path: '/surveys',
                name: 'Surveys',
                component: Surveys
            },
            {
                path: '/surveys/create',
                name: 'SurveyCreate',
                component: SurveyView
            },
            {
                path: '/surveys/:id',
                name: 'SurveyView',
                component: SurveyView
            },
            {
                path: '/answers',
                name: 'SurveyAns',
                component: SurveyAns
            },
            {
                path: '/survey-answers/:id',
                name: 'AnsVurveys',
                component: AnsVurveys
            },
        ]
    },
    {
        path: '/view/survey/:slug',
        name: 'SurveyPublicView',
        component: SurveyPublicView,
    },
    {
        path: '/auth',
        redirect: '/login',
        name: 'Auth',
        component: AuthLayout,
        meta: {isGuest: true},
        children: [
            {
                path: '/login',
                name: 'Login',
                component: Login
            },
            {
                path: '/register',
                name: 'Register',
                component: Register
            }
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    if (to.meta.rquiresAuth && !store.state.user.token) {
        // redirecting to login route. alias 'Login'
        next({name: 'Login'});
    } else if (store.state.user.token && to.meta.isGuest) {
        next({name: 'Dashboard'});
    } else {
        next();
    }
})

export default router