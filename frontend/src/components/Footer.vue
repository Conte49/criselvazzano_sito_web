<template>
  <footer class="footer" role="contentinfo">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-col">
          <h3>CRI Selvazzano Dentro</h3>
          <p>Croce Rossa Italiana - Comitato di Selvazzano Dentro</p>
          <p>Dal 1989 al servizio della comunità</p>
        </div>
        
        <div class="footer-col">
          <h4>Link Utili</h4>
          <ul>
            <li><router-link to="/chi-siamo">Chi Siamo</router-link></li>
            <li><router-link to="/servizi">Servizi</router-link></li>
            <li><router-link to="/news">News</router-link></li>
            <li><router-link to="/diventa-volontario">Diventa Volontario</router-link></li>
            <li><router-link to="/dona">Dona il 5x1000</router-link></li>
            <li><router-link to="/trasparenza">Trasparenza</router-link></li>
          </ul>
        </div>
        
        <div class="footer-col">
          <h4>Contatti</h4>
          <ul>
            <li class="footer-contact-item">
              <Icon name="location" :size="16" color="rgba(255,255,255,0.85)" />
              <span>Via Torquato Tasso 8, Caselle di Selvazzano (PD)</span>
            </li>
            <li class="footer-contact-item">
              <Icon name="phone" :size="16" color="rgba(255,255,255,0.85)" />
              <a href="tel:0498977463">049.8977463</a>
            </li>
            <li class="footer-contact-item">
              <Icon name="email" :size="16" color="rgba(255,255,255,0.85)" />
              <a href="mailto:selvazzanodentro@cri.it">selvazzanodentro@cri.it</a>
            </li>
          </ul>
        </div>
        
        <div class="footer-col">
          <h4>Dati Fiscali</h4>
          <details class="fiscal-details">
            <summary>Mostra dati fiscali</summary>
            <ul>
              <li>CF: 04776880280</li>
              <li>P.IVA: 04776880280</li>
              <li>SDI: XL13LG4</li>
              <li>IBAN: IT88N0306909606100000078845</li>
              <li>PEC: criselvazzanodentro@pec.cri.it</li>
            </ul>
          </details>
        </div>
      </div>
      
      <div class="footer-bottom">
        <p>&copy; {{ currentYear }} CRI Selvazzano Dentro. Tutti i diritti riservati.</p>
      </div>
    </div>
  </footer>
</template>

<script>
import Icon from './Icon.vue'

export default {
  components: { Icon },
  computed: {
    currentYear() {
      return new Date().getFullYear()
    }
  }
}
</script>

<style scoped>
.footer {
  background: #2d2d2d;
  color: var(--cri-white);
  padding: 72px 0 24px;
  margin-top: auto;
}

.footer-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 48px;
  margin-bottom: 48px;
}

.footer-col h3 {
  margin-bottom: 20px;
  color: var(--cri-white);
  font-size: 1.125rem;
}

.footer-col h4 {
  margin-bottom: 20px;
  color: var(--cri-light-red);
  font-size: 0.8125rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  font-weight: 700;
}

.footer-col p {
  color: rgba(255,255,255,0.85);
  margin-bottom: 8px;
}

.footer-col ul {
  list-style: none;
}

.footer-col ul li {
  margin-bottom: 8px;
  font-size: 0.9rem;
  color: rgba(255,255,255,0.85);
}

.footer-col a {
  color: rgba(255,255,255,0.85);
  position: relative;
}

.footer-col a::after {
  content: '';
  position: absolute;
  bottom: -1px;
  left: 0;
  width: 0;
  height: 1px;
  background: var(--cri-light-red);
  transition: width 300ms var(--cri-ease-out);
}

.footer-col a:hover {
  color: var(--cri-light-red);
}

.footer-col a:hover::after {
  width: 100%;
}

.footer-contact-item {
  display: flex;
  align-items: flex-start;
  gap: 8px;
}

.footer-contact-item svg {
  flex-shrink: 0;
  margin-top: 2px;
}

.fiscal-details summary {
  cursor: pointer;
  color: rgba(255,255,255,0.85);
  font-size: 0.9rem;
  padding: 4px 0;
}

.fiscal-details summary:hover {
  color: var(--cri-light-red);
}

.fiscal-details ul {
  margin-top: 12px;
}

.footer-bottom {
  border-top: 1px solid rgba(255,255,255,0.15);
  padding-top: 20px;
  text-align: center;
  color: rgba(255,255,255,0.7);
}

@media (max-width: 768px) {
  .footer-grid {
    grid-template-columns: 1fr;
    gap: 32px;
  }
}
</style>
