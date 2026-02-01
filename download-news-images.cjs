const fs = require('fs');
const path = require('path');
const https = require('https');
const http = require('http');

const posts = require('./frontend/src/data/posts.json');
const media = require('./frontend/src/data/media.json');

const newsImagesDir = path.join(__dirname, 'frontend/public/news-images');
if (!fs.existsSync(newsImagesDir)) {
  fs.mkdirSync(newsImagesDir, { recursive: true });
}

function downloadImage(url, filepath) {
  return new Promise((resolve, reject) => {
    const protocol = url.startsWith('https') ? https : http;
    const file = fs.createWriteStream(filepath);
    
    protocol.get(url, (response) => {
      if (response.statusCode === 200) {
        response.pipe(file);
        file.on('finish', () => {
          file.close();
          resolve();
        });
      } else {
        reject(new Error(`Failed to download: ${response.statusCode}`));
      }
    }).on('error', (err) => {
      fs.unlink(filepath, () => {});
      reject(err);
    });
  });
}

async function downloadNewsImages() {
  const imageMap = {};
  
  for (const post of posts) {
    console.log(`\nProcessing: ${post.title.rendered}`);
    
    // Download featured image
    if (post.featured_media) {
      const mediaItem = media.find(m => m.id === post.featured_media);
      if (mediaItem && mediaItem.source_url) {
        const ext = path.extname(mediaItem.source_url).split('?')[0];
        const newFilename = `${post.slug}${ext}`;
        const filepath = path.join(newsImagesDir, newFilename);
        
        try {
          console.log(`  Downloading featured image: ${newFilename}`);
          await downloadImage(mediaItem.source_url, filepath);
          imageMap[mediaItem.source_url] = `/news-images/${newFilename}`;
        } catch (err) {
          console.error(`  Error: ${err.message}`);
        }
      }
    }
    
    // Extract and download content images
    const imgRegex = /<img[^>]+src="([^">]+)"/g;
    let match;
    let imgIndex = 1;
    
    while ((match = imgRegex.exec(post.content.rendered)) !== null) {
      const imgUrl = match[1];
      if (imgUrl.includes('criselvazzanodentro.it') && !imageMap[imgUrl]) {
        const ext = path.extname(imgUrl).split('?')[0];
        const newFilename = `${post.slug}-${imgIndex}${ext}`;
        const filepath = path.join(newsImagesDir, newFilename);
        
        try {
          console.log(`  Downloading content image ${imgIndex}: ${newFilename}`);
          await downloadImage(imgUrl, filepath);
          imageMap[imgUrl] = `/news-images/${newFilename}`;
          imgIndex++;
        } catch (err) {
          console.error(`  Error: ${err.message}`);
        }
      }
    }
  }
  
  // Update posts.json with local paths
  const updatedPosts = posts.map(post => {
    let content = post.content.rendered;
    
    for (const [oldUrl, newUrl] of Object.entries(imageMap)) {
      content = content.replace(new RegExp(oldUrl.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g'), newUrl);
    }
    
    return { ...post, content: { ...post.content, rendered: content } };
  });
  
  // Update media.json with local paths
  const updatedMedia = media.map(m => {
    if (imageMap[m.source_url]) {
      return { ...m, source_url: imageMap[m.source_url] };
    }
    return m;
  });
  
  fs.writeFileSync('./frontend/src/data/posts.json', JSON.stringify(updatedPosts, null, 2));
  fs.writeFileSync('./frontend/src/data/media.json', JSON.stringify(updatedMedia, null, 2));
  
  console.log(`\n✅ Downloaded ${Object.keys(imageMap).length} images`);
  console.log('✅ Updated posts.json and media.json');
}

downloadNewsImages().catch(console.error);
