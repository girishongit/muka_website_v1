/**
 * Fetches live event data for a dedicated event page and merges it over
 * the page's hardcoded defaults. The defaults are rendered immediately
 * (good for SEO / no flicker on first load); live data refreshes them
 * once the API responds.
 *
 * Usage:
 *   const { liveData, loading } = useEventData('utsava-2025', defaults)
 *   // liveData is a computed ref — use it in the template
 *   // loading is true while the background fetch is in progress
 */
export function useEventData(slug, defaults) {
  const { public: { apiBaseUrl } } = useRuntimeConfig()
  const loading = ref(false)
  const liveData = ref({ ...defaults })

  // Determine "past" status: either the JSON says so, or the event date
  // has already passed according to the client clock.
  function isPast(data) {
    if (data.past) return true
    if (!data.date) return false
    return new Date(data.date) < new Date(new Date().toDateString())
  }

  // Resolve effective registrationStatus:
  // If the event is past and status wasn't already "closed", override it.
  function resolveStatus(data) {
    if (isPast(data) && data.registrationStatus !== 'closed') {
      return 'closed'
    }
    return data.registrationStatus
  }

  onMounted(async () => {
    loading.value = true
    try {
      const url = `${apiBaseUrl.replace(/\/$/, '')}/events.php?slug=${slug}`
      const remote = await $fetch(url)
      // Merge: remote wins for every key it provides
      liveData.value = {
        ...defaults,
        ...remote,
        registrationStatus: resolveStatus(remote),
        past: isPast(remote),
      }
    } catch {
      // API unavailable — keep defaults, still apply past-event logic
      liveData.value = {
        ...defaults,
        registrationStatus: resolveStatus(defaults),
        past: isPast(defaults),
      }
    } finally {
      loading.value = false
    }
  })

  return { liveData, loading }
}
