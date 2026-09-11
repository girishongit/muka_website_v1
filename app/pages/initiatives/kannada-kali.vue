<template>
  <div>
    <section class="initiative-hero">
      <div class="section-container">
        <span class="tag animate-item">Initiative</span>
        <h1 class="animate-item delay-1">Kannada Kali</h1>
        <p class="hero-kannada kannada-text animate-item delay-2">ಕನ್ನಡ ಕಲಿ</p>
        <p class="hero-desc animate-item delay-3">Learn Kannada through our structured learning programs for all age groups. Whether you're a child growing up abroad or an adult looking to reconnect with your roots.</p>
      </div>
    </section>

    <section class="section courses-section">
      <div class="section-container">
        <div class="section-header animate-observe">
          <span class="tag">Learning Paths</span>
          <h2 class="section-title">
            <span class="black">Our</span>
            <span class="red"> Courses</span>
          </h2>
        </div>

        <div class="courses-grid">
          <div class="course-card animate-observe">
            <div class="course-level">Beginner</div>
            <h3>ಪ್ರಾರಂಭಿಕ ಕನ್ನಡ</h3>
            <p class="course-en">Beginners</p>
            <p>Perfect for starting your Kannada journey. Learn basic letters, words, and simple sentences.</p>
          </div>
          <div class="course-card animate-observe delay-1">
            <div class="course-level level-mid">Intermediate</div>
            <h3>ಮಧ್ಯಮ ಕನ್ನಡ</h3>
            <p class="course-en">Intermediate</p>
            <p>Build on basics with grammar, vocabulary expansion, and conversation practice.</p>
          </div>
          <div class="course-card animate-observe delay-2">
            <div class="course-level level-adv">Advanced</div>
            <h3>ಉನ್ನತ ಕನ್ನಡ</h3>
            <p class="course-en">Advanced</p>
            <p>Master complex grammar, literature appreciation, and fluent conversation skills.</p>
          </div>
          <div class="course-card animate-observe">
            <div class="course-level level-kids">Kids</div>
            <h3>ಮಕ್ಕಳ ಕನ್ನಡ</h3>
            <p class="course-en">Children's Kannada</p>
            <p>Fun and interactive classes designed specifically for children to learn Kannada through games and activities.</p>
          </div>
          <div class="course-card animate-observe delay-1">
            <div class="course-level level-conv">Conversation</div>
            <h3>ಸಂಭಾಷಣೆ ಕನ್ನಡ</h3>
            <p class="course-en">Conversational Kannada</p>
            <p>Focus on speaking and listening skills for everyday conversations with family and friends.</p>
          </div>
          <div class="course-card animate-observe delay-2">
            <div class="course-level level-script">Script</div>
            <h3>ಕನ್ನಡ ಲಿಪಿ</h3>
            <p class="course-en">Kannada Script</p>
            <p>Master Kannada script with proper stroke order and beautiful handwriting techniques.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="section cta-section">
      <div class="section-container">
        <div class="cta-box animate-observe">
          <h2>Start Learning Kannada</h2>
          <p>Join our Kannada language classes and reconnect with your roots. Open to all ages and skill levels.</p>
          <div class="cta-buttons">
            <NuxtLink to="/membership/register" class="btn btn-primary">Enroll Now</NuxtLink>
            <NuxtLink to="/contact" class="btn btn-outline">Ask a Question</NuxtLink>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Kannada Kali | Munich Kannadigaru',
  description: 'Learn Kannada through our structured learning programs for all age groups in Munich.'
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
  background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
  color: var(--white);
  padding: 100px 0;
  text-align: center;
}
.initiative-hero .tag { background: rgba(255,255,255,0.15); color: var(--white); margin-bottom: 25px; }
.initiative-hero h1 { font-size: clamp(40px, 6vw, 68px); font-family: 'Playfair Display', serif; color: var(--white); margin-bottom: 15px; }
.hero-kannada { font-size: clamp(22px, 3vw, 30px); color: #A5D6A7; margin-bottom: 20px; display: block; }
.hero-desc { font-size: 18px; opacity: 0.9; max-width: 640px; margin: 0 auto; color: var(--white); }

.courses-section { background: var(--cream); }
.courses-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px; }
.course-card {
  background: var(--white);
  border-radius: 20px;
  padding: 35px 30px;
  transition: box-shadow 0.3s, transform 0.3s;
  position: relative;
}
.course-card:hover { box-shadow: 0 15px 40px rgba(0,0,0,0.08); transform: translateY(-4px); }
.course-level {
  display: inline-block;
  padding: 5px 14px;
  background: rgba(196,30,58,0.08);
  color: var(--primary-red);
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  font-family: 'Manrope', sans-serif;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 18px;
}
.level-mid { background: rgba(212,167,59,0.1); color: var(--gold-dark); }
.level-adv { background: rgba(25,118,210,0.08); color: #1976D2; }
.level-kids { background: rgba(233,30,99,0.08); color: #E91E63; }
.level-conv { background: rgba(0,121,107,0.08); color: #00796B; }
.level-script { background: rgba(81,45,168,0.08); color: #512DA8; }
.course-card h3 { font-size: 22px; font-family: 'Noto Sans Kannada', sans-serif; color: var(--text-dark); margin-bottom: 6px; }
.course-en { font-size: 13px; color: var(--text-light); margin-bottom: 12px; }
.course-card p { color: var(--text-light); font-size: 14px; line-height: 1.7; margin-bottom: 0; }

.cta-section { background: var(--cream-dark); }
.cta-box { text-align: center; max-width: 700px; margin: 0 auto; }
.cta-box h2 { font-size: clamp(28px, 4vw, 42px); font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 15px; }
.cta-box p { color: var(--text-light); margin-bottom: 35px; }
.cta-buttons { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; }

@media (max-width: 1024px) { .courses-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) { .courses-grid { grid-template-columns: 1fr; } }
</style>
