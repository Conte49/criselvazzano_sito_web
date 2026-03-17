<template>
  <div class="contatti">
    <section class="hero-small">
      <div class="container">
        <h1>Contatti</h1>
        <p>Siamo qui per te. Contattaci per qualsiasi informazione</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="contact-grid">
          <div class="contact-info reveal">
            <h2>CRI - Comitato di Selvazzano Dentro - ODV</h2>
            
            <div class="info-item">
              <div class="icon">
                <Icon name="location" :size="24" />
              </div>
              <div>
                <h3>Indirizzo</h3>
                <p>Via Torquato Tasso 8<br>35030, Caselle di Selvazzano - Padova</p>
              </div>
            </div>

            <div class="info-item">
              <div class="icon">
                <Icon name="phone" :size="24" />
              </div>
              <div>
                <h3>Telefono</h3>
                <p><a href="tel:0498977463">049.8977463</a></p>
              </div>
            </div>

            <div class="info-item">
              <div class="icon">
                <Icon name="email" :size="24" />
              </div>
              <div>
                <h3>Email</h3>
                <p><a href="mailto:selvazzanodentro@cri.it">selvazzanodentro@cri.it</a></p>
              </div>
            </div>

            <div class="info-item">
              <div class="icon">
                <Icon name="clock" :size="24" />
              </div>
              <div>
                <h3>Orari</h3>
                <p>Lunedì - Venerdì: 9:00 - 18:00</p>
              </div>
            </div>
          </div>

          <div class="contact-form reveal">
            <h2>Inviaci un Messaggio</h2>

            <div v-if="submitted" class="status-message success">
              <svg class="success-check" width="40" height="40" viewBox="0 0 40 40" aria-hidden="true">
                <circle cx="20" cy="20" r="18" fill="none" stroke="#4caf50" stroke-width="2" />
                <path class="check-path" d="M12 20l6 6 10-12" fill="none" stroke="#4caf50" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <p>Messaggio inviato tramite WhatsApp. Grazie per averci contattato.</p>
            </div>

            <form v-else @submit.prevent="submitForm" novalidate>
              <div class="form-group">
                <label for="contact-nome" class="form-label">Nome *</label>
                <input id="contact-nome" v-model="form.nome" type="text" class="form-input" :class="{ error: errors.nome, valid: !errors.nome && form.nome.trim() }" required>
                <span class="form-error">{{ errors.nome }}</span>
              </div>
              <div class="form-group">
                <label for="contact-email" class="form-label">Email *</label>
                <input id="contact-email" v-model="form.email" type="email" class="form-input" :class="{ error: errors.email, valid: !errors.email && form.email.trim() && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email) }" required>
                <span class="form-error">{{ errors.email }}</span>
              </div>
              <div class="form-group">
                <label for="contact-oggetto" class="form-label">Oggetto *</label>
                <input id="contact-oggetto" v-model="form.oggetto" type="text" class="form-input" :class="{ error: errors.oggetto, valid: !errors.oggetto && form.oggetto.trim() }" required>
                <span class="form-error">{{ errors.oggetto }}</span>
              </div>
              <div class="form-group">
                <label for="contact-messaggio" class="form-label">Messaggio *</label>
                <textarea id="contact-messaggio" v-model="form.messaggio" class="form-textarea" :class="{ error: errors.messaggio, valid: !errors.messaggio && form.messaggio.trim() }" rows="6" required></textarea>
                <span class="form-error">{{ errors.messaggio }}</span>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-primary">Invia via WhatsApp</button>
                <a :href="mailtoLink" class="btn btn-outline">Invia via Email</a>
              </div>
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
      form: { nome: '', email: '', oggetto: '', messaggio: '' },
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
  computed: {
    mailtoLink() {
      const subject = encodeURIComponent(this.form.oggetto || 'Contatto dal sito')
      const body = encodeURIComponent(
        `Nome: ${this.form.nome}\nEmail: ${this.form.email}\n\n${this.form.messaggio}`
      )
      return `mailto:selvazzanodentro@cri.it?subject=${subject}&body=${body}`
    }
  },
  methods: {
    validate() {
      this.errors = {}
      if (!this.form.nome.trim()) this.errors.nome = 'Il nome è obbligatorio'
      if (!this.form.email.trim()) this.errors.email = 'L\'email è obbligatoria'
      else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) this.errors.email = 'Email non valida'
      if (!this.form.oggetto.trim()) this.errors.oggetto = 'L\'oggetto è obbligatorio'
      if (!this.form.messaggio.trim()) this.errors.messaggio = 'Il messaggio è obbligatorio'
      return Object.keys(this.errors).length === 0
    },
    submitForm() {
      if (!this.validate()) return
      const testo = `*Nuovo messaggio dal sito*\n\n*Nome:* ${this.form.nome}\n*Email:* ${this.form.email}\n*Oggetto:* ${this.form.oggetto}\n\n*Messaggio:*\n${this.form.messaggio}`
      window.open(`https://wa.me/393409977463?text=${encodeURIComponent(testo)}`, '_blank')
      this.submitted = true
    }
  }
}
</script>

<style scoped>
.contact-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 60px;
}

.contact-info h2,
.contact-form h2 {
  font-size: 1.875rem;
  margin-bottom: 32px;
  color: var(--cri-red);
  letter-spacing: -0.01em;
}

.info-item {
  display: flex;
  gap: 16px;
  margin-bottom: 32px;
}

.icon {
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--cri-light-gray);
  border-radius: var(--cri-radius-md);
  flex-shrink: 0;
  color: var(--cri-red);
}

.info-item h3 {
  font-size: 1.125rem;
  margin-bottom: 8px;
  color: var(--cri-text);
}

.info-item p {
  color: var(--cri-text-light);
  line-height: 1.6;
}

.info-item a {
  color: var(--cri-text-light);
}

.info-item a:hover {
  color: var(--cri-red);
}

form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.form-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

@media (max-width: 768px) {
  .contact-grid {
    grid-template-columns: 1fr;
    gap: 40px;
  }

  .form-actions {
    flex-direction: column;
  }

  .form-actions .btn {
    text-align: center;
  }
}
</style>
