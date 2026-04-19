import { put } from '@vercel/blob'
import { readFileSync } from 'fs'

const token = process.env.BLOB_READ_WRITE_TOKEN

console.log('Token:', token ? token.substring(0, 20) + '...' : 'MISSING')

for (const type of ['posts', 'media']) {
  const content = readFileSync(`data/${type}.json`, 'utf-8')
  const blob = await put(`data/${type}.json`, content, {
    access: 'public',
    addRandomSuffix: false,
    token
  })
  console.log(`${type}: ${blob.url} (${content.length} bytes)`)
}

console.log('Seed completato!')
