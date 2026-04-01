<template>
  <div class="news-detail">
    <LoadingSpinner v-if="loading" :messages="['Caricamento articolo...', 'Recupero i contenuti...', 'Quasi pronto...']" />

    <div v-else-if="error" class="error-container section">
      <div class="container">
        <div class="status-message error">
          Non è stato possibile caricare l'articolo. 
          <router-link to="/news">Torna alle news</router-link>
        </div>
      </div>
    </div>

    <article v-else-if="post">
      <div class="article-header hero-animate" v-if="hasValidImage" :style="{ backgroundImage: `url(${getMediaUrl(post.featured_media)})` }">
        <div class="overlay"></div>
        <div class="container">
          <div class="header-content">
            <span class="date">{{ formatDate(post.date) }}</span>
            <h1 v-html="post.title.rendered"></h1>
          </div>
        </div>
      </div>
      
      <div class="article-header simple hero-animate" v-else>
        <div class="container">
          <div class="header-content">
            <span class="date">{{ formatDate(post.date) }}</span>
            <h1 v-html="post.title.rendered"></h1>
          </div>
        </div>
      </div>

      <section class="section">
        <div class="container">
          <div class="content reveal" v-html="cleanContent"></div>
          <div class="back-button reveal">
            <router-link to="/news" class="btn btn-outline">&larr; Torna alle news</router-link>
          </div>
        </div>
      </section>
    </article>
  </div>
</template>

<script>
import LoadingSpinner from '../components/LoadingSpinner.vue'
import { useReveal } from '../composables/useReveal'

const { init: initReveal, destroy: destroyReveal } = useReveal()

export default {
  components: { LoadingSpinner },
  data() {
    return {
      posts: [],
      media: [],
      loading: true,
      error: false
    }
  },
  async mounted() {
    try {
      const [postsRes, mediaRes] = await Promise.all([
        fetch('/api/get-data?type=posts'),
        fetch('/api/get-data?type=media')
      ])
      this.posts = await postsRes.json()
      this.media = await mediaRes.json()
    } catch (err) {
      console.error('Errore caricamento dati:', err)
      this.error = true
    } finally {
      this.loading = false
      this.$nextTick(() => initReveal(this.$el))
    }
  },
  beforeUnmount() {
    destroyReveal()
  },
  computed: {
    post() {
      return this.posts.find(p => p.id === parseInt(this.$route.params.id))
    },
    cleanContent() {
      if (!this.post) return ''
      let content = this.post.content.rendered
      const featuredUrl = this.getMediaUrl(this.post.featured_media)
      if (featuredUrl) {
        const escaped = featuredUrl.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
        const imgRegex = new RegExp(`<img[^>]*src=["']${escaped}["'][^>]*>`, 'gi')
        content = content.replace(imgRegex, '')
      }
      return content
    },
    hasValidImage() {
      return this.post?.featured_media && this.getMediaUrl(this.post.featured_media)
    }
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString('it-IT', {
        year: 'numeric', month: 'long', day: 'numeric'
      })
    },
    getMediaUrl(mediaId) {
      const item = this.media.find(m => m.id === mediaId)
      return item?.source_url || ''
    }
  }
}
</script>

<style scoped>
.article-header {
  position: relative;
  min-height: 400px;
  display: flex;
  align-items: center;
  background-size: cover;
  background-position: center;
  color: white;
}

.article-header.simple {
  background: linear-gradient(135deg, var(--cri-red) 0%, var(--cri-dark-red) 100%);
  min-height: 300px;
}

.overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.7));
}

.header-content {
  position: relative;
  z-index: 1;
  max-width: 800px;
}

.date {
  display: inline-block;
  background: var(--cri-red);
  color: white;
  padding: 6px 16px;
  border-radius: var(--cri-radius-full);
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 16px;
  text-transform: uppercase;
}

.article-header h1 {
  font-size: 2.5rem;
  line-height: 1.2;
  margin: 0;
}

.content {
  max-width: 800px;
  margin: 0 auto;
  font-size: 1.0625rem;
  line-height: 1.8;
}

.content :deep(p) {
  margin-bottom: 20px;
}

.content :deep(h2) {
  margin: 40px 0 20px;
  color: var(--cri-red);
  font-size: 1.75rem;
}

.content :deep(h3) {
  margin: 32px 0 16px;
  color: var(--cri-text);
  font-size: 1.375rem;
}

.content :deep(img) {
  max-width: 100%;
  height: auto;
  border-radius: var(--cri-radius-lg);
  margin: 32px 0;
  box-shadow: var(--cri-shadow-md);
}

.content :deep(ul),
.content :deep(ol) {
  margin: 20px 0;
  padding-left: 24px;
}

.content :deep(li) {
  margin-bottom: 8px;
}

.content :deep(blockquote) {
  border-left: 4px solid var(--cri-red);
  padding-left: 20px;
  margin: 24px 0;
  font-style: italic;
  color: var(--cri-text-light);
}

.back-button {
  max-width: 800px;
  margin: 40px auto 0;
}

/* Article header entrance */
.hero-animate {
  animation: hero-fade-in var(--cri-duration-slow) var(--cri-ease-out) both;
}

.hero-animate .header-content .date {
  animation: hero-slide-up 600ms var(--cri-ease-out-expo) 100ms both;
}

.hero-animate .header-content h1 {
  animation: hero-slide-up 600ms var(--cri-ease-out-expo) 200ms both;
}

@keyframes hero-fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes hero-slide-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.error-container {
  min-height: 40vh;
  display: flex;
  align-items: center;
}

@media (max-width: 768px) {
  .article-header {
    min-height: 300px;
  }

  .article-header.simple {
    min-height: 250px;
  }

  .article-header h1 {
    font-size: 1.75rem;
  }

  .content {
    font-size: 1rem;
  }

  .content :deep(h2) {
    font-size: 1.5rem;
  }
}
</style>
