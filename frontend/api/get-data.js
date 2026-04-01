import { readJSON } from './lib/data.js'

export default async function handler(req, res) {
  res.setHeader('Access-Control-Allow-Origin', '*')
  const type = req.query.type === 'media' ? 'media' : 'posts'
  const data = await readJSON(type)
  res.json(data)
}
