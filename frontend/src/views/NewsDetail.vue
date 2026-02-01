<template>
  <div class="news-detail" v-if="post">
    <section class="hero-small">
      <div class="container">
        <h1 v-html="post.title.rendered"></h1>
        <p class="date">{{ formatDate(post.date) }}</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <article class="content">
          <div class="featured-image" v-if="post.featured_media">
            <img :src="getMediaUrl(post.featured_media)" :alt="post.title.rendered">
          </div>
          <div v-html="post.content.rendered"></div>
        </article>
        
        <div style="margin-top: 40px;">
          <router-link to="/news" class="btn btn-outline">← Torna alle news</router-link>
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
  computed: {
    post() {
      return this.posts.find(p => p.id === parseInt(this.$route.params.id))
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

.date {
  opacity: 0.9;
}

.content {
  max-width: 800px;
  margin: 0 auto;
}

.featured-image {
  margin-bottom: 32px;
  border-radius: 8px;
  overflow: hidden;
}

.featured-image img {
  width: 100%;
  height: auto;
}

.content :deep(p) {
  margin-bottom: 16px;
  line-height: 1.8;
}

.content :deep(h2) {
  margin: 32px 0 16px;
  color: var(--cri-red);
}

.content :deep(img) {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  margin: 16px 0;
}
</style>
