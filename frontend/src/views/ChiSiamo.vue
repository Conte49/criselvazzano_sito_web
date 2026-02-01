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
          <h2>Dal 1989 al servizio della comunità</h2>
          <p>Il Comitato di Selvazzano Dentro della Croce Rossa Italiana opera dal 1989 nel territorio di Selvazzano Dentro e comuni limitrofi, portando avanti i valori e i principi fondamentali del Movimento Internazionale di Croce Rossa e Mezzaluna Rossa.</p>
          
          <h3>I nostri valori</h3>
          <ul>
            <li><strong>Umanità:</strong> Prevenire e alleviare la sofferenza umana</li>
            <li><strong>Imparzialità:</strong> Non fare discriminazioni di nazionalità, razza, religione</li>
            <li><strong>Neutralità:</strong> Non prendere parte alle ostilità</li>
            <li><strong>Indipendenza:</strong> Autonomia e indipendenza</li>
            <li><strong>Volontarietà:</strong> Movimento di soccorso volontario</li>
            <li><strong>Unità:</strong> Un solo Comitato per ogni Paese</li>
            <li><strong>Universalità:</strong> Movimento mondiale</li>
          </ul>

          <h3>Cosa facciamo</h3>
          <p>Il nostro Comitato opera attraverso 6 obiettivi strategici che guidano tutte le nostre attività quotidiane, dalla salute all'emergenza, dal sociale ai giovani.</p>
        </div>
      </div>
    </section>
  </div>
</template>

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
  max-width: 800px;
  margin: 0 auto;
}

.content h2 {
  font-size: 2rem;
  margin-bottom: 24px;
  color: var(--cri-red);
}

.content h3 {
  font-size: 1.5rem;
  margin: 32px 0 16px;
  color: var(--cri-text);
}

.content p {
  margin-bottom: 16px;
  line-height: 1.8;
}

.content ul {
  margin: 16px 0;
  padding-left: 20px;
}

.content li {
  margin-bottom: 12px;
  line-height: 1.6;
}

@media (max-width: 768px) {
  .hero-small {
    padding: 60px 0;
  }

  .hero-small h1 {
    font-size: 2rem;
  }

  .content {
    padding: 0 16px;
  }
}
</style>
