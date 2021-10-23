require('./bootstrap');

import Vue from 'vue'
import router from './routes'

import vSelect from 'vue-select'
Vue.component('v-select', vSelect)
import 'vue-select/dist/vue-select.css'

// VeeValidate
import * as VeeValidate from 'vee-validate'
Vue.use(VeeValidate)

import App from './App.vue'

import store from './store/store'

Vue.component('pagination', require('laravel-vue-pagination'))

import VueAuthHref from 'vue-auth-href'
const options = {
    token: () => store.state.token
  }
Vue.use(VueAuthHref, options)

new Vue({
    render: h => h(App),
    router,
    store,
}).$mount('#app')
