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
          <div class="contact-info">
            <h2>CRI - Comitato di Selvazzano Dentro - ODV</h2>
            
            <div class="info-item">
              <div class="icon">
                <Icon name="location" :size="24" color="#E31E24" />
              </div>
              <div>
                <h3>Indirizzo</h3>
                <p>Via Torquato Tasso 8<br>35030, Caselle di Selvazzano - Padova</p>
              </div>
            </div>

            <div class="info-item">
              <div class="icon">
                <Icon name="phone" :size="24" color="#E31E24" />
              </div>
              <div>
                <h3>Telefono</h3>
                <p>049.8977463</p>
              </div>
            </div>

            <div class="info-item">
              <div class="icon">
                <Icon name="email" :size="24" color="#E31E24" />
              </div>
              <div>
                <h3>Email</h3>
                <p>selvazzanodentro@cri.it</p>
              </div>
            </div>

            <div class="info-item">
              <div class="icon">
                <Icon name="clock" :size="24" color="#E31E24" />
              </div>
              <div>
                <h3>Orari</h3>
                <p>Lunedì - Venerdì: 9:00 - 18:00<br>Sabato: 9:00 - 13:00</p>
              </div>
            </div>
          </div>

          <div class="contact-form">
            <h2>Inviaci un Messaggio</h2>
            <form @submit.prevent="submitForm">
              <input type="text" placeholder="Nome" required>
              <input type="email" placeholder="Email" required>
              <input type="text" placeholder="Oggetto" required>
              <textarea placeholder="Messaggio" rows="6" required></textarea>
              <button type="submit" class="btn btn-primary">Invia Messaggio</button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import Icon from '../components/Icon.vue'

export default {
  components: { Icon },
  methods: {
    submitForm() {
      alert('Funzionalità in sviluppo')
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

.contact-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 60px;
}

.contact-info h2,
.contact-form h2 {
  font-size: 1.75rem;
  margin-bottom: 32px;
  color: var(--cri-red);
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
  border-radius: 8px;
  flex-shrink: 0;
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

form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

input,
textarea {
  padding: 12px 16px;
  border: 2px solid #e0e0e0;
  border-radius: 4px;
  font-family: inherit;
  font-size: 1rem;
  transition: border-color 0.3s;
}

input:focus,
textarea:focus {
  outline: none;
  border-color: var(--cri-red);
}

@media (max-width: 768px) {
  .contact-grid {
    grid-template-columns: 1fr;
  }
}
</style>
