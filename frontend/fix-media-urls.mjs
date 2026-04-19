import { put } from '@vercel/blob'
import { readFileSync } from 'fs'

const token = process.env.BLOB_READ_WRITE_TOKEN
const BLOB_BASE = 'https://qkpjjwk4cafznwut.public.blob.vercel-storage.com'

const media = JSON.parse(readFileSync('data/media.json', 'utf-8'))

const updated = media.map(m => {
  if (m.source_url && m.source_url.startsWith('/news-images/')) {
    return { ...m, source_url: `${BLOB_BASE}${m.source_url}` }
  }
  return m
})

const json = JSON.stringify(updated, null, 2)
const blob = await put('data/media.json', json, { access: 'public', addRandomSuffix: false, token })
console.log(`media.json aggiornato: ${blob.url}`)
console.log(`${updated.length} media, URL aggiornati`)
