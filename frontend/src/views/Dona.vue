<template>
  <div class="dona">
    <section class="hero-small">
      <div class="container">
        <h1>Sostieni la CRI</h1>
        <p>Il tuo contributo fa la differenza</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="content">
          <h2>Dona il tuo 5×1000</h2>
          <p>Destinare il 5×1000 alla Croce Rossa Italiana non costa nulla e ci permette di continuare le nostre attività sul territorio.</p>

          <div class="highlight-box">
            <h3>Come donare il 5×1000</h3>
            <div class="steps-simple">
              <div class="step-simple">
                <span class="number">1</span>
                <p>Firma nel riquadro "Sostegno del volontariato"</p>
              </div>
              <div class="step-simple">
                <span class="number">2</span>
                <p>Inserisci il codice fiscale della CRI</p>
              </div>
            </div>
            <div class="cf-box">
              <div class="cf-label">Codice Fiscale CRI</div>
              <div class="cf-code">00891381007</div>
            </div>
          </div>

          <h2>Altre modalità di donazione</h2>
          
          <div class="donation-methods">
            <div class="method-card">
              <div class="icon">
                <Icon name="bank" :size="48" color="#E31E24" />
              </div>
              <h3>Bonifico Bancario</h3>
              <p><strong>IBAN:</strong> IT88N0306909606100000078845</p>
              <p><strong>Intestato a:</strong> CRI - Comitato di Selvazzano Dentro - ODV</p>
            </div>

            <div class="method-card">
              <div class="icon">
                <Icon name="card" :size="48" color="#E31E24" />
              </div>
              <h3>Carta di Credito</h3>
              <p>Dona online in modo sicuro</p>
              <button class="btn btn-primary">Dona Ora</button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import Icon from '../components/Icon.vue'

export default {
  components: { Icon }
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

.highlight-box {
  background: var(--cri-light-gray);
  padding: 40px;
  border-radius: 8px;
  margin: 32px 0;
}

.steps-simple {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 24px;
  margin-bottom: 32px;
}

.step-simple {
  display: flex;
  align-items: center;
  gap: 16px;
}

.step-simple .number {
  width: 40px;
  height: 40px;
  background: var(--cri-red);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
}

.cf-box {
  background: white;
  padding: 24px;
  border-radius: 8px;
  text-align: center;
  border: 2px solid var(--cri-red);
}

.cf-code {
  font-size: 2rem;
  font-weight: 700;
  color: var(--cri-red);
  letter-spacing: 2px;
}

.donation-methods {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 24px;
  margin: 32px 0;
}

.method-card {
  background: white;
  padding: 32px;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  text-align: center;
}

.method-card .icon {
  margin-bottom: 16px;
  display: flex;
  justify-content: center;
}

.method-card h3 {
  font-size: 1.25rem;
  margin-bottom: 16px;
  color: var(--cri-red);
}

@media (max-width: 768px) {
  .hero-small {
    padding: 60px 0;
  }

  .hero-small h1 {
    font-size: 2rem;
  }

  .highlight-box {
    padding: 24px;
  }

  .cf-code {
    font-size: 1.5rem;
  }

  .steps-simple {
    grid-template-columns: 1fr;
  }
}
</style>
