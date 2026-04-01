import { put } from '@vercel/blob'
import { verifyToken } from './lib/auth.js'
import { readJSON, writeJSON, getNextId, generateSlug } from './lib/data.js'

export const config = { api: { bodyParser: false } }

export default async function handler(req, res) {
  if (req.method !== 'POST') return res.status(405).json({ success: false, message: 'Method not allowed' })

  const authenticated = await verifyToken(req)
  if (!authenticated) return res.status(401).json({ success: false, message: 'Non autorizzato' })

  try {
    const chunks = []
    for await (const chunk of req) chunks.push(chunk)
    const buffer = Buffer.concat(chunks)

    const boundary = req.headers['content-type']?.split('boundary=')[1]
    if (!boundary) return res.status(400).json({ success: false, message: 'Missing boundary' })

    const parts = parseMultipart(buffer, boundary)
    const filePart = parts.find(p => p.filename)
    const slugPart = parts.find(p => p.name === 'slug')

    if (!filePart) return res.status(400).json({ success: false, message: 'Nessun file caricato' })

    const slug = slugPart?.data?.toString() || 'upload'
    const ext = filePart.filename.split('.').pop()
    const filename = `${generateSlug(slug)}-${Date.now()}.${ext}`

    const blob = await put(`news-images/${filename}`, filePart.data, { access: 'public' })

    const media = await readJSON('media')
    const mediaId = getNextId(media)
    media.push({ id: mediaId, source_url: blob.url })
    await writeJSON('media', media)

    res.json({ success: true, mediaId, source_url: blob.url })
  } catch (err) {
    res.status(500).json({ success: false, message: 'Errore upload: ' + err.message })
  }
}

function parseMultipart(buffer, boundary) {
  const parts = []
  const boundaryBuf = Buffer.from(`--${boundary}`)
  let start = buffer.indexOf(boundaryBuf) + boundaryBuf.length + 2

  while (start < buffer.length) {
    const end = buffer.indexOf(boundaryBuf, start)
    if (end === -1) break
    const part = buffer.slice(start, end - 2)
    const headerEnd = part.indexOf('\r\n\r\n')
    if (headerEnd === -1) { start = end + boundaryBuf.length + 2; continue }
    const headers = part.slice(0, headerEnd).toString()
    const data = part.slice(headerEnd + 4)
    const nameMatch = headers.match(/name="([^"]+)"/)
    const filenameMatch = headers.match(/filename="([^"]+)"/)
    parts.push({ name: nameMatch?.[1], filename: filenameMatch?.[1], data })
    start = end + boundaryBuf.length + 2
  }
  return parts
}
