<template>
  <div class="volontario">
    <section class="hero-small">
      <div class="container">
        <h1>Diventa Volontario</h1>
        <p>Unisciti a noi e fai la differenza</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="content">
          <h2>Perché diventare volontario CRI?</h2>
          <p>Entra a far parte di un grande movimento internazionale che opera per alleviare la sofferenza umana.</p>

          <div class="benefits-grid stagger-children">
            <div class="benefit-card reveal" v-for="b in benefits" :key="b.icon">
              <div class="benefit-icon">
                <Icon :name="b.icon" :size="48" />
              </div>
              <h3>{{ b.title }}</h3>
              <p>{{ b.desc }}</p>
            </div>
          </div>

          <h2>Come diventare volontario</h2>
          <div class="steps stagger-children">
            <div class="step reveal" v-for="(s, i) in steps" :key="i">
              <div class="step-number">{{ i + 1 }}</div>
              <div>
                <h3>{{ s.title }}</h3>
                <p>{{ s.desc }}</p>
              </div>
            </div>
          </div>

          <div class="cta-box reveal">
            <h3>Pronto a iniziare?</h3>

            <div v-if="submitted" class="status-message success">
              <svg class="success-check" width="40" height="40" viewBox="0 0 40 40" aria-hidden="true">
                <circle cx="20" cy="20" r="18" fill="none" stroke="#4caf50" stroke-width="2" />
                <path class="check-path" d="M12 20l6 6 10-12" fill="none" stroke="#4caf50" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <p>Grazie per il tuo interesse. Ti contatteremo al più presto.</p>
            </div>

            <form v-else @submit.prevent="submitForm" novalidate>
              <div class="form-group">
                <label for="vol-nome" class="form-label">Nome e Cognome *</label>
                <input id="vol-nome" v-model="form.nome" type="text" class="form-input" :class="{ error: errors.nome, valid: !errors.nome && form.nome.trim() }">
                <span class="form-error">{{ errors.nome }}</span>
              </div>
              <div class="form-group">
                <label for="vol-email" class="form-label">Email *</label>
                <input id="vol-email" v-model="form.email" type="email" class="form-input" :class="{ error: errors.email, valid: !errors.email && form.email.trim() && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email) }">
                <span class="form-error">{{ errors.email }}</span>
              </div>
              <div class="form-group">
                <label for="vol-tel" class="form-label">Telefono *</label>
                <input id="vol-tel" v-model="form.telefono" type="tel" class="form-input" :class="{ error: errors.telefono, valid: !errors.telefono && form.telefono.trim() }">
                <span class="form-error">{{ errors.telefono }}</span>
              </div>
              <div class="form-group">
                <label for="vol-msg" class="form-label">Parlaci di te</label>
                <textarea id="vol-msg" v-model="form.messaggio" class="form-textarea" rows="4"></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Invia candidatura</button>
            </form>
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
      benefits: [
        { icon: 'school', title: 'Formazione Gratuita', desc: 'Corsi di primo soccorso e molto altro' },
        { icon: 'users', title: 'Nuove Amicizie', desc: 'Entra in una grande famiglia' },
        { icon: 'growth', title: 'Crescita Personale', desc: 'Sviluppa competenze e valori' },
        { icon: 'heart', title: 'Aiuta gli Altri', desc: 'Fai la differenza nella comunità' }
      ],
      steps: [
        { title: 'Contattaci', desc: 'Compila il form o vieni in sede' },
        { title: 'Colloquio', desc: 'Conosciamoci meglio' },
        { title: 'Formazione', desc: 'Corso base per volontari' },
        { title: 'Inizia', desc: 'Entra in servizio attivo' }
      ],
      form: { nome: '', email: '', telefono: '', messaggio: '' },
      errors: {},
      submitted: false
    }
  },
  mounted() {
    this.$nextTick(() => initReveal(this.$el))
  },
  beforeUnmount() {
    destroyReveal()
  },
  methods: {
    validate() {
      this.errors = {}
      if (!this.form.nome.trim()) this.errors.nome = 'Il nome è obbligatorio'
      if (!this.form.email.trim()) this.errors.email = 'L\'email è obbligatoria'
      else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) this.errors.email = 'Email non valida'
      if (!this.form.telefono.trim()) this.errors.telefono = 'Il telefono è obbligatorio'
      return Object.keys(this.errors).length === 0
    },
    submitForm() {
      if (!this.validate()) return
      const testo = `*Candidatura Volontario*\n\n*Nome:* ${this.form.nome}\n*Email:* ${this.form.email}\n*Telefono:* ${this.form.telefono}\n\n*Messaggio:*\n${this.form.messaggio || 'Nessun messaggio'}`
      window.open(`https://wa.me/393409977463?text=${encodeURIComponent(testo)}`, '_blank')
      this.submitted = true
    }
  }
}
</script>

<style scoped>
.content {
  max-width: 900px;
  margin: 0 auto;
}

.content h2 {
  font-size: 2.25rem;
  margin: 56px 0 24px;
  color: var(--cri-red);
  letter-spacing: -0.02em;
}

.content > p {
  margin-bottom: 16px;
  line-height: 1.8;
}

.benefits-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 24px;
  margin: 40px 0;
}

.benefit-card {
  text-align: center;
  padding: 24px;
  background: var(--cri-light-gray);
  border-radius: var(--cri-radius-md);
  transition: box-shadow var(--cri-transition);
}

.benefit-card:hover {
  box-shadow: var(--cri-shadow-md);
}

.benefit-icon {
  margin-bottom: 16px;
  display: flex;
  justify-content: center;
  color: var(--cri-red);
}

.benefit-card h3 {
  margin-bottom: 8px;
}

.benefit-card p {
  color: var(--cri-text-light);
  font-size: 0.95rem;
}

.steps {
  margin: 40px 0;
}

.step {
  display: flex;
  gap: 24px;
  margin-bottom: 32px;
  align-items: flex-start;
}

.step-number {
  width: 48px;
  height: 48px;
  min-width: 48px;
  background: var(--cri-red);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  font-weight: 700;
}

.step h3 {
  margin-bottom: 4px;
}

.step p {
  color: var(--cri-text-light);
}

.cta-box {
  background: var(--cri-light-gray);
  padding: 40px;
  border-radius: var(--cri-radius-md);
  margin-top: 48px;
}

.cta-box h3 {
  margin-bottom: 24px;
  font-size: 1.5rem;
}

form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

@media (max-width: 768px) {
  .benefits-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .cta-box {
    padding: 24px;
  }
}
</style>
