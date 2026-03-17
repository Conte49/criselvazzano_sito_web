<template>
  <div class="chi-siamo">
    <section class="hero-small">
      <div class="container">
        <h1>Chi Siamo</h1>
        <p>La storia della Croce Rossa di Selvazzano Dentro</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="content">
          <div class="intro reveal">
            <h2>Dal 1989 al servizio della comunità</h2>
            <p>Il Comitato di Selvazzano Dentro della Croce Rossa Italiana opera dal 1989 nel territorio di Selvazzano Dentro e comuni limitrofi, portando avanti i valori e i principi fondamentali del Movimento Internazionale di Croce Rossa e Mezzaluna Rossa.</p>
          </div>

          <h3 class="reveal">I 7 Principi Fondamentali</h3>
          <div class="values-grid stagger-children">
            <div class="value-card reveal" v-for="v in valori" :key="v.name">
              <div class="value-name">{{ v.name }}</div>
              <p>{{ v.desc }}</p>
            </div>
          </div>

          <h3 class="reveal">I nostri obiettivi strategici</h3>
          <div class="objectives-grid stagger-children">
            <div class="objective reveal" v-for="(o, i) in obiettivi" :key="i">
              <span class="obj-number">{{ i + 1 }}</span>
              <div>
                <strong>{{ o.title }}</strong>
                <p>{{ o.desc }}</p>
              </div>
            </div>
          </div>
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
      valori: [
        { name: 'Umanità', desc: 'Prevenire e alleviare la sofferenza umana' },
        { name: 'Imparzialità', desc: 'Non fare discriminazioni di nazionalità, razza, religione' },
        { name: 'Neutralità', desc: 'Non prendere parte alle ostilità' },
        { name: 'Indipendenza', desc: 'Autonomia e indipendenza' },
        { name: 'Volontarietà', desc: 'Movimento di soccorso volontario' },
        { name: 'Unità', desc: 'Un solo Comitato per ogni Paese' },
        { name: 'Universalità', desc: 'Movimento mondiale' }
      ],
      obiettivi: [
        { title: 'Organizzazione', desc: 'Rafforzare la struttura e la governance' },
        { title: 'Volontari', desc: 'Promuovere e valorizzare il volontariato' },
        { title: 'Principi e Valori', desc: 'Diffondere i principi fondamentali' },
        { title: 'Salute', desc: 'Tutelare e promuovere la salute e il benessere' },
        { title: 'Inclusione Sociale', desc: 'Supportare le persone vulnerabili' },
        { title: 'Emergenze', desc: 'Preparazione e risposta alle emergenze' },
        { title: 'Cooperazione Internazionale', desc: 'Solidarietà oltre i confini' }
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
  max-width: 900px;
  margin: 0 auto;
}

.intro {
  margin-bottom: 48px;
}

.intro h2 {
  font-size: 2.25rem;
  margin-bottom: 24px;
  color: var(--cri-red);
  letter-spacing: -0.02em;
}

.intro p {
  font-size: 1.0625rem;
  line-height: 1.8;
}

.content h3 {
  font-size: 1.5rem;
  margin: 48px 0 24px;
  color: var(--cri-text);
}

.values-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 16px;
  margin-bottom: 16px;
}

.value-card {
  background: var(--cri-light-gray);
  padding: 20px;
  border-radius: var(--cri-radius-md);
  border-left: 4px solid var(--cri-red);
  transition: box-shadow var(--cri-transition);
}

.value-card:hover {
  box-shadow: var(--cri-shadow-sm);
}

.value-name {
  font-weight: 700;
  color: var(--cri-red);
  margin-bottom: 8px;
  font-size: 1.125rem;
}

.value-card p {
  color: var(--cri-text-light);
  font-size: 0.95rem;
  line-height: 1.5;
}

.objectives-grid {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.objective {
  display: flex;
  gap: 16px;
  align-items: flex-start;
  padding: 16px 20px;
  background: white;
  border-radius: var(--cri-radius-md);
  box-shadow: var(--cri-shadow-sm);
}

.obj-number {
  width: 36px;
  height: 36px;
  min-width: 36px;
  background: var(--cri-red);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 0.9rem;
}

.objective strong {
  display: block;
  margin-bottom: 4px;
}

.objective p {
  color: var(--cri-text-light);
  font-size: 0.95rem;
}

@media (max-width: 768px) {
  .values-grid {
    grid-template-columns: 1fr;
  }
}
</style>
