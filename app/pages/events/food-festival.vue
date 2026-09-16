<template>
  <div>
    <div v-if="liveData.registrationStatus === 'closed'" class="past-banner">
      <span>🍽️ Registration for Karnataka Food Festival 2026 is now closed. Thank you to everyone who joined us!</span>
    </div>

    <section class="event-hero" :style="liveData.heroImage ? `--hero-bg: url('${liveData.heroImage}')` : ''">
      <div class="event-hero-container">
        <span class="tag event-tag-pill animate-item">🍽️ {{ liveData.tag }}</span>
        <h1 class="animate-item delay-1">{{ liveData.title }}</h1>
        <p class="event-kannada kannada-text animate-item delay-2">"{{ liveData.kannadaTitle }}"</p>
        <p class="event-desc animate-item delay-3">A full-day celebration of Karnataka's culinary heritage — food stalls, cooking demos, and cultural performances.</p>

        <div class="event-info animate-item delay-4">
          <div class="event-info-item"><span class="info-icon">📅</span> {{ liveData.date }}</div>
          <div v-if="liveData.time" class="event-info-item"><span class="info-icon">🕙</span> {{ liveData.time }}</div>
          <div v-if="liveData.venue" class="event-info-item"><span class="info-icon">📍</span> {{ liveData.venue }}</div>
        </div>
      </div>
    </section>

    <section class="about-event section">
      <div class="section-container">
        <div class="about-event-grid">
          <div class="about-event-content animate-observe">
            <span class="tag">About the Event</span>
            <div v-html="liveData.contentHtml" class="event-content-html"></div>

            <div class="about-event-buttons">
              <button
                v-if="liveData.registrationStatus === 'open'"
                class="btn btn-primary"
                @click="showDialog = true"
              >Register Now <span aria-hidden="true">→</span></button>
              <button
                v-else-if="liveData.registrationStatus === 'coming_soon'"
                class="btn btn-secondary status-btn"
                disabled
              >Registration Opens Soon</button>
              <button
                v-else
                class="btn btn-secondary status-btn"
                disabled
              >{{ 'Registration Closed' }}</button>
            </div>
          </div>

          <div class="event-images-wrap animate-observe delay-1">
            <template v-if="loading">
              <div class="img-skeleton img-banner-skeleton"></div>
              <div class="gallery-skeleton-row">
                <div class="img-skeleton img-skeleton--small"></div>
                <div class="img-skeleton img-skeleton--small"></div>
              </div>
            </template>
            <template v-else>
              <div v-if="(liveData.galleryImages || []).length" class="event-gallery-grid" :class="`gallery-count-${Math.min((liveData.galleryImages || []).length, 5)}`">
                <img
                  v-for="(img, i) in (liveData.galleryImages || []).slice(0, 5)"
                  :key="i"
                  :src="img"
                  :alt="`Food Festival 2026 gallery ${i + 1}`"
                  class="gallery-img"
                  :class="{ 'gallery-img--full': isFullWidth(i, (liveData.galleryImages || []).length) }"
                />
              </div>
            </template>
          </div>
        </div>
      </div>
    </section>

    <Teleport to="body">
      <div class="dialog-overlay" :class="{ active: showDialog }">
        <div class="dialog" role="dialog" aria-modal="true" aria-label="Register for Food Festival 2026">
          <div class="dialog-header">
            <div>
              <h3>Register for Food Festival 2026</h3>
              <p class="dialog-kannada kannada-text">ಆಹಾರ ಉತ್ಸವ ೨೦೨೬ ಗೆ ನೋಂದಣಿ</p>
            </div>
            <button class="dialog-close" @click="showDialog = false" aria-label="Close dialog">✕</button>
          </div>
          <div class="dialog-body">
            <div v-if="formSuccess" class="alert alert-success">Thank you for registering! We look forward to celebrating Karnataka food with you.</div>
            <div v-if="formError" class="alert alert-error">There was an error. Please try again or email us directly.</div>

            <form v-if="!formSuccess" @submit.prevent="submitForm">
              <div class="form-row">
                <div class="form-group">
                  <label>First Name <span class="required">*</span></label>
                  <input type="text" v-model="form.firstName" required placeholder="Enter your first name" />
                </div>
                <div class="form-group">
                  <label>Last Name <span class="required">*</span></label>
                  <input type="text" v-model="form.lastName" required placeholder="Enter your last name" />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>Email Address <span class="required">*</span></label>
                  <input type="email" v-model="form.email" required placeholder="your@email.com" />
                </div>
                <div class="form-group">
                  <label>Phone Number <span class="required">*</span></label>
                  <input type="tel" v-model="form.phone" required placeholder="+49 123 456 789" />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>Number of Adults <span class="required">*</span></label>
                  <select v-model="form.adults" required>
                    <option value="1">1</option><option value="2">2</option>
                    <option value="3">3</option><option value="4">4</option>
                    <option value="5">5+</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Number of Children</label>
                  <select v-model="form.children">
                    <option value="0">0</option><option value="1">1</option>
                    <option value="2">2</option><option value="3">3</option>
                    <option value="4">4+</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label>Dietary Requirements</label>
                <input type="text" v-model="form.dietary" placeholder="Vegetarian, Vegan, Allergies, etc." />
              </div>
              <NuxtTurnstile v-model="turnstileToken" class="form-turnstile" />
              <button type="submit" class="btn btn-primary btn-full" :disabled="submitting || !turnstileToken">
                {{ submitting ? 'Submitting…' : 'Complete Registration' }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Karnataka Food Festival 2026 | Munich Kannadigaru',
  description: "Savour the flavours of Karnataka at Munich Kannadigaru's Food Festival 2026 — Bisi Bele Bath, Masala Dosa, Mysore Pak and more.",
  ogTitle: 'Karnataka Food Festival 2026 — Munich',
  ogDescription: "A day-long celebration of Karnataka cuisine in Munich — cooking demos, food stalls, and cultural performances.",
  ogImage: 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=1200&q=80',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/events/food-festival-2026',
  twitterCard: 'summary_large_image',
  twitterTitle: 'Karnataka Food Festival 2026 — Munich Kannadigaru',
  twitterDescription: 'Karnataka food, culture, and community in Munich. 14 June 2026.',
})

useHead({
  script: [{
    type: 'application/ld+json',
    children: JSON.stringify({
      '@context': 'https://schema.org',
      '@type': 'Event',
      name: 'Karnataka Food Festival 2026',
      description: "A celebration of Karnataka's culinary traditions — food stalls, cooking demonstrations, and cultural performances.",
      startDate: '2026-06-14T12:00:00+02:00',
      endDate: '2026-06-14T20:00:00+02:00',
      location: { '@type': 'Place', name: 'Munich City Garden', address: { '@type': 'PostalAddress', addressLocality: 'Munich', addressCountry: 'DE' } },
      organizer: { '@type': 'Organization', name: 'Munich Kannadigaru', url: 'https://munichkannadigaru.org' },
      image: 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=1200&q=80',
      eventStatus: 'https://schema.org/EventScheduled',
      eventAttendanceMode: 'https://schema.org/OfflineEventAttendanceMode',
    })
  }]
})

const DEFAULTS = {
  slug: 'food-festival-2026',
  title: 'Karnataka Food Festival 2026',
  kannadaTitle: 'ಕರ್ನಾಟಕ ಆಹಾರ ಉತ್ಸವ',
  tag: 'Food & Culture',
  date: '2026-06-14',
  time: '12:00 PM – 8:00 PM',
  venue: 'Munich City Garden',
  heroImage: 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=1200&q=80',
  galleryImages: [
    'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=600&q=80',
    'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600&q=80',
  ],
  contentHtml: '<p>A celebration of Karnataka\'s rich culinary tradition — from Bisi Bele Bath and Masala Dosa to Mysore Pak and Filter Coffee. Experience the diversity of Karnataka cuisine prepared by families from across the community.</p><p>Cooking demonstrations, food stalls, and cultural performances make this a full-day family event.</p>',
  registrationStatus: 'coming_soon',
  past: false,
}

const { liveData, loading } = useEventData('food-festival-2026', DEFAULTS)

const { public: { apiBaseUrl } } = useRuntimeConfig()
const foodFestApiUrl = `${apiBaseUrl.replace(/\/$/, '')}/food-festival-register.php`

const showDialog = ref(false)
const submitting = ref(false)
const formSuccess = ref(false)
const formError = ref(false)
const turnstileToken = ref('')

const form = reactive({
  firstName: '', lastName: '', email: '', phone: '',
  adults: '1', children: '0', dietary: ''
})

async function submitForm() {
  submitting.value = true
  formSuccess.value = false
  formError.value = false
  try {
    await $fetch(foodFestApiUrl, {
      method: 'POST',
      body: { ...form, turnstileToken: turnstileToken.value }
    })
    formSuccess.value = true
    turnstileToken.value = ''
    Object.assign(form, { firstName: '', lastName: '', email: '', phone: '', adults: '1', children: '0', dietary: '' })
  } catch {
    formError.value = true
    turnstileToken.value = ''
  } finally {
    submitting.value = false
  }
}

onMounted(() => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); observer.unobserve(e.target) } })
  }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' })
  document.querySelectorAll('.animate-observe').forEach(el => observer.observe(el))
  document.addEventListener('keydown', e => { if (e.key === 'Escape') showDialog.value = false })
})

function isFullWidth(index, total) {
  if (total <= 2) return true
  if (total === 3) return index === 0
  if (total === 4) return index === 0 || index === 3
  return index === 0
}
</script>

<style scoped>
.past-banner {
  background: var(--gold);
  color: #1A1A1A;
  text-align: center;
  padding: 18px 24px;
  font-size: 16px;
  font-weight: 600;
  font-family: 'Manrope', sans-serif;
  letter-spacing: 0.02em;
}
.animate-item { opacity: 0; transform: translateY(24px); animation: fadeUp 0.7s ease forwards; }
.delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; }
.delay-3 { animation-delay: 0.3s; } .delay-4 { animation-delay: 0.4s; }
@keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
.animate-observe { opacity: 0; transform: translateY(28px); transition: opacity 0.7s ease, transform 0.7s ease; }
.animate-observe.delay-1 { transition-delay: 0.12s; }
.animate-observe.is-visible { opacity: 1; transform: translateY(0); }
@media (prefers-reduced-motion: reduce) {
  .animate-item, .animate-observe { animation: none !important; transition: none !important; opacity: 1 !important; transform: none !important; }
}
.event-hero {
  background: linear-gradient(135deg, #92400E 0%, #78350F 100%);
  color: var(--white); padding: 100px 0; position: relative; overflow: hidden; text-align: center;
}
.event-hero[style*="--hero-bg"] {
  background: none;
}
.event-hero::before {
  content: ''; position: absolute; inset: 0;
  background-image: var(--hero-bg); background-size: cover; background-position: center;
  z-index: 0;
}
.event-hero-container { max-width: 1280px; margin: 0 auto; padding: 0 40px; position: relative; z-index: 1; }
.event-tag-pill { background: rgba(255,255,255,0.15); color: var(--white); margin-bottom: 25px; }
.event-hero h1 { font-size: clamp(36px, 5vw, 68px); font-family: 'Playfair Display', serif; margin-bottom: 15px; color: var(--white); }
.event-kannada { font-size: clamp(22px, 3.5vw, 32px); color: var(--gold); margin-bottom: 25px; display: block; }
.event-desc { font-size: 18px; opacity: 0.9; max-width: 600px; margin: 0 auto 40px; color: var(--white); }
.event-info { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; margin-top: 40px; }
.event-info-item { background: rgba(255,255,255,0.1); padding: 14px 22px; border-radius: 12px; display: flex; align-items: center; gap: 10px; font-size: 15px; color: var(--white); }
.info-icon { font-size: 16px; }
.about-event { background: var(--cream); }
.about-event-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: start; }
.about-event-content .tag { margin-bottom: 20px; display: inline-block; }
.event-content-html :deep(p) { color: var(--text-light); font-size: 16px; line-height: 1.8; margin-bottom: 18px; }
.about-event-buttons { display: flex; gap: 20px; margin-top: 35px; flex-wrap: wrap; }
.status-btn { opacity: 0.6; cursor: not-allowed; }
.event-images-wrap { display: flex; flex-direction: column; gap: 12px; }
.event-gallery-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.gallery-count-1, .gallery-count-2 { grid-template-columns: 1fr; }
.gallery-img { width: 100%; height: 200px; object-fit: cover; border-radius: 12px; display: block; }
.gallery-img--full { grid-column: 1 / -1; height: 280px; border-radius: 16px; }
.gallery-count-1 .gallery-img--full { height: 420px; }
@keyframes shimmer { 0% { background-position: -400px 0; } 100% { background-position: 400px 0; } }
.img-skeleton { border-radius: 16px; background: linear-gradient(90deg, #e8e0d8 25%, #f0e8df 50%, #e8e0d8 75%); background-size: 800px 100%; animation: shimmer 1.4s infinite linear; }
.img-banner-skeleton { height: 280px; }
.gallery-skeleton-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.img-skeleton--small { height: 150px; border-radius: 12px; }
.dialog-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.7); display: flex; align-items: center; justify-content: center; z-index: 2000; opacity: 0; visibility: hidden; transition: all 0.3s; padding: 20px; }
.dialog-overlay.active { opacity: 1; visibility: visible; }
.dialog { background: var(--white); border-radius: 24px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; transform: scale(0.9); transition: transform 0.3s; }
.dialog-overlay.active .dialog { transform: scale(1); }
.dialog-header { padding: 30px 30px 0; display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
.dialog-header h3 { font-size: 26px; font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 4px; }
.dialog-kannada { color: var(--primary-red); font-size: 15px; margin-bottom: 0; }
.dialog-close { width: 40px; height: 40px; border-radius: 50%; border: none; background: var(--cream); cursor: pointer; font-size: 16px; color: var(--text-dark); transition: all 0.3s; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.dialog-close:hover { background: var(--primary-red); color: var(--white); }
.dialog-body { padding: 0 30px 30px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.required { color: var(--primary-red); }
.btn-full { width: 100%; justify-content: center; }
.btn-full:disabled { opacity: 0.7; cursor: not-allowed; }
.form-turnstile { margin-bottom: 12px; }
.alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
.alert-success { background: #d1fae5; color: #065f46; }
.alert-error { background: #fee2e2; color: #991b1b; }
@media (max-width: 1024px) { .about-event-grid { grid-template-columns: 1fr; } }
@media (max-width: 768px) {
  .event-hero { padding: 70px 0; }
  .event-hero-container { padding: 0 20px; }
  .about-event-buttons { flex-direction: column; }
  .form-row { grid-template-columns: 1fr; }
}
</style>
