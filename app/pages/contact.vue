<template>
  <div class="container-read">
    <h1 class="page-title">Contact Us</h1>

    <div class="content-card">
      <h2>Get in touch</h2>
      <ul class="contact-list">
        <li>
          <strong>Email</strong>
          <a href="mailto:kannadacommunitymunich@gmail.com">kannadacommunitymunich@gmail.com</a>
        </li>
        <li>
          <strong>Location</strong>
          Munich, Germany
        </li>
      </ul>
    </div>

    <div v-if="submitted" class="content-card">
      <h2>Message sent!</h2>
      <p>Thank you for reaching out. We'll get back to you as soon as possible.</p>
    </div>

    <!-- TODO: wire up to API endpoint — replace @submit.prevent with actual fetch/axios call -->
    <!-- TODO: add CAPTCHA / Turnstile before going live to prevent abuse -->
    <form v-else class="content-card contact-form" @submit.prevent="handleSubmit" novalidate>
      <h2>Send a message</h2>

      <div class="form-group">
        <label class="form-label" for="contact-name">Your name</label>
        <input
          id="contact-name"
          v-model="form.name"
          class="form-input"
          type="text"
          required
          autocomplete="name"
        />
      </div>

      <div class="form-group">
        <label class="form-label" for="contact-email">Email address</label>
        <input
          id="contact-email"
          v-model="form.email"
          class="form-input"
          type="email"
          required
          autocomplete="email"
        />
      </div>

      <div class="form-group">
        <label class="form-label" for="contact-message">Message</label>
        <textarea
          id="contact-message"
          v-model="form.message"
          class="form-textarea"
          required
        ></textarea>
      </div>

      <p v-if="error" class="form-error" role="alert">{{ error }}</p>

      <button type="submit" class="btn btn-primary" :disabled="loading">
        {{ loading ? 'Sending…' : 'Send message' }}
      </button>
    </form>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Contact — Munich Kannadigaru',
  description: 'Contact Kannada Community Munich — reach us by email or send a message.'
})

const form = reactive({ name: '', email: '', message: '' })
const submitted = ref(false)
const loading = ref(false)
const error = ref('')

async function handleSubmit() {
  error.value = ''
  if (!form.name || !form.email || !form.message) {
    error.value = 'Please fill in all fields.'
    return
  }
  loading.value = true
  try {
    // TODO: replace with real API call
    // await $fetch('/api/contact', { method: 'POST', body: form })
    await new Promise(resolve => setTimeout(resolve, 600))
    submitted.value = true
  } catch {
    error.value = 'Could not send message. Please email us directly at kannadacommunitymunich@gmail.com.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.contact-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: var(--space-md);
}
.contact-list li {
  display: flex;
  flex-direction: column;
  gap: 2px;
  margin: 0;
}
.contact-list strong {
  font-size: 0.8rem;
  color: var(--ink-muted);
}

.contact-form h2 {
  margin-bottom: var(--space-lg);
}

.form-error {
  color: var(--brand);
  font-size: 0.9rem;
  margin-bottom: var(--space-md);
}
</style>
