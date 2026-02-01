<template>
  <header class="header">
    <div class="container">
      <nav class="nav">
        <router-link to="/" class="logo">
          <img src="/src/assets/images/logo-cri.png" alt="CRI Selvazzano Dentro" class="logo-img">
        </router-link>
        
        <button class="menu-toggle" @click="menuOpen = !menuOpen">
          <span></span>
          <span></span>
          <span></span>
        </button>

        <ul class="menu" :class="{ open: menuOpen }">
          <li><router-link to="/" @click="menuOpen = false">Home</router-link></li>
          <li><router-link to="/chi-siamo" @click="menuOpen = false">Chi Siamo</router-link></li>
          <li><router-link to="/servizi" @click="menuOpen = false">Servizi</router-link></li>
          <li><router-link to="/news" @click="menuOpen = false">News</router-link></li>
          <li><router-link to="/diventa-volontario" @click="menuOpen = false">Volontario</router-link></li>
          <li><router-link to="/contatti" @click="menuOpen = false">Contatti</router-link></li>
          <li><router-link to="/dona" class="btn btn-primary" @click="menuOpen = false">Dona</router-link></li>
        </ul>
      </nav>
    </div>
  </header>
</template>

<script>
export default {
  data() {
    return {
      menuOpen: false
    }
  }
}
</script>

<style scoped>
.header {
  background: var(--cri-white);
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
  color: var(--cri-text);
}

.logo-img {
  height: 60px;
  width: auto;
}

.menu-toggle {
  display: none;
  flex-direction: column;
  gap: 4px;
  background: none;
  border: none;
  cursor: pointer;
}

.menu-toggle span {
  width: 24px;
  height: 3px;
  background: var(--cri-text);
  transition: 0.3s;
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
}

.menu .btn {
  padding: 8px 24px;
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
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    display: none;
  }

  .menu.open {
    display: flex;
  }
}
</style>
