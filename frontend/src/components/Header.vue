<template>
  <header class="header">
    <div class="container">
      <nav class="nav" aria-label="Navigazione principale">
        <router-link to="/" class="logo" aria-label="CRI Selvazzano Dentro - Torna alla home">
          <img src="@/assets/images/logo-cri.png" alt="" class="logo-img" width="60" height="60">
        </router-link>
        
        <button
          class="menu-toggle"
          :class="{ active: menuOpen }"
          @click="menuOpen = !menuOpen"
          :aria-expanded="String(menuOpen)"
          aria-controls="main-menu"
          aria-label="Apri menu di navigazione"
        >
          <span></span>
          <span></span>
          <span></span>
        </button>

        <ul id="main-menu" class="menu" :class="{ open: menuOpen }" role="menubar">
          <li role="none"><router-link to="/" role="menuitem" @click="menuOpen = false">Home</router-link></li>
          <li role="none"><router-link to="/chi-siamo" role="menuitem" @click="menuOpen = false">Chi Siamo</router-link></li>
          <li role="none"><router-link to="/servizi" role="menuitem" @click="menuOpen = false">Servizi</router-link></li>
          <li role="none"><router-link to="/news" role="menuitem" @click="menuOpen = false">News</router-link></li>
          <li role="none"><router-link to="/diventa-volontario" role="menuitem" @click="menuOpen = false">Volontario</router-link></li>
          <li role="none"><router-link to="/contatti" role="menuitem" @click="menuOpen = false">Contatti</router-link></li>
          <li role="none"><router-link to="/dona" class="btn btn-primary" role="menuitem" @click="menuOpen = false">Dona</router-link></li>
        </ul>
      </nav>
    </div>
  </header>
</template>

<script>
export default {
  data() {
    return { menuOpen: false }
  },
  watch: {
    '$route'() {
      this.menuOpen = false
    }
  }
}
</script>

<style scoped>
.header {
  background: var(--cri-white);
  box-shadow: var(--cri-shadow-sm);
  position: sticky;
  top: 0;
  z-index: 1000;
}

.nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 0;
}

.logo {
  display: flex;
  align-items: center;
}

.logo-img {
  height: 60px;
  width: auto;
}

/* Hamburger animato */
.menu-toggle {
  display: none;
  flex-direction: column;
  gap: 5px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 8px;
  z-index: 1001;
}

.menu-toggle span {
  width: 24px;
  height: 3px;
  background: var(--cri-text);
  border-radius: 2px;
  transition: all 0.3s ease;
  transform-origin: center;
}

.menu-toggle.active span:nth-child(1) {
  transform: translateY(8px) rotate(45deg);
}

.menu-toggle.active span:nth-child(2) {
  opacity: 0;
  transform: scaleX(0);
}

.menu-toggle.active span:nth-child(3) {
  transform: translateY(-8px) rotate(-45deg);
}

.menu {
  display: flex;
  list-style: none;
  gap: 32px;
  align-items: center;
}

.menu a {
  font-weight: 500;
  padding: 8px 0;
  color: var(--cri-text);
  position: relative;
}

.menu a::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 2px;
  background: var(--cri-red);
  transition: width 0.3s ease;
}

.menu a:hover::after,
.menu a.router-link-exact-active::after {
  width: 100%;
}

.menu a.router-link-exact-active {
  color: var(--cri-red);
}

.menu .btn {
  padding: 8px 24px;
  position: relative;
}

.menu .btn::before {
  content: '';
  position: absolute;
  inset: -3px;
  border-radius: var(--cri-radius-sm);
  background: var(--cri-red);
  opacity: 0;
  z-index: -1;
  animation: dona-pulse 3s ease-in-out infinite;
}

@keyframes dona-pulse {
  0%, 100% { opacity: 0; transform: scale(1); }
  50% { opacity: 0.15; transform: scale(1.08); }
}

.menu .btn::after {
  display: none;
}

.menu .btn.router-link-exact-active {
  color: var(--cri-white);
}

@media (max-width: 768px) {
  .menu-toggle {
    display: flex;
  }

  .menu {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    flex-direction: column;
    padding: 20px;
    box-shadow: var(--cri-shadow-sm);
    transform: translateY(-10px);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    pointer-events: none;
  }

  .menu.open {
    transform: translateY(0);
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
  }
}
</style>
