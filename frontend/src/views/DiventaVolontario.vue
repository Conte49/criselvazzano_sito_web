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

          <div class="benefits-grid">
            <div class="benefit-card">
              <div class="icon">
                <Icon name="school" :size="48" color="#E31E24" />
              </div>
              <h3>Formazione Gratuita</h3>
              <p>Corsi di primo soccorso e molto altro</p>
            </div>
            <div class="benefit-card">
              <div class="icon">
                <Icon name="users" :size="48" color="#E31E24" />
              </div>
              <h3>Nuove Amicizie</h3>
              <p>Entra in una grande famiglia</p>
            </div>
            <div class="benefit-card">
              <div class="icon">
                <Icon name="growth" :size="48" color="#E31E24" />
              </div>
              <h3>Crescita Personale</h3>
              <p>Sviluppa competenze e valori</p>
            </div>
            <div class="benefit-card">
              <div class="icon">
                <Icon name="heart" :size="48" color="#E31E24" />
              </div>
              <h3>Aiuta gli Altri</h3>
              <p>Fai la differenza</p>
            </div>
          </div>

          <h2>Come diventare volontario</h2>
          <div class="steps">
            <div class="step">
              <div class="step-number">1</div>
              <div>
                <h3>Contattaci</h3>
                <p>Compila il form o vieni in sede</p>
              </div>
            </div>
            <div class="step">
              <div class="step-number">2</div>
              <div>
                <h3>Colloquio</h3>
                <p>Conosciamoci meglio</p>
              </div>
            </div>
            <div class="step">
              <div class="step-number">3</div>
              <div>
                <h3>Formazione</h3>
                <p>Corso base per volontari</p>
              </div>
            </div>
            <div class="step">
              <div class="step-number">4</div>
              <div>
                <h3>Inizia</h3>
                <p>Entra in servizio</p>
              </div>
            </div>
          </div>

          <div class="cta-box">
            <h3>Pronto a iniziare?</h3>
            <form @submit.prevent="submitForm">
              <input type="text" placeholder="Nome e Cognome" required>
              <input type="email" placeholder="Email" required>
              <input type="tel" placeholder="Telefono" required>
              <textarea placeholder="Parlaci di te" rows="4" required></textarea>
              <button type="submit" class="btn btn-primary">Invia</button>
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
      alert('Grazie! Ti contatteremo presto.')
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

.content {
  max-width: 900px;
  margin: 0 auto;
}

.content h2 {
  font-size: 2rem;
  margin: 48px 0 24px;
  color: var(--cri-red);
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
  border-radius: 8px;
}

.benefit-card .icon {
  margin-bottom: 16px;
  display: flex;
  justify-content: center;
}

.steps {
  margin: 40px 0;
}

.step {
  display: flex;
  gap: 24px;
  margin-bottom: 32px;
}

.step-number {
  width: 48px;
  height: 48px;
  background: var(--cri-red);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  font-weight: 700;
}

.cta-box {
  background: var(--cri-light-gray);
  padding: 40px;
  border-radius: 8px;
  margin-top: 48px;
}

form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

input, textarea {
  padding: 12px 16px;
  border: 2px solid #e0e0e0;
  border-radius: 4px;
  font-family: inherit;
  font-size: 1rem;
  background: white;
}

input:focus, textarea:focus {
  outline: none;
  border-color: var(--cri-red);
}

@media (max-width: 768px) {
  .hero-small {
    padding: 60px 0;
  }

  .hero-small h1 {
    font-size: 2rem;
  }

  .benefits-grid {
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  }

  .cta-box {
    padding: 24px;
  }
}
</style>
