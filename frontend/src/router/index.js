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
  { path: '/trasparenza', name: 'trasparenza', component: () => import('../views/Trasparenza.vue') },
  { path: '/admin', name: 'admin-login', component: () => import('../views/admin/AdminLogin.vue') },
  { path: '/admin/dashboard', name: 'admin-dashboard', component: () => import('../views/admin/AdminDashboard.vue') },
  { path: '/admin/edit', name: 'admin-edit', component: () => import('../views/admin/AdminEdit.vue') },
  { path: '/:pathMatch(.*)*', name: 'not-found', component: () => import('../views/NotFound.vue') }
]

export default createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 }
  }
})
