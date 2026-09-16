export default defineNuxtRouteMiddleware((to) => {
  // Only runs client-side — admin pages are not SSR
  if (!import.meta.client) return

  // Login page is always accessible
  if (to.path === '/admin/login' || !to.path.startsWith('/admin')) return

  // The session lives in an HttpOnly cookie — we can't read it directly.
  // Each admin page makes a fetch on mount; if it gets 401 it navigates to login.
  // This middleware handles the initial redirect: if there is no hint cookie at all
  // (set client-side on successful login), send to login immediately.
  const hint = useCookie('admin_session_hint')
  if (!hint.value) {
    return navigateTo('/admin/login', { replace: true })
  }
})
