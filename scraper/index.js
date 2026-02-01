import axios from 'axios';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const SITE_URL = 'https://www.criselvazzanodentro.it';
const BASE_URL = `${SITE_URL}/wp-json/wp/v2`;
const OUTPUT_DIR = path.join(__dirname, '../scraped-data');

async function fetchAll(endpoint, params = {}) {
  let page = 1;
  let allData = [];
  
  while (true) {
    try {
      const response = await axios.get(`${BASE_URL}/${endpoint}`, {
        params: { ...params, page, per_page: 100 }
      });
      
      allData = allData.concat(response.data);
      console.log(`${endpoint}: scaricata pagina ${page} (${response.data.length} items)`);
      
      if (response.data.length < 100) break;
      page++;
    } catch (error) {
      if (error.response?.status === 400 || error.response?.status === 401) {
        console.log(`${endpoint}: accesso negato o fine dati`);
        break;
      }
      console.error(`Errore su ${endpoint}:`, error.message);
      break;
    }
  }
  
  return allData;
}

async function fetchSingle(url) {
  try {
    const response = await axios.get(url);
    return response.data;
  } catch (error) {
    console.error(`Errore su ${url}:`, error.message);
    return null;
  }
}

async function discoverEndpoints() {
  try {
    const response = await axios.get(`${SITE_URL}/wp-json`);
    return response.data;
  } catch (error) {
    console.error('Errore discovery:', error.message);
    return {};
  }
}

async function downloadImage(url, filepath) {
  try {
    const response = await axios.get(url, { responseType: 'stream' });
    const writer = fs.createWriteStream(filepath);
    response.data.pipe(writer);
    return new Promise((resolve, reject) => {
      writer.on('finish', resolve);
      writer.on('error', reject);
    });
  } catch (error) {
    console.error(`Errore download ${url}:`, error.message);
  }
}

async function scrapeAll() {
  if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR, { recursive: true });
  }

  console.log('Inizio scraping...\n');

  // Discovery endpoints disponibili
  console.log('Scopro endpoint disponibili...');
  const endpoints = await discoverEndpoints();
  fs.writeFileSync(
    path.join(OUTPUT_DIR, 'endpoints.json'),
    JSON.stringify(endpoints, null, 2)
  );
  console.log('✓ Endpoints salvati\n');

  // Categories
  console.log('Scarico categories...');
  const categories = await fetchAll('categories');
  fs.writeFileSync(
    path.join(OUTPUT_DIR, 'categories.json'),
    JSON.stringify(categories, null, 2)
  );
  console.log(`✓ ${categories.length} categories salvate\n`);

  // Tags
  console.log('Scarico tags...');
  const tags = await fetchAll('tags');
  fs.writeFileSync(
    path.join(OUTPUT_DIR, 'tags.json'),
    JSON.stringify(tags, null, 2)
  );
  console.log(`✓ ${tags.length} tags salvati\n`);

  // Posts
  console.log('Scarico posts...');
  const posts = await fetchAll('posts');
  fs.writeFileSync(
    path.join(OUTPUT_DIR, 'posts.json'),
    JSON.stringify(posts, null, 2)
  );
  console.log(`✓ ${posts.length} posts salvati\n`);

  // Pages
  console.log('Scarico pages...');
  const pages = await fetchAll('pages');
  fs.writeFileSync(
    path.join(OUTPUT_DIR, 'pages.json'),
    JSON.stringify(pages, null, 2)
  );
  console.log(`✓ ${pages.length} pages salvate\n`);

  // Media
  console.log('Scarico media...');
  const media = await fetchAll('media');
  fs.writeFileSync(
    path.join(OUTPUT_DIR, 'media.json'),
    JSON.stringify(media, null, 2)
  );
  console.log(`✓ ${media.length} media salvati\n`);

  // Users
  console.log('Scarico users...');
  const users = await fetchAll('users');
  if (users.length > 0) {
    fs.writeFileSync(
      path.join(OUTPUT_DIR, 'users.json'),
      JSON.stringify(users, null, 2)
    );
    console.log(`✓ ${users.length} users salvati\n`);
  } else {
    console.log('⚠ Users non disponibili (richiede autenticazione)\n');
  }

  // Comments
  console.log('Scarico comments...');
  const comments = await fetchAll('comments');
  fs.writeFileSync(
    path.join(OUTPUT_DIR, 'comments.json'),
    JSON.stringify(comments, null, 2)
  );
  console.log(`✓ ${comments.length} comments salvati\n`);

  // Menu locations (WP API Menus plugin)
  console.log('Scarico menu...');
  const menus = await fetchSingle(`${SITE_URL}/wp-json/wp-api-menus/v2/menus`);
  if (menus) {
    fs.writeFileSync(
      path.join(OUTPUT_DIR, 'menus.json'),
      JSON.stringify(menus, null, 2)
    );
    console.log('✓ Menu salvati\n');
  }

  // Menu items (alternativa)
  const menuItems = await fetchSingle(`${SITE_URL}/wp-json/wp-api-menus/v2/menu-locations`);
  if (menuItems) {
    fs.writeFileSync(
      path.join(OUTPUT_DIR, 'menu-locations.json'),
      JSON.stringify(menuItems, null, 2)
    );
    console.log('✓ Menu locations salvate\n');
  }

  // Settings
  console.log('Scarico settings...');
  const settings = await fetchSingle(`${BASE_URL}/settings`);
  if (settings) {
    fs.writeFileSync(
      path.join(OUTPUT_DIR, 'settings.json'),
      JSON.stringify(settings, null, 2)
    );
    console.log('✓ Settings salvati\n');
  }

  // Taxonomies
  console.log('Scarico taxonomies...');
  const taxonomies = await fetchAll('taxonomies');
  fs.writeFileSync(
    path.join(OUTPUT_DIR, 'taxonomies.json'),
    JSON.stringify(taxonomies, null, 2)
  );
  console.log(`✓ Taxonomies salvate\n`);

  // Post types
  console.log('Scarico post types...');
  const postTypes = await fetchAll('types');
  fs.writeFileSync(
    path.join(OUTPUT_DIR, 'post-types.json'),
    JSON.stringify(postTypes, null, 2)
  );
  console.log(`✓ Post types salvati\n`);

  // Download immagini
  console.log('Scarico immagini...');
  const imagesDir = path.join(OUTPUT_DIR, 'images');
  if (!fs.existsSync(imagesDir)) {
    fs.mkdirSync(imagesDir, { recursive: true });
  }

  for (const item of media) {
    if (item.source_url) {
      const filename = path.basename(item.source_url.split('?')[0]);
      const filepath = path.join(imagesDir, filename);
      console.log(`Scarico: ${filename}`);
      await downloadImage(item.source_url, filepath);
    }
  }

  console.log('\n✓ Scraping completato!');
  console.log(`Dati salvati in: ${OUTPUT_DIR}`);
}

scrapeAll().catch(console.error);
