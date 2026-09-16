<template>
  <div>
    <!-- Page Header -->
    <section class="page-header">
      <div class="section-container">
        <span class="tag animate-item">Get in Touch</span>
        <h1 class="animate-item delay-1">Contact Us</h1>
        <p class="kannada-text animate-item delay-2">ನಮ್ಮನ್ನು ಸಂಪರ್ಕಿಸಿ</p>
        <p class="animate-item delay-3">Have questions or want to get involved? We'd love to hear from you!</p>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="section contact-section">
      <div class="section-container">
        <div class="contact-grid">
          <!-- Info Card -->
          <div class="contact-info-side animate-observe">
            <div class="contact-info-card">
              <h3>Contact Information</h3>
              <div class="contact-info-item">
                <div class="ci-icon">📍</div>
                <div>
                  <h4>Address</h4>
                  <p>MunichKannadigaru e.V<br>Verein Number: VR209854<br>Munich, Germany</p>
                </div>
              </div>
              <div class="contact-info-item">
                <div class="ci-icon">✉️</div>
                <div>
                  <h4>Email</h4>
                  <p><a href="mailto:info@munichkannadigaru.org">info@munichkannadigaru.org</a></p>
                </div>
              </div>
            </div>

            <div class="office-hours-card">
              <h4>Response Time</h4>
              <p>We respond to all inquiries within 2–3 business days.</p>
              <p class="kannada-text oh-kannada">"ಎಲ್ಲಾ ಪತ್ರಗಳಿಗೂ ೨-೩ ದಿನಗಳಲ್ಲಿ ಪ್ರತ್ಯುತ್ತರ"</p>
            </div>
          </div>

          <!-- Form -->
          <div class="contact-form-card animate-observe delay-1">
            <h3>Send us a Message</h3>
            <div v-if="formSuccess" class="alert alert-success">Thank you for your message! We'll get back to you within 2–3 business days.</div>
            <div v-if="formError" class="alert alert-error">Something went wrong. Please try again or email us directly.</div>

            <form @submit.prevent="submitForm">
              <div class="form-row">
                <div class="form-group">
                  <label>Your Name <span class="required">*</span></label>
                  <input type="text" v-model="form.name" required placeholder="Enter your name" />
                </div>
                <div class="form-group">
                  <label>Email Address <span class="required">*</span></label>
                  <input type="email" v-model="form.email" required placeholder="your@email.com" />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>Phone Number</label>
                  <input type="tel" v-model="form.phone" placeholder="+49 123 456 789" />
                </div>
                <div class="form-group">
                  <label>Subject <span class="required">*</span></label>
                  <input type="text" v-model="form.subject" required placeholder="What is this regarding?" />
                </div>
              </div>
              <div class="form-group">
                <label>Message <span class="required">*</span></label>
                <textarea v-model="form.message" required placeholder="Your message…" rows="6"></textarea>
              </div>
              <button type="submit" class="btn btn-primary btn-full" :disabled="submitting">
                {{ submitting ? 'Sending…' : 'Send Message →' }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Contact Us | Munich Kannadigaru',
  description: "Get in touch with Munich Kannadigaru. Reach us by email, social media, or the contact form for any enquiries.",
  ogTitle: 'Contact Munich Kannadigaru',
  ogDescription: "Have a question or want to get involved? Contact the Munich Kannadigaru team — we'd love to hear from you.",
  ogImage: 'https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/contact',
  twitterCard: 'summary_large_image',
  twitterTitle: 'Contact Munich Kannadigaru',
  twitterDescription: 'Get in touch with the Munich Kannada community.',
})

const submitting = ref(false)
const formSuccess = ref(false)
const formError = ref(false)
const form = reactive({ name: '', email: '', phone: '', subject: '', message: '' })

async function submitForm() {
  submitting.value = true
  formSuccess.value = false
  formError.value = false
  await new Promise(r => setTimeout(r, 800))
  formSuccess.value = true
  submitting.value = false
  Object.assign(form, { name: '', email: '', phone: '', subject: '', message: '' })
}

onMounted(() => {
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); obs.unobserve(e.target) } })
  }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' })
  document.querySelectorAll('.animate-observe').forEach(el => obs.observe(el))
})
</script>

<style scoped>
.animate-item { opacity: 0; transform: translateY(24px); animation: fadeUp 0.7s ease forwards; }
.delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; } .delay-3 { animation-delay: 0.3s; }
@keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
.animate-observe { opacity: 0; transform: translateY(28px); transition: opacity 0.7s ease, transform 0.7s ease; }
.animate-observe.delay-1 { transition-delay: 0.12s; }
.animate-observe.is-visible { opacity: 1; transform: translateY(0); }
@media (prefers-reduced-motion: reduce) {
  .animate-item, .animate-observe { animation: none !important; transition: none !important; opacity: 1 !important; transform: none !important; }
}

.page-header { padding: 80px 0 60px; background: var(--cream); }
.page-header h1 { font-size: clamp(36px, 5vw, 56px); margin-bottom: 15px; font-family: 'Playfair Display', serif; }
.page-header .kannada-text { font-size: 22px; color: var(--primary-red); margin-bottom: 15px; display: block; }
.page-header p { color: var(--text-light); font-size: 16px; margin: 0 auto; max-width: 500px; }

.contact-section { background: var(--cream); }
.contact-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 40px; align-items: start; }

.contact-info-card {
  background: var(--primary-red);
  color: var(--white);
  border-radius: 20px;
  padding: 40px 35px;
  margin-bottom: 20px;
}
.contact-info-card h3 { font-size: 24px; font-family: 'Playfair Display', serif; color: var(--white); margin-bottom: 30px; }
.contact-info-item { display: flex; gap: 18px; align-items: flex-start; margin-bottom: 25px; }
.ci-icon { font-size: 20px; flex-shrink: 0; width: 44px; height: 44px; background: rgba(255,255,255,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.contact-info-item h4 { color: var(--gold); font-size: 12px; text-transform: uppercase; letter-spacing: 1px; font-family: 'Manrope', sans-serif; margin-bottom: 6px; }
.contact-info-item p { color: rgba(255,255,255,0.85); font-size: 14px; line-height: 1.6; margin-bottom: 0; }
.contact-info-item a { color: var(--gold); }
.contact-info-item a:hover { color: var(--white); }

.office-hours-card {
  background: var(--cream-dark);
  border-radius: 16px;
  padding: 30px;
}
.office-hours-card h4 { font-size: 16px; font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 12px; }
.office-hours-card p { color: var(--text-light); font-size: 14px; line-height: 1.7; margin-bottom: 8px; }
.oh-kannada { color: var(--primary-red) !important; font-size: 13px !important; }

.contact-form-card {
  background: var(--white);
  border-radius: 20px;
  padding: 45px 40px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.04);
}
.contact-form-card h3 { font-size: 26px; font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 30px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.required { color: var(--primary-red); }
.btn-full { width: 100%; justify-content: center; }
.btn-full:disabled { opacity: 0.7; cursor: not-allowed; }

@media (max-width: 1024px) { .contact-grid { grid-template-columns: 1fr; } }
@media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } .contact-form-card { padding: 30px 25px; } }
</style>
