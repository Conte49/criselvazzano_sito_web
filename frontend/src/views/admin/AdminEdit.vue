<template>
  <div class="admin-edit">
    <div class="admin-header">
      <h1>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="#E31E24"><path d="M19 3H5c-1.1 0-1.99.9-1.99 2L3 19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 11h-4v4h-4v-4H6v-4h4V6h4v4h4v4z"/></svg>
        {{ editMode ? 'Modifica' : 'Nuova' }} News
      </h1>
      <router-link to="/admin/dashboard" class="btn-back">Torna alla dashboard</router-link>
    </div>

    <div class="admin-container">
      <form @submit.prevent="save" class="edit-form">
        <div class="form-group">
          <label for="title">Titolo *</label>
          <div class="input-with-ai">
            <input type="text" id="title" v-model="form.title" required>
            <button type="button" @click="improveText('title')" class="btn-ai" :disabled="improving">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </button>
          </div>
        </div>

        <div class="form-group">
          <label for="excerpt">Estratto</label>
          <textarea id="excerpt" v-model="form.excerpt" rows="3"></textarea>
        </div>

        <div class="form-group">
          <label for="content">Contenuto *</label>
          <textarea id="content" v-model="form.content" rows="12" required></textarea>
          <button type="button" @click="improveText('content')" class="btn-ai btn-ai-block" :disabled="improving">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            {{ improving ? 'Miglioramento...' : 'Migliora testo' }}
          </button>
        </div>

        <div class="form-group">
          <label>Immagine in evidenza</label>
          <div class="dropzone" @click="$refs.fileInput.click()" @dragover.prevent="dragover = true" @dragleave="dragover = false" @drop.prevent="onDrop" :class="{ dragover }">
            <input ref="fileInput" type="file" accept="image/*" @change="onFileSelect" hidden>
            <div v-if="!imagePreview" class="dropzone-placeholder">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="#757575"><path d="M19 7v2.99s-1.99.01-2 0V7h-3s.01-1.99 0-2h3V2h2v3h3v2h-3zm-3 4V8h-3V5H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8h-3zM5 19l3-4 2 3 3-4 4 5H5z"/></svg>
              <p>Trascina un'immagine o clicca per selezionare</p>
            </div>
            <div v-else class="dropzone-preview">
              <img :src="imagePreview" alt="Preview">
              <button type="button" @click.stop="removeImage" class="remove-image">x</button>
            </div>
          </div>
          <div v-if="currentImage && !imagePreview" class="current-image">
            <p>Immagine attuale:</p>
            <img :src="currentImage" alt="Current">
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-save" :disabled="saving">{{ saving ? 'Salvataggio...' : 'Salva' }}</button>
          <router-link to="/admin/dashboard" class="btn-cancel">Annulla</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      editMode: false,
      form: { title: '', excerpt: '', content: '' },
      postId: null,
      featuredMediaId: 0,
      currentImage: '',
      selectedFile: null,
      imagePreview: null,
      dragover: false,
      saving: false,
      improving: false
    }
  },
  async mounted() {
    const id = this.$route.query.id
    if (id) {
      this.editMode = true
      this.postId = parseInt(id)
      await this.loadPost()
    }
  },
  methods: {
    async loadPost() {
      try {
        const [postsRes, mediaRes] = await Promise.all([
          fetch('/api/get-data?type=posts'),
          fetch('/api/get-data?type=media')
        ])
        const posts = await postsRes.json()
        const media = await mediaRes.json()
        const post = posts.find(p => p.id === this.postId)
        if (!post) return this.$router.push('/admin/dashboard')

        this.form.title = post.title.rendered.replace(/<[^>]*>/g, '')
        this.form.content = post.content.rendered
        this.form.excerpt = (post.excerpt.rendered || '').replace(/<[^>]*>/g, '')
        this.featuredMediaId = post.featured_media

        if (post.featured_media) {
          const m = media.find(m => m.id === post.featured_media)
          if (m) this.currentImage = m.source_url
        }
      } catch {
        this.$router.push('/admin/dashboard')
      }
    },
    onFileSelect(e) {
      const file = e.target.files[0]
      if (file) this.setFile(file)
    },
    onDrop(e) {
      this.dragover = false
      const file = e.dataTransfer.files[0]
      if (file) this.setFile(file)
    },
    setFile(file) {
      this.selectedFile = file
      const reader = new FileReader()
      reader.onload = e => { this.imagePreview = e.target.result }
      reader.readAsDataURL(file)
    },
    removeImage() {
      this.selectedFile = null
      this.imagePreview = null
    },
    async uploadImage() {
      if (!this.selectedFile) return this.featuredMediaId
      const fd = new FormData()
      fd.append('file', this.selectedFile)
      fd.append('slug', this.form.title)
      const res = await fetch('/api/upload', { method: 'POST', body: fd })
      const data = await res.json()
      return data.success ? data.mediaId : this.featuredMediaId
    },
    async save() {
      this.saving = true
      try {
        const mediaId = await this.uploadImage()
        const action = this.editMode ? 'edit' : 'create'
        const res = await fetch(`/api/posts?action=${action}`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            title: this.form.title,
            content: this.form.content,
            excerpt: this.form.excerpt,
            id: this.postId,
            featuredMediaId: mediaId
          })
        })
        const data = await res.json()
        if (data.success) {
          this.$router.push('/admin/dashboard?saved=1')
        } else {
          alert(data.message || 'Errore nel salvataggio')
        }
      } catch {
        alert('Errore di connessione')
      } finally {
        this.saving = false
      }
    },
    async improveText(type) {
      const text = type === 'title' ? this.form.title : this.form.content
      if (!text.trim()) return alert('Scrivi prima del testo da migliorare')
      this.improving = true
      try {
        const res = await fetch('/api/improve-text', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ text, type })
        })
        const data = await res.json()
        if (data.success) {
          const cleaned = data.text.replace(/^"|"$/g, '').replace(/^'|'$/g, '').trim()
          if (type === 'title') this.form.title = cleaned
          else this.form.content = cleaned
        } else {
          alert(data.message || 'Errore')
        }
      } catch {
        alert('Errore di connessione')
      } finally {
        this.improving = false
      }
    }
  }
}
</script>
