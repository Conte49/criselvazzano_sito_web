<template>
  <div id="app">
    <template v-if="isAdmin">
      <router-view />
    </template>
    <template v-else>
      <a href="#main-content" class="skip-link">Vai al contenuto principale</a>
      <Header />
      <main id="main-content" class="main-content" role="main">
        <router-view v-slot="{ Component }">
          <transition name="page" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </main>
      <Footer />
      <ScrollToTop />
    </template>
  </div>
</template>

<script>
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
import ScrollToTop from './components/ScrollToTop.vue'

export default {
  components: { Header, Footer, ScrollToTop },
  computed: {
    isAdmin() {
      return this.$route.path.startsWith('/admin')
    }
  }
}
</script>
