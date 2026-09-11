<template>
  <div>
    <section class="initiative-hero">
      <div class="section-container">
        <span class="tag animate-item">Initiative</span>
        <h1 class="animate-item delay-1">Kannada Kali</h1>
        <p class="hero-kannada kannada-text animate-item delay-2">ಕನ್ನಡ ಕಲಿ</p>
        <p class="hero-desc animate-item delay-3">Weekly Kannada language classes for children and adults — keeping our language alive, one lesson at a time.</p>
        <button class="btn btn-white animate-item delay-4" @click="openForm">
          Enrol Now <span aria-hidden="true">→</span>
        </button>
      </div>
    </section>

    <!-- How it works -->
    <section class="section how-section">
      <div class="section-container">
        <div class="how-intro animate-observe">
          <span class="tag">How It Works</span>
          <h2 class="section-title">
            <span class="black">Small Groups,</span>
            <span class="red"> Big Impact</span>
          </h2>
          <p>Kannada Kali runs on a weekly class basis in small groups of 3–5 children, ensuring each child gets personal attention and progresses at a comfortable pace. Classes are held on weekends, and parents are always welcome to sit in.</p>
          <p>We also offer a dedicated <strong>Kannada Kali for Adults</strong> — whether you grew up speaking Kannada at home but never learned to read and write, or you simply want to reconnect with your roots, our adult sessions are welcoming and practical.</p>
        </div>
      </div>
    </section>

    <!-- 3 Phases -->
    <section class="section phases-section">
      <div class="section-container">
        <div class="section-header animate-observe">
          <span class="tag">Learning Journey</span>
          <h2 class="section-title">
            <span class="black">Three</span>
            <span class="red"> Phases</span>
          </h2>
        </div>

        <div class="phases-grid">
          <div class="phase-card animate-observe">
            <div class="phase-number">01</div>
            <div class="phase-level beginner">Beginner</div>
            <h3>ಪ್ರಾರಂಭಿಕ</h3>
            <p>Start from the very beginning — learn the Kannada script (varnamale), vowels, consonants, and form simple words. Perfect for children with no prior exposure to the language.</p>
            <ul>
              <li>Kannada alphabet and script</li>
              <li>Basic vocabulary and numbers</li>
              <li>Simple conversational phrases</li>
              <li>Songs and rhymes for engagement</li>
            </ul>
          </div>

          <div class="phase-card animate-observe delay-1">
            <div class="phase-number">02</div>
            <div class="phase-level intermediate">Intermediate</div>
            <h3>ಮಧ್ಯಮ</h3>
            <p>Build on the basics — expand vocabulary, learn sentence construction, grammar rules, and practice reading simple passages. Children grow in confidence to speak Kannada at home.</p>
            <ul>
              <li>Grammar and sentence formation</li>
              <li>Reading short texts and stories</li>
              <li>Everyday conversation practice</li>
              <li>Writing short paragraphs</li>
            </ul>
          </div>

          <div class="phase-card animate-observe delay-2">
            <div class="phase-number">03</div>
            <div class="phase-level advanced">Advanced</div>
            <h3>ಉನ್ನತ</h3>
            <p>Master Kannada — read and write fluently, understand literature and poetry, and develop the ability to communicate confidently in both formal and informal contexts.</p>
            <ul>
              <li>Fluent reading and writing</li>
              <li>Appreciation of Kannada literature</li>
              <li>Poetry and prose composition</li>
              <li>Cultural context and idioms</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="section cta-section">
      <div class="section-container">
        <div class="cta-box animate-observe">
          <h2>Ready to Start Learning?</h2>
          <p class="kannada-text cta-kn">ಕನ್ನಡ ಕಲಿಯಲು ಸಿದ್ಧರಾಗಿದ್ದೀರಾ?</p>
          <p>Join our next batch and give your child the gift of their mother tongue. Adults welcome too!</p>
          <button class="btn btn-primary" @click="openForm">
            Enrol Now <span aria-hidden="true">→</span>
          </button>
        </div>
      </div>
    </section>

    <!-- ─── Enrollment Modal ──────────────────────────────────── -->
    <Teleport to="body">
      <div v-if="formOpen" class="modal-backdrop" @click.self="closeForm" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="modal-panel">
          <button class="modal-close" aria-label="Close" @click="closeForm">✕</button>

          <div v-if="formSuccess" class="modal-success">
            <div class="success-icon">✓</div>
            <h3>Enrollment Received!</h3>
            <p>Thank you for enrolling. We will contact you shortly with class details and schedules.</p>
            <p class="kannada-text success-kn">ಧನ್ಯವಾದಗಳು!</p>
            <button class="btn btn-primary" @click="closeForm">Close</button>
          </div>

          <template v-else>
            <div class="modal-header">
              <span class="tag">Enrol in Kannada Kali</span>
              <h2 id="modal-title">ನೋಂದಣಿ ಮಾಡಿ</h2>
              <p>We will contact you with class schedules and further details.</p>
            </div>

            <div v-if="formError" class="alert alert-error">
              Something went wrong. Please try again or email <a href="mailto:info@munichkannadigaru.org">info@munichkannadigaru.org</a>
            </div>

            <form @submit.prevent="submitForm">
              <h3 class="form-section-title">Parent Information</h3>
              <div class="form-row">
                <div class="form-group">
                  <label>Father's Name <span class="required">*</span></label>
                  <input type="text" v-model="form.fatherName" required placeholder="Father's full name" />
                </div>
                <div class="form-group">
                  <label>Mother's Name <span class="required">*</span></label>
                  <input type="text" v-model="form.motherName" required placeholder="Mother's full name" />
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

              <h3 class="form-section-title">Child's Information</h3>
              <div class="form-row">
                <div class="form-group">
                  <label>Child's Name <span class="required">*</span></label>
                  <input type="text" v-model="form.kidName" required placeholder="Child's full name" />
                </div>
                <div class="form-group">
                  <label>Age <span class="required">*</span></label>
                  <input type="number" v-model="form.kidAge" required min="3" max="18" placeholder="e.g. 7" />
                </div>
              </div>
              <div class="form-group">
                <label>Where are you from in Karnataka? <span class="required">*</span></label>
                <input type="text" v-model="form.fromKarnataka" required placeholder="e.g. Mysuru, Bengaluru, Dharwad…" />
              </div>

              <h3 class="form-section-title">Class Preference</h3>
              <div class="form-group">
                <label>Preferred Day to Attend Classes <span class="required">*</span></label>
                <select v-model="form.preferredDay" required>
                  <option value="" disabled>Select a day</option>
                  <option>Saturday</option>
                  <option>Sunday</option>
                  <option>Either Saturday or Sunday</option>
                </select>
              </div>

              <div class="disclaimer-box">
                <label class="checkbox-item">
                  <input type="checkbox" v-model="form.disclaimerAccepted" required />
                  <span>
                    I understand that Kannada Kali classes are offered on a <strong>contribution basis</strong>. I also acknowledge that students are required to <strong>purchase the course book online</strong>, which will be arranged by the coordinators. We will be contacted with class details and schedules.
                  </span>
                </label>
              </div>

              <button type="submit" class="btn btn-primary btn-full" :disabled="submitting || !form.disclaimerAccepted">
                {{ submitting ? 'Submitting…' : 'Submit Enrollment →' }}
              </button>
            </form>
          </template>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Kannada Kali | Munich Kannadigaru',
  description: 'Enroll your child in Kannada Kali — weekly Kannada language classes for kids and adults in Munich.'
})

const formOpen = ref(false)
const submitting = ref(false)
const formSuccess = ref(false)
const formError = ref(false)

const form = reactive({
  fatherName: '', motherName: '', email: '', phone: '',
  kidName: '', kidAge: '', fromKarnataka: '', preferredDay: '',
  disclaimerAccepted: false
})

function openForm() {
  formOpen.value = true
  document.body.style.overflow = 'hidden'
}

function closeForm() {
  formOpen.value = false
  document.body.style.overflow = ''
  if (formSuccess.value) {
    formSuccess.value = false
    Object.assign(form, { fatherName: '', motherName: '', email: '', phone: '', kidName: '', kidAge: '', fromKarnataka: '', preferredDay: '', disclaimerAccepted: false })
  }
}

async function submitForm() {
  submitting.value = true
  formError.value = false
  await new Promise(r => setTimeout(r, 900))
  formSuccess.value = true
  submitting.value = false
}

onMounted(() => {
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); obs.unobserve(e.target) } })
  }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' })
  document.querySelectorAll('.animate-observe').forEach(el => obs.observe(el))
})

onUnmounted(() => { document.body.style.overflow = '' })
</script>

<style scoped>
.animate-item { opacity: 0; transform: translateY(24px); animation: fadeUp 0.7s ease forwards; }
.delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; } .delay-3 { animation-delay: 0.3s; } .delay-4 { animation-delay: 0.4s; }
@keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
.animate-observe { opacity: 0; transform: translateY(28px); transition: opacity 0.7s ease, transform 0.7s ease; }
.animate-observe.delay-1 { transition-delay: 0.12s; } .animate-observe.delay-2 { transition-delay: 0.24s; }
.animate-observe.is-visible { opacity: 1; transform: translateY(0); }
@media (prefers-reduced-motion: reduce) {
  .animate-item, .animate-observe { animation: none !important; transition: none !important; opacity: 1 !important; transform: none !important; }
}

/* Hero */
.initiative-hero {
  background: linear-gradient(135deg, var(--primary-red) 0%, var(--primary-red-dark) 100%);
  color: var(--white);
  padding: 100px 0;
  text-align: center;
}
.initiative-hero .tag { background: rgba(255,255,255,0.15); color: var(--white); margin-bottom: 25px; }
.initiative-hero h1 { font-size: clamp(40px, 6vw, 68px); font-family: 'Playfair Display', serif; color: var(--white); margin-bottom: 15px; }
.hero-kannada { font-size: clamp(22px, 3vw, 30px); color: var(--gold); margin-bottom: 20px; display: block; }
.hero-desc { font-size: 18px; opacity: 0.9; max-width: 640px; margin: 0 auto 30px; color: var(--white); }

/* How it works */
.how-section { background: var(--cream); }
.how-intro { max-width: 820px; margin: 0 auto; text-align: center; }
.how-intro .tag { margin-bottom: 20px; display: inline-block; }
.how-intro .section-title { margin-bottom: 30px; }
.how-intro p { color: var(--text-light); font-size: 16px; line-height: 1.9; margin-bottom: 18px; }
.how-intro strong { color: var(--primary-red); }

/* Phases */
.phases-section { background: var(--cream-dark); }
.phases-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
.phase-card {
  background: var(--white);
  border-radius: 20px;
  padding: 40px 32px;
  transition: box-shadow 0.3s, transform 0.3s;
}
.phase-card:hover { box-shadow: 0 15px 40px rgba(0,0,0,0.08); transform: translateY(-5px); }
.phase-number {
  font-family: 'Playfair Display', serif;
  font-size: 52px;
  font-weight: 700;
  color: rgba(196,30,58,0.07);
  line-height: 1;
  margin-bottom: 8px;
}
.phase-level {
  display: inline-block;
  padding: 5px 14px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  font-family: 'Manrope', sans-serif;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 16px;
}
.phase-level.beginner { background: rgba(196,30,58,0.08); color: var(--primary-red); }
.phase-level.intermediate { background: rgba(212,167,59,0.1); color: var(--gold-dark); }
.phase-level.advanced { background: rgba(25,118,210,0.08); color: #1976D2; }
.phase-card h3 { font-size: 22px; font-family: 'Noto Sans Kannada', sans-serif; color: var(--text-dark); margin-bottom: 14px; }
.phase-card > p { color: var(--text-light); font-size: 14px; line-height: 1.7; margin-bottom: 18px; }
.phase-card ul { list-style: none; padding: 0; margin: 0; }
.phase-card ul li {
  color: var(--text-light);
  font-size: 13px;
  padding: 7px 0;
  border-bottom: 1px solid var(--border-light);
  display: flex;
  align-items: center;
  gap: 8px;
}
.phase-card ul li:last-child { border-bottom: none; }
.phase-card ul li::before { content: '✓'; color: var(--primary-red); font-weight: 700; flex-shrink: 0; }

/* CTA section */
.cta-section { background: var(--cream); }
.cta-box { text-align: center; max-width: 700px; margin: 0 auto; }
.cta-box h2 { font-size: clamp(28px, 4vw, 42px); font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 10px; }
.cta-kn { color: var(--primary-red); font-size: 18px; margin-bottom: 15px; }
.cta-box p { color: var(--text-light); margin-bottom: 30px; }

/* ─── Modal ─────────────────────────────────────────────────── */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.55);
  z-index: 2000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  backdrop-filter: blur(3px);
}

.modal-panel {
  background: var(--white);
  border-radius: 24px;
  padding: 50px;
  width: 100%;
  max-width: 720px;
  max-height: 90vh;
  overflow-y: auto;
  position: relative;
  box-shadow: 0 30px 80px rgba(0,0,0,0.2);
  animation: modalIn 0.3s ease;
}
@keyframes modalIn {
  from { opacity: 0; transform: translateY(20px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

.modal-close {
  position: absolute;
  top: 20px;
  right: 20px;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: none;
  background: var(--cream-dark);
  color: var(--text-dark);
  font-size: 14px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s;
  flex-shrink: 0;
}
.modal-close:hover { background: var(--border-light); }

.modal-header { text-align: center; margin-bottom: 35px; }
.modal-header .tag { margin-bottom: 14px; display: inline-block; }
.modal-header h2 { font-size: 26px; font-family: 'Noto Sans Kannada', sans-serif; color: var(--text-dark); margin-bottom: 8px; }
.modal-header p { color: var(--text-light); font-size: 14px; margin-bottom: 0; }

/* Success state */
.modal-success { text-align: center; padding: 20px 0; }
.success-icon {
  width: 70px;
  height: 70px;
  background: rgba(46,125,50,0.08);
  color: #2E7D32;
  border-radius: 50%;
  font-size: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 24px;
  font-weight: 700;
}
.modal-success h3 { font-size: 26px; font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 12px; }
.modal-success p { color: var(--text-light); line-height: 1.7; margin-bottom: 10px; }
.success-kn { color: var(--primary-red); font-size: 20px; margin-bottom: 28px !important; }
.modal-success .btn { margin-top: 10px; }

/* Form elements */
.form-section-title { font-size: 17px; font-family: 'Playfair Display', serif; color: var(--text-dark); margin: 30px 0 16px; padding-bottom: 10px; border-bottom: 2px solid var(--border-light); }
.form-section-title:first-of-type { margin-top: 0; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.required { color: var(--primary-red); }
select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%236B6B6B' d='M6 8L0 0h12z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 16px center; padding-right: 40px; }

.disclaimer-box {
  background: var(--cream-dark);
  border-radius: 12px;
  padding: 18px 22px;
  margin: 22px 0;
  border-left: 4px solid var(--gold);
}
.checkbox-item { display: flex; align-items: flex-start; gap: 14px; cursor: pointer; }
.checkbox-item input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--primary-red); cursor: pointer; flex-shrink: 0; margin-top: 2px; }
.checkbox-item span { color: var(--text-light); font-size: 14px; line-height: 1.7; }
.checkbox-item strong { color: var(--text-dark); }

.btn-full { width: 100%; justify-content: center; margin-top: 5px; }
.btn-full:disabled { opacity: 0.5; cursor: not-allowed; }

@media (max-width: 1024px) { .phases-grid { grid-template-columns: 1fr; } }
@media (max-width: 768px) {
  .modal-panel { padding: 35px 24px; }
  .form-row { grid-template-columns: 1fr; }
  .phase-card { padding: 30px 25px; }
}
</style>
