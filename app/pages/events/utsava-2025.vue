<template>
  <div>
    <!-- Event Hero -->
    <section class="event-hero">
      <div class="event-hero-container">
        <span class="tag event-tag-pill animate-item">📅 Featured Event</span>
        <h1 class="animate-item delay-1">UTSAVA 2025</h1>
        <p class="event-kannada kannada-text animate-item delay-2">"ಉತ್ಸವ ೨೦೨೫"</p>
        <p class="event-desc animate-item delay-3">Our flagship annual cultural festival celebrating Karnataka's rich heritage through music, dance, food, and community bonding.</p>

        <!-- Countdown -->
        <div class="countdown animate-item delay-4">
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
          <div class="event-info-item"><span class="info-icon">📅</span> May 15, 2025</div>
          <div class="event-info-item"><span class="info-icon">🕙</span> 10:00 AM – 8:00 PM</div>
          <div class="event-info-item"><span class="info-icon">📍</span> Munich Community Center</div>
        </div>
      </div>
    </section>

    <!-- About Event -->
    <section class="about-event section">
      <div class="section-container">
        <div class="about-event-grid">
          <div class="about-event-content animate-observe">
            <span class="tag">About the Event</span>
            <h2>A Celebration of Culture</h2>
            <p>UTSAVA is our annual flagship event that brings together the Kannada-speaking community in Munich for a day of celebration, culture, and connection.</p>
            <p>Experience the vibrant traditions of Karnataka through classical dance performances, melodious Kannada songs, traditional drama, and much more.</p>
            <p>Indulge in authentic Karnataka cuisine, participate in fun activities, and create lasting memories with your community.</p>

            <div class="about-event-buttons">
              <button class="btn btn-primary" @click="showDialog = true">
                Register Now <span aria-hidden="true">→</span>
              </button>
              <NuxtLink to="/contact" class="btn btn-outline">Contact for Details</NuxtLink>
            </div>
          </div>

          <div class="event-images-grid animate-observe delay-1">
            <img
              src="https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=600&q=80"
              alt="UTSAVA 2025 Celebration"
              class="img-main"
            />
            <img
              src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&q=80"
              alt="Karnataka Food"
              class="img-small"
            />
            <img
              src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=400&q=80"
              alt="Community"
              class="img-small"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Registration Dialog -->
    <Teleport to="body">
      <div class="dialog-overlay" :class="{ active: showDialog }" @click.self="showDialog = false">
        <div class="dialog" role="dialog" aria-modal="true" aria-label="Register for UTSAVA 2025">
          <div class="dialog-header">
            <div>
              <h3>Register for UTSAVA 2025</h3>
              <p class="dialog-kannada kannada-text">ಉತ್ಸವ ೨೦೨೫ ಗೆ ನೋಂದಣಿ</p>
            </div>
            <button class="dialog-close" @click="showDialog = false" aria-label="Close dialog">✕</button>
          </div>
          <div class="dialog-body">
            <div v-if="formSuccess" class="alert alert-success">Thank you for registering! We look forward to seeing you at UTSAVA 2025.</div>
            <div v-if="formError" class="alert alert-error">There was an error. Please try again or email us directly.</div>

            <form @submit.prevent="submitForm">
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
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5+">5+</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Number of Children</label>
                  <select v-model="form.children">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4+">4+</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label>Dietary Requirements</label>
                <input type="text" v-model="form.dietary" placeholder="Vegetarian, Vegan, Allergies, etc." />
              </div>
              <button type="submit" class="btn btn-primary btn-full" :disabled="submitting">
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
  title: 'UTSAVA 2025 | Munich Kannadigaru',
  description: "UTSAVA 2025 — Our flagship annual cultural festival celebrating Karnataka's rich heritage."
})

const showDialog = ref(false)
const submitting = ref(false)
const formSuccess = ref(false)
const formError = ref(false)

const form = reactive({
  firstName: '', lastName: '', email: '', phone: '',
  adults: '1', children: '0', dietary: ''
})

const countdown = reactive({ days: '00', hours: '00', minutes: '00', seconds: '00' })

function updateCountdown() {
  const eventDate = new Date('2025-05-15T10:00:00').getTime()
  const now = Date.now()
  const dist = eventDate - now
  if (dist > 0) {
    countdown.days    = String(Math.floor(dist / 86400000)).padStart(2, '0')
    countdown.hours   = String(Math.floor((dist % 86400000) / 3600000)).padStart(2, '0')
    countdown.minutes = String(Math.floor((dist % 3600000) / 60000)).padStart(2, '0')
    countdown.seconds = String(Math.floor((dist % 60000) / 1000)).padStart(2, '0')
  }
}

async function submitForm() {
  submitting.value = true
  formSuccess.value = false
  formError.value = false
  await new Promise(r => setTimeout(r, 800))
  formSuccess.value = true
  submitting.value = false
  Object.assign(form, { firstName: '', lastName: '', email: '', phone: '', adults: '1', children: '0', dietary: '' })
}

let timer
onMounted(() => {
  updateCountdown()
  timer = setInterval(updateCountdown, 1000)

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); observer.unobserve(e.target) } })
  }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' })
  document.querySelectorAll('.animate-observe').forEach(el => observer.observe(el))

  document.addEventListener('keydown', e => { if (e.key === 'Escape') showDialog.value = false })
})
onUnmounted(() => clearInterval(timer))
</script>

<style scoped>
/* Animations */
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

/* Hero */
.event-hero {
  background: linear-gradient(135deg, var(--primary-red) 0%, var(--primary-red-dark) 100%);
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

/* Countdown */
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

/* Event info bar */
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

/* About section */
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
.about-event-content p {
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

/* Image grid */
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
.dialog-overlay.active {
  opacity: 1;
  visibility: visible;
}
.dialog {
  background: var(--white);
  border-radius: 24px;
  max-width: 600px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  transform: scale(0.9);
  transition: transform 0.3s;
}
.dialog-overlay.active .dialog { transform: scale(1); }
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
.dialog-kannada {
  color: var(--primary-red);
  font-size: 15px;
  margin-bottom: 0;
}
.dialog-close {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: none;
  background: var(--cream);
  cursor: pointer;
  font-size: 16px;
  color: var(--text-dark);
  transition: all 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.dialog-close:hover { background: var(--primary-red); color: var(--white); }
.dialog-body { padding: 0 30px 30px; }
.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}
.required { color: var(--primary-red); }
.btn-full { width: 100%; justify-content: center; }
.btn-full:disabled { opacity: 0.7; cursor: not-allowed; }

/* Responsive */
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
</style>
