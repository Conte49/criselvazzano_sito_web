import { put } from '@vercel/blob'
import { readFileSync, existsSync } from 'fs'
import { join } from 'path'

export default async function handler(req, res) {
  if (req.method !== 'POST') return res.status(405).json({ message: 'POST only' })

  const secret = req.headers['x-seed-secret']
  if (secret !== process.env.SEED_SECRET) return res.status(401).json({ message: 'Non autorizzato' })

  const results = []

  for (const type of ['posts', 'media']) {
    const localPath = join(process.cwd(), 'data', `${type}.json`)
    if (existsSync(localPath)) {
      const content = readFileSync(localPath, 'utf-8')
      const blob = await put(`data/${type}.json`, content, { access: 'public', addRandomSuffix: false })
      results.push({ type, url: blob.url, size: content.length })
    } else {
      await put(`data/${type}.json`, '[]', { access: 'public', addRandomSuffix: false })
      results.push({ type, url: 'created empty', size: 2 })
    }
  }

  res.json({ success: true, results })
}
