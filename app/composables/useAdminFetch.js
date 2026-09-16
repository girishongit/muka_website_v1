/**
 * Thin wrapper around $fetch that always sends cookies cross-origin.
 * Use for all admin API calls so PHP sessions work when the dev server
 * (localhost:3000) calls the remote PHP host.
 */
export function useAdminFetch(url, opts = {}) {
  return $fetch(url, { credentials: 'include', ...opts })
}
