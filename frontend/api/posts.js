import { verifyToken } from './lib/auth.js'
import { readJSON, writeJSON, getNextId, generateSlug } from './lib/data.js'

export default async function handler(req, res) {
  const authenticated = await verifyToken(req)
  if (!authenticated) return res.status(401).json({ success: false, message: 'Non autorizzato' })

  const { method } = req
  const action = req.query.action

  if (method === 'GET' && action === 'list') {
    const posts = await readJSON('posts')
    posts.sort((a, b) => new Date(b.date) - new Date(a.date))
    return res.json(posts)
  }

  if (method === 'POST' && action === 'create') {
    return createOrUpdate(req, res, false)
  }

  if (method === 'POST' && action === 'edit') {
    return createOrUpdate(req, res, true)
  }

  if (method === 'DELETE') {
    const id = parseInt(req.query.id)
    if (!id) return res.status(400).json({ success: false, message: 'ID mancante' })
    let posts = await readJSON('posts')
    posts = posts.filter(p => p.id !== id)
    await writeJSON('posts', posts)
    return res.json({ success: true })
  }

  res.status(405).json({ success: false, message: 'Azione non valida' })
}

async function createOrUpdate(req, res, editMode) {
  const { title, content, excerpt, id, featuredMediaId } = req.body || {}

  if (!title || !content) {
    return res.status(400).json({ success: false, message: 'Titolo e contenuto sono obbligatori' })
  }

  const posts = await readJSON('posts')
  const now = new Date().toISOString().replace('Z', '').split('.')[0]
  const slug = generateSlug(title)

  const postData = {
    date: now,
    date_gmt: now,
    modified: now,
    modified_gmt: now,
    slug,
    status: 'publish',
    type: 'post',
    title: { rendered: title },
    content: { rendered: content, protected: false },
    excerpt: { rendered: excerpt || title.substring(0, 150), protected: false },
    featured_media: featuredMediaId || 0,
    author: 3,
    comment_status: 'closed',
    ping_status: 'closed',
    sticky: false,
    template: '',
    format: 'standard',
    meta: { ngg_post_thumbnail: 0 },
    categories: [5],
    tags: [],
    _links: []
  }

  if (editMode && id) {
    const editId = parseInt(id)
    const idx = posts.findIndex(p => p.id === editId)
    if (idx === -1) return res.status(404).json({ success: false, message: 'Post non trovato' })
    postData.id = editId
    postData.date = posts[idx].date
    postData.date_gmt = posts[idx].date_gmt
    postData.guid = posts[idx].guid
    postData.link = posts[idx].link
    posts[idx] = postData
  } else {
    postData.id = getNextId(posts)
    postData.guid = { rendered: `https://www.criselvazzanodentro.it/?p=${postData.id}` }
    postData.link = `https://www.criselvazzanodentro.it/${slug}/`
    posts.unshift(postData)
  }

  await writeJSON('posts', posts)
  res.json({ success: true, post_id: postData.id })
}
