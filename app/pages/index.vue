<template>
  <div>
    <!-- ─── Hero ────────────────────────────────────────────── -->
    <section class="hero">
      <div class="hero-container">
        <!-- Content left -->
        <div class="hero-content">
          <span class="tag">Welcome to our Community</span>
          <h1 class="hero-title">
            <span class="hero-title-black">Munich</span>
            <span class="hero-title-gradient">Kannadigaru</span>
          </h1>
          <p class="hero-kannada kannada-text">ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು</p>

          <blockquote class="hero-quote">
            <p class="kannada-text">"ಎಲ್ಲಾದರು ಇರು ಎಂತಾದರು ಇರು<br>ಎಂದೆಂದಿಗೂ ನೀ ಕನ್ನಡವಾಗಿರು.."</p>
            <cite class="kannada-text">— ಕುವೆಂಪು</cite>
          </blockquote>

          <p class="hero-desc">
            A vibrant community of passionate individuals from Karnataka and the Kannada-speaking tribe,
            dedicated to uniting all Kannada speakers in and around Munich.
          </p>

          <div class="hero-buttons">
            <NuxtLink to="/membership/register" class="btn btn-primary">
              Join Our Community <span aria-hidden="true">→</span>
            </NuxtLink>
            <NuxtLink to="/about" class="btn btn-outline">Learn More</NuxtLink>
          </div>
        </div>

        <!-- Image right -->
        <div class="hero-image">
          <img
            src="https://images.unsplash.com/photo-1616606484004-5ef3cc46e39d?w=800&q=80"
            alt="Karnataka Heritage - Stone Chariot at Hampi"
          />
          <span class="image-badge est">Est. 2019</span>
          <div class="image-label">
            <small>Heritage</small>
            <span>Stone Chariot</span>
          </div>
          <div class="small-image">
            <img
              src="https://images.unsplash.com/photo-1582510003544-4d00b7f74220?w=200&q=80"
              alt="Karnataka Temple"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- ─── Events ───────────────────────────────────────────── -->
    <section class="section events-section">
      <div class="section-container">
        <div class="section-header">
          <span class="tag">Upcoming Events</span>
          <h2 class="section-title">
            <span class="black">Join Our</span>
            <span class="red"> Celebrations</span>
          </h2>
        </div>

        <div class="event-card">
          <div class="event-content">
            <span class="event-tag">📅 Featured Event</span>
            <h3>UTSAVA 2025</h3>
            <p class="event-kannada kannada-text">"ಉತ್ಸವ ೨೦೨೫"</p>
            <p>Our flagship annual cultural festival celebrating Karnataka's rich heritage through music, dance, food, and community bonding.</p>
            <NuxtLink to="/events/utsava-2025" class="btn btn-white">
              Learn More <span aria-hidden="true">→</span>
            </NuxtLink>
          </div>
          <div class="event-image">
            <img
              src="https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800&q=80"
              alt="UTSAVA 2025 Cultural Celebration"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- ─── Initiatives ──────────────────────────────────────── -->
    <section class="section initiatives-section">
      <div class="section-container">
        <div class="section-header">
          <span class="tag">Our Initiatives</span>
          <h2 class="section-title">
            <span class="black">Programs That</span>
            <span class="red"> Matter</span>
          </h2>
          <p class="section-subtitle">We run several initiatives to keep our culture alive and thriving in Munich.</p>
        </div>

        <div class="initiatives-grid">
          <div class="initiative-card">
            <div class="initiative-icon icon-red">
              <span aria-hidden="true">🎵</span>
            </div>
            <h3>Karnataka Cultural</h3>
            <p class="kannada-text initiative-kn">ಕರ್ನಾಟಕ ಸಾಂಸ್ಕೃತಿಕ</p>
            <p>Celebrating the rich cultural heritage of Karnataka through various programs and events.</p>
            <NuxtLink to="/initiatives/karnataka-cultural" class="link-arrow">
              Learn More <span aria-hidden="true">→</span>
            </NuxtLink>
          </div>

          <div class="initiative-card">
            <div class="initiative-icon icon-gold">
              <span aria-hidden="true">📖</span>
            </div>
            <h3>Jnana Deepa</h3>
            <p class="kannada-text initiative-kn">ಜ್ಞಾನ ದೀಪ</p>
            <p>Our Kannada library initiative promoting literature and knowledge sharing.</p>
            <NuxtLink to="/initiatives/jnana-deepa" class="link-arrow">
              Learn More <span aria-hidden="true">→</span>
            </NuxtLink>
          </div>

          <div class="initiative-card">
            <div class="initiative-icon icon-green">
              <span aria-hidden="true">👥</span>
            </div>
            <h3>Kannada Kali</h3>
            <p class="kannada-text initiative-kn">ಕನ್ನಡ ಕಲಿ</p>
            <p>Learn Kannada through our structured learning programs for all age groups.</p>
            <NuxtLink to="/initiatives/kannada-kali" class="link-arrow">
              Learn More <span aria-hidden="true">→</span>
            </NuxtLink>
          </div>
        </div>
      </div>
    </section>

    <!-- ─── CTA ──────────────────────────────────────────────── -->
    <section class="section cta-section">
      <div class="section-container">
        <div class="cta-box">
          <h2>Become part of our growing community</h2>
          <p>Experience the joy of connecting with fellow Kannadigas in Munich.</p>
          <div class="cta-buttons">
            <NuxtLink to="/membership/register" class="btn btn-primary">Join Now <span aria-hidden="true">→</span></NuxtLink>
            <NuxtLink to="/contact" class="btn btn-outline">Contact Us</NuxtLink>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Munich Kannadigaru — ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು',
  description: 'A vibrant community of Kannadigas in Munich celebrating language, culture, and belonging.'
})
</script>

<style scoped>
/* ─── Hero ─────────────────────────────────────────────────── */
.hero {
  padding: 60px 0 80px;
  background: var(--cream);
}

.hero-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 40px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
}

.hero-content .tag {
  margin-bottom: 25px;
  display: inline-block;
}

.hero-title {
  font-size: clamp(44px, 6vw, 72px);
  margin-bottom: 15px;
  font-family: 'Playfair Display', serif;
  line-height: 1.1;
}
.hero-title-black {
  color: var(--text-dark);
  display: block;
}
.hero-title-gradient {
  background: linear-gradient(90deg, var(--gold) 0%, var(--primary-red) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  display: block;
}

.hero-kannada {
  font-size: 28px;
  color: var(--text-dark);
  margin-bottom: 30px;
}

.hero-quote {
  border-left: 4px solid var(--gold);
  padding-left: 25px;
  margin: 0 0 30px;
}
.hero-quote p {
  font-size: 20px;
  font-style: italic;
  color: var(--text-dark);
  line-height: 1.6;
  margin: 0 0 12px;
}
.hero-quote cite {
  display: block;
  color: var(--primary-red);
  font-weight: 600;
  font-style: normal;
  font-size: 15px;
}

.hero-desc {
  color: var(--text-light);
  margin-bottom: 35px;
  font-size: 16px;
  line-height: 1.8;
}

.hero-buttons {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

/* Hero Image */
.hero-image {
  position: relative;
}
.hero-image > img {
  width: 100%;
  height: 500px;
  object-fit: cover;
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.15);
  display: block;
  position: relative;
  z-index: 1;
}
.hero-image::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.4), transparent);
  border-radius: 20px;
  z-index: 2;
  pointer-events: none;
}
.image-badge {
  position: absolute;
  background: var(--gold);
  color: var(--text-dark);
  padding: 12px 24px;
  border-radius: 10px;
  font-weight: 600;
  z-index: 3;
}
.image-badge.est {
  top: -15px;
  right: -15px;
  font-family: 'Playfair Display', serif;
  font-size: 18px;
}
.image-label {
  position: absolute;
  bottom: 30px;
  right: 30px;
  background: rgba(255,255,255,0.95);
  padding: 15px 25px;
  border-radius: 12px;
  z-index: 3;
}
.image-label small {
  display: block;
  color: var(--text-light);
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 1px;
}
.image-label span {
  font-family: 'Playfair Display', serif;
  font-size: 22px;
  color: var(--text-dark);
}
.small-image {
  position: absolute;
  bottom: -30px;
  left: -30px;
  width: 140px;
  height: 140px;
  border-radius: 16px;
  overflow: hidden;
  border: 4px solid var(--white);
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  z-index: 3;
}
.small-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* ─── Events ─────────────────────────────────────────────── */
.events-section {
  background: var(--cream);
}

.event-card {
  display: grid;
  grid-template-columns: 1fr 1fr;
  background: var(--primary-red);
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 10px 40px rgba(196, 30, 58, 0.2);
}
.event-content {
  padding: 60px;
  color: var(--white);
}
.event-tag {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(255,255,255,0.15);
  color: var(--white);
  font-size: 13px;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 1px;
  border-radius: 30px;
  padding: 8px 20px;
  margin-bottom: 25px;
  font-family: 'Manrope', sans-serif;
}
.event-content h3 {
  font-size: clamp(36px, 4vw, 52px);
  margin-bottom: 15px;
  font-family: 'Playfair Display', serif;
  color: var(--white);
}
.event-kannada {
  font-size: 26px;
  color: var(--gold);
  margin-bottom: 25px;
  display: block;
}
.event-content p {
  opacity: 0.9;
  margin-bottom: 35px;
  font-size: 16px;
  line-height: 1.8;
}
.event-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

/* ─── Initiatives ─────────────────────────────────────────── */
.initiatives-section {
  background: var(--cream-dark);
}

.initiatives-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 30px;
}

.initiative-card {
  background: var(--white);
  border-radius: 20px;
  padding: 40px 35px;
  transition: box-shadow 0.3s, transform 0.3s;
}
.initiative-card:hover {
  box-shadow: 0 20px 60px rgba(0,0,0,0.1);
  transform: translateY(-5px);
}

.initiative-icon {
  width: 70px;
  height: 70px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 25px;
  font-size: 28px;
}
.icon-red { background: rgba(196, 30, 58, 0.08); }
.icon-gold { background: rgba(229, 184, 76, 0.12); }
.icon-green { background: rgba(76, 175, 80, 0.08); }

.initiative-card h3 {
  font-size: 24px;
  margin-bottom: 10px;
  font-family: 'Playfair Display', serif;
  color: var(--text-dark);
}
.initiative-kn {
  color: var(--primary-red);
  font-size: 15px;
  margin-bottom: 18px;
  display: block;
}
.initiative-card p {
  color: var(--text-light);
  font-size: 15px;
  margin-bottom: 25px;
  line-height: 1.7;
}

/* ─── CTA ──────────────────────────────────────────────────── */
.cta-section { background: var(--cream); }

.cta-box {
  text-align: center;
  max-width: 700px;
  margin: 0 auto;
}
.cta-box h2 {
  font-size: clamp(28px, 4vw, 42px);
  margin-bottom: 15px;
  font-family: 'Playfair Display', serif;
  color: var(--text-dark);
}
.cta-box p {
  color: var(--text-light);
  margin-bottom: 35px;
  font-size: 16px;
}
.cta-buttons {
  display: flex;
  gap: 20px;
  justify-content: center;
  flex-wrap: wrap;
}

/* ─── Responsive ──────────────────────────────────────────── */
@media (max-width: 1024px) {
  .hero-container { grid-template-columns: 1fr; }
  .event-card { grid-template-columns: 1fr; }
  .initiatives-grid { grid-template-columns: repeat(2, 1fr); }
  .hero-image img { height: 400px; }
}

@media (max-width: 768px) {
  .hero { padding: 40px 0 70px; }
  .hero-container { padding: 0 20px; gap: 30px; }
  .hero-kannada { font-size: 22px; }
  .hero-buttons { flex-direction: column; }
  .hero-buttons .btn { width: 100%; justify-content: center; }
  .hero-image img { height: 280px; }
  .small-image { display: none; }
  .image-badge.est { top: -10px; right: -5px; }
  .event-content { padding: 35px 25px; }
  .initiatives-grid { grid-template-columns: 1fr; }
}
</style>
