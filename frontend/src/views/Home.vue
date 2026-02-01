<template>
  <div class="home">
    <section class="hero">
      <div class="container">
        <div class="hero-content">
          <h1>Croce Rossa Italiana<br>Selvazzano Dentro</h1>
          <p>Dal 1989 al servizio della comunità con passione, dedizione e umanità</p>
          <div class="hero-buttons">
            <router-link to="/diventa-volontario" class="btn btn-primary">Diventa Volontario</router-link>
            <router-link to="/chi-siamo" class="btn btn-outline">Scopri di più</router-link>
          </div>
        </div>
      </div>
    </section>

    <section class="section services">
      <div class="container">
        <h2 class="section-title">I Nostri Obiettivi</h2>
        <p class="section-subtitle">Lavoriamo ogni giorno per la comunità attraverso 6 obiettivi strategici</p>
        
        <div class="services-grid">
          <div class="service-card" v-for="os in obiettivi" :key="os.id">
            <div class="service-icon">
              <Icon :name="os.icon" :size="48" color="#E31E24" />
            </div>
            <h3>{{ os.title }}</h3>
            <p>{{ os.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <section class="section cta-section">
      <div class="container">
        <div class="cta-content">
          <h2>Sostieni la Croce Rossa</h2>
          <p>Il tuo contributo fa la differenza. Dona il tuo 5x1000 o fai una donazione.</p>
          <router-link to="/dona" class="btn btn-primary">Dona Ora</router-link>
        </div>
      </div>
    </section>

    <section class="section news-section">
      <div class="container">
        <h2 class="section-title">Ultime News</h2>
        <div class="news-grid">
          <router-link :to="`/news/${post.id}`" class="news-card" v-for="post in latestNews" :key="post.id">
            <div class="news-image" v-if="post.featured_media">
              <img :src="getMediaUrl(post.featured_media)" :alt="post.title.rendered" loading="lazy">
            </div>
            <div class="news-image placeholder" v-else>
              <Icon name="medical" :size="64" color="#E31E24" />
            </div>
            <div class="news-content">
              <div class="news-date">{{ formatDate(post.date) }}</div>
              <h3 v-html="post.title.rendered"></h3>
              <div class="news-excerpt" v-html="truncateExcerpt(post.excerpt.rendered)"></div>
            </div>
          </router-link>
        </div>
        <div style="text-align: center; margin-top: 40px;">
          <router-link to="/news" class="btn btn-outline">Vedi tutte le news</router-link>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import posts from '../data/posts.json'
import media from '../data/media.json'
import Icon from '../components/Icon.vue'

export default {
  components: { Icon },
  data() {
    return {
      obiettivi: [
        { id: 1, icon: 'medical', title: 'Salute', description: 'Assistenza sanitaria e primo soccorso' },
        { id: 2, icon: 'handshake', title: 'Sociale', description: 'Supporto alle persone vulnerabili' },
        { id: 3, icon: 'emergency', title: 'Emergenza', description: 'Interventi in situazioni di crisi' },
        { id: 4, icon: 'heart', title: 'Principi', description: 'Diffusione dei valori della CRI' },
        { id: 5, icon: 'users', title: 'Giovani', description: 'Educazione e formazione' },
        { id: 6, icon: 'growth', title: 'Sviluppo', description: 'Crescita e innovazione' }
      ],
      posts,
      media
    }
  },
  computed: {
    latestNews() {
      return this.posts.slice(0, 3)
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
      return text.length > 120 ? text.substring(0, 120) + '...' : text
    }
  }
}
</script>

<style scoped>
.hero {
  background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('/src/assets/images/hero-home.jpg');
  background-size: cover;
  background-position: center;
  color: white;
  padding: 120px 0;
  text-align: center;
}

.hero h1 {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 24px;
  line-height: 1.2;
}

.hero p {
  font-size: 1.25rem;
  margin-bottom: 32px;
  opacity: 0.95;
}

.hero-buttons {
  display: flex;
  gap: 16px;
  justify-content: center;
  flex-wrap: wrap;
}

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 32px;
}

.service-card {
  background: white;
  padding: 32px;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: 0.3s;
  text-align: center;
}

.service-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.service-icon {
  margin-bottom: 16px;
  display: flex;
  justify-content: center;
}

.service-card h3 {
  font-size: 1.5rem;
  margin-bottom: 12px;
  color: var(--cri-red);
}

.cta-section {
  background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/src/assets/images/sostieni.jpg');
  background-size: cover;
  background-position: center;
  text-align: center;
}

.cta-content h2 {
  font-size: 2.5rem;
  margin-bottom: 16px;
  color: white;
}

.cta-content p {
  font-size: 1.25rem;
  margin-bottom: 32px;
  color: white;
}

.news-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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

.news-date {
  color: var(--cri-red);
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 12px;
  display: block;
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

@media (max-width: 768px) {
  .hero h1 {
    font-size: 2rem;
  }
  
  .hero p {
    font-size: 1rem;
  }
}
</style>
