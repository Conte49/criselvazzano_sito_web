<template>
  <div class="news-detail" v-if="post">
    <article class="article">
      <div class="article-header" v-if="hasValidImage" :style="{ backgroundImage: `url(${getMediaUrl(post.featured_media)})` }">
        <div class="overlay"></div>
        <div class="container">
          <div class="header-content">
            <span class="date">{{ formatDate(post.date) }}</span>
            <h1 v-html="post.title.rendered"></h1>
          </div>
        </div>
      </div>
      
      <div class="article-header simple" v-else>
        <div class="container">
          <div class="header-content">
            <span class="date">{{ formatDate(post.date) }}</span>
            <h1 v-html="post.title.rendered"></h1>
          </div>
        </div>
      </div>

      <section class="section">
        <div class="container">
          <div class="content" v-html="cleanContent"></div>
          
          <div class="back-button">
            <router-link to="/news" class="btn btn-outline">← Torna alle news</router-link>
          </div>
        </div>
      </section>
    </article>
  </div>
</template>

<script>
export default {
  data() {
    return {
      posts: [],
      media: []
    }
  },
  async mounted() {
    try {
      const [postsRes, mediaRes] = await Promise.all([
        fetch('/admin/get-data.php?type=posts'),
        fetch('/admin/get-data.php?type=media')
      ])
      this.posts = await postsRes.json()
      this.media = await mediaRes.json()
    } catch (error) {
      console.error('Errore caricamento dati:', error)
    }
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
        const imgRegex = new RegExp(`<img[^>]*src=["']${featuredUrl.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}["'][^>]*>`, 'gi')
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
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      })
    },
    getMediaUrl(mediaId) {
      const mediaItem = this.media.find(m => m.id === mediaId)
      return mediaItem?.source_url || ''
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
  border-radius: 20px;
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
  border-radius: 12px;
  margin: 32px 0;
  box-shadow: 0 4px 16px rgba(0,0,0,0.1);
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
    padding: 0 16px;
    font-size: 1rem;
  }

  .content :deep(h2) {
    font-size: 1.5rem;
  }
}
</style>
