<template>
  <div v-if="event">
    <div v-if="event.registrationStatus === 'closed'" class="past-banner">
      <span>Registration for this event is now closed.</span>
    </div>
    <section class="event-hero" :style="event.heroImage ? `--hero-bg: url('${event.heroImage}')` : ''">
      <div class="event-hero-container">
        <span class="tag event-tag-pill animate-item">📅 {{ event.tag }}</span>
        <h1 class="animate-item delay-1">{{ event.title }}</h1>
        <p v-if="event.kannadaTitle" class="event-kannada kannada-text animate-item delay-2">"{{ event.kannadaTitle }}"</p>
        <div class="event-info animate-item delay-3">
          <div class="event-info-item"><span class="info-icon">📅</span> {{ event.date }}</div>
          <div v-if="event.time" class="event-info-item"><span class="info-icon">🕙</span> {{ event.time }}</div>
          <div v-if="event.venue" class="event-info-item"><span class="info-icon">📍</span> {{ event.venue }}</div>
        </div>
      </div>
    </section>

    <section class="about-event section">
      <div class="section-container">
        <div class="about-event-grid">
          <div class="about-event-content animate-observe">
            <span class="tag">About the Event</span>
            <div v-html="event.contentHtml" class="event-content-html"></div>
            <div class="about-event-buttons">
              <button
                v-if="event.registrationStatus === 'open'"
                class="btn btn-primary"
                @click="showDialog = true"
              >Register Now <span aria-hidden="true">→</span></button>
              <button
                v-else-if="event.registrationStatus === 'coming_soon'"
                class="btn btn-secondary status-btn"
                disabled
              >Registration Opens Soon</button>
              <button
                v-else
                class="btn btn-secondary status-btn"
                disabled
              >Registration Closed</button>
            </div>
          </div>

          <div v-if="(event.galleryImages || []).length" class="event-images-wrap animate-observe delay-1">
            <img
              :src="(event.galleryImages || [])[0]"
              :alt="`${event.title} featured`"
              class="img-banner"
            />
            <div v-if="(event.galleryImages || []).length > 1" class="event-gallery-grid" :class="`gallery-count-${Math.min((event.galleryImages || []).length - 1, 7)}`">
              <img
                v-for="(img, i) in (event.galleryImages || []).slice(1, 8)"
                :key="i"
                :src="img"
                :alt="`${event.title} gallery ${i + 2}`"
                class="gallery-img"
              />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Google Form modal (only shown when registrationStatus is 'open') -->
    <Teleport to="body">
      <div v-if="showDialog" class="dialog-overlay" role="dialog" :aria-label="`${event.title} Registration`">
        <div class="dialog-panel">
          <div class="dialog-header">
            <h3>Register — {{ event.title }}</h3>
            <button class="dialog-close" @click="showDialog = false" aria-label="Close">✕</button>
          </div>
          <div class="dialog-iframe-wrap">
            <iframe
              :src="event.googleFormUrl"
              width="100%"
              height="560"
              frameborder="0"
              marginheight="0"
              marginwidth="0"
              title="Registration form"
            >Loading…</iframe>
          </div>
        </div>
      </div>
    </Teleport>
  </div>

  <div v-else-if="pending" class="loading-state section">
    <div class="section-container"><p>Loading event…</p></div>
  </div>

  <div v-else class="error-state section">
    <div class="section-container">
      <h1>Event not found</h1>
      <p>This event does not exist or may have been removed.</p>
      <NuxtLink to="/" class="btn btn-primary">Back to Home</NuxtLink>
    </div>
  </div>
</template>

<script setup>
const route = useRoute()
const { public: { apiBaseUrl } } = useRuntimeConfig()

const { data: event, pending } = await useAsyncData(
  `event-${route.params.slug}`,
  () => $fetch(`${apiBaseUrl.replace(/\/$/, '')}/events.php?slug=${route.params.slug}`).catch(() => null)
)

const showDialog = ref(false)

watch(showDialog, open => {
  if (process.client) document.body.style.overflow = open ? 'hidden' : ''
})

onMounted(() => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); observer.unobserve(e.target) } })
  }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' })
  document.querySelectorAll('.animate-observe').forEach(el => observer.observe(el))
  document.addEventListener('keydown', e => { if (e.key === 'Escape') showDialog.value = false })
})

if (event.value) {
  const plainText = (html) => html.replace(/<[^>]+>/g, '').slice(0, 160)

  useSeoMeta({
    title: `${event.value.title} | Munich Kannadigaru`,
    description: plainText(event.value.contentHtml || ''),
    ogTitle: event.value.title,
    ogDescription: plainText(event.value.contentHtml || ''),
    ogImage: event.value.heroImage || 'https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png',
    ogType: 'website',
    ogUrl: `https://munichkannadigaru.org/events/${route.params.slug}`,
    twitterCard: 'summary_large_image',
    twitterTitle: event.value.title,
    twitterDescription: plainText(event.value.contentHtml || ''),
  })

  useHead({
    script: [{
      type: 'application/ld+json',
      children: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'Event',
        name: event.value.title,
        description: plainText(event.value.contentHtml || ''),
        startDate: event.value.date,
        location: {
          '@type': 'Place',
          name: event.value.venue,
          address: { '@type': 'PostalAddress', addressLocality: 'Munich', addressCountry: 'DE' }
        },
        organizer: { '@type': 'Organization', name: 'Munich Kannadigaru', url: 'https://munichkannadigaru.org' },
        image: event.value.heroImage,
        eventStatus: 'https://schema.org/EventScheduled',
        eventAttendanceMode: 'https://schema.org/OfflineEventAttendanceMode',
      })
    }]
  })
}
</script>

<style scoped>
.past-banner {
  background: var(--gold);
  color: #1A1A1A;
  text-align: center;
  padding: 18px 24px;
  font-size: 16px;
  font-weight: 600;
  font-family: 'Manrope', sans-serif;
  letter-spacing: 0.02em;
}
.animate-item {
  opacity: 0;
  transform: translateY(24px);
  animation: fadeUp 0.7s ease forwards;
}
.delay-1 { animation-delay: 0.1s; }
.delay-2 { animation-delay: 0.2s; }
.delay-3 { animation-delay: 0.3s; }
@keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

.animate-observe {
  opacity: 0;
  transform: translateY(28px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
.animate-observe.delay-1 { transition-delay: 0.12s; }
.animate-observe.is-visible { opacity: 1; transform: translateY(0); }

@media (prefers-reduced-motion: reduce) {
  .animate-item, .animate-observe {
    animation: none !important; transition: none !important;
    opacity: 1 !important; transform: none !important;
  }
}

.event-hero {
  background: linear-gradient(135deg, var(--primary-red) 0%, #8B0000 100%);
  color: var(--white);
  padding: 100px 0;
  position: relative;
  overflow: hidden;
  text-align: center;
}
.event-hero[style*="--hero-bg"] {
  background: linear-gradient(135deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.75) 100%);
}
.event-hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image: var(--hero-bg);
  background-size: cover;
  background-position: center;
  opacity: 0.85;
  z-index: 0;
}
.event-hero-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 40px;
  position: relative;
  z-index: 1;
}
.event-tag-pill {
  background: rgba(255,255,255,0.15);
  color: var(--white);
  margin-bottom: 25px;
}
.event-hero h1 {
  font-size: clamp(36px, 6vw, 72px);
  font-family: 'Playfair Display', serif;
  margin-bottom: 15px;
  color: var(--white);
}
.event-kannada {
  font-size: clamp(20px, 3.5vw, 32px);
  color: var(--gold);
  margin-bottom: 25px;
  display: block;
}
.event-info {
  display: flex;
  gap: 20px;
  justify-content: center;
  flex-wrap: wrap;
  margin-top: 32px;
}
.event-info-item {
  background: rgba(255,255,255,0.1);
  padding: 14px 22px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 15px;
  color: var(--white);
}
.info-icon { font-size: 16px; }

.about-event { background: var(--cream); }
.about-event-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: start;
}
.about-event-content .tag { margin-bottom: 20px; display: inline-block; }
.event-content-html :deep(p) {
  color: var(--text-light);
  font-size: 16px;
  line-height: 1.8;
  margin-bottom: 18px;
}
.about-event-buttons {
  display: flex;
  gap: 20px;
  margin-top: 32px;
  flex-wrap: wrap;
}
.status-btn { opacity: 0.6; cursor: not-allowed; }

.event-images-wrap { display: flex; flex-direction: column; gap: 12px; }
.img-banner {
  width: 100%;
  height: 280px;
  object-fit: cover;
  border-radius: 16px;
  display: block;
}
.event-gallery-grid {
  display: grid;
  gap: 10px;
  grid-template-columns: 1fr 1fr;
}
/* 1 image: full width */
.gallery-count-1 { grid-template-columns: 1fr; }
/* 2–4: 2 columns */
.gallery-count-2, .gallery-count-3, .gallery-count-4 { grid-template-columns: 1fr 1fr; }
/* 5+: 3 columns */
.gallery-count-5, .gallery-count-6, .gallery-count-7, .gallery-count-8 { grid-template-columns: 1fr 1fr 1fr; }
.gallery-img {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 12px;
  display: block;
}

/* Google Form dialog */
.dialog-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.6);
  z-index: 2000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px;
}
.dialog-panel {
  background: var(--white);
  border-radius: 20px;
  width: 100%;
  max-width: 760px;
  height: calc(100vh - 48px);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}
.dialog-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 24px;
  border-bottom: 1px solid var(--border-light);
  flex-shrink: 0;
}
.dialog-header h3 {
  font-family: 'Manrope', sans-serif;
  font-size: 1rem;
  font-weight: 600;
  letter-spacing: 0.01em;
  margin: 0;
  color: var(--text-dark);
}
.dialog-close {
  width: 36px; height: 36px; border-radius: 50%;
  border: none; background: var(--cream);
  cursor: pointer; font-size: 15px; color: var(--text-dark);
  transition: all 0.2s; display: flex; align-items: center; justify-content: center;
}
.dialog-close:hover { background: var(--primary-red); color: var(--white); }
.dialog-iframe-wrap { flex: 1; overflow: hidden; min-height: 0; }
.dialog-iframe-wrap iframe { display: block; width: 100%; height: 100%; }

.loading-state, .error-state { text-align: center; padding: 80px 40px; }
.error-state h1 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(1.8rem, 4vw, 2.8rem);
  margin-bottom: 12px;
  color: var(--text-dark);
}
.error-state p { color: var(--text-light); margin-bottom: 24px; }

@media (max-width: 1024px) {
  .about-event-grid { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
  .event-hero { padding: 70px 0; }
  .event-hero-container { padding: 0 20px; }
  .about-event-buttons { flex-direction: column; }
  .event-images-wrap { gap: 8px; }
  .img-banner { height: 200px; }
  .gallery-count-5, .gallery-count-6, .gallery-count-7, .gallery-count-8 { grid-template-columns: 1fr 1fr; }
  .gallery-img { height: 120px; }
  .dialog-panel { height: calc(100vh - 24px); border-radius: 12px; }
  .dialog-iframe-wrap iframe { min-height: unset; }
}
</style>
