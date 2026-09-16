<template>
  <div>
    <section class="page-header">
      <div class="section-container">
        <span class="tag animate-item">Legal</span>
        <h1 class="animate-item delay-1">Community Resolutions</h1>
        <p class="kannada-text animate-item delay-2">ಸಮುದಾಯ ತೀರ್ಮಾನಗಳು</p>
      </div>
    </section>

    <section class="section resolutions-section">
      <div class="section-container">
        <div class="resolutions-card animate-observe">
          <div class="resolution-item">
            <h3>1. Association Registration</h3>
            <p>Munich Kannadigaru e.V. is officially registered as a non-profit association (eingetragener Verein) in Germany with Verein Number: VR209854.</p>
          </div>

          <div class="resolution-item">
            <h3>2. Purpose of the Association</h3>
            <p>The purpose of Munich Kannadigaru e.V. is to:</p>
            <ul>
              <li>Promote and preserve Kannada culture, language, and traditions in Munich and surrounding areas</li>
              <li>Create a supportive community for Kannada-speaking individuals and families</li>
              <li>Organize cultural, social, and educational events</li>
              <li>Foster integration while maintaining cultural identity</li>
            </ul>
          </div>

          <div class="resolution-item">
            <h3>3. Membership</h3>
            <p>Membership is open to all individuals who:</p>
            <ul>
              <li>Support the objectives of the association</li>
              <li>Are willing to abide by the association's bylaws</li>
              <li>Pay the applicable membership fees</li>
            </ul>
          </div>

          <div class="resolution-item">
            <h3>4. Membership Types and Fees</h3>
            <ul>
              <li><strong>Family Membership:</strong> €50 per year — Includes parents and children under 18</li>
              <li><strong>Individual Membership:</strong> €30 per year — For single adults</li>
            </ul>
          </div>

          <p class="resolution-updated">Last Updated: September 2026</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Community Resolutions | Munich Kannadigaru',
  description: 'Official resolutions and governance decisions of Munich Kannadigaru e.V. — a record of our community commitments.',
  ogTitle: 'Munich Kannadigaru — Community Resolutions',
  ogDescription: 'Official resolutions and governance decisions of Munich Kannadigaru e.V.',
  ogImage: 'https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/resolutions',
  twitterCard: 'summary_large_image',
  twitterTitle: 'Munich Kannadigaru — Resolutions',
  twitterDescription: 'Official resolutions of the Munich Kannadigaru community.',
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

.resolutions-section { background: var(--cream-dark); }
.resolutions-card { max-width: 900px; margin: 0 auto; background: var(--white); border-radius: 20px; padding: 50px; box-shadow: 0 5px 20px rgba(0,0,0,0.04); }
.resolution-item { margin-bottom: 40px; padding-bottom: 35px; border-bottom: 1px solid var(--border-light); }
.resolution-item:last-of-type { border-bottom: none; margin-bottom: 20px; }
.resolution-item h3 { font-size: 22px; font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 15px; }
.resolution-item p { color: var(--text-light); line-height: 1.8; }
.resolution-item ul { color: var(--text-light); line-height: 1.8; padding-left: 25px; }
.resolution-item ul li { margin-bottom: 8px; }
.resolution-updated { color: var(--text-light); font-size: 13px; padding-top: 20px; border-top: 1px solid var(--border-light); margin-bottom: 0; }

@media (max-width: 768px) { .resolutions-card { padding: 30px 25px; } }
</style>
