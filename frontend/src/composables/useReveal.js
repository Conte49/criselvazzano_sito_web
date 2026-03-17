/**
 * Scroll-reveal via Intersection Observer.
 * Aggiunge la classe .visible agli elementi .reveal quando entrano nel viewport.
 * Rispetta prefers-reduced-motion automaticamente (via CSS).
 */
export function useReveal() {
  let observer = null

  function init(rootEl) {
    if (typeof IntersectionObserver === 'undefined') {
      // Fallback: mostra tutto subito
      rootEl.querySelectorAll('.reveal').forEach(el => el.classList.add('visible'))
      return
    }

    observer = new IntersectionObserver(
      (entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible')
            observer.unobserve(entry.target)
          }
        })
      },
      { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
    )

    rootEl.querySelectorAll('.reveal').forEach(el => observer.observe(el))
  }

  function destroy() {
    if (observer) {
      observer.disconnect()
      observer = null
    }
  }

  return { init, destroy }
}
