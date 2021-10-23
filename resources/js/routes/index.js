import Vue from 'vue'
import Router from 'vue-router'
import store from '../store/store'

Vue.use(Router)

const router = new Router({
    mode: 'history',
    base: '/',
    routes: [
        {
            path: '',
            component: require('../layout/main/Main.vue').default,
            children: [
                {
                    path: '/',
                    name: 'home',
                    component: require('../pages/Homepage.vue').default, 
                },
                {
                    path: '/salud',
                    name: 'salud',
                    component: require('../pages/Salud.vue').default, 
                },
                {
                    path: '/asignacion',
                    name: 'asignacion',
                    component: require('../pages/Register.vue').default,
                },
                {
                    path: '/contact',
                    name: 'contact',
                    component: require('../pages/Contact.vue').default,
                }
            ]
        },

        {
            path: '',
            component: require('../layout/full-page/FullPage.vue').default, 
            children: [
                {
                    path: '/login',
                    name: 'login',
                    component: require('../pages/Login.vue').default, 
                }
            ]
        }
]})

router.beforeEach((to, from, next) => {
    console.log(store.state.isAuthenticated)
    if (to.name !== 'login' && !store.state.isAuthenticated){
      router.push({ path: '/login' })
    }
    else if(to.name == 'login') {
      next()
    }
    else{
      next()
    } 
  })

export default router
