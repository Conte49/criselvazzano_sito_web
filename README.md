# CRI Selvazzano Dentro - Nuovo Sito Web

Sito web moderno per il Comitato di Selvazzano Dentro della Croce Rossa Italiana.

## Struttura Progetto

```
sito-web/
├── scraper/          # Script per scaricare contenuti da WordPress
├── scraped-data/     # Dati scaricati dal vecchio sito
├── frontend/         # Applicazione Vue.js
└── README.md
```

## Setup

### 1. Scraping (già eseguito)

```bash
npm install
npm run scrape
```

### 2. Frontend

```bash
cd frontend
npm install
npm run dev
```

Il sito sarà disponibile su http://localhost:3000

## Caratteristiche

- [x] Design moderno in stile Croce Rossa
- [x] Responsive (mobile-first)
- [x] Colori ufficiali CRI (#E31E24)
- [x] Navigazione intuitiva
- [x] Sezione News con dettaglio
- [x] Pagine statiche (Chi Siamo, Contatti)
- [x] Dati importati da WordPress

## Tecnologie

- **Frontend:** Vue 3, Vue Router, Vite
- **Scraper:** Node.js, Axios
- **Design:** CSS moderno con variabili

## Prossimi Passi

- [ ] Pannello admin PHP per gestire i post
- [ ] Database per i contenuti
- [ ] Form contatti funzionante
- [ ] Ottimizzazione immagini
- [ ] SEO e meta tags
