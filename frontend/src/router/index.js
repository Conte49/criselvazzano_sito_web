import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'

const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/chi-siamo', name: 'chi-siamo', component: () => import('../views/ChiSiamo.vue') },
  { path: '/news', name: 'news', component: () => import('../views/News.vue') },
  { path: '/news/:id', name: 'news-detail', component: () => import('../views/NewsDetail.vue') },
  { path: '/contatti', name: 'contatti', component: () => import('../views/Contatti.vue') },
  { path: '/diventa-volontario', name: 'diventa-volontario', component: () => import('../views/DiventaVolontario.vue') },
  { path: '/dona', name: 'dona', component: () => import('../views/Dona.vue') },
  { path: '/servizi', name: 'servizi', component: () => import('../views/Servizi.vue') },
  { path: '/:pathMatch(.*)*', name: 'not-found', component: () => import('../views/NotFound.vue') }
]

export default createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 }
  }
})
