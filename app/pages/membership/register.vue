<template>
  <div>
    <section class="page-header">
      <div class="section-container">
        <span class="tag animate-item">Join Us</span>
        <h1 class="animate-item delay-1">Membership Registration</h1>
        <p class="kannada-text animate-item delay-2">ಸದಸ್ಯತ್ವ ನೋಂದಣಿ</p>
        <p class="animate-item delay-3">Fill in the form below to become a member of Munich Kannadigaru.</p>
      </div>
    </section>

    <section class="section form-section">
      <div class="section-container">
        <div class="form-container animate-observe">
          <div class="form-header">
            <span class="tag">Membership Form</span>
            <h2>Complete Your Registration</h2>
            <p class="kannada-text form-kn">ನೋಂದಣಿ ಪೂರ್ಣಗೊಳಿಸಿ</p>
          </div>

          <div v-if="formSuccess" class="alert alert-success">Thank you for registering! We'll send you a confirmation email shortly. Welcome to Munich Kannadigaru!</div>
          <div v-if="formError" class="alert alert-error">Something went wrong. Please try again or email us at info@munichkannadigaru.org</div>

          <form @submit.prevent="submitForm">
            <input type="hidden" v-model="form.type" />

            <h3 class="form-section-title">Personal Information</h3>
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

            <h3 class="form-section-title">Membership Type</h3>
            <div class="membership-type-selector">
              <label class="type-option" :class="{ active: form.type === 'individual' }">
                <input type="radio" v-model="form.type" value="individual" />
                <div class="type-content">
                  <span class="type-name">Individual</span>
                  <span class="type-price">€30/year</span>
                </div>
              </label>
              <label class="type-option" :class="{ active: form.type === 'family' }">
                <input type="radio" v-model="form.type" value="family" />
                <div class="type-content">
                  <span class="type-name">Family</span>
                  <span class="type-price">€50/year</span>
                </div>
              </label>
            </div>

            <h3 class="form-section-title">Address</h3>
            <div class="form-group">
              <label>Street Address <span class="required">*</span></label>
              <input type="text" v-model="form.address" required placeholder="Enter your street address" />
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>City <span class="required">*</span></label>
                <input type="text" v-model="form.city" required placeholder="Munich" />
              </div>
              <div class="form-group">
                <label>Postal Code <span class="required">*</span></label>
                <input type="text" v-model="form.postalCode" required placeholder="80331" />
              </div>
            </div>

            <div v-if="form.type === 'family'">
              <h3 class="form-section-title">Family Information</h3>
              <div class="form-group">
                <label>Family Members (Names and Ages)</label>
                <textarea v-model="form.familyMembers" placeholder="e.g., Spouse: Name (Age), Child 1: Name (Age)" rows="3"></textarea>
              </div>
            </div>

            <h3 class="form-section-title">Areas of Interest</h3>
            <div class="checkbox-group">
              <label class="checkbox-item" v-for="interest in interests" :key="interest">
                <input type="checkbox" :value="interest" v-model="form.interests" />
                <span>{{ interest }}</span>
              </label>
            </div>

            <button type="submit" class="btn btn-primary btn-full" :disabled="submitting">
              {{ submitting ? 'Processing…' : 'Complete Registration →' }}
            </button>
          </form>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Membership Registration | Munich Kannadigaru',
  description: 'Register as a member of Munich Kannadigaru and join our vibrant Kannada community in Munich.'
})

const route = useRoute()
const submitting = ref(false)
const formSuccess = ref(false)
const formError = ref(false)

const interests = ['Cultural Events', 'Music & Dance', 'Language Learning', 'Social Gatherings', 'Volunteering', 'Sports & Fitness']

const form = reactive({
  type: route.query.type || 'individual',
  firstName: '', lastName: '', email: '', phone: '',
  address: '', city: '', postalCode: '',
  familyMembers: '', interests: []
})

async function submitForm() {
  submitting.value = true
  formSuccess.value = false
  formError.value = false
  await new Promise(r => setTimeout(r, 1000))
  formSuccess.value = true
  submitting.value = false
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
.animate-observe.is-visible { opacity: 1; transform: translateY(0); }
@media (prefers-reduced-motion: reduce) {
  .animate-item, .animate-observe { animation: none !important; transition: none !important; opacity: 1 !important; transform: none !important; }
}

.page-header { padding: 80px 0 60px; background: var(--cream); text-align: center; }
.page-header h1 { font-size: clamp(30px, 5vw, 50px); margin-bottom: 15px; font-family: 'Playfair Display', serif; }
.page-header .kannada-text { font-size: 20px; color: var(--primary-red); margin-bottom: 15px; display: block; }
.page-header p { color: var(--text-light); font-size: 16px; }

.form-section { background: var(--cream-dark); }
.form-container { max-width: 780px; margin: 0 auto; background: var(--white); border-radius: 24px; padding: 50px 50px; box-shadow: 0 5px 30px rgba(0,0,0,0.05); }
.form-header { text-align: center; margin-bottom: 40px; }
.form-header .tag { margin-bottom: 15px; }
.form-header h2 { font-size: 30px; font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 8px; }
.form-kn { color: var(--primary-red); font-size: 16px; margin-bottom: 0; }
.form-section-title { font-size: 18px; font-family: 'Playfair Display', serif; color: var(--text-dark); margin: 35px 0 20px; padding-bottom: 10px; border-bottom: 2px solid var(--border-light); }
.form-section-title:first-of-type { margin-top: 0; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.required { color: var(--primary-red); }

.membership-type-selector { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 5px; }
.type-option { display: flex; align-items: center; gap: 0; cursor: pointer; }
.type-option input[type="radio"] { display: none; }
.type-content {
  width: 100%;
  padding: 20px 25px;
  border: 2px solid var(--border-light);
  border-radius: 14px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: all 0.3s;
}
.type-option.active .type-content { border-color: var(--primary-red); background: rgba(196,30,58,0.04); }
.type-name { font-weight: 600; font-size: 16px; color: var(--text-dark); }
.type-price { font-size: 18px; font-weight: 700; font-family: 'Playfair Display', serif; color: var(--primary-red); }

.checkbox-group { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 30px; }
.checkbox-item { display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 14px; color: var(--text-light); }
.checkbox-item input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--primary-red); cursor: pointer; }

.btn-full { width: 100%; justify-content: center; margin-top: 10px; }
.btn-full:disabled { opacity: 0.7; cursor: not-allowed; }

@media (max-width: 768px) {
  .form-container { padding: 30px 25px; }
  .form-row { grid-template-columns: 1fr; }
  .membership-type-selector { grid-template-columns: 1fr; }
  .checkbox-group { grid-template-columns: repeat(2, 1fr); }
}
</style>
