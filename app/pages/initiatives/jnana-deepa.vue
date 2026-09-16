<template>
  <div>
    <section class="initiative-hero">
      <div class="section-container">
        <span class="tag animate-item">Initiative</span>
        <h1 class="animate-item delay-1">Jnana Deepa</h1>
        <p class="hero-kannada kannada-text animate-item delay-2">ಜ್ಞಾನ ದೀಪ</p>
        <p class="hero-desc animate-item delay-3">Our Kannada library initiative promoting literature and knowledge sharing among the community.</p>
      </div>
    </section>

    <section class="section library-section">
      <div class="section-container">
        <div class="section-header animate-observe">
          <span class="tag">Our Collection</span>
          <h2 class="section-title">
            <span class="black">What We</span>
            <span class="red"> Offer</span>
          </h2>
        </div>

        <div class="library-grid">
          <div class="library-card animate-observe">
            <div class="library-icon">📚</div>
            <h3>Classic Literature</h3>
            <p class="kannada-text library-kn">ಶ್ರೇಷ್ಠ ಸಾಹಿತ್ಯ</p>
            <p>Works by legendary Kannada authors including Kuvempu, Bendre, Shivaram Karanth, and U.R. Ananthamurthy.</p>
          </div>
          <div class="library-card animate-observe delay-1">
            <div class="library-icon">📖</div>
            <h3>Children's Books</h3>
            <p class="kannada-text library-kn">ಮಕ್ಕಳ ಸಾಹಿತ್ಯ</p>
            <p>A special collection of Kannada children's literature to help young ones connect with their roots.</p>
          </div>
          <div class="library-card animate-observe delay-2">
            <div class="library-icon">📰</div>
            <h3>Magazines &amp; Periodicals</h3>
            <p class="kannada-text library-kn">ನಿಯತಕಾಲಿಕೆಗಳು</p>
            <p>Current and archived issues of popular Kannada magazines and newspapers.</p>
          </div>
          <div class="library-card animate-observe">
            <div class="library-icon">🎓</div>
            <h3>Learning Resources</h3>
            <p class="kannada-text library-kn">ಕಲಿಕಾ ಸಾಮಗ್ರಿ</p>
            <p>Textbooks, reference materials, and learning resources for Kannada language students.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="section cta-section">
      <div class="section-container">
        <div class="cta-box animate-observe">
          <h2>Access Our Library</h2>
          <p>Become a member of Munich Kannadigaru to access our growing collection of Kannada literature and resources.</p>
          <div class="cta-buttons">
            <NuxtLink to="/membership" class="btn btn-primary">Become a Member</NuxtLink>
            <NuxtLink to="/contact" class="btn btn-outline">Contact Us</NuxtLink>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Jnana Deepa | Munich Kannadigaru',
  description: 'Our Kannada library initiative promoting literature and knowledge sharing among the community.'
})
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
.animate-observe.delay-1 { transition-delay: 0.12s; } .animate-observe.delay-2 { transition-delay: 0.24s; }
.animate-observe.is-visible { opacity: 1; transform: translateY(0); }
@media (prefers-reduced-motion: reduce) {
  .animate-item, .animate-observe { animation: none !important; transition: none !important; opacity: 1 !important; transform: none !important; }
}

.initiative-hero {
  background: linear-gradient(135deg, #D4A73B 0%, #B8860B 100%);
  color: var(--white);
  padding: 100px 0;
  text-align: center;
}
.initiative-hero .tag { background: rgba(255,255,255,0.15); color: var(--white); margin-bottom: 25px; }
.initiative-hero h1 { font-size: clamp(40px, 6vw, 68px); font-family: 'Playfair Display', serif; color: var(--white); margin-bottom: 15px; }
.hero-kannada { font-size: clamp(22px, 3vw, 30px); color: var(--white); opacity: 0.9; margin-bottom: 20px; display: block; }
.hero-desc { font-size: 18px; opacity: 0.9; max-width: 600px; margin: 0 auto; color: var(--white); }

.library-section { background: var(--cream); }
.library-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; }
.library-card {
  background: var(--white);
  border-radius: 20px;
  padding: 40px 35px;
  transition: box-shadow 0.3s, transform 0.3s;
}
.library-card:hover { box-shadow: 0 15px 40px rgba(0,0,0,0.08); transform: translateY(-4px); }
.library-icon { font-size: 36px; margin-bottom: 20px; }
.library-card h3 { font-size: 22px; font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 8px; }
.library-kn { color: var(--gold-dark); font-size: 14px; margin-bottom: 12px; display: block; }
.library-card p { color: var(--text-light); font-size: 15px; line-height: 1.7; margin-bottom: 0; }

.cta-section { background: var(--cream-dark); }
.cta-box { text-align: center; max-width: 700px; margin: 0 auto; }
.cta-box h2 { font-size: clamp(28px, 4vw, 42px); font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 15px; }
.cta-box p { color: var(--text-light); margin-bottom: 35px; }
.cta-buttons { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; }

@media (max-width: 768px) {
  .library-grid { grid-template-columns: 1fr; }
}
</style>
