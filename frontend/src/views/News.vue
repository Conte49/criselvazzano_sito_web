<template>
  <div class="news-page">
    <section class="hero-small">
      <div class="container">
        <h1>News</h1>
        <p>Tutte le novità e gli aggiornamenti dal nostro Comitato</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="news-grid">
          <div class="news-card" v-for="post in posts" :key="post.id">
            <div class="news-image" v-if="post.featured_media">
              <img :src="getMediaUrl(post.featured_media)" :alt="post.title.rendered">
            </div>
            <div class="news-content">
              <div class="news-date">{{ formatDate(post.date) }}</div>
              <h3 v-html="post.title.rendered"></h3>
              <div class="news-excerpt" v-html="post.excerpt.rendered"></div>
              <router-link :to="`/news/${post.id}`" class="btn btn-outline">Leggi di più</router-link>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import posts from '../data/posts.json'
import media from '../data/media.json'

export default {
  data() {
    return {
      posts,
      media
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
.hero-small {
  background: linear-gradient(135deg, var(--cri-red) 0%, var(--cri-dark-red) 100%);
  color: white;
  padding: 80px 0;
  text-align: center;
}

.hero-small h1 {
  font-size: 2.5rem;
  margin-bottom: 16px;
}

.news-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 32px;
}

.news-card {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: 0.3s;
}

.news-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.news-image {
  height: 200px;
  overflow: hidden;
  background: var(--cri-light-gray);
}

.news-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.news-content {
  padding: 24px;
}

.news-date {
  color: var(--cri-text-light);
  font-size: 0.875rem;
  margin-bottom: 8px;
}

.news-content h3 {
  font-size: 1.25rem;
  margin-bottom: 12px;
  color: var(--cri-text);
}

.news-excerpt {
  color: var(--cri-text-light);
  margin-bottom: 16px;
  line-height: 1.6;
}
</style>
