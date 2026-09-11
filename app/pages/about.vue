<template>
  <div>
    <!-- Page Header -->
    <section class="page-header">
      <div class="section-container">
        <span class="tag animate-item">About Us</span>
        <h1 class="animate-item delay-1">Our Story</h1>
        <p class="kannada-text animate-item delay-2">ನಮ್ಮ ಕಥೆ</p>
      </div>
    </section>

    <!-- About Section -->
    <section class="about-hero">
      <div class="section-container">
        <div class="about-grid">
          <div class="about-image animate-observe">
            <img
              src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=800&q=80"
              alt="Munich Kannadigaru community gathering"
            />
            <div class="about-badge">
              <span class="number">500+</span>
              <span class="label">Active Members</span>
            </div>
          </div>

          <div class="about-content animate-observe delay-1">
            <span class="tag">Who We Are</span>
            <h2>
              <span class="black">Building Bridges,</span><br>
              <span class="red">Preserving Culture</span>
            </h2>
            <p>Munich Kannadigaru is a vibrant community of passionate individuals from Karnataka and the Kannada-speaking tribe, dedicated to uniting all Kannada speakers in and around Munich, Germany.</p>
            <p>Founded in 2019, our community has grown from a small group of like-minded individuals to a thriving network of over 500 active members. We're more than just a social club — we're a source of support, cultural preservation, and personal growth for our members.</p>
            <p>Through regular events and activities such as cultural festivals, sports tournaments, educational programs, and social gatherings, we strive to keep the Kannada spirit alive in the heart of Europe.</p>
            <NuxtLink to="/forerunner" class="link-arrow">Meet Our Team <span aria-hidden="true">→</span></NuxtLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Values Section -->
    <section class="values-section section">
      <div class="section-container">
        <div class="section-header animate-observe">
          <span class="tag">What We Stand For</span>
          <h2 class="section-title">
            <span class="black">Our Core</span>
            <span class="red"> Values</span>
          </h2>
        </div>

        <div class="values-grid">
          <div class="value-card animate-observe">
            <div class="value-icon">❤️</div>
            <h4>Community First</h4>
            <p>We prioritize the well-being and growth of our community members above all else.</p>
          </div>
          <div class="value-card animate-observe delay-1">
            <div class="value-icon">🏛️</div>
            <h4>Cultural Heritage</h4>
            <p>We are committed to preserving and promoting Kannada culture and traditions.</p>
          </div>
          <div class="value-card animate-observe delay-2">
            <div class="value-icon">🤝</div>
            <h4>Mutual Support</h4>
            <p>We believe in helping each other through challenges and celebrating successes together.</p>
          </div>
          <div class="value-card animate-observe">
            <div class="value-icon">💡</div>
            <h4>Innovation</h4>
            <p>We embrace new ideas while staying rooted in our traditions.</p>
          </div>
          <div class="value-card animate-observe delay-1">
            <div class="value-icon">👥</div>
            <h4>Inclusivity</h4>
            <p>We welcome all who share our love for Kannada language and culture.</p>
          </div>
          <div class="value-card animate-observe delay-2">
            <div class="value-icon">⭐</div>
            <h4>Excellence</h4>
            <p>We strive for excellence in everything we do for our community.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Quote Section -->
    <section class="quote-section">
      <div class="section-container">
        <div class="quote-inner animate-observe">
          <div class="quote-icon">🎭</div>
          <p class="quote-kannada kannada-text">"ತನು ಕನ್ನಡ, ಮನ ಕನ್ನಡ, ನಡೆ ಕನ್ನಡ, ನುಡಿ ಕನ್ನಡ"</p>
          <p class="quote-english">Body is Kannada, Mind is Kannada, Walk is Kannada, Speech is Kannada</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'About Us | Munich Kannadigaru',
  description: 'Learn about Munich Kannadigaru — a vibrant community dedicated to uniting Kannada speakers in Munich, Germany.'
})

onMounted(() => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible')
        observer.unobserve(entry.target)
      }
    })
  }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' })

  document.querySelectorAll('.animate-observe').forEach(el => observer.observe(el))
})
</script>

<style scoped>
/* Page header overrides */
.page-header {
  padding: 80px 0 60px;
}
.page-header h1 {
  font-size: clamp(40px, 5vw, 60px);
  margin-bottom: 15px;
  font-family: 'Playfair Display', serif;
}
.page-header .kannada-text {
  font-size: 24px;
  color: var(--primary-red);
  margin-bottom: 0;
}

/* Mount animations */
.animate-item {
  opacity: 0;
  transform: translateY(24px);
  animation: fadeUp 0.7s ease forwards;
}
.delay-1 { animation-delay: 0.1s; }
.delay-2 { animation-delay: 0.2s; }
.delay-3 { animation-delay: 0.3s; }
@keyframes fadeUp {
  to { opacity: 1; transform: translateY(0); }
}

/* Scroll animations */
.animate-observe {
  opacity: 0;
  transform: translateY(28px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
.animate-observe.delay-1 { transition-delay: 0.12s; }
.animate-observe.delay-2 { transition-delay: 0.24s; }
.animate-observe.is-visible { opacity: 1; transform: translateY(0); }

@media (prefers-reduced-motion: reduce) {
  .animate-item, .animate-observe {
    animation: none !important;
    transition: none !important;
    opacity: 1 !important;
    transform: none !important;
  }
}

/* About hero */
.about-hero {
  background: var(--cream);
  padding: 80px 0;
}
.about-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
}
.about-image {
  position: relative;
}
.about-image img {
  width: 100%;
  border-radius: 24px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.1);
  display: block;
}
.about-badge {
  position: absolute;
  bottom: -20px;
  right: 30px;
  background: var(--primary-red);
  color: var(--white);
  padding: 25px 35px;
  border-radius: 16px;
  text-align: center;
  z-index: 2;
}
.about-badge .number {
  font-size: 42px;
  font-weight: 700;
  display: block;
  font-family: 'Playfair Display', serif;
  line-height: 1;
}
.about-badge .label {
  font-size: 14px;
  opacity: 0.9;
  margin-top: 4px;
  display: block;
}
.about-content .tag { margin-bottom: 20px; display: inline-block; }
.about-content h2 {
  font-size: clamp(32px, 4vw, 48px);
  margin-bottom: 25px;
  font-family: 'Playfair Display', serif;
  line-height: 1.2;
}
.about-content h2 .black { color: var(--text-dark); }
.about-content h2 .red { color: var(--primary-red); }
.about-content p {
  color: var(--text-light);
  font-size: 16px;
  line-height: 1.8;
  margin-bottom: 20px;
}

/* Values */
.values-section { background: var(--cream-dark); }
.values-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 30px;
}
.value-card {
  background: var(--white);
  border-radius: 20px;
  padding: 40px 35px;
  text-align: center;
  transition: box-shadow 0.3s, transform 0.3s;
}
.value-card:hover {
  box-shadow: 0 15px 40px rgba(0,0,0,0.08);
  transform: translateY(-5px);
}
.value-icon {
  width: 70px;
  height: 70px;
  background: rgba(196,30,58,0.08);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 25px;
  font-size: 28px;
}
.value-card h4 {
  font-size: 20px;
  margin-bottom: 12px;
  font-family: 'Playfair Display', serif;
  color: var(--text-dark);
}
.value-card p {
  color: var(--text-light);
  font-size: 14px;
  line-height: 1.6;
  margin-bottom: 0;
}

/* Quote section */
.quote-section {
  background: var(--primary-red);
  color: var(--white);
  padding: 100px 0;
  text-align: center;
}
.quote-inner { max-width: 700px; margin: 0 auto; }
.quote-icon { font-size: 48px; margin-bottom: 30px; }
.quote-kannada {
  font-size: 28px;
  margin-bottom: 15px;
  color: var(--white);
  line-height: 1.6;
}
.quote-english {
  font-size: 16px;
  opacity: 0.8;
  color: var(--white);
  margin-bottom: 0;
}

/* Responsive */
@media (max-width: 1024px) {
  .about-grid { grid-template-columns: 1fr; gap: 50px; }
  .values-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
  .about-hero { padding: 60px 0 80px; }
  .about-content h2 { font-size: 30px; }
  .values-grid { grid-template-columns: 1fr; }
  .about-badge { bottom: -15px; right: 15px; padding: 18px 25px; }
  .about-badge .number { font-size: 32px; }
  .quote-kannada { font-size: 20px; }
}
</style>
