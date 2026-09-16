<template>
  <div>
    <!-- Hero -->
    <section class="membership-hero">
      <div class="section-container">
        <span class="tag hero-tag animate-item">Join Our Community</span>
        <h1 class="animate-item delay-1">Become a Member</h1>
        <p class="hero-kannada kannada-text animate-item delay-2">ಸದಸ್ಯರಾಗಿ</p>
        <p class="hero-desc animate-item delay-3">Join the largest Kannada community in Munich — thriving to promote the Art, Language, and Culture of Karnataka.</p>
        <button class="btn btn-white animate-item delay-4" @click="openForm">Join Now <span aria-hidden="true">→</span></button>
      </div>
    </section>

    <!-- Welcome Letter (first section after hero) -->
    <section class="section welcome-section">
      <div class="section-container">
        <div class="welcome-grid animate-observe">
          <div class="welcome-letter">
            <p class="welcome-greeting kannada-text">ಆತ್ಮೀಯ ಸ್ನೇಹಿತರೆ,</p>
            <h2>Welcome to Munich<br><span class="red">Kannadigaru e.V.</span> 💛❤️</h2>
            <p>"Join us, Make a difference, Make an impact."</p>
            <p>We'd love you to join our amazing community and help make it even stronger! Our community is a place where people from various diverse backgrounds come together to share their experiences, knowledge, and skills.</p>
            <p>As a member, you'll have the chance to participate in decision-making about community activities. Plus, you'll get access to sweet perks like vouchers and coupons from our sponsors.</p>
            <p>Together we are strong! Join the largest Kannada community, thriving for promoting Art, Language and Culture of Karnataka.</p>
            <p class="welcome-motto kannada-text">"ತನು ಕನ್ನಡ, ಮನ ಕನ್ನಡ, ನಡೆ ಕನ್ನಡ, ನುಡಿ ಕನ್ನಡ"</p>
            <button class="btn btn-primary" @click="openForm">Join Our Family →</button>
          </div>
          <div class="welcome-image animate-observe delay-1">
            <img
              :src="membershipProcessImage"
              alt="Munich Kannadigaru membership process"
              loading="lazy"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- How It Works -->
    <section class="section process-section">
      <div class="section-container">
        <div class="section-header animate-observe">
          <span class="tag">Simple Process</span>
          <h2 class="section-title">
            <span class="black">How to</span>
            <span class="red"> Join</span>
          </h2>
        </div>
        <div class="process-steps">
          <div class="process-step animate-observe">
            <div class="step-number">01</div>
            <h4>Choose a Plan</h4>
            <p>Pick the membership plan that suits you — Student, Single Adult, or Family.</p>
          </div>
          <div class="process-connector" aria-hidden="true"></div>
          <div class="process-step animate-observe delay-1">
            <div class="step-number">02</div>
            <h4>Fill the Form</h4>
            <p>Complete the registration form with your personal details and submit.</p>
          </div>
          <div class="process-connector" aria-hidden="true"></div>
          <div class="process-step animate-observe delay-2">
            <div class="step-number">03</div>
            <h4>Get Your ID</h4>
            <p>Receive your unique Membership ID via email within a few days of registration.</p>
          </div>
          <div class="process-connector" aria-hidden="true"></div>
          <div class="process-step animate-observe delay-3">
            <div class="step-number">04</div>
            <h4>Enjoy Benefits</h4>
            <p>Access exclusive events, community network, discounts, and much more.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Pricing -->
    <section class="section pricing-section">
      <div class="section-container">
        <div class="section-header animate-observe">
          <span class="tag">Membership Plans</span>
          <h2 class="section-title">
            <span class="black">Choose Your</span>
            <span class="red"> Plan</span>
          </h2>
          <p class="section-subtitle">All plans valid for 12 months. Pricing valid from May 1, 2023. Munich Kannadigaru e.V. (VR209854)</p>
        </div>

        <div class="pricing-grid">
          <div class="pricing-card animate-observe">
            <div class="plan-icon">🎓</div>
            <h3>Student</h3>
            <p class="plan-kannada kannada-text">ವಿದ್ಯಾರ್ಥಿ</p>
            <div class="price">€8<span>/year</span></div>
            <ul>
              <li>Enrolled in any German university</li>
              <li>Access to all community events</li>
              <li>Member-only WhatsApp group</li>
              <li>Newsletter subscription</li>
              <li>Community network access</li>
            </ul>
            <button class="btn btn-outline btn-full" @click="openFormWithPlan('student')">Select Plan</button>
          </div>

          <div class="pricing-card animate-observe delay-1">
            <div class="plan-icon">👤</div>
            <h3>Single Adult</h3>
            <p class="plan-kannada kannada-text">ವ್ಯಕ್ತಿಗತ</p>
            <div class="price">€12<span>/year</span></div>
            <ul>
              <li>Age 18+, eligible to stay in Germany</li>
              <li>Access to all community events</li>
              <li>Member-only WhatsApp group</li>
              <li>Voting rights in general assembly</li>
              <li>Sponsor vouchers &amp; discounts</li>
            </ul>
            <button class="btn btn-outline btn-full" @click="openFormWithPlan('single')">Select Plan</button>
          </div>

          <div class="pricing-card popular animate-observe delay-2">
            <span class="popular-badge">Most Popular</span>
            <div class="plan-icon">👨‍👩‍👧‍👦</div>
            <h3>Family</h3>
            <p class="plan-kannada kannada-text">ಕುಟುಂಬ</p>
            <div class="price">€20<span>/year</span></div>
            <ul>
              <li>Two adults + children under 18</li>
              <li>All Single Adult benefits</li>
              <li>Priority event registration</li>
              <li>Exclusive family activities</li>
              <li>Free entry to UTSAVA</li>
            </ul>
            <button class="btn btn-white btn-full" @click="openFormWithPlan('family')">Select Plan</button>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="section cta-section">
      <div class="section-container">
        <div class="cta-box animate-observe">
          <h2>Ready to Join?</h2>
          <p>Be part of a supportive, inclusive community that celebrates Kannada culture in the heart of Europe.</p>
          <button class="btn btn-primary" @click="openForm">Register Now →</button>
        </div>
      </div>
    </section>

    <!-- Registration Modal -->
    <Teleport to="body">
      <div v-if="formOpen" class="modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="modal-panel">
          <button class="modal-close" @click="closeForm" aria-label="Close registration form">✕</button>

          <!-- Success state -->
          <div v-if="formSuccess" class="form-success">
            <div class="success-icon">✓</div>
            <h3>Registration Received!</h3>
            <p>Thank you for your interest in joining Munich Kannadigaru. We'll send further details to <strong>{{ form.email }}</strong> within a few days.</p>
            <p class="success-sub kannada-text">ಸ್ವಾಗತ! ನಮ್ಮ ಕುಟುಂಬವನ್ನು ಸೇರುವಲ್ಲಿ ಆಸಕ್ತಿ ತೋರಿಸಿದ್ದಕ್ಕಾಗಿ ಧನ್ಯವಾದಗಳು.</p>
            <button class="btn btn-primary" @click="closeForm">Close</button>
          </div>

          <!-- Form -->
          <div v-else class="modal-body">
            <div class="modal-header">
              <span class="tag">Membership Registration</span>
              <h2 id="modal-title">Join Munich Kannadigaru</h2>
              <p class="modal-subtitle kannada-text">ಸದಸ್ಯತ್ವ ನೋಂದಣಿ</p>
            </div>

            <div v-if="formError" class="alert alert-error">Something went wrong. Please try again or email us at info@munichkannadigaru.org</div>

            <form @submit.prevent="submitForm" novalidate>

              <!-- Membership Plan -->
              <h3 class="form-section-title">Membership Plan</h3>
              <div class="plan-selector">
                <label class="plan-option" :class="{ active: form.planType === 'student' }">
                  <input type="radio" v-model="form.planType" value="student" />
                  <span class="plan-label">🎓 Student</span>
                  <span class="plan-price">€8/yr</span>
                </label>
                <label class="plan-option" :class="{ active: form.planType === 'single' }">
                  <input type="radio" v-model="form.planType" value="single" />
                  <span class="plan-label">👤 Single Adult</span>
                  <span class="plan-price">€12/yr</span>
                </label>
                <label class="plan-option" :class="{ active: form.planType === 'family' }">
                  <input type="radio" v-model="form.planType" value="family" />
                  <span class="plan-label">👨‍👩‍👧‍👦 Family</span>
                  <span class="plan-price">€20/yr</span>
                </label>
              </div>

              <!-- Personal Details -->
              <h3 class="form-section-title">Personal Details</h3>
              <div class="form-row">
                <div class="form-group">
                  <label>First Name <span class="required">*</span></label>
                  <input type="text" v-model="form.firstName" required placeholder="First name" />
                </div>
                <div class="form-group">
                  <label>Last Name <span class="required">*</span></label>
                  <input type="text" v-model="form.lastName" required placeholder="Last name" />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>Phone Number <span class="required">*</span></label>
                  <input type="tel" v-model="form.phone" required placeholder="+49 123 456 789" />
                </div>
                <div class="form-group">
                  <label>WhatsApp Number</label>
                  <input type="tel" v-model="form.whatsapp" placeholder="If different from phone" />
                </div>
              </div>
              <div class="form-group">
                <label>Email Address <span class="required">*</span></label>
                <input type="email" v-model="form.email" required placeholder="your@email.com" />
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>Date of Birth <span class="required">*</span></label>
                  <input type="date" v-model="form.dob" required />
                </div>
                <div class="form-group">
                  <label>Citizenship <span class="required">*</span></label>
                  <input type="text" v-model="form.citizenship" required placeholder="e.g. Indian" />
                </div>
              </div>

              <!-- Address -->
              <h3 class="form-section-title">Current Address</h3>
              <div class="form-group">
                <label>Street Address <span class="required">*</span></label>
                <input type="text" v-model="form.address" required placeholder="Street and house number" />
              </div>
              <div class="form-row three-col">
                <div class="form-group">
                  <label>Postcode <span class="required">*</span></label>
                  <input type="text" v-model="form.postcode" required placeholder="80331" />
                </div>
                <div class="form-group">
                  <label>City <span class="required">*</span></label>
                  <input type="text" v-model="form.city" required placeholder="Munich" />
                </div>
                <div class="form-group">
                  <label>Country</label>
                  <input type="text" v-model="form.country" readonly class="input-readonly" />
                </div>
              </div>

              <!-- Student extra: Student ID -->
              <div v-if="form.planType === 'student'">
                <h3 class="form-section-title">Student Information</h3>
                <div class="form-group">
                  <label>Student ID Number <span class="required">*</span></label>
                  <input type="text" v-model="form.studentId" :required="form.planType === 'student'" placeholder="Your university student ID" />
                  <p class="field-hint">Required for Student membership. Must be enrolled in a German university.</p>
                </div>
              </div>

              <!-- Family extra -->
              <div v-if="form.planType === 'family'">
                <h3 class="form-section-title">Family Members</h3>
                <div class="form-row">
                  <div class="form-group">
                    <label>Spouse / Partner Name <span class="required">*</span></label>
                    <input type="text" v-model="form.spouseName" :required="form.planType === 'family'" placeholder="Full name" />
                  </div>
                  <div class="form-group">
                    <label>Spouse Date of Birth <span class="required">*</span></label>
                    <input type="date" v-model="form.spouseDob" :required="form.planType === 'family'" />
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label>Child 1 Name</label>
                    <input type="text" v-model="form.child1Name" placeholder="Full name" />
                  </div>
                  <div class="form-group">
                    <label>Child 1 Date of Birth</label>
                    <input type="date" v-model="form.child1Dob" />
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label>Child 2 Name</label>
                    <input type="text" v-model="form.child2Name" placeholder="Full name" />
                  </div>
                  <div class="form-group">
                    <label>Child 2 Date of Birth</label>
                    <input type="date" v-model="form.child2Dob" />
                  </div>
                </div>
                <p class="field-hint">Family plan covers two adults and children under 18. Children must reside with the primary member.</p>
              </div>

              <!-- Disclaimer -->
              <h3 class="form-section-title">Declaration</h3>
              <div class="disclaimer-box">
                <p class="disclaimer-title">By submitting this form, I confirm that:</p>
                <ul class="disclaimer-list">
                  <li>All information provided is accurate and complete to the best of my knowledge.</li>
                  <li>I meet the eligibility criteria for my selected membership plan (age, student status, or family composition).</li>
                  <li>I agree to abide by the rules and regulations of Munich Kannadigaru e.V. (VR209854).</li>
                  <li>I understand that membership is valid for 12 months and must be renewed annually.</li>
                  <li>My personal data will be stored and used solely for membership and community communication purposes in accordance with GDPR.</li>
                  <li>I acknowledge that membership fees are non-refundable once processed.</li>
                </ul>
                <label class="disclaimer-check">
                  <input type="checkbox" v-model="form.agreed" required />
                  <span>I have read and agree to the above declaration <span class="required">*</span></span>
                </label>
              </div>

              <NuxtTurnstile v-model="turnstileToken" class="form-turnstile" />
              <button
                type="submit"
                class="btn btn-primary btn-full"
                :disabled="submitting || !turnstileToken || !form.agreed"
              >
                {{ submitting ? 'Submitting…' : 'Complete Registration →' }}
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
  title: 'Membership | Munich Kannadigaru',
  description: "Join Munich Kannadigaru. Student, Single, and Family membership plans from €8/year. Be part of Munich's Kannada community.",
  ogTitle: 'Join Munich Kannadigaru — Membership',
  ogDescription: "Become a member of Munich's Kannada community. Student €8/yr, Single Adult €12/yr, Family €20/yr.",
  ogImage: 'https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/membership',
  twitterCard: 'summary_large_image',
  twitterTitle: 'Munich Kannadigaru Membership',
  twitterDescription: 'Join the Munich Kannada community. Plans from €8/year.',
})

const { public: { apiBaseUrl } } = useRuntimeConfig()
const membershipProcessImage = `${apiBaseUrl}/assets/MembershipProcess.png`
const membershipApiUrl = `${apiBaseUrl.replace(/\/$/, '')}/membership-register.php`

const formOpen = ref(false)
const submitting = ref(false)
const formSuccess = ref(false)
const formError = ref(false)
const turnstileToken = ref('')

const form = reactive({
  planType: 'single',
  firstName: '', lastName: '', phone: '', whatsapp: '', email: '',
  dob: '', citizenship: '',
  address: '', postcode: '', city: '', country: 'Germany',
  studentId: '',
  spouseName: '', spouseDob: '',
  child1Name: '', child1Dob: '',
  child2Name: '', child2Dob: '',
  agreed: false
})

function openForm() {
  formOpen.value = true
  document.body.style.overflow = 'hidden'
}

function openFormWithPlan(plan) {
  form.planType = plan
  openForm()
}

function closeForm() {
  formOpen.value = false
  document.body.style.overflow = ''
  turnstileToken.value = ''
  if (formSuccess.value) {
    formSuccess.value = false
    Object.assign(form, {
      planType: 'single',
      firstName: '', lastName: '', phone: '', whatsapp: '', email: '',
      dob: '', citizenship: '',
      address: '', postcode: '', city: '', country: 'Germany',
      studentId: '',
      spouseName: '', spouseDob: '',
      child1Name: '', child1Dob: '',
      child2Name: '', child2Dob: '',
      agreed: false
    })
  }
}

async function submitForm() {
  submitting.value = true
  formError.value = false

  try {
    await $fetch(membershipApiUrl, {
      method: 'POST',
      body: {
        planType: form.planType,
        firstName: form.firstName,
        lastName: form.lastName,
        phone: form.phone,
        whatsapp: form.whatsapp,
        email: form.email,
        dob: form.dob,
        citizenship: form.citizenship,
        address: form.address,
        postcode: form.postcode,
        city: form.city,
        country: form.country,
        agreed: form.agreed,
        studentId: form.studentId,
        spouseName: form.spouseName,
        spouseDob: form.spouseDob,
        child1Name: form.child1Name,
        child1Dob: form.child1Dob,
        child2Name: form.child2Name,
        child2Dob: form.child2Dob,
        turnstileToken: turnstileToken.value
      }
    })

    formSuccess.value = true
  } catch {
    formError.value = true
  } finally {
    submitting.value = false
    turnstileToken.value = ''
  }
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
.delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; }
.delay-3 { animation-delay: 0.3s; } .delay-4 { animation-delay: 0.4s; }
@keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
.animate-observe { opacity: 0; transform: translateY(28px); transition: opacity 0.7s ease, transform 0.7s ease; }
.animate-observe.delay-1 { transition-delay: 0.12s; } .animate-observe.delay-2 { transition-delay: 0.24s; }
.animate-observe.delay-3 { transition-delay: 0.36s; }
.animate-observe.is-visible { opacity: 1; transform: translateY(0); }
@media (prefers-reduced-motion: reduce) {
  .animate-item, .animate-observe { animation: none !important; transition: none !important; opacity: 1 !important; transform: none !important; }
}

/* Hero */
.membership-hero {
  background: linear-gradient(135deg, var(--primary-red) 0%, var(--primary-red-dark, #9B1530) 100%);
  padding: 100px 0;
  text-align: center;
}
.hero-tag { background: rgba(255,255,255,0.15); color: var(--white); margin-bottom: 25px; }
.membership-hero h1 { font-size: clamp(40px, 6vw, 68px); font-family: 'Playfair Display', serif; color: var(--white); margin-bottom: 15px; }
.hero-kannada { font-size: 24px; color: var(--gold); margin-bottom: 20px; display: block; }
.hero-desc { font-size: 18px; opacity: 0.9; max-width: 600px; margin: 0 auto 35px; color: var(--white); line-height: 1.7; }

/* Process */
.process-section { background: var(--cream); }
.process-steps {
  display: flex;
  align-items: center;
  gap: 0;
  margin-top: 10px;
}
.process-step {
  flex: 1;
  text-align: center;
  padding: 30px 20px;
}
.step-number {
  width: 56px;
  height: 56px;
  background: var(--primary-red);
  color: var(--white);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Playfair Display', serif;
  font-size: 20px;
  font-weight: 700;
  margin: 0 auto 18px;
}
.process-step h4 { font-size: 17px; font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 10px; }
.process-step p { color: var(--text-light); font-size: 14px; line-height: 1.7; margin: 0; }
.process-connector {
  width: 40px;
  flex-shrink: 0;
  height: 2px;
  background: var(--border-light);
  margin-bottom: 50px;
}

/* Pricing */
.pricing-section { background: var(--cream-dark); }
.section-subtitle { color: var(--text-light); font-size: 13px; margin-top: 10px; }
.pricing-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; max-width: 960px; margin: 0 auto; }
.pricing-card {
  background: var(--white);
  border-radius: 24px;
  padding: 45px 36px;
  position: relative;
  box-shadow: 0 5px 20px rgba(0,0,0,0.04);
  transition: box-shadow 0.3s, transform 0.3s;
  display: flex;
  flex-direction: column;
}
.pricing-card:hover { box-shadow: 0 20px 60px rgba(0,0,0,0.1); transform: translateY(-5px); }
.pricing-card.popular { background: var(--primary-red); color: var(--white); }
.popular-badge {
  position: absolute;
  top: -14px;
  left: 50%;
  transform: translateX(-50%);
  background: var(--gold);
  color: var(--text-dark);
  padding: 6px 20px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 700;
  font-family: 'Manrope', sans-serif;
  white-space: nowrap;
}
.plan-icon { font-size: 36px; margin-bottom: 14px; }
.pricing-card h3 { font-size: 26px; font-family: 'Playfair Display', serif; margin-bottom: 6px; }
.pricing-card.popular h3 { color: var(--white); }
.plan-kannada { font-size: 15px; margin-bottom: 18px; color: var(--text-light); }
.pricing-card.popular .plan-kannada { color: rgba(255,255,255,0.75); }
.price { font-size: 52px; font-weight: 700; font-family: 'Playfair Display', serif; margin-bottom: 28px; }
.pricing-card.popular .price { color: var(--white); }
.price span { font-size: 18px; font-weight: 400; opacity: 0.7; }
.pricing-card ul { list-style: none; padding: 0; margin-bottom: 30px; flex: 1; }
.pricing-card ul li {
  padding: 9px 0;
  border-bottom: 1px solid rgba(0,0,0,0.06);
  font-size: 14px;
  display: flex;
  align-items: flex-start;
  gap: 10px;
  color: var(--text-light);
  line-height: 1.5;
}
.pricing-card.popular ul li { color: rgba(255,255,255,0.85); border-bottom-color: rgba(255,255,255,0.15); }
.pricing-card ul li::before { content: '✓'; color: var(--primary-red); font-weight: 700; flex-shrink: 0; margin-top: 1px; }
.pricing-card.popular ul li::before { color: var(--gold); }
.btn-full { width: 100%; justify-content: center; }
.pricing-card.popular .btn-white { background: var(--white); color: var(--primary-red); }
.pricing-card.popular .btn-white:hover { background: rgba(255,255,255,0.9); }

/* Welcome / personal letter */
.welcome-section { background: var(--cream); }
.welcome-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 70px;
  align-items: center;
}
.welcome-letter { display: flex; flex-direction: column; gap: 0; }
.welcome-greeting {
  font-size: 22px;
  color: var(--primary-red);
  margin-bottom: 16px;
  display: block;
}
.welcome-letter h2 {
  font-size: clamp(28px, 3.5vw, 42px);
  font-family: 'Playfair Display', serif;
  color: var(--text-dark);
  line-height: 1.25;
  margin-bottom: 22px;
}
.welcome-letter h2 .red { color: var(--primary-red); }
.welcome-letter p {
  color: var(--text-light);
  font-size: 15px;
  line-height: 1.8;
  margin-bottom: 16px;
}
.welcome-motto {
  font-size: 16px;
  color: var(--primary-red);
  opacity: 0.85;
  margin-bottom: 28px !important;
  font-style: italic;
}
.welcome-image {
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0,0,0,0.1);
}
.welcome-image img {
  width: 100%;
  display: block;
}

/* CTA */
.cta-section { background: var(--cream-dark); }
.cta-box { text-align: center; max-width: 700px; margin: 0 auto; }
.cta-box h2 { font-size: clamp(28px, 4vw, 42px); font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 15px; }
.cta-box p { color: var(--text-light); margin-bottom: 35px; line-height: 1.7; }

/* ── Modal ── */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.55);
  z-index: 9999;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding: 40px 20px;
  overflow-y: auto;
}
.modal-panel {
  background: var(--white);
  border-radius: 20px;
  width: 100%;
  max-width: 700px;
  position: relative;
  box-shadow: 0 30px 80px rgba(0,0,0,0.25);
  margin: auto;
}
.modal-close {
  position: sticky;
  top: 0;
  float: right;
  margin: 18px 18px 0 0;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: 2px solid var(--border-light);
  background: var(--white);
  cursor: pointer;
  font-size: 14px;
  color: var(--text-light);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s, color 0.2s;
  z-index: 10;
}
.modal-close:hover { background: var(--primary-red); color: var(--white); border-color: var(--primary-red); }

/* Success state */
.form-success {
  padding: 60px 50px;
  text-align: center;
}
.success-icon {
  width: 72px;
  height: 72px;
  background: rgba(0,180,80,0.1);
  color: #00B450;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 32px;
  font-weight: 700;
  margin: 0 auto 24px;
}
.form-success h3 { font-size: 28px; font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 14px; }
.form-success p { color: var(--text-light); font-size: 15px; line-height: 1.7; margin-bottom: 10px; }
.success-sub { color: var(--primary-red); font-size: 16px; margin-bottom: 30px; }

/* Modal body / form */
.modal-body { padding: 10px 50px 50px; }
.modal-header { text-align: center; margin-bottom: 35px; }
.modal-header .tag { margin-bottom: 14px; display: inline-block; }
.modal-header h2 { font-size: 28px; font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 6px; }
.modal-subtitle { color: var(--primary-red); font-size: 16px; }

.form-section-title {
  font-size: 16px;
  font-family: 'Playfair Display', serif;
  color: var(--text-dark);
  margin: 30px 0 16px;
  padding-bottom: 10px;
  border-bottom: 2px solid var(--border-light);
}
.form-section-title:first-of-type { margin-top: 0; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.form-row.three-col { grid-template-columns: 1fr 1fr 1fr; }
.form-group { display: flex; flex-direction: column; gap: 7px; margin-bottom: 16px; }
.form-group label { font-size: 13px; font-weight: 600; color: var(--text-dark); font-family: 'Manrope', sans-serif; }
.form-group input,
.form-group textarea,
.form-group select {
  padding: 11px 14px;
  border: 2px solid var(--border-light);
  border-radius: 10px;
  font-size: 14px;
  font-family: 'Manrope', sans-serif;
  color: var(--text-dark);
  background: var(--white);
  transition: border-color 0.2s;
  outline: none;
}
.form-group input:focus,
.form-group textarea:focus { border-color: rgba(196,30,58,0.5); }
.input-readonly { background: var(--cream-dark) !important; color: var(--text-light) !important; cursor: default; }
.required { color: var(--primary-red); }
.field-hint { font-size: 12px; color: var(--text-light); margin: 4px 0 0; line-height: 1.5; }

/* Plan selector */
.plan-selector { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 8px; }
.plan-option {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
  padding: 16px 12px;
  border: 2px solid var(--border-light);
  border-radius: 12px;
  cursor: pointer;
  text-align: center;
  transition: border-color 0.2s, background 0.2s;
}
.plan-option input[type="radio"] { display: none; }
.plan-option.active { border-color: var(--primary-red); background: rgba(196,30,58,0.04); }
.plan-label { font-size: 13px; font-weight: 600; color: var(--text-dark); font-family: 'Manrope', sans-serif; }
.plan-option.active .plan-label { color: var(--primary-red); }
.plan-price { font-size: 16px; font-weight: 700; color: var(--primary-red); font-family: 'Playfair Display', serif; }

/* Alert */
.alert { padding: 14px 18px; border-radius: 10px; font-size: 14px; margin-bottom: 20px; }
.alert-error { background: rgba(196,30,58,0.07); color: var(--primary-red); border: 1px solid rgba(196,30,58,0.2); }

/* Disclaimer */
.disclaimer-box {
  background: var(--cream-dark);
  border-radius: 12px;
  padding: 22px 24px;
  margin-bottom: 24px;
}
.disclaimer-title { font-size: 14px; font-weight: 600; color: var(--text-dark); margin-bottom: 12px; font-family: 'Manrope', sans-serif; }
.disclaimer-list { list-style: none; padding: 0; margin: 0 0 18px; }
.disclaimer-list li {
  font-size: 13px;
  color: var(--text-light);
  line-height: 1.6;
  padding: 5px 0 5px 20px;
  position: relative;
}
.disclaimer-list li::before {
  content: '•';
  position: absolute;
  left: 6px;
  color: var(--primary-red);
}
.disclaimer-check {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  cursor: pointer;
  font-size: 13px;
  color: var(--text-dark);
  font-family: 'Manrope', sans-serif;
  font-weight: 500;
}
.disclaimer-check input[type="checkbox"] { width: 17px; height: 17px; accent-color: var(--primary-red); flex-shrink: 0; margin-top: 2px; cursor: pointer; }

.btn-full { width: 100%; justify-content: center; }
.btn-full:disabled { opacity: 0.5; cursor: not-allowed; }
.form-turnstile { margin-bottom: 12px; }

/* Responsive */
@media (max-width: 1024px) {
  .pricing-grid { grid-template-columns: repeat(3, 1fr); gap: 20px; }
  .welcome-grid { gap: 40px; }
}
@media (max-width: 768px) {
  .process-steps { flex-direction: column; gap: 20px; }
  .process-connector { display: none; }
  .pricing-grid { grid-template-columns: 1fr; max-width: 420px; margin: 0 auto; }
  .welcome-grid { grid-template-columns: 1fr; gap: 30px; }
  .welcome-image { order: -1; }
  .modal-backdrop { padding: 20px 12px; }
  .modal-body { padding: 10px 24px 36px; }
  .form-success { padding: 40px 24px; }
  .form-row { grid-template-columns: 1fr; }
  .form-row.three-col { grid-template-columns: 1fr; }
  .plan-selector { grid-template-columns: 1fr; }
}
</style>
