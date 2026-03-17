<template>
  <div class="not-found">
    <div class="container">
      <div class="not-found-content">
        <div class="illustration" aria-hidden="true">
          <svg width="120" height="120" viewBox="0 0 120 120" fill="none">
            <circle cx="60" cy="60" r="56" stroke="var(--cri-red)" stroke-width="2" stroke-dasharray="8 4" class="circle-rotate" />
            <path d="M40 50c0-2.2 1.8-4 4-4s4 1.8 4 4M72 50c0-2.2 1.8-4 4-4s4 1.8 4 4" stroke="var(--cri-text)" stroke-width="3" stroke-linecap="round" />
            <path d="M44 76c4-4 12-6 16-6s12 2 16 6" stroke="var(--cri-text)" stroke-width="3" stroke-linecap="round" fill="none" />
            <circle cx="60" cy="60" r="40" stroke="var(--cri-border)" stroke-width="1.5" fill="none" />
          </svg>
        </div>
        <h1>404</h1>
        <p class="message">Questa pagina sembra essersi persa.<br>Capita anche ai migliori.</p>
        <router-link to="/" class="btn btn-primary">Torna alla Home</router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
.not-found {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 60vh;
  text-align: center;
}

.not-found-content {
  animation: fade-up 600ms var(--cri-ease-out-expo) both;
}

.illustration {
  margin-bottom: 24px;
}

.circle-rotate {
  animation: slow-spin 20s linear infinite;
  transform-origin: 60px 60px;
}

@keyframes slow-spin {
  to { transform: rotate(360deg); }
}

@keyframes fade-up {
  from {
    opacity: 0;
    transform: translateY(16px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.not-found h1 {
  font-size: 5rem;
  color: var(--cri-red);
  line-height: 1;
  margin-bottom: 12px;
  font-weight: 800;
  letter-spacing: -2px;
}

.message {
  font-size: 1.125rem;
  color: var(--cri-text-light);
  margin-bottom: 32px;
  line-height: 1.6;
}
</style>
