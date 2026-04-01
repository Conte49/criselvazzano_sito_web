import { verifyPassword, createToken } from './lib/auth.js'

export default async function handler(req, res) {
  if (req.method !== 'POST') return res.status(405).json({ success: false, message: 'Method not allowed' })

  const { password } = req.body || {}
  if (!password) return res.status(400).json({ success: false, message: 'Password richiesta' })

  const valid = await verifyPassword(password)
  if (!valid) return res.status(401).json({ success: false, message: 'Password errata' })

  const token = await createToken()
  res.setHeader('Set-Cookie', `admin_token=${token}; Path=/; HttpOnly; SameSite=Strict; Max-Age=28800`)
  res.json({ success: true })
}
