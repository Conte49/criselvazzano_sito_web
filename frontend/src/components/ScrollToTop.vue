<template>
  <button
    class="scroll-top-btn"
    :class="{ visible: isVisible }"
    @click="scrollToTop"
    aria-label="Torna in cima alla pagina"
  >
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
      <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z" fill="currentColor"/>
    </svg>
  </button>
</template>

<script>
export default {
  data() {
    return { isVisible: false }
  },
  mounted() {
    window.addEventListener('scroll', this.handleScroll, { passive: true })
  },
  beforeUnmount() {
    window.removeEventListener('scroll', this.handleScroll)
  },
  methods: {
    handleScroll() {
      this.isVisible = window.scrollY > 400
    },
    scrollToTop() {
      window.scrollTo({ top: 0, behavior: 'smooth' })
    }
  }
}
</script>
