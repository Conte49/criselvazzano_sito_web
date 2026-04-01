<template>
  <div class="admin-login">
    <div class="login-box">
      <div class="login-header">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="#E31E24"><path d="M19 3H5c-1.1 0-1.99.9-1.99 2L3 19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 11h-4v4h-4v-4H6v-4h4V6h4v4h4v4z"/></svg>
        <h1>CRI Selvazzano</h1>
        <p>Pannello Amministrazione</p>
      </div>

      <div v-if="error" class="login-error">{{ error }}</div>

      <form @submit.prevent="login">
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" v-model="password" required autofocus>
        </div>
        <button type="submit" class="btn-login" :disabled="loading">
          {{ loading ? 'Accesso...' : 'Accedi' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return { password: '', error: '', loading: false }
  },
  methods: {
    async login() {
      this.error = ''
      this.loading = true
      try {
        const res = await fetch('/api/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ password: this.password })
        })
        const data = await res.json()
        if (data.success) {
          this.$router.push('/admin/dashboard')
        } else {
          this.error = data.message || 'Password errata'
        }
      } catch {
        this.error = 'Errore di connessione'
      } finally {
        this.loading = false
      }
    }
  }
}
</script>
