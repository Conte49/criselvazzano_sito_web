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

          <div class="highlight-box reveal">
            <h3>Come donare il 5×1000</h3>
            <div class="steps-simple">
              <div class="step-simple">
                <span class="number" aria-hidden="true">1</span>
                <p>Firma nel riquadro "Sostegno del volontariato"</p>
              </div>
              <div class="step-simple">
                <span class="number" aria-hidden="true">2</span>
                <p>Inserisci il codice fiscale della CRI</p>
              </div>
            </div>
            <div class="cf-box">
              <div class="cf-label">Codice Fiscale CRI</div>
              <div class="cf-code" aria-label="Codice fiscale: 04776880280">04776880280</div>
            </div>
          </div>

          <h2>Altre modalità di donazione</h2>
          
          <div class="donation-methods stagger-children">
            <div class="method-card reveal">
              <div class="method-icon" aria-hidden="true">
                <Icon name="bank" :size="48" />
              </div>
              <h3>Bonifico Bancario</h3>
              <p><strong>IBAN:</strong></p>
              <p class="iban">IT88N0306909606100000078845</p>
              <p><strong>Intestato a:</strong> CRI - Comitato di Selvazzano Dentro - ODV</p>
            </div>

            <div class="method-card reveal">
              <div class="method-icon" aria-hidden="true">
                <Icon name="card" :size="48" />
              </div>
              <h3>PayPal</h3>
              <p>Dona online in modo sicuro e veloce</p>
              <div id="paypal-container-NTW9NKU3UYPY4"></div>
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
  mounted() {
    this.$nextTick(() => initReveal(this.$el))
    this.loadPayPal()
  },
  beforeUnmount() {
    destroyReveal()
    // Rimuovi lo script PayPal al cambio pagina
    const script = document.querySelector('script[src*="paypal.com/sdk"]')
    if (script) script.remove()
  },
  methods: {
    loadPayPal() {
      const script = document.createElement('script')
      script.src = 'https://www.paypal.com/sdk/js?client-id=BAAYkTK-7EBWJ6e7P19jJPeh-U-JO5FiCeM-DYS4G9aKxehbZcl9Jbvt3gtJOGBlQXvj1qmdB9R2QZGWas&components=hosted-buttons&disable-funding=venmo&currency=EUR'
      script.onload = () => {
        if (window.paypal) {
          window.paypal.HostedButtons({
            hostedButtonId: 'NTW9NKU3UYPY4'
          }).render('#paypal-container-NTW9NKU3UYPY4')
        }
      }
      document.head.appendChild(script)
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

.content h2:first-of-type {
  margin-top: 0;
}

.content > p {
  line-height: 1.8;
}

.highlight-box {
  background: var(--cri-light-gray);
  padding: 40px;
  border-radius: var(--cri-radius-md);
  margin: 32px 0;
}

.highlight-box h3 {
  margin-bottom: 24px;
  font-size: 1.25rem;
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
  min-width: 40px;
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
  padding: 32px;
  border-radius: var(--cri-radius-md);
  text-align: center;
  border: 3px solid var(--cri-red);
}

.cf-label {
  font-size: 0.8125rem;
  color: var(--cri-text-light);
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  font-weight: 600;
}

.cf-code {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--cri-red);
  letter-spacing: 4px;
  font-variant-numeric: tabular-nums;
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
  border-radius: var(--cri-radius-md);
  box-shadow: var(--cri-shadow-sm);
  text-align: center;
}

.method-icon {
  margin-bottom: 16px;
  display: flex;
  justify-content: center;
  color: var(--cri-red);
}

.method-card h3 {
  font-size: 1.25rem;
  margin-bottom: 16px;
  color: var(--cri-red);
}

.method-card p {
  margin-bottom: 8px;
}

.iban {
  font-family: monospace;
  font-size: 0.95rem;
  word-break: break-all;
  background: var(--cri-light-gray);
  padding: 8px 12px;
  border-radius: var(--cri-radius-sm);
  display: inline-block;
  margin-bottom: 12px;
}

@media (max-width: 768px) {
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
