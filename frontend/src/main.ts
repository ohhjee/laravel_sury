import { createApp } from 'vue'

import './assets/css/tailwind.css'
import store from './store'
import router from './router'
import App from './App.vue'

createApp(App).use(store).use(router).mount('#app')
