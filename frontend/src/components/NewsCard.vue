<template>
  <router-link :to="`/news/${post.id}`" class="news-card">
    <div class="news-image" :class="{ placeholder: !imageUrl }">
      <img
        v-if="imageUrl"
        :src="imageUrl"
        :alt="plainTitle"
        loading="lazy"
        width="400"
        height="220"
      >
      <img v-else src="@/assets/images/logo-cri.png" alt="CRI Selvazzano" width="120" height="120">
    </div>
    <div class="news-content">
      <span class="news-date">{{ formattedDate }}</span>
      <h3 v-html="post.title.rendered"></h3>
      <p class="news-excerpt">{{ plainExcerpt }}</p>
      <span class="read-more">Leggi di più <svg class="arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
    </div>
  </router-link>
</template>

<script>
export default {
  props: {
    post: { type: Object, required: true },
    mediaList: { type: Array, default: () => [] },
    excerptLength: { type: Number, default: 120 }
  },
  computed: {
    imageUrl() {
      if (!this.post.featured_media) return ''
      const item = this.mediaList.find(m => m.id === this.post.featured_media)
      return item?.source_url || ''
    },
    plainTitle() {
      const tmp = document.createElement('div')
      tmp.innerHTML = this.post.title.rendered
      const text = tmp.textContent || ''
      tmp.remove()
      return text
    },
    formattedDate() {
      return new Date(this.post.date).toLocaleDateString('it-IT', {
        day: 'numeric', month: 'short', year: 'numeric'
      })
    },
    plainExcerpt() {
      const text = this.post.excerpt.rendered.replace(/<[^>]*>/g, '')
      return text.length > this.excerptLength
        ? text.substring(0, this.excerptLength) + '...'
        : text
    }
  }
}
</script>

<style scoped>
.news-card {
  background: white;
  border-radius: var(--cri-radius-lg);
  overflow: hidden;
  box-shadow: var(--cri-shadow-sm);
  transition: all var(--cri-transition);
  display: flex;
  flex-direction: column;
  color: inherit;
}

.news-card:hover {
  transform: translateY(-6px);
  box-shadow: var(--cri-shadow-lg);
}

.news-image {
  aspect-ratio: 400 / 220;
  overflow: hidden;
  background: var(--cri-light-gray);
  position: relative;
}

.news-image.placeholder {
  display: flex;
  align-items: center;
  justify-content: center;
}

.news-image.placeholder img {
  object-fit: contain;
  padding: 40px;
  width: auto;
  height: auto;
}

.news-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform var(--cri-transition);
}

.news-card:hover .news-image img {
  transform: scale(1.05);
}

.news-content {
  padding: 20px;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.news-date {
  color: var(--cri-red);
  font-size: 0.875rem;
  font-weight: 600;
  text-transform: uppercase;
  margin-bottom: 8px;
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
  flex: 1;
}

.read-more {
  color: var(--cri-red);
  font-weight: 600;
  font-size: 0.875rem;
  margin-top: 12px;
  transition: color var(--cri-transition);
  display: inline-flex;
  align-items: center;
  gap: 4px;
}

.read-more .arrow {
  transition: transform 300ms var(--cri-ease-out);
}

.news-card:hover .read-more {
  color: var(--cri-dark-red);
}

.news-card:hover .read-more .arrow {
  transform: translateX(4px);
}

@media (max-width: 768px) {
  .news-image {
    aspect-ratio: 16 / 9;
  }
}
</style>
