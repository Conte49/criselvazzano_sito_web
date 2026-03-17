<template>
  <div class="home">
    <section class="hero">
      <div class="container">
        <div class="hero-content hero-animate">
          <h1>Croce Rossa Italiana<br>Selvazzano Dentro</h1>
          <p>Dal 1989 al servizio della comunità con passione, dedizione e umanità</p>
          <div class="hero-buttons">
            <router-link to="/diventa-volontario" class="btn btn-primary">Diventa Volontario</router-link>
            <router-link to="/chi-siamo" class="btn btn-outline btn-white">Scopri di più</router-link>
          </div>
        </div>
      </div>
    </section>

    <section class="section services">
      <div class="container">
        <h2 class="section-title reveal">I Nostri Obiettivi</h2>
        <p class="section-subtitle reveal">Lavoriamo ogni giorno per la comunità attraverso 7 obiettivi strategici</p>
        
        <div class="services-grid stagger-children">
          <div class="service-card reveal" v-for="os in obiettivi" :key="os.id">
            <div class="service-icon">
              <Icon :name="os.icon" :size="48" />
            </div>
            <h3>{{ os.title }}</h3>
            <p>{{ os.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <section class="section cta-section">
      <div class="container">
        <div class="cta-content reveal">
          <h2>Sostieni la Croce Rossa</h2>
          <p>Il tuo contributo fa la differenza. Dona il tuo 5x1000 o fai una donazione.</p>
          <router-link to="/dona" class="btn btn-primary">Dona Ora</router-link>
        </div>
      </div>
    </section>

    <section class="section news-section">
      <div class="container">
        <h2 class="section-title reveal">Ultime News</h2>

        <LoadingSpinner v-if="loading" :messages="['Caricamento news...', 'Recupero gli ultimi aggiornamenti...', 'Quasi pronto...']" />

        <div v-else-if="error" class="status-message error">
          Non è stato possibile caricare le news. Riprova più tardi.
        </div>

        <template v-else>
          <div class="news-grid">
            <NewsCard
              v-for="post in latestNews"
              :key="post.id"
              :post="post"
              :media-list="media"
            />
          </div>
          <div class="cta-wrapper">
            <router-link to="/news" class="btn btn-outline">Vedi tutte le news</router-link>
          </div>
        </template>
      </div>
    </section>
  </div>
</template>

<script>
import Icon from '../components/Icon.vue'
import NewsCard from '../components/NewsCard.vue'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import { useReveal } from '../composables/useReveal'

const { init: initReveal, destroy: destroyReveal } = useReveal()

export default {
  components: { Icon, NewsCard, LoadingSpinner },
  data() {
    return {
      obiettivi: [
        { id: 1, icon: 'users', title: 'Organizzazione', description: 'Rafforzare la struttura e la governance' },
        { id: 2, icon: 'heart', title: 'Volontari', description: 'Promuovere e valorizzare il volontariato' },
        { id: 3, icon: 'growth', title: 'Principi e Valori', description: 'Diffondere i principi fondamentali' },
        { id: 4, icon: 'medical', title: 'Salute', description: 'Tutelare e promuovere la salute' },
        { id: 5, icon: 'handshake', title: 'Inclusione Sociale', description: 'Supportare le persone vulnerabili' },
        { id: 6, icon: 'emergency', title: 'Emergenze', description: 'Preparazione e risposta alle emergenze' },
        { id: 7, icon: 'globe', title: 'Cooperazione', description: 'Solidarietà oltre i confini' }
      ],
      posts: [],
      media: [],
      loading: true,
      error: false
    }
  },
  async mounted() {
    this.$nextTick(() => initReveal(this.$el))
    try {
      const [postsRes, mediaRes] = await Promise.all([
        fetch('/admin/get-data.php?type=posts'),
        fetch('/admin/get-data.php?type=media')
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
    latestNews() {
      return this.posts.slice(0, 3)
    }
  }
}
</script>

<style scoped>
.hero {
  background: linear-gradient(var(--cri-overlay-light), var(--cri-overlay-light)), url('@/assets/images/hero-home.jpg');
  background-size: cover;
  background-position: center;
  color: white;
  padding: 160px 0;
  text-align: center;
}

.hero h1 {
  font-size: 3.75rem;
  font-weight: 700;
  margin-bottom: 24px;
  line-height: 1.1;
  letter-spacing: -0.03em;
}

.hero p {
  font-size: 1.3125rem;
  margin-bottom: 40px;
  opacity: 0.95;
  max-width: 560px;
  margin-left: auto;
  margin-right: auto;
}

/* Hero entrance animation */
.hero-animate {
  animation: hero-fade-in var(--cri-duration-slow) var(--cri-ease-out) both;
}

.hero-animate h1 {
  animation: hero-slide-up 600ms var(--cri-ease-out-expo) 100ms both;
}

.hero-animate p {
  animation: hero-slide-up 600ms var(--cri-ease-out-expo) 200ms both;
}

.hero-animate .hero-buttons {
  animation: hero-slide-up 600ms var(--cri-ease-out-expo) 300ms both;
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

.hero-buttons {
  display: flex;
  gap: 16px;
  justify-content: center;
  flex-wrap: wrap;
}

.btn-white {
  border-color: white;
  color: white;
}

.btn-white:hover {
  background: white;
  color: var(--cri-red);
}

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 24px;
}

.service-card {
  background: white;
  padding: 40px 28px;
  border-radius: var(--cri-radius-md);
  box-shadow: var(--cri-shadow-sm);
  transition: box-shadow var(--cri-transition);
  text-align: center;
}

.service-card:hover {
  box-shadow: var(--cri-shadow-md);
}

.service-icon {
  width: 72px;
  height: 72px;
  margin: 0 auto 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(227, 30, 36, 0.08);
  border-radius: 50%;
  color: var(--cri-red);
}

.service-card h3 {
  font-size: 1.25rem;
  margin-bottom: 8px;
  color: var(--cri-red);
}

.service-card p {
  color: var(--cri-text-light);
  font-size: 0.95rem;
}

.cta-section {
  background: linear-gradient(var(--cri-overlay-dark), var(--cri-overlay-dark)), url('@/assets/images/sostieni.jpg');
  background-size: cover;
  background-position: center;
  text-align: center;
  padding: 120px 0;
}

.cta-content h2 {
  font-size: 3rem;
  margin-bottom: 20px;
  color: white;
  letter-spacing: -0.02em;
}

.cta-content p {
  font-size: 1.25rem;
  margin-bottom: 40px;
  color: white;
  max-width: 520px;
  margin-left: auto;
  margin-right: auto;
  opacity: 0.9;
}

.news-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 24px;
}

@media (max-width: 768px) {
  .hero {
    padding: 100px 0;
  }

  .hero h1 {
    font-size: 2.5rem;
  }
  
  .hero p {
    font-size: 1.0625rem;
  }

  .cta-section {
    padding: 80px 0;
  }

  .cta-content h2 {
    font-size: 2rem;
  }

  .services-grid {
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 16px;
  }

  .service-card {
    padding: 24px 16px;
  }

  .service-icon {
    width: 56px;
    height: 56px;
    margin-bottom: 12px;
  }

  .service-card h3 {
    font-size: 1rem;
  }

  .service-card p {
    font-size: 0.85rem;
  }
}
</style>
