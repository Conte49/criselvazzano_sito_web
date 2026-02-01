<template>
  <footer class="footer">
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
          </ul>
        </div>
        
        <div class="footer-col">
          <h4>Contatti</h4>
          <ul>
            <li>📍 Via Torquato Tasso 8, Caselle di Selvazzano (PD)</li>
            <li>📞 Tel: 049.8977463</li>
            <li>✉️ selvazzanodentro@cri.it</li>
            <li>📧 PEC: criselvazzanodentro@pec.cri.it</li>
          </ul>
        </div>
        
        <div class="footer-col">
          <h4>Dati Fiscali</h4>
          <ul>
            <li>CF: 04776880280</li>
            <li>P.IVA: 04776880280</li>
            <li>IBAN: IT88N0306909606100000078845</li>
          </ul>
        </div>
      </div>
      
      <div class="footer-bottom">
        <p>&copy; 2025 CRI Selvazzano Dentro. Tutti i diritti riservati.</p>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.footer {
  background: var(--cri-gray);
  color: var(--cri-white);
  padding: 60px 0 20px;
  margin-top: auto;
}

.footer-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 40px;
  margin-bottom: 40px;
}

.footer-col h3 {
  margin-bottom: 16px;
  color: var(--cri-white);
}

.footer-col h4 {
  margin-bottom: 16px;
  color: var(--cri-light-red);
  font-size: 1rem;
}

.footer-col p {
  color: rgba(255,255,255,0.8);
  margin-bottom: 8px;
}

.footer-col ul {
  list-style: none;
}

.footer-col ul li {
  margin-bottom: 8px;
  font-size: 0.9rem;
}

.footer-col a {
  color: rgba(255,255,255,0.8);
}

.footer-col a:hover {
  color: var(--cri-light-red);
}

.social {
  display: flex;
  gap: 12px;
}

.social-icon {
  width: 40px;
  height: 40px;
  background: rgba(255,255,255,0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  font-weight: 600;
  transition: 0.3s;
}

.social-icon:hover {
  background: var(--cri-red);
  color: white;
}

.footer-bottom {
  border-top: 1px solid rgba(255,255,255,0.1);
  padding-top: 20px;
  text-align: center;
  color: rgba(255,255,255,0.6);
}
</style>
