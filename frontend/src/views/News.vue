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
        <div class="filters reveal">
          <label for="news-search" class="sr-only">Cerca nelle news</label>
          <div class="search-wrapper">
            <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
              <path d="M16 16l4.5 4.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <input
              id="news-search"
              type="search"
              v-model="searchQuery"
              @input="onSearchInput"
              placeholder="Cerca nelle news..."
              class="search-input"
            >
          </div>
        </div>

        <LoadingSpinner v-if="loading" :messages="['Caricamento news...', 'Recupero tutti gli articoli...', 'Un momento ancora...']" />

        <div v-else-if="error" class="status-message error">
          Non è stato possibile caricare le news. Riprova più tardi.
        </div>

        <template v-else>
          <div class="news-grid stagger-children">
            <NewsCard
              v-for="post in filteredPosts"
              :key="post.id"
              :post="post"
              :media-list="media"
              :excerpt-length="150"
              class="reveal"
            />
          </div>
          
          <div v-if="filteredPosts.length === 0" class="no-results">
            <p>Nessuna news trovata per "{{ searchQuery }}"</p>
          </div>
        </template>
      </div>
    </section>
  </div>
</template>

<script>
import NewsCard from '../components/NewsCard.vue'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import { useReveal } from '../composables/useReveal'

const { init: initReveal, destroy: destroyReveal } = useReveal()

export default {
  components: { NewsCard, LoadingSpinner },
  data() {
    return {
      posts: [],
      media: [],
      searchQuery: '',
      debouncedQuery: '',
      loading: true,
      error: false,
      debounceTimer: null
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
    } catch (err) {
      console.error('Errore caricamento dati:', err)
      this.error = true
    } finally {
      this.loading = false
      this.$nextTick(() => initReveal(this.$el))
    }
  },
  beforeUnmount() {
    clearTimeout(this.debounceTimer)
    destroyReveal()
  },
  computed: {
    filteredPosts() {
      if (!this.debouncedQuery) return this.posts
      const q = this.debouncedQuery.toLowerCase()
      return this.posts.filter(post =>
        post.title.rendered.toLowerCase().includes(q) ||
        post.excerpt.rendered.toLowerCase().includes(q)
      )
    }
  },
  methods: {
    onSearchInput() {
      clearTimeout(this.debounceTimer)
      this.debounceTimer = setTimeout(() => {
        this.debouncedQuery = this.searchQuery
      }, 300)
    }
  }
}
</script>

<style scoped>
.filters {
  margin-bottom: 32px;
}

.search-wrapper {
  position: relative;
  max-width: 500px;
}

.search-icon {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--cri-text-light);
  transition: color var(--cri-transition);
  pointer-events: none;
}

.search-wrapper:focus-within .search-icon {
  color: var(--cri-red);
}

.search-input {
  width: 100%;
  padding: 12px 20px 12px 44px;
  border: 2px solid var(--cri-border);
  border-radius: var(--cri-radius-full);
  font-size: 1rem;
  font-family: inherit;
  transition: border-color var(--cri-transition), box-shadow var(--cri-transition);
}

.search-input:focus {
  outline: none;
  border-color: var(--cri-red);
  box-shadow: 0 0 0 3px rgba(227, 30, 36, 0.1);
}

.news-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 24px;
}

.no-results {
  text-align: center;
  padding: 60px 20px;
  color: var(--cri-text-light);
  font-size: 1.125rem;
}

@media (max-width: 768px) {
  .news-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }
}
</style>
