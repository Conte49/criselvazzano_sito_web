import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import ChiSiamo from '../views/ChiSiamo.vue'
import News from '../views/News.vue'
import NewsDetail from '../views/NewsDetail.vue'
import Contatti from '../views/Contatti.vue'
import DiventaVolontario from '../views/DiventaVolontario.vue'
import Dona from '../views/Dona.vue'
import Servizi from '../views/Servizi.vue'

const routes = [
  { path: '/', component: Home },
  { path: '/chi-siamo', component: ChiSiamo },
  { path: '/news', component: News },
  { path: '/news/:id', component: NewsDetail },
  { path: '/contatti', component: Contatti },
  { path: '/diventa-volontario', component: DiventaVolontario },
  { path: '/dona', component: Dona },
  { path: '/servizi', component: Servizi }
]

export default createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 }
  }
})
