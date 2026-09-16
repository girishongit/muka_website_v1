<template>
  <div>
    <div class="page-header">
      <div>
        <NuxtLink to="/admin/events" class="back-link">← Events</NuxtLink>
        <h2>{{ form.title || route.params.slug }}</h2>
      </div>
      <div class="header-actions">
        <button class="btn-admin btn-admin-secondary" @click="cloneDialog = true">Clone</button>
        <button v-if="!isCoreEvent && !isPublished" class="btn-admin btn-admin-publish" @click="publishEvent">Publish</button>
        <button v-if="isPublished && !form.past" class="btn-admin btn-admin-past" @click="markPast">Mark as Past</button>
        <button v-if="!isCoreEvent" class="btn-admin btn-admin-danger" @click="deleteEvent">Delete</button>
      </div>
    </div>

    <div v-if="isPublished && !isCoreEvent" class="published-notice">
      <span class="notice-icon">🔒</span>
      This event is published. Fields are read-only — clone it to create a new editable version.
    </div>
    <div v-else-if="isCoreEvent && !form.date" class="stub-notice">
      <span class="notice-icon">💡</span>
      This default event has no backend data yet. Fill in the details below and save — it will be created in <code>events.json</code>.
    </div>

    <div v-if="loadError" class="alert alert-error">{{ loadError }}</div>

    <form v-else @submit.prevent="save" class="event-form">
      <div class="form-grid">

        <!-- Basic Info -->
        <div class="form-section">
          <h3 class="section-title">Basic Info</h3>
          <div class="form-row-2">
            <div class="form-group">
              <label>Title <span class="req">*</span></label>
              <input v-model="form.title" type="text" required placeholder="UTSAVA 2026" :readonly="isReadOnly" :class="{ readonly: isReadOnly }" />
              <span class="help-text">The full event name shown on the event page hero and in the site header.</span>
            </div>
            <div class="form-group">
              <label>Slug</label>
              <input v-model="form.slug" type="text" readonly class="readonly" />
              <span class="help-text">URL identifier — locked after creation. Event URL: /events/<strong>{{ form.slug }}</strong></span>
            </div>
          </div>
          <div class="form-row-2">
            <div class="form-group">
              <label>Kannada Title</label>
              <input v-model="form.kannadaTitle" type="text" placeholder="ಉತ್ಸವ ೨೦೨೬" class="kannada-text" :readonly="isReadOnly" :class="{ readonly: isReadOnly }" />
              <span class="help-text">Optional. Shown in Kannada script below the main title on the event page.</span>
            </div>
            <div class="form-group">
              <label>Nav Label</label>
              <input v-model="form.navLabel" type="text" placeholder="UTSAVA 2026" :readonly="isReadOnly || isCoreEvent" :class="{ readonly: isReadOnly || isCoreEvent }" />
              <span class="help-text">Short label used in the site navigation menu. If left blank, title is used.</span>
            </div>
          </div>
          <div class="form-row-2">
            <div class="form-group">
              <label>Tag</label>
              <input v-model="form.tag" type="text" placeholder="Featured Event" :readonly="isReadOnly" :class="{ readonly: isReadOnly }" />
              <span class="help-text">Small badge displayed on the event card and hero, e.g. "Cultural Festival" or "Annual Event".</span>
            </div>
            <div class="form-group">
              <label>Registration Status</label>
              <select v-model="form.registrationStatus" :disabled="isReadOnly">
                <option value="coming_soon">Coming Soon</option>
                <option value="open">Open</option>
                <option value="closed">Closed</option>
              </select>
              <span class="help-text"><strong>Coming Soon</strong> — shows teaser. <strong>Open</strong> — shows Register button. <strong>Closed</strong> — shows "Registration Closed" message.</span>
            </div>
          </div>
        </div>

        <!-- Date & Venue -->
        <div class="form-section">
          <h3 class="section-title">Date & Venue</h3>
          <div class="form-row-3">
            <div class="form-group">
              <label>Date <span class="req">*</span></label>
              <input v-model="form.date" type="date" required :readonly="isReadOnly" :class="{ readonly: isReadOnly }" />
              <span class="help-text">Displayed on the event page and used for past-event detection.</span>
            </div>
            <div class="form-group">
              <label>Time</label>
              <input v-model="form.time" type="text" placeholder="10:00 AM – 8:00 PM" :readonly="isReadOnly" :class="{ readonly: isReadOnly }" />
              <span class="help-text">Free-text time range. Leave blank if not yet confirmed.</span>
            </div>
            <div class="form-group">
              <label>Venue</label>
              <input v-model="form.venue" type="text" placeholder="Munich Community Hall" :readonly="isReadOnly" :class="{ readonly: isReadOnly }" />
              <span class="help-text">Full venue name shown on the event page info strip.</span>
            </div>
          </div>
        </div>

        <!-- Images -->
        <div class="form-section">
          <h3 class="section-title">Images</h3>
          <div class="form-group">
            <label>Banner / Hero Image URL</label>
            <input v-model="form.heroImage" type="url" placeholder="https://..." :readonly="isReadOnly" :class="{ readonly: isReadOnly }" />
            <span class="help-text">Large banner image displayed at the top of the event page (1200×600 or wider recommended). This is the main visual — do not add it to the gallery again.</span>
          </div>
          <div class="form-group">
            <label>Gallery Images <span class="field-note">(one URL per line, max 8)</span></label>
            <textarea v-model="galleryText" rows="5" placeholder="https://image1.jpg&#10;https://image2.jpg" :readonly="isReadOnly" :class="{ readonly: isReadOnly }"></textarea>
            <span class="help-text">Up to 8 photos shown in the image grid below the event description. One URL per line. The hero/banner image above is not included here.</span>
          </div>
        </div>

        <!-- Content -->
        <div class="form-section">
          <h3 class="section-title">Content</h3>
          <div class="form-group">
            <label>Content HTML <span class="field-note">(raw HTML)</span></label>
            <textarea v-model="form.contentHtml" rows="10" placeholder="<p>Event description...</p>" class="code-textarea" :readonly="isReadOnly" :class="{ readonly: isReadOnly }"></textarea>
            <span class="help-text">Enter the event description as raw HTML. Basic tags like &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;/&lt;li&gt;, &lt;br&gt; are supported. No live preview — double-check on the event page after publishing.</span>
          </div>
          <div v-if="!isCoreEvent" class="form-group">
            <label>Google Form URL <span class="field-note">(for registration modal)</span></label>
            <input v-model="form.googleFormUrl" type="url" placeholder="https://docs.google.com/forms/..." :readonly="isReadOnly" :class="{ readonly: isReadOnly }" />
            <span class="help-text">Paste the Google Form embed URL here. When registration is "Open", clicking Register opens this form in a popup.</span>
          </div>
          <div v-else class="info-box">
            <span class="info-icon">ℹ️</span>
            This is a core event ({{ form.slug }}) — it uses a built-in custom registration form. No Google Form URL needed.
          </div>
        </div>

        <!-- Ticket Key (core events only) -->
        <div v-if="isCoreEvent" class="form-section">
          <h3 class="section-title">Ticket Key</h3>
          <div class="form-group">
            <label>Ticket Key</label>
            <input v-model="form.ticketKey" type="text" placeholder="utsava_2026" class="tc-id-input" />
            <span class="help-text">Key from the <NuxtLink to="/admin/event-tickets">Event Tickets</NuxtLink> page. Leave blank for no ticket sales.</span>
          </div>
        </div>

        <!-- Settings -->
        <div class="form-section">
          <h3 class="section-title">Display Settings</h3>
          <div class="form-row-2">
            <div class="form-group">
              <label>Nav Order</label>
              <input v-model.number="form.navOrder" type="number" min="0" placeholder="99" :readonly="isReadOnly" :class="{ readonly: isReadOnly }" />
              <span class="help-text">Controls order in the navigation menu. Lower numbers appear first. Default 99 means it goes at the end.</span>
            </div>
            <div class="form-group flags-group">
              <label class="checkbox-label">
                <input v-model="form.pinToTop" type="checkbox" :disabled="isReadOnly" />
                <span>Pin to top of nav</span>
              </label>
              <span class="checkbox-help">Always shows this event first in navigation, overriding the nav order.</span>
              <label class="checkbox-label" style="margin-top:10px">
                <input v-model="form.past" type="checkbox" :disabled="isReadOnly && !isCoreEvent" />
                <span>Mark as past / concluded</span>
              </label>
              <span class="checkbox-help">Shows a "This event has concluded" banner on the event page. Check this after the event date has passed.</span>
            </div>
          </div>
        </div>

      </div>

      <div v-if="saveError" class="alert alert-error">{{ saveError }}</div>
      <div v-if="saveSuccess" class="alert alert-success">Saved successfully.</div>

      <div v-if="!isReadOnly || isCoreEvent" class="form-footer">
        <NuxtLink to="/admin/events" class="btn-admin btn-admin-secondary">Cancel</NuxtLink>
        <button type="submit" class="btn-admin btn-admin-primary" :disabled="saving">
          <span v-if="saving" class="spinner"></span>
          {{ saving ? 'Saving…' : 'Save' }}
        </button>
      </div>
    </form>

    <!-- Clone modal -->
    <div v-if="cloneDialog" class="modal-overlay" @click.self="cloneDialog = false">
      <div class="modal">
        <h3>Clone this event</h3>
        <p>Enter a unique slug for the new draft:</p>
        <input v-model="cloneSlug" type="text" class="modal-input" placeholder="e.g. utsava-2027" />
        <div v-if="cloneError" class="alert alert-error">{{ cloneError }}</div>
        <div class="modal-actions">
          <button class="btn-admin btn-admin-secondary" @click="cloneDialog = false">Cancel</button>
          <button class="btn-admin btn-admin-primary" :disabled="!cloneSlug || cloning" @click="doClone">
            {{ cloning ? 'Cloning…' : 'Clone' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
definePageMeta({ layout: 'admin', middleware: 'admin-auth' })

const route = useRoute()
const slug  = route.params.slug
const { public: { apiBaseUrl } } = useRuntimeConfig()
const base = apiBaseUrl.replace(/\/$/, '')

const CORE_DEFAULTS = {
  'ugadi': {
    slug: 'ugadi', title: 'Ugadi', kannadaTitle: 'ಯುಗಾದಿ',
    navLabel: 'Ugadi', tag: 'Cultural Festival',
    date: '', time: '', venue: '', heroImage: '', contentHtml: '',
    registrationStatus: 'coming_soon', navOrder: 1,
    pinToTop: false, past: false, published: true,
  },
  'food-festival': {
    slug: 'food-festival', title: 'Karnataka Food Festival', kannadaTitle: 'ಕರ್ನಾಟಕ ಆಹಾರ ಉತ್ಸವ',
    navLabel: 'Food Festival', tag: 'Food & Culture',
    date: '', time: '', venue: '', heroImage: '', contentHtml: '',
    registrationStatus: 'coming_soon', navOrder: 2,
    pinToTop: false, past: false, published: true,
  },
  'utsava': {
    slug: 'utsava', title: 'UTSAVA', kannadaTitle: 'ಉತ್ಸವ',
    navLabel: 'UTSAVA', tag: 'Annual Gala',
    date: '', time: '', venue: '', heroImage: '', contentHtml: '',
    registrationStatus: 'coming_soon', navOrder: 3,
    pinToTop: false, past: false, published: true,
  },
}

const CORE_SLUGS = Object.keys(CORE_DEFAULTS)

const loadError   = ref('')
const saving      = ref(false)
const saveError   = ref('')
const saveSuccess = ref(false)
const cloneDialog = ref(false)
const cloneSlug   = ref('')
const cloning     = ref(false)
const cloneError  = ref('')

const form = reactive({
  slug: '', title: '', kannadaTitle: '', navLabel: '', tag: '',
  date: '', time: '', venue: '', heroImage: '', contentHtml: '',
  googleFormUrl: '', registrationStatus: 'coming_soon',
  navOrder: 99, pinToTop: false, past: false,
  published: false,
  ticketKey: '',
})

const galleryText = ref('')

const isCoreEvent = computed(() => CORE_SLUGS.includes(form.slug || slug))
const isPublished = computed(() => !!form.published)
// Core events are always editable; published non-core events are read-only
const isReadOnly  = computed(() => isPublished.value && !isCoreEvent.value)

onMounted(async () => {
  try {
    const all = await useAdminFetch(`${base}/admin/get-events.php`)
    const ev  = all.find(e => e.slug === slug)
    if (!ev) {
      // Fall back to hardcoded defaults for core events so the form is pre-filled
      const defaults = CORE_DEFAULTS[slug]
      if (defaults) {
        Object.assign(form, defaults)
        galleryText.value = ''
      } else {
        loadError.value = 'Event not found.'
      }
      cloneSlug.value = slug + '-copy'
      return
    }
    Object.assign(form, ev)
    galleryText.value = (ev.galleryImages || []).join('\n')
    cloneSlug.value   = slug + '-copy'
  } catch (e) {
    if (e.status === 401) { navigateTo('/admin/login'); return }
    loadError.value = 'Failed to load event.'
  }
})

async function save() {
  saving.value = true
  saveError.value = ''
  saveSuccess.value = false
  const payload = {
    ...form,
    galleryImages: galleryText.value.split('\n').map(s => s.trim()).filter(Boolean),
  }
  try {
    await useAdminFetch(`${base}/admin/save-event.php`, { method: 'POST', body: { event: payload } })
    saveSuccess.value = true
    setTimeout(() => { saveSuccess.value = false }, 3000)
  } catch (e) {
    saveError.value = e?.data?.error || 'Failed to save.'
  } finally {
    saving.value = false
  }
}

async function publishEvent() {
  if (!confirm(`Publish "${form.title}"? Published events cannot be edited (use Clone to create a new version).`)) return
  try {
    await useAdminFetch(`${base}/admin/publish-event.php`, { method: 'POST', body: { slug: form.slug } })
    navigateTo('/admin/events')
  } catch (e) {
    alert(e?.data?.error || 'Failed to publish.')
  }
}

async function markPast() {
  if (!confirm(`Mark "${form.title}" as past/concluded? This shows a "concluded" banner on the event page.`)) return
  try {
    await useAdminFetch(`${base}/admin/mark-past.php`, { method: 'POST', body: { slug: form.slug } })
    form.past = true
    saveSuccess.value = true
    setTimeout(() => { saveSuccess.value = false }, 3000)
  } catch (e) {
    alert(e?.data?.error || 'Failed to mark as past.')
  }
}

async function deleteEvent() {
  const confirmMsg = isPublished.value
    ? `DELETE "${form.title}"?\n\nThis event is published and visible on the site. Deleting it will remove it immediately.\n\nType DELETE to confirm.`
    : `Delete draft "${form.title}"?`
  if (isPublished.value) {
    const input = prompt(confirmMsg)
    if (input !== 'DELETE') return
  } else {
    if (!confirm(confirmMsg)) return
  }
  try {
    await useAdminFetch(`${base}/admin/delete-event.php`, { method: 'POST', body: { slug: form.slug } })
    navigateTo('/admin/events')
  } catch (e) {
    alert(e?.data?.error || 'Failed to delete.')
  }
}

async function doClone() {
  cloning.value = true
  cloneError.value = ''
  try {
    const res = await useAdminFetch(`${base}/admin/clone-event.php`, {
      method: 'POST', body: { slug: form.slug, newSlug: cloneSlug.value }
    })
    cloneDialog.value = false
    navigateTo(`/admin/events/${res.newSlug}`)
  } catch (e) {
    cloneError.value = e?.data?.error || 'Failed to clone.'
  } finally {
    cloning.value = false
  }
}
</script>

<style scoped>
.page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 28px; gap: 16px; flex-wrap: wrap; }
.page-header h2 { font-family: var(--admin-font-family); font-size: 24px; font-weight: 700; margin: 4px 0 0; color: #1A1A1A; }
.back-link { font-size: 13px; color: #6B7280; text-decoration: none; display: inline-block; margin-bottom: 4px; }
.back-link:hover { color: #1A1A1A; }
.header-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
.published-notice { background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #92400E; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
.stub-notice { background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #1E40AF; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
.stub-notice code { font-family: monospace; font-size: 12px; background: #DBEAFE; padding: 1px 5px; border-radius: 4px; }
.notice-icon { font-size: 16px; }
.info-box { background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #1D4ED8; display: flex; align-items: flex-start; gap: 8px; }
.info-icon { font-size: 14px; flex-shrink: 0; }
.event-form { display: flex; flex-direction: column; gap: 4px; }
.form-grid { display: flex; flex-direction: column; gap: 4px; }
.form-section { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 1px 4px rgba(0,0,0,0.05); margin-bottom: 16px; }
.section-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #6B7280; margin: 0 0 20px; }
.form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
.form-group { display: flex; flex-direction: column; gap: 5px; margin-bottom: 16px; }
.form-group:last-child { margin-bottom: 0; }
.form-group label { font-size: 13px; font-weight: 600; color: #374151; }
.form-group input, .form-group select, .form-group textarea {
  padding: 10px 14px; border: 1.5px solid #E5E7EB; border-radius: 8px;
  font-size: 14px; font-family: 'Manrope', sans-serif; color: #1A1A1A;
  outline: none; transition: border-color 0.2s;
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #B91C1C; }
.form-group textarea { resize: vertical; line-height: 1.6; }
.code-textarea { font-family: 'Courier New', monospace; font-size: 13px; }
.readonly { background: #F9FAFB !important; color: #6B7280; cursor: not-allowed; }
.help-text { font-size: 12px; color: #9CA3AF; line-height: 1.5; }
.help-text strong { color: #6B7280; }
.field-note { font-size: 11px; color: #9CA3AF; font-weight: 400; margin-left: 4px; }
.req { color: #B91C1C; }
.flags-group { gap: 4px; }
.checkbox-label { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; color: #374151; cursor: pointer; }
.checkbox-label input[type=checkbox] { width: 15px; height: 15px; cursor: pointer; accent-color: #B91C1C; }
.checkbox-help { font-size: 12px; color: #9CA3AF; line-height: 1.4; margin-left: 23px; }
/* Gallery */
.gallery-count-note { font-size: 12px; font-weight: 400; color: #9CA3AF; margin-left: 4px; }
.gallery-inputs { display: flex; flex-direction: column; gap: 8px; }
.gallery-input-row { display: flex; align-items: center; gap: 8px; }
.gallery-num { font-size: 12px; font-weight: 600; color: #9CA3AF; width: 18px; text-align: right; flex-shrink: 0; }
.gallery-url-input { flex: 1; padding: 9px 12px; border: 1.5px solid #E5E7EB; border-radius: 8px; font-size: 13px; font-family: 'Manrope', sans-serif; color: #1A1A1A; outline: none; transition: border-color 0.2s; }
.gallery-url-input:focus { border-color: #B91C1C; }
.gallery-remove-btn { width: 28px; height: 28px; border-radius: 50%; border: 1px solid #E5E7EB; background: #fff; color: #9CA3AF; font-size: 11px; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.15s; }
.gallery-remove-btn:hover { background: #FEE2E2; border-color: #FCA5A5; color: #B91C1C; }
.gallery-add-btn { align-self: flex-start; padding: 7px 14px; border-radius: 7px; border: 1.5px dashed #D1D5DB; background: transparent; color: #6B7280; font-size: 13px; font-family: 'Manrope', sans-serif; font-weight: 600; cursor: pointer; transition: all 0.15s; }
.gallery-add-btn:hover { border-color: #B91C1C; color: #B91C1C; }
.form-footer { display: flex; gap: 12px; justify-content: flex-end; margin-top: 8px; padding: 20px 24px; background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.05); }
.btn-admin { padding: 10px 22px; border-radius: 8px; border: none; font-size: 14px; font-weight: 600; font-family: 'Manrope', sans-serif; cursor: pointer; transition: all 0.15s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
.btn-admin-primary { background: #B91C1C; color: #fff; }
.btn-admin-primary:hover:not(:disabled) { background: #991B1B; }
.btn-admin-primary:disabled { opacity: 0.6; cursor: not-allowed; }
.btn-admin-secondary { background: #F3F4F6; color: #374151; }
.btn-admin-secondary:hover { background: #E5E7EB; }
.btn-admin-publish { background: #059669; color: #fff; }
.btn-admin-publish:hover { background: #047857; }
.btn-admin-past { background: #D97706; color: #fff; }
.btn-admin-past:hover { background: #B45309; }
.btn-admin-danger { background: #FEE2E2; color: #B91C1C; border: 1px solid #FECACA; }
.btn-admin-danger:hover { background: #FECACA; }
.alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin: 8px 0; }
.alert-error { background: #FEE2E2; color: #991B1B; }
.alert-success { background: #D1FAE5; color: #065F46; }
.spinner { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.4); border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 20px; }
.modal { background: #fff; border-radius: 16px; padding: 32px; width: 100%; max-width: 400px; }
.modal h3 { font-family: var(--admin-font-family); font-size: 20px; font-weight: 700; margin: 0 0 8px; }
.modal p { font-size: 14px; color: #6B7280; margin-bottom: 12px; }
.modal-input { width: 100%; padding: 10px 14px; border: 1.5px solid #E5E7EB; border-radius: 8px; font-size: 14px; font-family: 'Manrope', sans-serif; outline: none; }
.modal-input:focus { border-color: #B91C1C; }
.modal-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 20px; }
@media (max-width: 768px) {
  .form-row-2, .form-row-3 { grid-template-columns: 1fr; }
  .header-actions { width: 100%; }
}
.tc-id-input { font-family: monospace; max-width: 240px; padding: 7px 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
.section-desc { color: #666; font-size: 13px; margin: -8px 0 16px; }
</style>
