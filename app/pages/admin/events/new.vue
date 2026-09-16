<template>
  <div>
    <div class="page-header">
      <div>
        <NuxtLink to="/admin/events" class="back-link">← Events</NuxtLink>
        <h2>New Event</h2>
      </div>
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
              <input v-model="form.title" type="text" required placeholder="UTSAVA 2026" @input="autoSlug" />
              <span class="help-text">The full event name shown on the event page hero and in the site header.</span>
            </div>
            <div class="form-group">
              <label>Slug <span class="req">*</span></label>
              <input v-model="form.slug" type="text" required placeholder="utsava-2026" @input="slugEdited = true" />
              <span class="help-text">URL-safe identifier — auto-generated from title. Used in the event URL: /events/<strong>{{ form.slug || 'slug' }}</strong>. Cannot be changed after first save.</span>
            </div>
          </div>
          <div class="form-row-2">
            <div class="form-group">
              <label>Kannada Title</label>
              <input v-model="form.kannadaTitle" type="text" placeholder="ಉತ್ಸವ ೨೦೨೬" class="kannada-text" />
              <span class="help-text">Optional. Shown in Kannada script below the main title on the event page.</span>
            </div>
            <div class="form-group">
              <label>Nav Label</label>
              <input v-model="form.navLabel" type="text" placeholder="UTSAVA 2026" />
              <span class="help-text">Short label used in the site navigation menu. If left blank, title is used.</span>
            </div>
          </div>
          <div class="form-row-2">
            <div class="form-group">
              <label>Tag</label>
              <input v-model="form.tag" type="text" placeholder="Featured Event" />
              <span class="help-text">Small badge displayed on the event card and hero, e.g. "Cultural Festival" or "Annual Event".</span>
            </div>
            <div class="form-group">
              <label>Registration Status <span class="req">*</span></label>
              <select v-model="form.registrationStatus" required>
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
              <input v-model="form.date" type="date" required />
              <span class="help-text">Displayed on the event page and used for past-event detection.</span>
            </div>
            <div class="form-group">
              <label>Time</label>
              <input v-model="form.time" type="text" placeholder="10:00 AM – 8:00 PM" />
              <span class="help-text">Free-text time range, e.g. "11:00 AM – 9:00 PM". Leave blank if not yet confirmed.</span>
            </div>
            <div class="form-group">
              <label>Venue</label>
              <input v-model="form.venue" type="text" placeholder="Munich Community Hall" />
              <span class="help-text">Full venue name or address shown on the event page info strip.</span>
            </div>
          </div>
        </div>

        <!-- Images -->
        <div class="form-section">
          <h3 class="section-title">Images</h3>
          <div class="form-group">
            <label>Banner / Hero Image URL</label>
            <input v-model="form.heroImage" type="url" placeholder="https://..." />
            <span class="help-text">Large banner image displayed at the top of the event page (1200×600 or wider recommended). This is the main visual — do not add it to the gallery again.</span>
          </div>
          <div class="form-group">
            <label>Gallery Images <span class="gallery-count-note">({{ galleryUrls.length }}/{{ MAX_GALLERY }})</span></label>
            <div class="gallery-inputs">
              <div v-for="(url, i) in galleryUrls" :key="i" class="gallery-input-row">
                <span class="gallery-num">{{ i + 1 }}</span>
                <input
                  v-model="galleryUrls[i]"
                  type="url"
                  :placeholder="`https://gallery-image-${i + 1}.jpg`"
                  class="gallery-url-input"
                />
                <button type="button" class="gallery-remove-btn" @click="removeGalleryUrl(i)" title="Remove">✕</button>
              </div>
              <button
                v-if="galleryUrls.length < MAX_GALLERY"
                type="button"
                class="gallery-add-btn"
                @click="addGalleryUrl"
              >+ Add image</button>
            </div>
            <span class="help-text">Up to {{ MAX_GALLERY }} photos shown in the image grid below the event description. Paste direct image URLs — one per row. The hero image above is not included here.</span>
          </div>
        </div>

        <!-- Content -->
        <div class="form-section">
          <h3 class="section-title">Content</h3>
          <div class="form-group">
            <label>Content HTML <span class="field-note">(raw HTML)</span></label>
            <textarea v-model="form.contentHtml" rows="10" placeholder="<p>Event description...</p>" class="code-textarea"></textarea>
            <span class="help-text">Enter the event description as raw HTML. Basic tags like &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;/&lt;li&gt;, &lt;br&gt; are supported. No live preview — double-check on the event page after publishing.</span>
          </div>
          <div class="form-group">
            <label>Google Form URL <span class="field-note">(for registration modal)</span></label>
            <input v-model="form.googleFormUrl" type="url" placeholder="https://docs.google.com/forms/..." />
            <span class="help-text">Paste the Google Form embed URL here. When registration is "Open", clicking Register opens this form in a popup. Leave blank if using a custom form (e.g. Ugadi, Food Festival, Utsava).</span>
          </div>
        </div>

        <!-- Settings -->
        <div class="form-section">
          <h3 class="section-title">Display Settings</h3>
          <div class="form-row-2">
            <div class="form-group">
              <label>Nav Order</label>
              <input v-model.number="form.navOrder" type="number" min="0" placeholder="99" />
              <span class="help-text">Controls order in the navigation menu. Lower numbers appear first. Default 99 means it goes at the end.</span>
            </div>
            <div class="form-group flags-group">
              <label class="checkbox-label">
                <input v-model="form.pinToTop" type="checkbox" />
                <span>Pin to top of nav</span>
              </label>
              <span class="checkbox-help">Always shows this event first in navigation, overriding the nav order.</span>
              <label class="checkbox-label" style="margin-top:10px">
                <input v-model="form.past" type="checkbox" />
                <span>Mark as past / concluded</span>
              </label>
              <span class="checkbox-help">Shows a "This event has concluded" banner on the event page. Check this after the event date has passed.</span>
            </div>
          </div>
        </div>

      </div>

      <div v-if="saveError" class="alert alert-error">{{ saveError }}</div>
      <div v-if="saveSuccess" class="alert alert-success">Saved successfully.</div>

      <div class="form-footer">
        <NuxtLink to="/admin/events" class="btn-admin btn-admin-secondary">Cancel</NuxtLink>
        <button type="submit" class="btn-admin btn-admin-primary" :disabled="saving">
          <span v-if="saving" class="spinner"></span>
          {{ saving ? 'Saving…' : 'Save Draft' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
definePageMeta({ layout: 'admin', middleware: 'admin-auth' })

const route  = useRoute()
const { public: { apiBaseUrl } } = useRuntimeConfig()
const base = apiBaseUrl.replace(/\/$/, '')

const MAX_GALLERY = 8

const loadError   = ref('')
const saving      = ref(false)
const saveError   = ref('')
const saveSuccess = ref(false)

const form = reactive({
  slug: '', title: '', kannadaTitle: '', navLabel: '', tag: '',
  date: '', time: '', venue: '',
  heroImage: '', contentHtml: '', googleFormUrl: '',
  registrationStatus: 'coming_soon',
  navOrder: 99, pinToTop: false, past: false,
})

const galleryUrls = ref([''])

function addGalleryUrl() {
  if (galleryUrls.value.length < MAX_GALLERY) galleryUrls.value.push('')
}
function removeGalleryUrl(i) {
  galleryUrls.value.splice(i, 1)
  if (galleryUrls.value.length === 0) galleryUrls.value.push('')
}

let slugEdited = false
function autoSlug() {
  if (slugEdited) return
  form.slug = form.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')
}

async function save() {
  saving.value = true
  saveError.value = ''
  saveSuccess.value = false
  const payload = {
    ...form,
    galleryImages: galleryUrls.value.map(s => s.trim()).filter(Boolean),
  }
  try {
    await useAdminFetch(`${base}/admin/save-event.php`, { method: 'POST', body: { event: payload } })
    saveSuccess.value = true
    navigateTo(`/admin/events/${form.slug}`)
  } catch (e) {
    saveError.value = e?.data?.error || 'Failed to save.'
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
.page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 28px; gap: 16px; flex-wrap: wrap; }
.page-header h2 { font-family: var(--admin-font-family); font-size: 24px; font-weight: 700; margin: 4px 0 0; color: #1A1A1A; }
.back-link { font-size: 13px; color: #6B7280; text-decoration: none; display: inline-block; margin-bottom: 4px; }
.back-link:hover { color: #1A1A1A; }
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
.alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin: 8px 0; }
.alert-error { background: #FEE2E2; color: #991B1B; }
.alert-success { background: #D1FAE5; color: #065F46; }
.spinner { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.4); border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
@media (max-width: 768px) {
  .form-row-2, .form-row-3 { grid-template-columns: 1fr; }
}
</style>
