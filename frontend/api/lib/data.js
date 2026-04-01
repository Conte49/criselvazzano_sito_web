import { put, list } from '@vercel/blob'

const POSTS_KEY = 'data/posts.json'
const MEDIA_KEY = 'data/media.json'

async function getBlobUrl(key) {
  const { blobs } = await list({ prefix: key })
  return blobs.length ? blobs[blobs.length - 1].url : null
}

export async function readJSON(type) {
  const key = type === 'media' ? MEDIA_KEY : POSTS_KEY
  const url = await getBlobUrl(key)
  if (!url) return []
  try {
    const res = await fetch(url)
    return res.ok ? await res.json() : []
  } catch {
    return []
  }
}

export async function writeJSON(type, data) {
  const key = type === 'media' ? MEDIA_KEY : POSTS_KEY
  const json = JSON.stringify(data, null, 2)
  await put(key, json, { access: 'public', addRandomSuffix: false })
}

export function getNextId(items) {
  return items.reduce((max, item) => Math.max(max, item.id || 0), 0) + 1
}

export function generateSlug(title) {
  return title
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '_')
    .replace(/^_|_$/g, '')
}
