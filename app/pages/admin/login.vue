<template>
  <div class="admin-login-page">
    <div class="login-card">
      <div class="login-logo">
        <span class="login-logo-kannada kannada-text">ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು</span>
        <h1>Admin Portal</h1>
      </div>

      <!-- Step 1: Email -->
      <form v-if="step === 'email'" @submit.prevent="sendOtp" class="login-form">
        <p class="login-hint">Enter your admin email to receive a one-time password.</p>
        <div class="form-group">
          <label for="email">Email Address</label>
          <input
            id="email"
            v-model="email"
            type="email"
            required
            autocomplete="email"
            placeholder="you@example.com"
            :disabled="sending"
          />
        </div>
        <div v-if="emailError" class="alert alert-error">{{ emailError }}</div>
        <button type="submit" class="btn-admin btn-admin-primary" :disabled="sending">
          <span v-if="sending" class="spinner"></span>
          {{ sending ? 'Sending…' : 'Send OTP' }}
        </button>
      </form>

      <!-- Step 2: OTP -->
      <form v-else @submit.prevent="verifyOtp" class="login-form">
        <p class="login-hint">
          A 6-digit code was sent to <strong>{{ email }}</strong>.
          <button type="button" class="link-btn" @click="step = 'email'">Change email</button>
        </p>
        <div class="form-group">
          <label for="otp">One-Time Password</label>
          <input
            id="otp"
            ref="otpInput"
            v-model="otp"
            type="text"
            inputmode="numeric"
            pattern="[0-9]{6}"
            maxlength="6"
            required
            placeholder="123456"
            :disabled="verifying"
            class="otp-input"
          />
        </div>
        <div v-if="otpError" class="alert alert-error">{{ otpError }}</div>
        <button type="submit" class="btn-admin btn-admin-primary" :disabled="verifying || otp.length < 6">
          <span v-if="verifying" class="spinner"></span>
          {{ verifying ? 'Verifying…' : 'Log In' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
definePageMeta({ layout: false })

const { public: { apiBaseUrl } } = useRuntimeConfig()
const base = apiBaseUrl.replace(/\/$/, '')

const step     = ref('email')
const email    = ref('')
const otp      = ref('')
const sending  = ref(false)
const verifying = ref(false)
const emailError = ref('')
const otpError   = ref('')
const otpInput   = ref(null)

async function sendOtp() {
  sending.value = true
  emailError.value = ''
  try {
    await useAdminFetch(`${base}/admin/send-otp.php`, {
      method: 'POST',
      body: { email: email.value }
    })
    step.value = 'otp'
    await nextTick()
    otpInput.value?.focus()
  } catch (e) {
    emailError.value = e?.data?.error || 'Failed to send OTP. Please try again.'
  } finally {
    sending.value = false
  }
}

async function verifyOtp() {
  verifying.value = true
  otpError.value = ''
  try {
    await useAdminFetch(`${base}/admin/verify-otp.php`, {
      method: 'POST',
      body: { email: email.value, otp: otp.value }
    })
    // Set the hint cookie client-side so the admin-auth middleware can read it
    const hint = useCookie('admin_session_hint', { maxAge: 3600, path: '/' })
    hint.value = '1'
    await navigateTo('/admin/events')
  } catch (e) {
    otpError.value = e?.data?.error || 'Invalid or expired OTP.'
    otp.value = ''
    otpInput.value?.focus()
  } finally {
    verifying.value = false
  }
}
</script>

<style scoped>
.admin-login-page {
  --admin-font-family: 'Manrope', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #F7F4F0;
  padding: 24px;
  font-family: var(--admin-font-family);
}
.login-card {
  background: #fff;
  border-radius: 20px;
  padding: 48px 44px;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 4px 40px rgba(0,0,0,0.08);
}
.login-logo { text-align: center; margin-bottom: 36px; }
.login-logo-kannada { display: block; font-size: 15px; color: #B91C1C; margin-bottom: 8px; }
.login-logo h1 { font-family: var(--admin-font-family); font-size: 28px; font-weight: 700; color: #1A1A1A; margin: 0; }
.login-hint { font-size: 14px; color: #6B7280; margin-bottom: 24px; line-height: 1.6; }
.login-hint strong { color: #1A1A1A; }
.login-form { display: flex; flex-direction: column; gap: 20px; }
.form-group { display: flex; flex-direction: column; gap: 8px; }
.form-group label { font-size: 13px; font-weight: 600; color: #374151; }
.form-group input {
  padding: 12px 16px;
  border: 1.5px solid #E5E7EB;
  border-radius: 10px;
  font-size: 16px;
  font-family: var(--admin-font-family);
  transition: border-color 0.2s;
  outline: none;
  color: #1A1A1A;
}
.form-group input:focus { border-color: #B91C1C; }
.form-group input:disabled { background: #F9FAFB; color: #9CA3AF; }
.otp-input { letter-spacing: 0.3em; font-size: 22px; text-align: center; }
.btn-admin {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  padding: 13px 20px; border-radius: 10px; border: none;
  font-size: 15px; font-weight: 600; font-family: var(--admin-font-family);
  cursor: pointer; transition: all 0.2s;
}
.btn-admin-primary { background: #B91C1C; color: #fff; }
.btn-admin-primary:hover:not(:disabled) { background: #991B1B; }
.btn-admin-primary:disabled { opacity: 0.6; cursor: not-allowed; }
.alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; }
.alert-error { background: #FEE2E2; color: #991B1B; }
.link-btn { background: none; border: none; color: #B91C1C; cursor: pointer; font-size: 13px; padding: 0; text-decoration: underline; }
.spinner {
  width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.4);
  border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>
