<template>
  <div>
    <!-- Past event banner -->
    <div v-if="liveData.past" class="past-banner">
      <span>🎉 This event has concluded — thank you to everyone who joined us for UTSAVA 2025!</span>
    </div>

    <section class="event-hero">
      <div class="event-hero-container">
        <span class="tag event-tag-pill animate-item">📅 {{ liveData.tag }}</span>
        <h1 class="animate-item delay-1">{{ liveData.title }}</h1>
        <p class="event-kannada kannada-text animate-item delay-2">"{{ liveData.kannadaTitle }}"</p>
        <p class="event-desc animate-item delay-3">Our flagship annual cultural festival celebrating Karnataka's rich heritage through music, dance, food, and community bonding.</p>

        <!-- Countdown (only shown when event is upcoming) -->
        <div v-if="!liveData.past" class="countdown animate-item delay-4">
          <div class="countdown-item">
            <div class="cd-number">{{ countdown.days }}</div>
            <div class="cd-label">Days</div>
          </div>
          <div class="countdown-item">
            <div class="cd-number">{{ countdown.hours }}</div>
            <div class="cd-label">Hours</div>
          </div>
          <div class="countdown-item">
            <div class="cd-number">{{ countdown.minutes }}</div>
            <div class="cd-label">Minutes</div>
          </div>
          <div class="countdown-item">
            <div class="cd-number">{{ countdown.seconds }}</div>
            <div class="cd-label">Seconds</div>
          </div>
        </div>

        <div class="event-info animate-item delay-5">
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
              >{{ liveData.past ? 'Event Concluded' : 'Registration Closed' }}</button>
            </div>
          </div>

          <!-- Image grid with loading skeleton -->
          <div class="event-images-grid animate-observe delay-1">
            <template v-if="loading">
              <div class="img-skeleton img-skeleton--main"></div>
              <div class="img-skeleton img-skeleton--small"></div>
              <div class="img-skeleton img-skeleton--small"></div>
            </template>
            <template v-else>
              <img :src="liveData.heroImage" alt="UTSAVA 2025 Celebration" class="img-main" />
              <img
                v-for="(img, i) in (liveData.galleryImages || []).slice(0, 2)"
                :key="i"
                :src="img"
                :alt="`UTSAVA 2025 gallery ${i + 1}`"
                class="img-small"
              />
            </template>
          </div>
        </div>
      </div>
    </section>

    <!-- Registration Dialog -->
    <Teleport to="body">
      <div class="dialog-overlay" :class="{ active: showDialog }" @click.self="showDialog = false">
        <div class="dialog" role="dialog" aria-modal="true" aria-label="Register for UTSAVA">
          <div class="dialog-scroll">
          <div class="dialog-header">
            <div>
              <h3>Register for UTSAVA</h3>
              <p class="dialog-kannada kannada-text">ಉತ್ಸವಕ್ಕೆ ನೋಂದಣಿ</p>
            </div>
            <button class="dialog-close" @click="showDialog = false" aria-label="Close dialog">✕</button>
          </div>
          <div class="dialog-body">
            <div v-if="formSuccess" class="alert alert-success">
              Thank you for registering! We'll be in touch with more details soon.
            </div>
            <div v-if="formError" class="alert alert-error">
              There was an error. Please try again or email us directly.
            </div>

            <form v-if="!formSuccess" @submit.prevent="submitForm">

              <!-- Section 1: Personal Details -->
              <p class="form-section-label">Personal Details</p>
              <div class="form-group">
                <label>Full Name <span class="required">*</span></label>
                <input type="text" v-model="form.fullName" required placeholder="Your full name" />
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>Email Address <span class="required">*</span></label>
                  <input
                    type="email"
                    v-model="form.email"
                    required
                    placeholder="your@email.com"
                    :class="{ 'input-invalid': emailError }"
                    @blur="validateEmail"
                  />
                  <span v-if="emailError" class="field-error">{{ emailError }}</span>
                </div>
                <div class="form-group">
                  <label>Phone Number <span class="required">*</span></label>
                  <input
                    type="tel"
                    v-model="form.phone"
                    required
                    placeholder="+49 123 456 789"
                    :class="{ 'input-invalid': phoneError }"
                    @blur="validatePhone"
                  />
                  <span v-if="phoneError" class="field-error">{{ phoneError }}</span>
                </div>
              </div>

              <!-- Section 2: Membership -->
              <p class="form-section-label">Membership</p>
              <div class="form-group">
                <label>Are you a member of Munich Kannadigaru? <span class="required">*</span></label>
                <div class="membership-toggle">
                  <button
                    type="button"
                    class="toggle-pill"
                    :class="{ active: form.isMember === true }"
                    @click="setMembership(true)"
                  >Yes, I'm a member</button>
                  <button
                    type="button"
                    class="toggle-pill"
                    :class="{ active: form.isMember === false }"
                    @click="setMembership(false)"
                  >No, I'm not a member</button>
                </div>
              </div>
              <div v-if="form.isMember === true" class="form-group">
                <label>Membership ID <span class="required">*</span></label>
                <div class="membership-id-wrap">
                  <input
                    type="text"
                    v-model="form.membershipId"
                    placeholder="e.g. MK-1234"
                    :class="{
                      'input-valid':   membershipStatus === 'valid',
                      'input-invalid': membershipStatus === 'invalid'
                    }"
                  />
                  <span v-if="membershipStatus === 'checking'" class="member-hint member-hint--checking">Checking…</span>
                  <span v-else-if="membershipStatus === 'valid'" class="member-hint member-hint--valid">✓ {{ membershipType === 'family' ? 'Family member' : 'Member' }}</span>
                  <span v-else-if="membershipStatus === 'invalid'" class="member-hint member-hint--invalid">Membership ID not found</span>
                </div>
              </div>

              <!-- Section 3: Tickets -->
              <template v-if="form.isMember !== null">
                <p class="form-section-label">Tickets</p>
                <div v-if="ticketsLoading" class="tickets-loading">Loading ticket options…</div>
                <div v-else-if="ticketsError" class="alert alert-error">Could not load ticket options. Please close and try again.</div>
                <template v-else-if="computedTickets.length">
                  <div class="ticket-list">
                    <div v-for="ticket in computedTickets" :key="ticket.id" class="ticket-row">
                      <div class="ticket-info">
                        <span class="ticket-label">{{ ticket.label }}</span>
                        <span class="ticket-price">€{{ ticket.price }}</span>
                      </div>
                      <div class="ticket-stepper">
                        <button type="button" class="stepper-btn" @click="stepQty(ticket.id, -1)">−</button>
                        <span class="stepper-qty">{{ quantities[ticket.id] || 0 }}</span>
                        <button type="button" class="stepper-btn" @click="stepQty(ticket.id, 1)">+</button>
                      </div>
                    </div>
                  </div>
                  <div class="ticket-total">
                    Total: <strong>€{{ computedTotal }}</strong>
                  </div>
                </template>
              </template>

              <NuxtTurnstile v-model="turnstileToken" class="form-turnstile" />
              <button
                type="submit"
                class="btn btn-primary btn-full"
                :disabled="submitting || !canSubmit"
              >
                {{ submitting ? 'Submitting…' : 'Complete Registration' }}
              </button>
            </form>
          </div><!-- /.dialog-body -->
          </div><!-- /.dialog-scroll -->
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'UTSAVA | Munich Kannadigaru',
  description: "UTSAVA — Munich Kannadigaru's flagship annual cultural festival celebrating Karnataka's heritage through music, dance, and food in Munich.",
  ogTitle: 'UTSAVA — Kannada Cultural Festival Munich',
  ogDescription: "Join Munich Kannadigaru for UTSAVA — a celebration of Karnataka culture with music, dance, food, and community.",
  ogImage: 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=1200&q=80',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/events/utsava',
  twitterCard: 'summary_large_image',
  twitterTitle: 'UTSAVA — Munich Kannadigaru',
  twitterDescription: 'Karnataka cultural festival in Munich — music, dance, food, and community.',
})

useHead({
  script: [{
    type: 'application/ld+json',
    children: JSON.stringify({
      '@context': 'https://schema.org',
      '@type': 'Event',
      name: 'UTSAVA',
      description: "Munich Kannadigaru's flagship annual cultural festival celebrating Karnataka's rich heritage.",
      location: { '@type': 'Place', name: 'Munich', address: { '@type': 'PostalAddress', addressLocality: 'Munich', addressCountry: 'DE' } },
      organizer: { '@type': 'Organization', name: 'Munich Kannadigaru', url: 'https://munichkannadigaru.org' },
      image: 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=1200&q=80',
      eventAttendanceMode: 'https://schema.org/OfflineEventAttendanceMode',
    })
  }]
})

const DEFAULTS = {
  slug: 'utsava',
  title: 'UTSAVA',
  kannadaTitle: 'ಉತ್ಸವ',
  tag: 'Featured Event',
  date: '',
  time: '',
  venue: '',
  heroImage: 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=1200&q=80',
  galleryImages: [
    'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80',
    'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=600&q=80',
  ],
  contentHtml: '<p>UTSAVA is our annual flagship event that brings together the Kannada-speaking community in Munich for a day of celebration, culture, and connection.</p><p>Experience the vibrant traditions of Karnataka through classical dance performances, melodious Kannada songs, traditional drama, and much more.</p>',
  registrationStatus: 'open',
  past: false,
}

const { liveData, loading } = useEventData('utsava', DEFAULTS)

const { public: { apiBaseUrl } } = useRuntimeConfig()
const registerUrl  = `${apiBaseUrl.replace(/\/$/, '')}/utsava-register.php`
const ticketsUrl   = `${apiBaseUrl.replace(/\/$/, '')}/utsava-tickets.php`
const validateUrl  = `${apiBaseUrl.replace(/\/$/, '')}/validate-membership.php`

// ── Dialog visibility ────────────────────────────────────────────────────────
const showDialog  = ref(false)
const submitting  = ref(false)
const formSuccess = ref(false)
const formError   = ref(false)
const turnstileToken = ref('')

// ── Personal details ─────────────────────────────────────────────────────────
const form = reactive({
  fullName: '', email: '', phone: '',
  isMember: null,   // null = unanswered, true/false after selection
  membershipId: '',
})

const emailError = ref('')
const phoneError = ref('')

function validateEmail() {
  const v = form.email.trim()
  emailError.value = v && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) ? 'Please enter a valid email address.' : ''
}
function validatePhone() {
  const v = form.phone.trim()
  phoneError.value = v && !/^\+?[\d\s\-().]{7,20}$/.test(v) ? 'Please enter a valid phone number.' : ''
}

// ── Membership validation state ───────────────────────────────────────────────
// null = not yet checked, 'checking', 'valid', 'invalid'
const membershipStatus = ref(null)
const membershipType   = ref(null)   // e.g. 'single_adult', 'family'
const eligibleIds      = ref([])     // categoryIds allowed for this membership

let validateTimer = null
watch(() => form.membershipId, (id) => {
  membershipStatus.value = null
  membershipType.value   = null
  eligibleIds.value      = []
  clearTimeout(validateTimer)
  if (!id.trim()) return
  membershipStatus.value = 'checking'
  validateTimer = setTimeout(async () => {
    try {
      const res = await $fetch(`${validateUrl}?id=${encodeURIComponent(id.trim())}`)
      if (res.valid) {
        membershipStatus.value = 'valid'
        membershipType.value   = res.type
        eligibleIds.value      = res.eligibleCategoryIds || []
        quantities.value       = {}   // reset quantities when eligibility changes
      } else {
        membershipStatus.value = 'invalid'
      }
    } catch {
      membershipStatus.value = 'invalid'
    }
  }, 600)
})

// ── Ticket state ─────────────────────────────────────────────────────────────
const ticketConfig   = ref(null)   // { member: [...], nonMember: [...] }
const quantities     = ref({})     // { [categoryId]: number }
const ticketsLoading = ref(false)
const ticketsError   = ref(false)

async function fetchTickets() {
  if (ticketConfig.value) return
  ticketsLoading.value = true
  ticketsError.value = false
  try {
    ticketConfig.value = await $fetch(ticketsUrl)
  } catch {
    ticketsError.value = true
  } finally {
    ticketsLoading.value = false
  }
}

// ── Derived ticket state ──────────────────────────────────────────────────────
const computedTickets = computed(() => {
  if (!ticketConfig.value || form.isMember === null) return []
  if (form.isMember) {
    // Filter member tickets to only those eligible for this membership type
    return ticketConfig.value.member.filter(t => eligibleIds.value.includes(t.id))
  }
  return ticketConfig.value.nonMember
})

const computedTotal = computed(() =>
  computedTickets.value.reduce((sum, t) => sum + (quantities.value[t.id] || 0) * t.price, 0)
)

const computedTicketPayload = computed(() =>
  computedTickets.value
    .filter(t => (quantities.value[t.id] || 0) > 0)
    .map(t => ({ categoryId: t.id, label: t.label, quantity: quantities.value[t.id], priceEach: t.price }))
)

const canSubmit = computed(() =>
  !!turnstileToken.value &&
  computedTicketPayload.value.length > 0 &&
  form.fullName && form.email && form.phone &&
  !emailError.value && !phoneError.value &&
  form.isMember !== null &&
  (!form.isMember || (form.membershipId.trim() !== '' && membershipStatus.value === 'valid'))
)

// ── Membership toggle ────────────────────────────────────────────────────────
function setMembership(value) {
  form.isMember = value
  form.membershipId = ''
  membershipStatus.value = null
  membershipType.value   = null
  eligibleIds.value      = []
  quantities.value = {}
}

// ── Quantity stepper ─────────────────────────────────────────────────────────
function stepQty(categoryId, delta) {
  const current = quantities.value[categoryId] || 0
  quantities.value = { ...quantities.value, [categoryId]: Math.max(0, current + delta) }
}

// ── Form submission ───────────────────────────────────────────────────────────
async function submitForm() {
  submitting.value = true
  formSuccess.value = false
  formError.value = false
  try {
    await $fetch(registerUrl, {
      method: 'POST',
      body: {
        fullName:       form.fullName,
        email:          form.email,
        phone:          form.phone,
        isMember:       form.isMember,
        membershipId:   form.membershipId,
        membershipType: membershipType.value,
        tickets:        computedTicketPayload.value,
        totalAmount:    computedTotal.value,
        currency:       'EUR',
        turnstileToken: turnstileToken.value,
      }
    })
    formSuccess.value = true
    turnstileToken.value = ''
    Object.assign(form, { fullName: '', email: '', phone: '', isMember: null, membershipId: '' })
    membershipStatus.value = null
    membershipType.value   = null
    eligibleIds.value      = []
    quantities.value = {}
    emailError.value = ''
    phoneError.value = ''
  } catch {
    formError.value = true
    turnstileToken.value = ''
  } finally {
    submitting.value = false
  }
}

// ── Countdown ────────────────────────────────────────────────────────────────
const countdown = reactive({ days: '00', hours: '00', minutes: '00', seconds: '00' })

function updateCountdown() {
  if (!liveData.value?.date) return
  const eventDate = new Date(liveData.value.date).getTime()
  const dist = eventDate - Date.now()
  if (dist > 0) {
    countdown.days    = String(Math.floor(dist / 86400000)).padStart(2, '0')
    countdown.hours   = String(Math.floor((dist % 86400000) / 3600000)).padStart(2, '0')
    countdown.minutes = String(Math.floor((dist % 3600000) / 60000)).padStart(2, '0')
    countdown.seconds = String(Math.floor((dist % 60000) / 1000)).padStart(2, '0')
  }
}

// ── Watch dialog open → fetch tickets ────────────────────────────────────────
watch(showDialog, (open) => {
  if (open) fetchTickets()
  else {
    turnstileToken.value = ''
    emailError.value = ''
    phoneError.value = ''
  }
})

let escHandler
let timer
onMounted(() => {
  updateCountdown()
  timer = setInterval(updateCountdown, 1000)

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); observer.unobserve(e.target) } })
  }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' })
  document.querySelectorAll('.animate-observe').forEach(el => observer.observe(el))
  escHandler = e => { if (e.key === 'Escape') showDialog.value = false }
  document.addEventListener('keydown', escHandler)
})
onUnmounted(() => {
  clearInterval(timer)
  document.removeEventListener('keydown', escHandler)
})
</script>

<style scoped>
/* Past banner */
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

.animate-item {
  opacity: 0;
  transform: translateY(24px);
  animation: fadeUp 0.7s ease forwards;
}
.delay-1 { animation-delay: 0.1s; }
.delay-2 { animation-delay: 0.2s; }
.delay-3 { animation-delay: 0.3s; }
.delay-4 { animation-delay: 0.4s; }
.delay-5 { animation-delay: 0.5s; }
@keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

.animate-observe {
  opacity: 0;
  transform: translateY(28px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
.animate-observe.delay-1 { transition-delay: 0.12s; }
.animate-observe.is-visible { opacity: 1; transform: translateY(0); }

@media (prefers-reduced-motion: reduce) {
  .animate-item, .animate-observe {
    animation: none !important; transition: none !important;
    opacity: 1 !important; transform: none !important;
  }
}

.event-hero {
  background: linear-gradient(135deg, var(--primary-red) 0%, #8B0000 100%);
  color: var(--white);
  padding: 100px 0;
  position: relative;
  overflow: hidden;
  text-align: center;
}
.event-hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background: url('https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=1200&q=80') center/cover;
  opacity: 0.15;
}
.event-hero-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 40px;
  position: relative;
  z-index: 1;
}
.event-tag-pill {
  background: rgba(255,255,255,0.15);
  color: var(--white);
  margin-bottom: 25px;
}
.event-hero h1 {
  font-size: clamp(48px, 7vw, 80px);
  font-family: 'Playfair Display', serif;
  margin-bottom: 15px;
  color: var(--white);
}
.event-kannada {
  font-size: clamp(24px, 4vw, 36px);
  color: var(--gold);
  margin-bottom: 25px;
  display: block;
}
.event-desc {
  font-size: 18px;
  opacity: 0.9;
  max-width: 600px;
  margin: 0 auto 40px;
  color: var(--white);
}

.countdown {
  display: flex;
  gap: 25px;
  justify-content: center;
  margin: 40px 0;
  flex-wrap: wrap;
}
.countdown-item {
  background: rgba(255,255,255,0.1);
  border-radius: 16px;
  padding: 25px 35px;
  text-align: center;
  min-width: 100px;
}
.cd-number {
  font-size: clamp(36px, 5vw, 52px);
  font-weight: 700;
  font-family: 'Playfair Display', serif;
  color: var(--white);
  line-height: 1;
  margin-bottom: 5px;
}
.cd-label {
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 1px;
  opacity: 0.8;
}

.event-info {
  display: flex;
  gap: 20px;
  justify-content: center;
  flex-wrap: wrap;
  margin-top: 40px;
}
.event-info-item {
  background: rgba(255,255,255,0.1);
  padding: 14px 22px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 15px;
  color: var(--white);
}
.info-icon { font-size: 16px; }

.about-event { background: var(--cream); }
.about-event-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: start;
}
.about-event-content .tag { margin-bottom: 20px; display: inline-block; }
.about-event-content h2 {
  font-size: clamp(30px, 4vw, 44px);
  font-family: 'Playfair Display', serif;
  margin-bottom: 25px;
  color: var(--text-dark);
}
.event-content-html :deep(p) {
  color: var(--text-light);
  font-size: 16px;
  line-height: 1.8;
  margin-bottom: 18px;
}
.about-event-buttons {
  display: flex;
  gap: 20px;
  margin-top: 35px;
  flex-wrap: wrap;
}
.status-btn { opacity: 0.6; cursor: not-allowed; }

.event-images-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}
.img-main {
  grid-column: span 2;
  width: 100%;
  height: 250px;
  object-fit: cover;
  border-radius: 16px;
  display: block;
}
.img-small {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 16px;
  display: block;
}

/* Loading skeletons */
@keyframes shimmer {
  0% { background-position: -400px 0; }
  100% { background-position: 400px 0; }
}
.img-skeleton {
  border-radius: 16px;
  background: linear-gradient(90deg, #e8e0d8 25%, #f0e8df 50%, #e8e0d8 75%);
  background-size: 800px 100%;
  animation: shimmer 1.4s infinite linear;
}
.img-skeleton--main {
  grid-column: span 2;
  height: 250px;
}
.img-skeleton--small { height: 180px; }

/* Dialog */
.dialog-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s;
  padding: 20px;
}
.dialog-overlay.active { opacity: 1; visibility: visible; }
.dialog {
  background: var(--white);
  border-radius: 24px;
  max-width: 600px;
  width: 100%;
  max-height: 90vh;
  overflow: hidden;
  transform: scale(0.9);
  transition: transform 0.3s;
}
.dialog-overlay.active .dialog { transform: scale(1); }
.dialog-scroll {
  max-height: 90vh;
  overflow-y: auto;
}
.dialog-header {
  padding: 30px 30px 0;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 20px;
}
.dialog-header h3 {
  font-size: 26px;
  font-family: 'Playfair Display', serif;
  color: var(--text-dark);
  margin-bottom: 4px;
}
.dialog-kannada { color: var(--primary-red); font-size: 15px; margin-bottom: 0; }
.dialog-close {
  width: 40px; height: 40px; border-radius: 50%;
  border: none; background: var(--cream);
  cursor: pointer; font-size: 16px; color: var(--text-dark);
  transition: all 0.3s; display: flex; align-items: center;
  justify-content: center; flex-shrink: 0;
}
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

@media (max-width: 1024px) {
  .about-event-grid { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
  .event-hero { padding: 70px 0; }
  .event-hero-container { padding: 0 20px; }
  .countdown { gap: 12px; }
  .countdown-item { padding: 18px 20px; min-width: 70px; }
  .about-event-buttons { flex-direction: column; }
  .form-row { grid-template-columns: 1fr; }
}

/* Form section labels */
.form-section-label {
  font-size: 13px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--primary-red);
  margin: 24px 0 12px;
  padding-bottom: 6px;
  border-bottom: 1px solid var(--cream);
  font-family: 'Manrope', sans-serif;
}
.form-section-label:first-of-type { margin-top: 0; }

/* Membership toggle pills */
.membership-toggle {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}
.toggle-pill {
  padding: 9px 20px;
  border-radius: 50px;
  border: 2px solid var(--cream);
  background: var(--white);
  color: var(--text-dark);
  font-size: 14px;
  font-family: 'Manrope', sans-serif;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}
.toggle-pill:hover { border-color: var(--primary-red); color: var(--primary-red); }
.toggle-pill.active {
  background: var(--primary-red);
  border-color: var(--primary-red);
  color: var(--white);
}

/* Ticket list */
.ticket-list { display: flex; flex-direction: column; gap: 10px; margin-bottom: 14px; }
.ticket-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 16px;
  background: var(--cream);
  border-radius: 12px;
  gap: 12px;
}
.ticket-info { display: flex; flex-direction: column; gap: 2px; }
.ticket-label { font-size: 15px; font-weight: 600; color: var(--text-dark); font-family: 'Manrope', sans-serif; }
.ticket-price { font-size: 13px; color: var(--text-light); }
.ticket-stepper { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.stepper-btn {
  width: 32px; height: 32px; border-radius: 50%;
  border: 2px solid var(--primary-red);
  background: var(--white); color: var(--primary-red);
  font-size: 18px; font-weight: 700; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.2s; line-height: 1;
}
.stepper-btn:hover { background: var(--primary-red); color: var(--white); }
.stepper-qty {
  min-width: 28px; text-align: center;
  font-size: 17px; font-weight: 700;
  font-family: 'Manrope', sans-serif;
  color: var(--text-dark);
}
.ticket-total {
  text-align: right;
  font-size: 16px;
  color: var(--text-dark);
  font-family: 'Manrope', sans-serif;
  margin-bottom: 20px;
}
.ticket-total strong { color: var(--primary-red); font-size: 18px; }
.tickets-loading {
  font-size: 14px; color: var(--text-light);
  padding: 16px 0; text-align: center;
  font-family: 'Manrope', sans-serif;
}

/* Membership ID field with validation indicator */
.membership-id-wrap { display: flex; flex-direction: column; gap: 6px; }
.membership-id-wrap input { width: 100%; }
.input-valid  { border-color: #059669 !important; }
.input-invalid { border-color: #dc2626 !important; }
.field-error { display: block; margin-top: 4px; font-size: 12px; color: #dc2626; }
.member-hint {
  font-size: 12px;
  font-family: 'Manrope', sans-serif;
  font-weight: 600;
}
.member-hint--checking { color: var(--text-light); }
.member-hint--valid    { color: #059669; }
.member-hint--invalid  { color: #dc2626; }
</style>
