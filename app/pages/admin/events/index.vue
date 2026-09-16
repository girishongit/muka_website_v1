<template>
  <div>
    <div class="page-header">
      <h2>Events</h2>
      <NuxtLink to="/admin/events/new" class="btn-admin btn-admin-primary">+ New Event</NuxtLink>
    </div>

    <div v-if="loading" class="loading-msg">Loading events…</div>
    <div v-else-if="error" class="alert alert-error">{{ error }}</div>
    <template v-else>

      <!-- Default Events (always present, clone-only) -->
      <section class="event-section">
        <h3 class="section-label">Default Events <span class="count-pill count-pill--core">{{ coreEvents.length }}</span></h3>
        <p class="section-desc">These three events are always present. Click a title to edit details. Slug is fixed — clone to create a new version with a different slug.</p>
        <div class="event-table">
          <div class="event-row event-row--head">
            <span>Title</span><span>Date</span><span>Status</span><span>Actions</span>
          </div>
          <div v-for="e in coreEvents" :key="e.slug" class="event-row">
            <span class="event-title">
              <NuxtLink :to="`/admin/events/${e.slug}`" class="event-title-link">{{ e.title }}</NuxtLink>
              <span v-if="e._stub" class="stub-badge">not in backend yet</span>
            </span>
            <span class="event-date">{{ e.date }}</span>
            <span>
              <span v-if="e.registrationStatus === 'closed'" class="pill pill-past">Closed</span>
              <span v-else-if="e.published" class="pill pill-pub">Published</span>
              <span v-else class="pill pill-draft">Draft</span>
            </span>
            <span class="event-actions">
              <NuxtLink :to="`/admin/events/${e.slug}`" class="action-btn">Edit</NuxtLink>
              <a v-if="!e._stub" :href="`/events/${e.slug}`" target="_blank" class="action-btn">View</a>
              <button v-if="e.published && e.registrationStatus !== 'closed'" class="action-btn action-btn--past" @click="markPast(e.slug, e.title)">Close</button>
              <button class="action-btn" @click="openClone(e)">Clone</button>
            </span>
          </div>
        </div>
      </section>

      <!-- Drafts -->
      <section class="event-section">
        <h3 class="section-label">Drafts <span class="count-pill">{{ drafts.length }}</span></h3>
        <div v-if="!drafts.length" class="empty-msg">No drafts yet.</div>
        <div v-else class="event-table">
          <div class="event-row event-row--head">
            <span>Title</span><span>Date</span><span>Status</span><span>Actions</span>
          </div>
          <div v-for="e in drafts" :key="e.slug" class="event-row">
            <span class="event-title">{{ e.title }}</span>
            <span class="event-date">{{ e.date }}</span>
            <span><span class="pill pill-draft">Draft</span></span>
            <span class="event-actions">
              <NuxtLink :to="`/admin/events/${e.slug}`" class="action-btn">Edit</NuxtLink>
              <button class="action-btn action-btn--publish" @click="publish(e.slug)">Publish</button>
              <button class="action-btn" @click="openClone(e)">Clone</button>
              <button class="action-btn action-btn--icon action-btn--danger" @click="deleteEvent(e.slug)" title="Delete draft">🗑</button>
            </span>
          </div>
        </div>
      </section>

      <!-- Published -->
      <section class="event-section">
        <h3 class="section-label">Published <span class="count-pill count-pill--pub">{{ published.length }}</span></h3>
        <div v-if="!published.length" class="empty-msg">No published events.</div>
        <div v-else class="event-table">
          <div class="event-row event-row--head">
            <span>Title</span><span>Date</span><span>Status</span><span>Actions</span>
          </div>
          <div v-for="e in published" :key="e.slug" class="event-row">
            <span class="event-title">{{ e.title }}</span>
            <span class="event-date">{{ e.date }}</span>
            <span>
              <span v-if="e.registrationStatus === 'closed'" class="pill pill-past">Closed</span>
              <span v-else class="pill pill-pub">Published</span>
            </span>
            <span class="event-actions">
              <a :href="`/events/${e.slug}`" target="_blank" class="action-btn">View</a>
              <button v-if="e.registrationStatus !== 'closed'" class="action-btn action-btn--past" @click="markPast(e.slug, e.title)">Close</button>
              <button class="action-btn" @click="openClone(e)">Clone</button>
              <button class="action-btn action-btn--icon action-btn--danger" @click="deletePublished(e.slug, e.title)" title="Delete published event">🗑</button>
            </span>
          </div>
        </div>
      </section>
    </template>

    <!-- Clone modal -->
    <div v-if="cloneTarget" class="modal-overlay" @click.self="cloneTarget = null">
      <div class="modal">
        <h3>Clone "{{ cloneTarget.title }}"</h3>
        <p>Enter a unique slug for the new draft:</p>
        <input v-model="newSlug" type="text" placeholder="e.g. utsava-2026" class="modal-input" />
        <div v-if="cloneError" class="alert alert-error">{{ cloneError }}</div>
        <div class="modal-actions">
          <button class="btn-admin btn-admin-secondary" @click="cloneTarget = null">Cancel</button>
          <button class="btn-admin btn-admin-primary" :disabled="!newSlug || cloning" @click="doClone">
            {{ cloning ? 'Cloning…' : 'Create Clone' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
definePageMeta({ layout: 'admin', middleware: 'admin-auth' })

const { public: { apiBaseUrl } } = useRuntimeConfig()
const base = apiBaseUrl.replace(/\/$/, '')

const events  = ref([])
const loading = ref(true)
const error   = ref('')

const CORE_SLUGS = ['ugadi', 'food-festival', 'utsava']
const CORE_DEFAULTS = {
  'ugadi':        { slug: 'ugadi',        title: 'Ugadi',                   date: '', published: true, past: false },
  'food-festival':{ slug: 'food-festival', title: 'Karnataka Food Festival', date: '', published: true, past: false },
  'utsava':       { slug: 'utsava',        title: 'UTSAVA',                  date: '', published: true, past: false },
}

const drafts    = computed(() => events.value.filter(e => !e.published && !CORE_SLUGS.includes(e.slug)))
const published = computed(() => events.value.filter(e => e.published && !CORE_SLUGS.includes(e.slug)))
const coreEvents = computed(() => {
  const map = {}
  events.value.forEach(e => { if (CORE_SLUGS.includes(e.slug)) map[e.slug] = e })
  return CORE_SLUGS.map(s => map[s] ?? { ...CORE_DEFAULTS[s], _stub: true })
})

async function fetchEvents() {
  loading.value = true
  try {
    events.value = await useAdminFetch(`${base}/admin/get-events.php`)
  } catch (e) {
    if (e.status === 401) { navigateTo('/admin/login'); return }
    error.value = 'Failed to load events.'
  } finally {
    loading.value = false
  }
}

async function publish(slug) {
  if (!confirm(`Publish "${slug}"? This cannot be undone.`)) return
  try {
    await useAdminFetch(`${base}/admin/publish-event.php`, { method: 'POST', body: { slug } })
    await fetchEvents()
  } catch (e) {
    alert(e?.data?.error || 'Failed to publish.')
  }
}

async function deleteEvent(slug) {
  if (!confirm(`Delete draft "${slug}"?`)) return
  try {
    await useAdminFetch(`${base}/admin/delete-event.php`, { method: 'POST', body: { slug } })
    await fetchEvents()
  } catch (e) {
    alert(e?.data?.error || 'Failed to delete.')
  }
}

async function markPast(slug, title) {
  if (!confirm(`Close registration for "${title}"? This will show a banner on the event page.`)) return
  try {
    await useAdminFetch(`${base}/admin/mark-past.php`, { method: 'POST', body: { slug } })
    await fetchEvents()
  } catch (e) {
    alert(e?.data?.error || 'Failed to close registration.')
  }
}

async function deletePublished(slug, title) {
  const input = prompt(`DELETE published event "${title}"?\n\nThis event is live on the site. Deleting it will remove it immediately.\n\nType DELETE to confirm.`)
  if (input !== 'DELETE') return
  try {
    await useAdminFetch(`${base}/admin/delete-event.php`, { method: 'POST', body: { slug } })
    await fetchEvents()
  } catch (e) {
    alert(e?.data?.error || 'Failed to delete.')
  }
}


const cloneTarget = ref(null)
const newSlug     = ref('')
const cloning     = ref(false)
const cloneError  = ref('')

function openClone(e) {
  cloneTarget.value = e
  newSlug.value = e.slug + '-copy'
  cloneError.value = ''
}

async function doClone() {
  cloning.value = true
  cloneError.value = ''
  try {
    const res = await useAdminFetch(`${base}/admin/clone-event.php`, {
      method: 'POST',
      body: { slug: cloneTarget.value.slug, newSlug: newSlug.value }
    })
    cloneTarget.value = null
    await fetchEvents()
    navigateTo(`/admin/events/${res.newSlug}`)
  } catch (e) {
    cloneError.value = e?.data?.error || 'Failed to clone.'
  } finally {
    cloning.value = false
  }
}

onMounted(fetchEvents)
</script>

<style scoped>
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; }
.page-header h2 { font-family: var(--admin-font-family); font-size: 26px; font-weight: 700; margin: 0; color: #1A1A1A; }
.event-section { margin-bottom: 40px; }
.section-label { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #6B7280; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
.count-pill { background: #E5E7EB; color: #374151; padding: 2px 8px; border-radius: 20px; font-size: 12px; }
.count-pill--pub { background: #D1FAE5; color: #065F46; }
.count-pill--core { background: #EDE9FE; color: #5B21B6; }
.section-desc { font-size: 12px; color: #9CA3AF; margin: -6px 0 12px; line-height: 1.5; }
.empty-msg { font-size: 14px; color: #9CA3AF; padding: 20px 0; }
.event-table { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
.event-row {
  display: grid;
  grid-template-columns: 2fr 1fr 100px 1fr;
  gap: 12px;
  padding: 14px 20px;
  align-items: center;
  border-bottom: 1px solid #F3F4F6;
  font-size: 14px;
}
.event-row:last-child { border-bottom: none; }
.event-row--head { background: #F9FAFB; font-size: 12px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.05em; }
.event-title { font-weight: 600; color: #1A1A1A; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.event-title-link { color: #1A1A1A; text-decoration: none; font-weight: 600; }
.event-title-link:hover { color: #B91C1C; text-decoration: underline; }
.stub-badge { font-size: 10px; font-weight: 600; background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; padding: 1px 7px; border-radius: 20px; white-space: nowrap; }
.event-date { color: #6B7280; }
.pill { padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.pill-draft { background: #FEF3C7; color: #92400E; }
.pill-pub { background: #D1FAE5; color: #065F46; }
.pill-past { background: #FEF3C7; color: #92400E; }
.event-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.action-btn {
  padding: 5px 12px; border-radius: 6px; border: 1px solid #E5E7EB;
  background: #fff; color: #374151; font-size: 12px; font-weight: 600;
  cursor: pointer; text-decoration: none; transition: all 0.15s;
  font-family: 'Manrope', sans-serif;
}
.action-btn:hover { background: #F9FAFB; border-color: #9CA3AF; }
.action-btn--icon {
  padding: 5px 8px;
  min-width: 30px;
  font-size: 14px;
}
.action-btn--publish:hover { background: #D1FAE5; }
.action-btn--past { border-color: #D97706; color: #D97706; }
.action-btn--past:hover { background: #FEF3C7; }
.action-btn--danger { border-color: #EF4444; color: #EF4444; }
.action-btn--danger:hover { background: #FEE2E2; }
.loading-msg { color: #6B7280; font-size: 14px; }
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 20px; }
.modal { background: #fff; border-radius: 16px; padding: 32px; width: 100%; max-width: 440px; }
.modal h3 { font-family: var(--admin-font-family); font-size: 20px; font-weight: 700; margin: 0 0 8px; }
.modal p { font-size: 14px; color: #6B7280; margin-bottom: 16px; }
.modal-input { width: 100%; padding: 10px 14px; border: 1.5px solid #E5E7EB; border-radius: 8px; font-size: 15px; font-family: 'Manrope', sans-serif; outline: none; }
.modal-input:focus { border-color: #B91C1C; }
.modal-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 20px; }
.btn-admin { padding: 10px 20px; border-radius: 8px; border: none; font-size: 14px; font-weight: 600; font-family: 'Manrope', sans-serif; cursor: pointer; transition: all 0.15s; }
.btn-admin-primary { background: #B91C1C; color: #fff; }
.btn-admin-primary:hover:not(:disabled) { background: #991B1B; }
.btn-admin-primary:disabled { opacity: 0.6; cursor: not-allowed; }
.btn-admin-secondary { background: #F3F4F6; color: #374151; }
.btn-admin-secondary:hover { background: #E5E7EB; }
.alert { padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-top: 12px; }
.alert-error { background: #FEE2E2; color: #991B1B; }
</style>
