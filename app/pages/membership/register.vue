<template>
  <div class="container-read">
    <h1 class="page-title">Membership Registration</h1>

    <div v-if="submitted" class="content-card">
      <h2>Thank you!</h2>
      <p>Your registration has been received. We'll be in touch via email shortly.</p>
      <NuxtLink to="/" class="btn btn-outline">Back to home</NuxtLink>
    </div>

    <!-- TODO: wire up to API endpoint — replace @submit.prevent with actual fetch/axios call -->
    <!-- TODO: add CAPTCHA / Turnstile before going live to prevent abuse -->
    <form v-else class="register-form" @submit.prevent="handleSubmit" novalidate>
      <div class="form-group">
        <label class="form-label" for="full-name">Full name</label>
        <input
          id="full-name"
          v-model="form.name"
          class="form-input"
          type="text"
          required
          autocomplete="name"
        />
      </div>

      <div class="form-group">
        <label class="form-label" for="email">Email address</label>
        <input
          id="email"
          v-model="form.email"
          class="form-input"
          type="email"
          required
          autocomplete="email"
        />
      </div>

      <div class="form-group">
        <label class="form-label" for="phone">Phone number (optional)</label>
        <input
          id="phone"
          v-model="form.phone"
          class="form-input"
          type="tel"
          autocomplete="tel"
        />
      </div>

      <div class="form-group">
        <label class="form-label" for="membership-type">Membership type</label>
        <select
          id="membership-type"
          v-model="form.membershipType"
          class="form-select"
          required
        >
          <option value="" disabled>Select a type</option>
          <option value="individual">Individual</option>
          <option value="family">Family</option>
          <option value="student">Student</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label" for="address">City / address (optional)</label>
        <input
          id="address"
          v-model="form.address"
          class="form-input"
          type="text"
          autocomplete="address-level2"
        />
      </div>

      <p v-if="error" class="form-error" role="alert">{{ error }}</p>

      <button type="submit" class="btn btn-primary" :disabled="loading">
        {{ loading ? 'Submitting…' : 'Submit registration' }}
      </button>
    </form>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Register — Munich Kannadigaru',
  description: 'Register for membership in Kannada Community Munich.'
})

const form = reactive({
  name: '',
  email: '',
  phone: '',
  membershipType: '',
  address: ''
})

const submitted = ref(false)
const loading = ref(false)
const error = ref('')

async function handleSubmit() {
  error.value = ''
  if (!form.name || !form.email || !form.membershipType) {
    error.value = 'Please fill in your name, email, and membership type.'
    return
  }

  loading.value = true
  try {
    // TODO: replace with real API call
    // await $fetch('/api/membership', { method: 'POST', body: form })
    await new Promise(resolve => setTimeout(resolve, 600))
    submitted.value = true
  } catch {
    error.value = 'Something went wrong. Please email kannadacommunitymunich@gmail.com directly.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.register-form { max-width: 540px; }

.form-error {
  color: var(--brand);
  font-size: 0.9rem;
  margin-bottom: var(--space-md);
}
</style>
