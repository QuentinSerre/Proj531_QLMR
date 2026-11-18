const BASE_URL = '/api'

export async function request(path, { method = 'GET', body } = {}) {
  const response = await fetch(BASE_URL + path, {
    method,
    headers: body ? { 'Content-Type': 'application/json' } : {},
    body: body ? JSON.stringify(body) : undefined,
  })

  // 204 = succès sans contenu : pas de JSON à lire
  const data = response.status === 204 ? null : await response.json()

  if (!response.ok) {
    const error = new Error(data?.error ?? 'Erreur serveur')
    error.status = response.status
    throw error
  }

  return data
}