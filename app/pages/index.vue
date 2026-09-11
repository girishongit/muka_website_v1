<template>
  <div>
    <!-- ─── Hero ────────────────────────────────────────────── -->
    <section class="hero">
      <div class="hero-container">
        <div class="hero-content" ref="heroContent">
          <span class="tag animate-item">Welcome to our Community</span>
          <h1 class="hero-title animate-item delay-1">
            <span class="hero-title-black">Munich</span>
            <span class="hero-title-gradient">Kannadigaru</span>
          </h1>
          <p class="hero-kannada kannada-text animate-item delay-2">ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು</p>

          <blockquote class="hero-quote animate-item delay-3">
            <p class="kannada-text">"ಎಲ್ಲಾದರು ಇರು ಎಂತಾದರು ಇರು<br>ಎಂದೆಂದಿಗೂ ನೀ ಕನ್ನಡವಾಗಿರು.."</p>
            <cite class="kannada-text">— ಕುವೆಂಪು</cite>
          </blockquote>

          <p class="hero-desc animate-item delay-4">
            A vibrant community of passionate individuals from Karnataka and the Kannada-speaking tribe,
            dedicated to uniting all Kannada speakers in and around Munich.
          </p>

          <div class="hero-buttons animate-item delay-5">
            <NuxtLink to="/membership/register" class="btn btn-primary">
              Join Our Community <span aria-hidden="true">→</span>
            </NuxtLink>
            <NuxtLink to="/about" class="btn btn-outline">Learn More</NuxtLink>
          </div>
        </div>

        <div class="hero-image animate-from-right" ref="heroImage">
          <img
            src="https://images.unsplash.com/photo-1616606484004-5ef3cc46e39d?w=800&q=80"
            alt="Karnataka Heritage — Stone Chariot at Hampi"
          />
          <span class="image-badge-est">Est. 2019</span>
          <!-- Transparent label directly on image -->
          <div class="image-label-overlay">
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

    <!-- ─── About ────────────────────────────────────────────── -->
    <section class="section about-section" ref="aboutSection">
      <div class="about-container">
        <div class="about-image animate-observe">
          <img
            src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=800&q=80"
            alt="Kannadigas community gathering"
          />
          <div class="about-badge">
            <span class="about-badge-number">500+</span>
            <span class="about-badge-label">Active Members</span>
          </div>
        </div>
        <div class="about-content animate-observe delay-1">
          <span class="tag">About Us</span>
          <h2 class="about-heading">
            Building Bridges,<br>
            <span class="red">Preserving Culture</span>
          </h2>
          <p>Looking for ways to improve your social life and connect with like-minded Kannadigas? Look no further than our Kannada community! We're passionate about creating a strong and vibrant community that promotes personal growth and well-being.</p>
          <p>Through regular events and activities such as cultural festivals, sports tournaments, and social gatherings, we're more than just a social club — we're a source of support for our members.</p>
          <NuxtLink to="/about" class="link-arrow">Read More About Us <span aria-hidden="true">→</span></NuxtLink>
        </div>
      </div>
    </section>

    <!-- ─── Events ───────────────────────────────────────────── -->
    <section class="section events-section">
      <div class="section-container">
        <div class="section-header animate-observe">
          <span class="tag">Upcoming Events</span>
          <h2 class="section-title">
            <span class="black">Join Our</span>
            <span class="red"> Celebrations</span>
          </h2>
        </div>

        <!-- Carousel wrapper -->
        <div class="events-carousel" @touchstart.passive="onTouchStart" @touchend.passive="onTouchEnd">
          <div
            v-for="(event, i) in carouselEvents"
            :key="event.id"
            class="event-card"
            :class="[event.theme, { active: currentSlide === i }]"
          >
            <div class="event-content">
              <span class="event-tag">{{ event.tag }}</span>
              <h3>{{ event.title }}</h3>
              <p class="event-kannada kannada-text">{{ event.titleKn }}</p>
              <p>{{ event.desc }}</p>
              <NuxtLink :to="event.link" class="btn btn-white">
                Learn More <span aria-hidden="true">→</span>
              </NuxtLink>
            </div>
            <div class="event-image-wrap">
              <div class="event-image-overlay" aria-hidden="true"></div>
              <img :src="event.img" :alt="event.title" />
            </div>
          </div>

          <!-- Dot indicators -->
          <div class="carousel-dots" role="tablist" aria-label="Event slides">
            <button
              v-for="(_, i) in carouselEvents"
              :key="i"
              class="carousel-dot"
              :class="{ active: currentSlide === i }"
              :aria-label="`Show event ${i + 1}`"
              :aria-selected="String(currentSlide === i)"
              role="tab"
              @click="goToSlide(i)"
            ></button>
          </div>
        </div>
      </div>
    </section>

    <!-- ─── Initiatives ──────────────────────────────────────── -->
    <section class="section initiatives-section">
      <div class="section-container">
        <div class="section-header animate-observe">
          <span class="tag">Our Initiatives</span>
          <h2 class="section-title">
            <span class="black">Programs That</span>
            <span class="red"> Matter</span>
          </h2>
          <p class="section-subtitle">We run several initiatives to keep our culture alive and thriving in Munich.</p>
        </div>

        <div class="initiatives-grid">
          <div class="initiative-card animate-observe">
            <div class="initiative-icon icon-red">🎵</div>
            <h3>Karnataka Cultural</h3>
            <p class="kannada-text initiative-kn">ಕರ್ನಾಟಕ ಸಾಂಸ್ಕೃತಿಕ</p>
            <p>Celebrating the rich cultural heritage of Karnataka through various programs and events.</p>
            <NuxtLink to="/initiatives/karnataka-cultural" class="link-arrow">Learn More <span aria-hidden="true">→</span></NuxtLink>
          </div>
          <div class="initiative-card animate-observe delay-1">
            <div class="initiative-icon icon-gold">📖</div>
            <h3>Jnana Deepa</h3>
            <p class="kannada-text initiative-kn">ಜ್ಞಾನ ದೀಪ</p>
            <p>Our Kannada library initiative promoting literature and knowledge sharing.</p>
            <NuxtLink to="/initiatives/jnana-deepa" class="link-arrow">Learn More <span aria-hidden="true">→</span></NuxtLink>
          </div>
          <div class="initiative-card animate-observe delay-2">
            <div class="initiative-icon icon-green">👥</div>
            <h3>Kannada Kali</h3>
            <p class="kannada-text initiative-kn">ಕನ್ನಡ ಕಲಿ</p>
            <p>Learn Kannada through our structured learning programs for all age groups.</p>
            <NuxtLink to="/initiatives/kannada-kali" class="link-arrow">Learn More <span aria-hidden="true">→</span></NuxtLink>
          </div>
        </div>
      </div>
    </section>

    <!-- ─── CTA ──────────────────────────────────────────────── -->
    <section class="section cta-section">
      <div class="section-container">
        <div class="cta-box animate-observe">
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

// ─── Events carousel ─────────────────────────────────────────
const carouselEvents = [
  {
    id: 1,
    theme: 'theme-red',
    tag: '📅 Featured Event',
    title: 'UTSAVA 2025',
    titleKn: '"ಉತ್ಸವ ೨೦೨೫"',
    desc: 'Our flagship annual cultural festival celebrating Karnataka\'s rich heritage through music, dance, food, and community bonding.',
    link: '/events/utsava-2025',
    img: 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800&q=80'
  },
  {
    id: 2,
    theme: 'theme-gold',
    tag: '🎉 Annual Celebration',
    title: 'Kannada Rajyotsava',
    titleKn: '"ಕನ್ನಡ ರಾಜ್ಯೋತ್ಸವ"',
    desc: 'Join us on November 1st to celebrate Karnataka\'s state formation day with cultural performances, traditional cuisine, and community pride.',
    link: '/events/utsava-2025',
    img: 'https://images.unsplash.com/photo-1514222134-b57cbb8ce073?w=800&q=80'
  },
  {
    id: 3,
    theme: 'theme-red',
    tag: '⚽ Community Sports',
    title: 'Sporting Kannadigaru',
    titleKn: '"ಕ್ರೀಡಾ ಕನ್ನಡಿಗರು"',
    desc: 'A day of friendly competition, team spirit, and cultural bonding. Cricket, football, and traditional games for all age groups.',
    link: '/events/utsava-2025',
    img: 'https://images.unsplash.com/photo-1517649763962-0c623066013b?w=800&q=80'
  }
]

const currentSlide = ref(0)
let autoPlayTimer = null

function goToSlide(i) { currentSlide.value = i; resetAutoPlay() }
function nextSlide() { currentSlide.value = (currentSlide.value + 1) % carouselEvents.length; resetAutoPlay() }
function prevSlide() { currentSlide.value = (currentSlide.value - 1 + carouselEvents.length) % carouselEvents.length; resetAutoPlay() }

function startAutoPlay() {
  autoPlayTimer = setInterval(() => { currentSlide.value = (currentSlide.value + 1) % carouselEvents.length }, 5000)
}
function resetAutoPlay() { clearInterval(autoPlayTimer); startAutoPlay() }

// Touch swipe support
let touchStartX = 0
function onTouchStart(e) { touchStartX = e.touches[0].clientX }
function onTouchEnd(e) {
  const dx = e.changedTouches[0].clientX - touchStartX
  if (Math.abs(dx) > 40) { dx < 0 ? nextSlide() : prevSlide() }
}

// ─── Intersection Observer for scroll animations
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
  startAutoPlay()
})

onUnmounted(() => { clearInterval(autoPlayTimer) })
</script>

<style scoped>
/* ─── Scroll animation base states ──────────────────────────── */
.animate-observe {
  opacity: 0;
  transform: translateY(28px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
.animate-observe.delay-1 { transition-delay: 0.1s; }
.animate-observe.delay-2 { transition-delay: 0.2s; }
.animate-observe.is-visible {
  opacity: 1;
  transform: translateY(0);
}

/* Hero items animate on mount */
.animate-item {
  opacity: 0;
  transform: translateY(24px);
  animation: fadeUp 0.7s ease forwards;
}
.delay-1 { animation-delay: 0.1s; }
.delay-2 { animation-delay: 0.2s; }
.delay-3 { animation-delay: 0.3s; }
.delay-4 { animation-delay: 0.4s; }
.delay-5 { animation-delay: 0.5s; }
.animate-from-right {
  opacity: 0;
  transform: translateX(32px);
  animation: fadeRight 0.8s ease 0.3s forwards;
}
@keyframes fadeUp {
  to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeRight {
  to { opacity: 1; transform: translateX(0); }
}
@media (prefers-reduced-motion: reduce) {
  .animate-item, .animate-from-right, .animate-observe {
    animation: none !important;
    transition: none !important;
    opacity: 1 !important;
    transform: none !important;
  }
}

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
.hero-content .tag { margin-bottom: 25px; display: inline-block; }
.hero-title {
  font-size: clamp(44px, 6vw, 72px);
  margin-bottom: 15px;
  font-family: 'Playfair Display', serif;
  line-height: 1.1;
}
.hero-title-black { color: var(--text-dark); display: block; }
.hero-title-gradient {
  background: linear-gradient(90deg, var(--gold) 0%, var(--primary-red) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  display: block;
}
.hero-kannada { font-size: 28px; color: var(--text-dark); margin-bottom: 30px; }
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
.hero-desc { color: var(--text-light); margin-bottom: 35px; font-size: 16px; line-height: 1.8; }
.hero-buttons { display: flex; gap: 20px; flex-wrap: wrap; }

/* Hero Image */
.hero-image { position: relative; }
.hero-image > img {
  width: 100%;
  height: 500px;
  object-fit: cover;
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.15);
  display: block;
}
/* dark-to-transparent gradient at bottom for text legibility */
.hero-image::before {
  content: '';
  position: absolute;
  bottom: 0; left: 0; right: 0;
  height: 50%;
  background: linear-gradient(to top, rgba(0,0,0,0.55), transparent);
  border-radius: 0 0 20px 20px;
  z-index: 2;
  pointer-events: none;
}
.image-badge-est {
  position: absolute;
  top: -15px;
  right: -15px;
  background: var(--gold);
  color: var(--text-dark);
  padding: 12px 24px;
  border-radius: 10px;
  font-weight: 700;
  font-family: 'Playfair Display', serif;
  font-size: 18px;
  z-index: 3;
}
/* Transparent label directly on image — text with subtle shadow, no box */
.image-label-overlay {
  position: absolute;
  bottom: 25px;
  right: 25px;
  z-index: 3;
  color: var(--white);
  text-align: right;
}
.image-label-overlay small {
  display: block;
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  opacity: 0.9;
  font-family: 'Manrope', sans-serif;
  text-shadow: 0 1px 3px rgba(0,0,0,0.6);
  margin-bottom: 2px;
}
.image-label-overlay span {
  font-family: 'Playfair Display', serif;
  font-size: 20px;
  font-weight: 600;
  display: block;
  text-shadow: 0 1px 6px rgba(0,0,0,0.7);
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
.small-image img { width: 100%; height: 100%; object-fit: cover; }

/* ─── About ─────────────────────────────────────────────────── */
.about-section { background: #F5F5F0; }
.about-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 40px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
}
.about-image { position: relative; }
.about-image img {
  width: 100%;
  height: 420px;
  object-fit: cover;
  border-radius: 20px;
  display: block;
}
.about-badge {
  position: absolute;
  bottom: -20px;
  right: -20px;
  background: var(--primary-red);
  color: var(--white);
  padding: 20px 30px;
  border-radius: 15px;
  text-align: center;
  z-index: 2;
}
.about-badge-number {
  font-family: 'Playfair Display', serif;
  font-size: 36px;
  font-weight: 700;
  display: block;
  line-height: 1;
}
.about-badge-label { font-size: 14px; opacity: 0.9; display: block; margin-top: 4px; }
.about-content { padding-bottom: 20px; }
.about-content .tag { margin-bottom: 20px; display: inline-block; }
.about-heading {
  font-family: 'Playfair Display', serif;
  font-size: clamp(32px, 4vw, 48px);
  color: var(--text-dark);
  margin-bottom: 20px;
  line-height: 1.2;
}
.about-heading .red { color: var(--primary-red); }
.about-content p { color: var(--text-light); font-size: 16px; margin-bottom: 16px; line-height: 1.8; }

/* ─── Events ─────────────────────────────────────────────── */
.events-section { background: var(--cream); }

.events-carousel {
  position: relative;
  /* Reserve space equal to tallest card so dots don't jump */
}

/* All cards stacked; only active is shown */
.event-card {
  display: none;
  grid-template-columns: 1fr 1fr;
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 10px 40px rgba(196, 30, 58, 0.2);
  height: 440px;
}
.event-card.active { display: grid; }

/* Red theme (default) */
.event-card.theme-red {
  background: var(--primary-red);
  box-shadow: 0 10px 40px rgba(196, 30, 58, 0.2);
}
.event-card.theme-red .event-image-overlay {
  background: linear-gradient(to right, var(--primary-red) 0%, transparent 65%);
}
.event-card.theme-red .btn-white { color: var(--primary-red); }

/* Gold theme — lighter base so text reads well, wider overlay for seamless blend */
.event-card.theme-gold {
  background: linear-gradient(135deg, #D4A017 0%, #C8940A 100%);
  box-shadow: 0 10px 40px rgba(196, 148, 10, 0.25);
}
.event-card.theme-gold .event-image-overlay {
  background: linear-gradient(to right, #C8940A 0%, rgba(200, 148, 10, 0.7) 45%, transparent 75%);
}
.event-card.theme-gold .event-tag { background: rgba(255,255,255,0.2); }
.event-card.theme-gold .event-kannada { color: rgba(255,255,255,0.95); }
.event-card.theme-gold .btn-white { color: #8B6300; }

.event-content {
  padding: 60px;
  color: var(--white);
  display: flex;
  flex-direction: column;
  justify-content: center;
  overflow: hidden;
}
.event-tag {
  display: inline-flex;
  align-self: flex-start;
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
.event-kannada { font-size: 26px; color: var(--gold); margin-bottom: 25px; display: block; }
.event-content p { opacity: 0.9; margin-bottom: 35px; font-size: 16px; line-height: 1.8; }

/* Event image — fills fixed card height */
.event-image-wrap {
  position: relative;
  overflow: hidden;
  height: 100%;
  min-height: 440px;
}
.event-image-overlay {
  position: absolute;
  inset: 0;
  z-index: 1;
  pointer-events: none;
}
.event-image-wrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  position: absolute;
  inset: 0;
}

/* Carousel controls */
.carousel-dots {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 24px;
}
.carousel-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  border: 2px solid var(--primary-red);
  background: transparent;
  cursor: pointer;
  transition: background 0.3s;
  padding: 0;
}
.carousel-dot.active { background: var(--primary-red); }

/* ─── Initiatives ─────────────────────────────────────────── */
.initiatives-section { background: var(--cream-dark); }
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
  width: 70px; height: 70px;
  border-radius: 16px;
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 25px; font-size: 28px;
}
.icon-red  { background: rgba(196,30,58,0.08); }
.icon-gold { background: rgba(229,184,76,0.12); }
.icon-green{ background: rgba(76,175,80,0.08); }
.initiative-card h3 {
  font-size: 24px; margin-bottom: 10px;
  font-family: 'Playfair Display', serif; color: var(--text-dark);
}
.initiative-kn { color: var(--primary-red); font-size: 15px; margin-bottom: 18px; display: block; }
.initiative-card p { color: var(--text-light); font-size: 15px; margin-bottom: 25px; line-height: 1.7; }

/* ─── CTA ──────────────────────────────────────────────────── */
.cta-section { background: var(--cream); }
.cta-box { text-align: center; max-width: 700px; margin: 0 auto; }
.cta-box h2 {
  font-size: clamp(28px,4vw,42px); margin-bottom: 15px;
  font-family: 'Playfair Display', serif; color: var(--text-dark);
}
.cta-box p { color: var(--text-light); margin-bottom: 35px; font-size: 16px; }
.cta-buttons { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; }

/* ─── Responsive ──────────────────────────────────────────── */
@media (max-width: 1024px) {
  .hero-container, .about-container, .event-card { grid-template-columns: 1fr; }
  .initiatives-grid { grid-template-columns: repeat(2,1fr); }
  .hero-image img { height: 400px; }
  .about-image img { height: 320px; }
  .about-badge { bottom: -10px; right: 10px; }
  /* When card stacks to single column, release fixed height */
  .event-card { height: auto; }
  .event-image-wrap { height: 280px; min-height: unset; }
}
@media (max-width: 768px) {
  .hero { padding: 40px 0 70px; }
  .hero-container, .about-container { padding: 0 20px; gap: 30px; }
  .hero-kannada { font-size: 22px; }
  .hero-buttons { flex-direction: column; }
  .hero-buttons .btn { width: 100%; justify-content: center; }
  .hero-image img { height: 280px; }
  .small-image { display: none; }
  .image-badge-est { top: -10px; right: -5px; }
  .event-content { padding: 35px 25px; }
  .event-card { height: auto; }
  .event-image-wrap { min-height: 240px; height: auto; }
  .initiatives-grid { grid-template-columns: 1fr; }
  .about-image { order: 2; }
  .about-content { order: 1; }
}
</style>
