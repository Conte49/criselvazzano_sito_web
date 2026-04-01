<template>
  <div class="admin-dashboard">
    <div class="admin-header">
      <h1>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="#E31E24"><path d="M19 3H5c-1.1 0-1.99.9-1.99 2L3 19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 11h-4v4h-4v-4H6v-4h4V6h4v4h4v4z"/></svg>
        Dashboard
      </h1>
      <div class="admin-header-actions">
        <router-link to="/admin/edit" class="btn-new">+ Nuova News</router-link>
        <button @click="logout" class="btn-logout">Esci</button>
      </div>
    </div>

    <div class="admin-container">
      <div v-if="message" class="admin-message success">{{ message }}</div>

      <div class="admin-stats">
        <span>{{ posts.length }} news pubblicate</span>
      </div>

      <div v-if="loading" class="admin-loading">Caricamento...</div>

      <div v-else class="posts-list">
        <div v-for="post in posts" :key="post.id" class="post-item">
          <div class="post-info">
            <div class="post-title">{{ plainTitle(post.title.rendered) }}</div>
            <div class="post-date">{{ formatDate(post.date) }}</div>
          </div>
          <div class="post-actions">
            <router-link :to="`/admin/edit?id=${post.id}`" class="btn-action btn-edit">Modifica</router-link>
            <button @click="deletePost(post.id, plainTitle(post.title.rendered))" class="btn-action btn-delete">Elimina</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return { posts: [], loading: true, message: '' }
  },
  async mounted() {
    if (this.$route.query.saved) this.message = 'News salvata con successo'
    if (this.$route.query.deleted) this.message = 'News eliminata con successo'
    await this.loadPosts()
  },
  methods: {
    async loadPosts() {
      try {
        const res = await fetch('/api/posts?action=list')
        if (res.status === 401) return this.$router.push('/admin')
        this.posts = await res.json()
      } catch { /* ignore */ } finally {
        this.loading = false
      }
    },
    plainTitle(html) {
      const el = document.createElement('div')
      el.innerHTML = html
      const text = el.textContent
      return text
    },
    formatDate(d) {
      return new Date(d).toLocaleDateString('it-IT', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })
    },
    async deletePost(id, title) {
      if (!confirm(`Eliminare "${title}"?`)) return
      const res = await fetch(`/api/posts?action=delete&id=${id}`, { method: 'DELETE' })
      if (res.ok) {
        this.posts = this.posts.filter(p => p.id !== id)
        this.message = 'News eliminata'
      }
    },
    async logout() {
      await fetch('/api/logout')
      this.$router.push('/admin')
    }
  }
}
</script>
