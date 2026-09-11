<template>
  <div>
    <section class="initiative-hero">
      <div class="section-container">
        <span class="tag animate-item">Initiative</span>
        <h1 class="animate-item delay-1">Karnataka Cultural</h1>
        <p class="hero-kannada kannada-text animate-item delay-2">ಕರ್ನಾಟಕ ಸಾಂಸ್ಕೃತಿಕ</p>
        <p class="hero-desc animate-item delay-3">Celebrating and preserving the rich cultural heritage of Karnataka through various artistic programs and events in Munich.</p>
      </div>
    </section>

    <section class="section activities-section">
      <div class="section-container">
        <div class="section-header animate-observe">
          <span class="tag">Our Programs</span>
          <h2 class="section-title">
            <span class="black">Cultural</span>
            <span class="red"> Activities</span>
          </h2>
        </div>

        <div class="activities-grid">
          <div class="activity-card animate-observe">
            <div class="activity-icon">🎵</div>
            <div class="activity-content">
              <h3>Classical Music</h3>
              <p class="kannada-text activity-kn">ಶಾಸ್ತ್ರೀಯ ಸಂಗೀತ</p>
              <p>Carnatic music concerts and workshops featuring local and visiting artists.</p>
            </div>
          </div>
          <div class="activity-card animate-observe delay-1">
            <div class="activity-icon">💃</div>
            <div class="activity-content">
              <h3>Traditional Dance</h3>
              <p class="kannada-text activity-kn">ಸಾಂಪ್ರದಾಯಿಕ ನೃತ್ಯ</p>
              <p>Bharatanatyam, Yakshagana, and folk dance performances and classes.</p>
            </div>
          </div>
          <div class="activity-card animate-observe delay-2">
            <div class="activity-icon">🎨</div>
            <div class="activity-content">
              <h3>Art &amp; Craft</h3>
              <p class="kannada-text activity-kn">ಕಲೆ ಮತ್ತು ಕಸುಬು</p>
              <p>Workshops on traditional Karnataka arts including Mysore paintings and rangoli.</p>
            </div>
          </div>
          <div class="activity-card animate-observe">
            <div class="activity-icon">🎭</div>
            <div class="activity-content">
              <h3>Drama &amp; Theater</h3>
              <p class="kannada-text activity-kn">ನಾಟಕ ಮತ್ತು ರಂಗಭೂಮಿ</p>
              <p>Kannada plays and theatrical performances bringing our literature to life.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section cta-section">
      <div class="section-container">
        <div class="cta-box animate-observe">
          <h2>Join Our Cultural Events</h2>
          <p>Be a part of our vibrant cultural community and help preserve Karnataka's rich heritage in Munich.</p>
          <div class="cta-buttons">
            <NuxtLink to="/events/utsava-2025" class="btn btn-primary">View Upcoming Events</NuxtLink>
            <NuxtLink to="/contact" class="btn btn-outline">Get in Touch</NuxtLink>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Karnataka Cultural | Munich Kannadigaru',
  description: 'Celebrating and preserving the rich cultural heritage of Karnataka through various artistic programs and events in Munich.'
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
  background: linear-gradient(135deg, var(--primary-red) 0%, var(--primary-red-dark) 100%);
  color: var(--white);
  padding: 100px 0;
  text-align: center;
}
.initiative-hero .tag { background: rgba(255,255,255,0.15); color: var(--white); margin-bottom: 25px; }
.initiative-hero h1 { font-size: clamp(40px, 6vw, 68px); font-family: 'Playfair Display', serif; color: var(--white); margin-bottom: 15px; }
.hero-kannada { font-size: clamp(22px, 3vw, 30px); color: var(--gold); margin-bottom: 20px; display: block; }
.hero-desc { font-size: 18px; opacity: 0.9; max-width: 600px; margin: 0 auto; color: var(--white); }

.activities-section { background: var(--cream); }
.activities-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; }
.activity-card {
  background: var(--white);
  border-radius: 20px;
  padding: 35px;
  display: flex;
  gap: 25px;
  align-items: flex-start;
  transition: box-shadow 0.3s, transform 0.3s;
}
.activity-card:hover { box-shadow: 0 15px 40px rgba(0,0,0,0.08); transform: translateY(-4px); }
.activity-icon { font-size: 36px; flex-shrink: 0; width: 60px; height: 60px; background: rgba(196,30,58,0.08); border-radius: 14px; display: flex; align-items: center; justify-content: center; }
.activity-content h3 { font-size: 22px; font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 6px; }
.activity-kn { color: var(--primary-red); font-size: 14px; margin-bottom: 10px; display: block; }
.activity-content p { color: var(--text-light); font-size: 15px; line-height: 1.7; margin-bottom: 0; }

.cta-section { background: var(--cream-dark); }
.cta-box { text-align: center; max-width: 700px; margin: 0 auto; }
.cta-box h2 { font-size: clamp(28px, 4vw, 42px); font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 15px; }
.cta-box p { color: var(--text-light); margin-bottom: 35px; }
.cta-buttons { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; }

@media (max-width: 768px) {
  .activities-grid { grid-template-columns: 1fr; }
  .activity-card { flex-direction: column; }
}
</style>
