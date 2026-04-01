import { verifyToken } from './lib/auth.js'

const rateLimits = new Map()

export default async function handler(req, res) {
  if (req.method !== 'POST') return res.status(405).json({ success: false, message: 'Method not allowed' })

  const authenticated = await verifyToken(req)
  if (!authenticated) return res.status(401).json({ success: false, message: 'Non autorizzato' })

  const { text, type } = req.body || {}
  if (!text) return res.status(400).json({ success: false, message: 'Testo mancante' })
  if (text.length > 5000) return res.status(400).json({ success: false, message: 'Testo troppo lungo' })

  // Rate limiting (per IP)
  const ip = req.headers['x-forwarded-for'] || 'unknown'
  const now = Date.now()
  const limit = rateLimits.get(ip) || { count: 0, reset: now + 60000 }
  if (now > limit.reset) { limit.count = 0; limit.reset = now + 60000 }
  limit.count++
  rateLimits.set(ip, limit)
  if (limit.count > 10) return res.status(429).json({ success: false, message: 'Troppi tentativi, riprova tra un minuto' })

  const apiKey = process.env.OPENAI_API_KEY
  if (!apiKey) return res.status(500).json({ success: false, message: 'Chiave API non configurata' })

  const prompt = type === 'title'
    ? `Migliora questo titolo per una news della Croce Rossa Italiana rendendolo più accattivante e professionale, mantieni lo stesso significato. Rispondi SOLO con il titolo migliorato, senza virgolette o spiegazioni:\n\n${text}`
    : `Migliora questo testo per una news della Croce Rossa Italiana rendendolo più chiaro, professionale e coinvolgente. Mantieni il tono istituzionale ma umano. Correggi eventuali errori grammaticali. Rispondi SOLO con il testo migliorato:\n\n${text}`

  try {
    const response = await fetch('https://api.openai.com/v1/chat/completions', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Authorization: `Bearer ${apiKey}` },
      body: JSON.stringify({
        model: 'gpt-3.5-turbo',
        messages: [
          { role: 'system', content: 'Sei un assistente che aiuta a migliorare i testi per la Croce Rossa Italiana. Scrivi in italiano in modo chiaro, professionale e coinvolgente.' },
          { role: 'user', content: prompt }
        ],
        temperature: 0.7,
        max_tokens: 500
      })
    })

    if (!response.ok) return res.status(502).json({ success: false, message: 'Errore API OpenAI' })

    const result = await response.json()
    const improvedText = result.choices?.[0]?.message?.content?.trim() || ''
    res.json({ success: true, text: improvedText })
  } catch {
    res.status(502).json({ success: false, message: 'Errore API OpenAI' })
  }
}
