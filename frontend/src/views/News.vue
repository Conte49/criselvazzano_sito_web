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
        <div class="filters">
          <input type="text" v-model="searchQuery" placeholder="Cerca nelle news..." class="search-input">
        </div>
        
        <div class="news-grid">
          <router-link :to="`/news/${post.id}`" class="news-card" v-for="post in filteredPosts" :key="post.id">
            <div class="news-image" v-if="post.featured_media">
              <img :src="getMediaUrl(post.featured_media)" :alt="post.title.rendered" loading="lazy" @error="$event.target.src='/src/assets/images/logo-cri.png'; $event.target.style.objectFit='contain'; $event.target.style.padding='40px'; $event.target.style.background='#f5f5f5'">
            </div>
            <div class="news-image placeholder" v-else>
              <img src="/src/assets/images/logo-cri.png" alt="CRI Selvazzano" style="object-fit: contain; padding: 40px; background: #f5f5f5;">
            </div>
            <div class="news-content">
              <div class="news-meta">
                <span class="news-date">{{ formatDate(post.date) }}</span>
              </div>
              <h3 v-html="post.title.rendered"></h3>
              <div class="news-excerpt" v-html="truncateExcerpt(post.excerpt.rendered)"></div>
            </div>
          </router-link>
        </div>
        
        <div v-if="filteredPosts.length === 0" class="no-results">
          <p>Nessuna news trovata</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import Icon from '../components/Icon.vue'

export default {
  components: { Icon },
  data() {
    return {
      posts: [],
      media: [],
      searchQuery: ''
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
    filteredPosts() {
      if (!this.searchQuery) return this.posts
      const query = this.searchQuery.toLowerCase()
      return this.posts.filter(post => 
        post.title.rendered.toLowerCase().includes(query) ||
        post.excerpt.rendered.toLowerCase().includes(query)
      )
    }
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString('it-IT', { 
        day: 'numeric',
        month: 'short',
        year: 'numeric'
      })
    },
    getMediaUrl(mediaId) {
      const mediaItem = this.media.find(m => m.id === mediaId)
      return mediaItem?.source_url || ''
    },
    truncateExcerpt(html) {
      const text = html.replace(/<[^>]*>/g, '')
      return text.length > 150 ? text.substring(0, 150) + '...' : text
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

.filters {
  margin-bottom: 32px;
}

.search-input {
  width: 100%;
  max-width: 500px;
  padding: 12px 20px;
  border: 2px solid #e0e0e0;
  border-radius: 24px;
  font-size: 1rem;
  transition: border-color 0.3s;
}

.search-input:focus {
  outline: none;
  border-color: var(--cri-red);
}

.news-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 24px;
}

.news-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 12px rgba(0,0,0,0.08);
  transition: all 0.3s;
  display: block;
  color: inherit;
}

.news-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 32px rgba(227,30,36,0.15);
}

.news-image {
  height: 220px;
  overflow: hidden;
  background: var(--cri-light-gray);
  position: relative;
}

.news-image.placeholder {
  display: flex;
  align-items: center;
  justify-content: center;
}

.news-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s;
}

.news-card:hover .news-image img {
  transform: scale(1.05);
}

.news-content {
  padding: 20px;
}

.news-meta {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 12px;
}

.news-date {
  color: var(--cri-red);
  font-size: 0.875rem;
  font-weight: 600;
  text-transform: uppercase;
}

.news-content h3 {
  font-size: 1.25rem;
  margin-bottom: 12px;
  color: var(--cri-text);
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.news-excerpt {
  color: var(--cri-text-light);
  line-height: 1.6;
  font-size: 0.95rem;
}

.no-results {
  text-align: center;
  padding: 60px 20px;
  color: var(--cri-text-light);
  font-size: 1.125rem;
}

@media (max-width: 768px) {
  .hero-small {
    padding: 60px 0;
  }

  .hero-small h1 {
    font-size: 2rem;
  }

  .news-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .news-image {
    height: 180px;
  }

  .search-input {
    font-size: 0.9rem;
  }
}
</style>
