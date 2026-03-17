<template>
  <div class="loading-container" role="status" aria-live="polite">
    <div class="spinner-wrapper">
      <svg class="spinner-svg" width="48" height="48" viewBox="0 0 48 48" aria-hidden="true">
        <circle class="spinner-track" cx="24" cy="24" r="20" fill="none" stroke-width="3" />
        <circle class="spinner-arc" cx="24" cy="24" r="20" fill="none" stroke-width="3" stroke-linecap="round" />
      </svg>
    </div>
    <p class="loading-text">{{ currentMessage }}</p>
  </div>
</template>

<script>
export default {
  props: {
    text: { type: String, default: '' },
    messages: {
      type: Array,
      default: () => [
        'Caricamento in corso...',
        'Quasi pronto...',
        'Un momento ancora...'
      ]
    }
  },
  data() {
    return {
      messageIndex: 0,
      timer: null
    }
  },
  computed: {
    currentMessage() {
      if (this.text) return this.text
      return this.messages[this.messageIndex]
    }
  },
  mounted() {
    if (!this.text && this.messages.length > 1) {
      this.timer = setInterval(() => {
        this.messageIndex = (this.messageIndex + 1) % this.messages.length
      }, 2500)
    }
  },
  beforeUnmount() {
    clearInterval(this.timer)
  }
}
</script>

<style scoped>
.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 60px 20px;
  gap: 20px;
}

.spinner-wrapper {
  animation: fade-in 300ms ease both;
}

.spinner-svg {
  animation: spin 1s linear infinite;
}

.spinner-track {
  stroke: var(--cri-light-gray);
}

.spinner-arc {
  stroke: var(--cri-red);
  stroke-dasharray: 80 126;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

@keyframes fade-in {
  from { opacity: 0; transform: scale(0.8); }
  to { opacity: 1; transform: scale(1); }
}

.loading-text {
  color: var(--cri-text-light);
  font-size: 0.95rem;
  transition: opacity 200ms ease;
  min-height: 1.4em;
  text-align: center;
}
</style>
