<template>
  <div>
    <section class="page-header">
      <div class="section-container">
        <span class="tag animate-item">Legal</span>
        <h1 class="animate-item delay-1">Privacy Policy</h1>
        <p class="kannada-text animate-item delay-2">ಗೌಪ್ಯತಾ ನೀತಿ</p>
      </div>
    </section>

    <section class="section privacy-section">
      <div class="section-container">
        <div class="privacy-card animate-observe">
          <div class="privacy-item">
            <h3>1. Introduction</h3>
            <p>Munich Kannadigaru e.V. ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or become a member of our community.</p>
          </div>

          <div class="privacy-item">
            <h3>2. Information We Collect</h3>
            <p>We may collect the following types of information:</p>
            <ul>
              <li><strong>Personal Information:</strong> Name, email address, phone number, postal address, and other information you provide when registering for membership or events.</li>
              <li><strong>Membership Information:</strong> Membership type, payment details, and family members included in membership.</li>
              <li><strong>Communication Data:</strong> Messages, inquiries, and feedback you send us.</li>
              <li><strong>Technical Data:</strong> IP address, browser type, device information, and cookies.</li>
            </ul>
          </div>

          <div class="privacy-item">
            <h3>3. How We Use Your Information</h3>
            <p>We use your information for the following purposes:</p>
            <ul>
              <li>To process membership registration and maintain membership records</li>
              <li>To communicate with you about events, activities, and community updates</li>
              <li>To process event registrations and payments</li>
              <li>To improve our website and services</li>
              <li>To comply with legal obligations</li>
            </ul>
          </div>

          <div class="privacy-item">
            <h3>4. Your Rights Under GDPR</h3>
            <p>Under GDPR, you have the following rights:</p>
            <ul>
              <li>Right to access your personal data</li>
              <li>Right to rectification of inaccurate data</li>
              <li>Right to erasure ("right to be forgotten")</li>
              <li>Right to restrict processing</li>
              <li>Right to data portability</li>
            </ul>
          </div>

          <div class="privacy-item">
            <h3>5. Contact Us</h3>
            <p>If you have any questions about this Privacy Policy or your personal data, please contact us at:</p>
            <p><strong>Email:</strong> <a href="mailto:info@munichkannadigaru.org">info@munichkannadigaru.org</a></p>
            <p><strong>Address:</strong> MunichKannadigaru e.V., Verein Number: VR209854, Munich, Germany</p>
          </div>

          <p class="privacy-updated">Last Updated: September 2026</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Privacy Policy | Munich Kannadigaru',
  description: 'Privacy policy for Munich Kannadigaru — how we collect, use, and protect your personal data in line with GDPR.',
  ogTitle: 'Privacy Policy | Munich Kannadigaru',
  ogDescription: 'Our GDPR-compliant privacy policy explaining how we handle your personal data.',
  ogImage: 'https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/privacy-policy',
  twitterCard: 'summary',
  twitterTitle: 'Privacy Policy | Munich Kannadigaru',
  twitterDescription: 'GDPR privacy policy for Munich Kannadigaru.',
})
onMounted(() => {
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); obs.unobserve(e.target) } })
  }, { threshold: 0.12 })
  document.querySelectorAll('.animate-observe').forEach(el => obs.observe(el))
})
</script>

<style scoped>
.animate-item { opacity: 0; transform: translateY(24px); animation: fadeUp 0.7s ease forwards; }
.delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; }
@keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
.animate-observe { opacity: 0; transform: translateY(28px); transition: opacity 0.7s ease, transform 0.7s ease; }
.animate-observe.is-visible { opacity: 1; transform: translateY(0); }
@media (prefers-reduced-motion: reduce) {
  .animate-item, .animate-observe { animation: none !important; transition: none !important; opacity: 1 !important; transform: none !important; }
}

.page-header { padding: 80px 0 60px; background: var(--cream); text-align: center; }
.page-header h1 { font-size: clamp(30px, 5vw, 50px); margin-bottom: 15px; font-family: 'Playfair Display', serif; }
.page-header .kannada-text { font-size: 20px; color: var(--primary-red); margin-bottom: 0; display: block; }

.privacy-section { background: var(--cream-dark); }
.privacy-card { max-width: 900px; margin: 0 auto; background: var(--white); border-radius: 20px; padding: 50px; box-shadow: 0 5px 20px rgba(0,0,0,0.04); }
.privacy-item { margin-bottom: 35px; padding-bottom: 30px; border-bottom: 1px solid var(--border-light); }
.privacy-item:last-of-type { border-bottom: none; margin-bottom: 20px; }
.privacy-item h3 { font-size: 22px; font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 15px; }
.privacy-item p { color: var(--text-light); line-height: 1.8; margin-bottom: 10px; }
.privacy-item p:last-child { margin-bottom: 0; }
.privacy-item a { color: var(--primary-red); }
.privacy-item ul { color: var(--text-light); line-height: 1.8; padding-left: 25px; }
.privacy-item ul li { margin-bottom: 8px; }
.privacy-updated { color: var(--text-light); font-size: 13px; padding-top: 20px; border-top: 1px solid var(--border-light); margin-bottom: 0; }

@media (max-width: 768px) { .privacy-card { padding: 30px 25px; } }
</style>
