<template>
  <div class="trasparenza">
    <section class="hero-small">
      <div class="container">
        <h1>Trasparenza</h1>
        <p>Documenti ufficiali e bilanci del Comitato</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="content">
          <p class="intro reveal">
            In questa sezione sono disponibili i documenti ufficiali del Comitato CRI di Selvazzano Dentro,
            in conformità agli obblighi di trasparenza previsti per gli enti del Terzo Settore.
          </p>

          <div class="docs-list stagger-children">
            <a
              v-for="doc in documenti"
              :key="doc.file"
              :href="`/documenti/${doc.file}`"
              target="_blank"
              rel="noopener"
              class="doc-row reveal"
            >
              <div class="doc-icon" aria-hidden="true">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <polyline points="14,2 14,8 20,8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <div class="doc-info">
                <span class="doc-name">{{ doc.nome }}</span>
                <span v-if="doc.anno" class="doc-anno">{{ doc.anno }}</span>
              </div>
              <span class="doc-badge">PDF</span>
            </a>
          </div>

          <p v-if="!documenti.length" class="empty-state">
            Nessun documento disponibile al momento.
          </p>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { useReveal } from '../composables/useReveal'

const { init: initReveal, destroy: destroyReveal } = useReveal()

export default {
  data() {
    return {
      documenti: [
        // Aggiungi qui i documenti man mano che li carichi in public/documenti/
        // { nome: 'Bilancio consuntivo 2024', anno: '2024', file: 'bilancio-2024.pdf' },
        // { nome: 'Bilancio consuntivo 2023', anno: '2023', file: 'bilancio-2023.pdf' },
      ]
    }
  },
  mounted() {
    this.$nextTick(() => initReveal(this.$el))
  },
  beforeUnmount() {
    destroyReveal()
  }
}
</script>

<style scoped>
.content {
  max-width: 700px;
  margin: 0 auto;
}

.intro {
  font-size: 1.0625rem;
  line-height: 1.8;
  color: var(--cri-text-light);
  margin-bottom: 48px;
}

.docs-list {
  display: flex;
  flex-direction: column;
  gap: 1px;
  background: var(--cri-border);
  border-radius: var(--cri-radius-md);
  overflow: hidden;
}

.doc-row {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 20px 24px;
  background: white;
  color: var(--cri-text);
  text-decoration: none;
  transition: background var(--cri-transition);
}

.doc-row:hover {
  background: var(--cri-light-gray);
  color: var(--cri-text);
}

.doc-icon {
  color: var(--cri-red);
  flex-shrink: 0;
}

.doc-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.doc-name {
  font-weight: 600;
  font-size: 0.9375rem;
}

.doc-anno {
  font-size: 0.8125rem;
  color: var(--cri-text-light);
}

.doc-badge {
  font-size: 0.6875rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--cri-red);
  background: rgba(227, 30, 36, 0.08);
  padding: 4px 10px;
  border-radius: var(--cri-radius-full);
  flex-shrink: 0;
}

.empty-state {
  text-align: center;
  color: var(--cri-text-light);
  padding: 60px 0;
  font-size: 1rem;
}

@media (max-width: 768px) {
  .doc-row {
    padding: 16px;
  }
}
</style>
