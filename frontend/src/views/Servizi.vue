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
        <div class="services-list">
          <div class="service-detail">
            <div class="service-icon">
              <Icon name="ambulance" :size="64" color="#E31E24" />
            </div>
            <div class="service-content">
              <h2>Trasporto Sanitario</h2>
              <p>Servizio di trasporto per visite mediche, terapie e dimissioni ospedaliere.</p>
              <ul>
                <li>Trasporti programmati</li>
                <li>Dimissioni ospedaliere</li>
                <li>Assistenza durante il trasporto</li>
              </ul>
              <a href="#" class="btn btn-primary">Richiedi</a>
            </div>
          </div>

          <div class="service-detail">
            <div class="service-icon">
              <Icon name="event" :size="64" color="#E31E24" />
            </div>
            <div class="service-content">
              <h2>Assistenza Manifestazioni</h2>
              <p>Primo soccorso durante eventi sportivi e manifestazioni pubbliche.</p>
              <ul>
                <li>Eventi sportivi</li>
                <li>Manifestazioni pubbliche</li>
                <li>Concerti e feste</li>
              </ul>
              <a href="#" class="btn btn-primary">Richiedi</a>
            </div>
          </div>

          <div class="service-detail">
            <div class="service-icon">
              <Icon name="medical" :size="64" color="#E31E24" />
            </div>
            <div class="service-content">
              <h2>Corsi Primo Soccorso</h2>
              <p>Corsi di primo soccorso per cittadini, aziende e scuole.</p>
              <ul>
                <li>Corso BLSD</li>
                <li>Primo soccorso pediatrico</li>
                <li>Corsi per aziende</li>
              </ul>
              <a href="#" class="btn btn-primary">Info</a>
            </div>
          </div>

          <div class="service-detail">
            <div class="service-icon">
              <Icon name="handshake" :size="64" color="#E31E24" />
            </div>
            <div class="service-content">
              <h2>Sportello Sociale</h2>
              <p>Supporto alle persone in difficoltà e distribuzione beni di prima necessità.</p>
              <ul>
                <li>Distribuzione alimentare</li>
                <li>Supporto alle famiglie</li>
                <li>Orientamento ai servizi</li>
              </ul>
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

.services-list {
  max-width: 900px;
  margin: 0 auto;
}

.service-detail {
  display: flex;
  gap: 32px;
  margin-bottom: 60px;
  padding-bottom: 60px;
  border-bottom: 1px solid #e0e0e0;
}

.service-detail:last-child {
  border-bottom: none;
}

.service-icon {
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.service-content h2 {
  font-size: 1.75rem;
  margin-bottom: 16px;
  color: var(--cri-red);
}

.service-content ul {
  margin: 16px 0 24px;
  padding-left: 20px;
}

.service-content li {
  margin-bottom: 8px;
}

@media (max-width: 768px) {
  .hero-small {
    padding: 60px 0;
  }

  .hero-small h1 {
    font-size: 2rem;
  }

  .service-detail {
    flex-direction: column;
    gap: 16px;
    margin-bottom: 40px;
    padding-bottom: 40px;
  }
}
</style>
