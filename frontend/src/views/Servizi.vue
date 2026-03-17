<template>
  <div class="servizi">
    <section class="hero-small">
      <div class="container">
        <h1>I Nostri Servizi</h1>
        <p>Al servizio della comunità</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <nav class="service-nav" aria-label="Indice servizi">
          <a v-for="s in servizi" :key="s.id" :href="`#${s.id}`" class="service-nav-link">{{ s.title }}</a>
        </nav>

        <div class="services-list">
          <div :id="s.id" class="service-detail reveal" v-for="s in servizi" :key="s.id">
            <div class="service-icon" aria-hidden="true">
              <Icon :name="s.icon" :size="64" />
            </div>
            <div class="service-content">
              <h2>{{ s.title }}</h2>
              <p>{{ s.desc }}</p>
              <ul>
                <li v-for="item in s.items" :key="item">{{ item }}</li>
              </ul>
              <p class="service-note">{{ s.note }}</p>
              <a :href="s.whatsapp" target="_blank" rel="noopener" class="btn btn-primary">{{ s.cta }}</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import Icon from '../components/Icon.vue'
import { useReveal } from '../composables/useReveal'

const { init: initReveal, destroy: destroyReveal } = useReveal()

export default {
  components: { Icon },
  data() {
    return {
      servizi: [
        {
          id: 'trasporto',
          icon: 'ambulance',
          title: 'Trasporto Sanitario',
          desc: 'Servizio di trasporto per visite mediche, terapie e dimissioni ospedaliere.',
          items: ['Trasporti programmati', 'Dimissioni ospedaliere', 'Assistenza durante il trasporto'],
          note: 'Contattaci per informazioni e tariffe',
          cta: 'Richiedi su WhatsApp',
          whatsapp: 'https://wa.me/393801720578?text=Buongiorno,%20vorrei%20richiedere%20informazioni%20sul%20servizio%20di%20Trasporto%20Sanitario'
        },
        {
          id: 'manifestazioni',
          icon: 'event',
          title: 'Assistenza Manifestazioni',
          desc: 'Primo soccorso durante eventi sportivi e manifestazioni pubbliche.',
          items: ['Eventi sportivi', 'Manifestazioni pubbliche', 'Concerti e feste'],
          note: 'Contattaci per un preventivo personalizzato',
          cta: 'Richiedi su WhatsApp',
          whatsapp: 'https://wa.me/393801720578?text=Buongiorno,%20vorrei%20richiedere%20informazioni%20sul%20servizio%20di%20Assistenza%20Manifestazioni'
        },
        {
          id: 'corsi',
          icon: 'medical',
          title: 'Corsi Primo Soccorso',
          desc: 'Corsi di primo soccorso per cittadini, aziende e scuole.',
          items: ['Corso BLSD', 'Primo soccorso pediatrico', 'Corsi per aziende'],
          note: 'Richiedi informazioni su costi e calendario',
          cta: 'Info su WhatsApp',
          whatsapp: 'https://wa.me/393801720578?text=Buongiorno,%20vorrei%20informazioni%20sui%20Corsi%20di%20Primo%20Soccorso'
        },
        {
          id: 'sportello',
          icon: 'handshake',
          title: 'Sportello Sociale',
          desc: 'Punto di ascolto e accoglienza dove l\'aiuto diventa realtà.',
          items: ['Ascolto attivo dei bisogni e delle vulnerabilità', 'Orientamento e facilitazione di accesso ai servizi', 'Reinserimento sociale e coinvolgimento delle persone in difficoltà'],
          note: 'Servizio gratuito su appuntamento',
          cta: 'Prenota: 366 628 5870',
          whatsapp: 'https://wa.me/393666285870?text=Buongiorno,%20vorrei%20informazioni%20sullo%20Sportello%20Sociale'
        }
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
.service-nav {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  margin-bottom: 48px;
  padding-bottom: 24px;
  border-bottom: 1px solid var(--cri-border);
}

.service-nav-link {
  padding: 8px 20px;
  background: var(--cri-light-gray);
  border-radius: var(--cri-radius-full);
  font-weight: 500;
  font-size: 0.95rem;
  color: var(--cri-text);
  transition: all var(--cri-transition);
}

.service-nav-link:hover {
  background: var(--cri-red);
  color: white;
}

.services-list {
  max-width: 900px;
  margin: 0 auto;
}

.service-detail {
  display: flex;
  gap: 32px;
  margin-bottom: 60px;
  padding-bottom: 60px;
  border-bottom: 1px solid var(--cri-border);
  scroll-margin-top: 100px;
}

.service-detail:last-child {
  border-bottom: none;
  margin-bottom: 0;
  padding-bottom: 0;
}

.service-icon {
  flex-shrink: 0;
  width: 80px;
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(227, 30, 36, 0.08);
  border-radius: 50%;
  color: var(--cri-red);
}

.service-content h2 {
  font-size: 1.875rem;
  margin-bottom: 16px;
  color: var(--cri-red);
  letter-spacing: -0.01em;
}

.service-content > p {
  line-height: 1.7;
}

.service-content ul {
  margin: 16px 0 24px;
  padding-left: 20px;
}

.service-content li {
  margin-bottom: 8px;
  line-height: 1.6;
}

.service-note {
  font-size: 0.9rem;
  color: var(--cri-text-light);
  font-style: italic;
  margin-top: 16px;
  margin-bottom: 16px;
}

@media (max-width: 768px) {
  .service-nav {
    gap: 8px;
  }

  .service-nav-link {
    font-size: 0.85rem;
    padding: 6px 14px;
  }

  .service-detail {
    flex-direction: column;
    gap: 16px;
    margin-bottom: 40px;
    padding-bottom: 40px;
  }
}
</style>
