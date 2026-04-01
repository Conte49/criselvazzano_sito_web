import { SignJWT, jwtVerify } from 'jose'
import bcrypt from 'bcryptjs'

const SECRET = new TextEncoder().encode(process.env.JWT_SECRET || 'cri-selvazzano-default-secret-change-me')
const ADMIN_PASSWORD_HASH = process.env.ADMIN_PASSWORD_HASH || '$2y$10$0VcV1r0GRLLoDe7ie.HFtOQxDvJJVz9uLWw1cepCUXdY.PLP0ztc.'

export async function createToken() {
  return new SignJWT({ admin: true })
    .setProtectedHeader({ alg: 'HS256' })
    .setExpirationTime('8h')
    .sign(SECRET)
}

export async function verifyToken(req) {
  const cookie = req.headers.cookie || ''
  const match = cookie.match(/admin_token=([^;]+)/)
  if (!match) return false
  try {
    await jwtVerify(match[1], SECRET)
    return true
  } catch {
    return false
  }
}

export async function verifyPassword(password) {
  return bcrypt.compare(password, ADMIN_PASSWORD_HASH)
}
