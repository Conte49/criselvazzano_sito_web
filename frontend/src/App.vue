<template>
  <div id="app">
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
  </div>
</template>

<script>
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
import ScrollToTop from './components/ScrollToTop.vue'

export default {
  components: { Header, Footer, ScrollToTop }
}
</script>
