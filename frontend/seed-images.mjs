import { put } from '@vercel/blob'
import { readFileSync, readdirSync } from 'fs'
import { join } from 'path'

const token = process.env.BLOB_READ_WRITE_TOKEN
const dir = 'public/news-images'
const files = readdirSync(dir)

console.log(`Caricamento ${files.length} immagini...`)

for (const file of files) {
  const data = readFileSync(join(dir, file))
  const blob = await put(`news-images/${file}`, data, {
    access: 'public',
    addRandomSuffix: false,
    token
  })
  console.log(`  ${file} -> ${blob.url}`)
}

console.log('Upload immagini completato!')
