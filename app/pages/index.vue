<template>
  <div>
    <!-- Hero -->
    <section class="hero" aria-label="Welcome">
      <div class="hero-inner">
        <p class="hero-eyebrow kannada">ಕನ್ನಡ ಸಮುದಾಯ — ಮ್ಯೂನಿಕ್</p>
        <h1 class="hero-title">We carry Karnataka<br>wherever we go.</h1>
        <p class="hero-subtitle">
          A community of Kannadigas in and around Munich — celebrating language,
          culture, and belonging across generations.
        </p>
        <div class="hero-actions">
          <NuxtLink to="/membership" class="btn btn-primary">Join the community</NuxtLink>
          <NuxtLink to="/about" class="btn btn-outline">Learn more</NuxtLink>
        </div>
      </div>
    </section>

    <hr class="gold-rule" />

    <!-- Initiatives -->
    <section class="section" aria-label="Our initiatives">
      <h2 class="section-title">What we do</h2>
      <div class="initiative-grid">
        <NuxtLink to="/initiatives/karnataka-cultural" class="initiative-card">
          <h3>Karnataka Cultural</h3>
          <p>Festivals, music, dance, and folk traditions celebrating Karnataka's artistic heritage in Munich.</p>
        </NuxtLink>
        <NuxtLink to="/initiatives/jnana-deepa" class="initiative-card">
          <h3>Jnana Deepa</h3>
          <p>Workshops, mentorship, and knowledge-sharing programs for students and young professionals.</p>
        </NuxtLink>
        <NuxtLink to="/initiatives/kannada-kali" class="initiative-card">
          <h3>Kannada Kali</h3>
          <p>Structured Kannada language classes for children and adults, keeping the language alive abroad.</p>
        </NuxtLink>
      </div>
    </section>

    <hr class="gold-rule" />

    <!-- UTSAVA 2025 CTA -->
    <section class="section" aria-label="UTSAVA 2025">
      <div class="event-cta-inner">
        <p class="kannada event-kn">ಉತ್ಸವ ೨೦೨೫</p>
        <h2>UTSAVA 2025 — Karnataka Rajyotsava</h2>
        <p>
          Join us for a grand celebration of Karnataka Rajyotsava with cultural performances,
          community dinner, and festive activities for all age groups.
        </p>
        <NuxtLink to="/events/utsava-2025" class="btn btn-primary">View event details</NuxtLink>
      </div>
    </section>

    <hr class="gold-rule" />

    <!-- Kannada script feature -->
    <section class="section" aria-label="Our values">
      <blockquote class="kannada-quote">
        <p class="kannada">ನಮ್ಮ ಸಂಸ್ಕೃತಿ ನಮ್ಮ ಹೆಮ್ಮೆ</p>
        <footer>Our culture, our pride.</footer>
      </blockquote>
    </section>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Munich Kannadigaru — ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು',
  description: 'A vibrant Kannada community in Munich celebrating language, culture, and belonging.'
})
</script>

<style scoped>
/* Hero */
.hero {
  padding: var(--space-2xl) 0;
}

.hero-inner {
  max-width: 640px;
  animation: hero-in 0.6s ease both;
}

@keyframes hero-in {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}
@media (prefers-reduced-motion: reduce) {
  .hero-inner { animation: none; }
}

.hero-eyebrow {
  font-size: 1.1rem;
  color: var(--ink-muted);
  margin-bottom: var(--space-sm);
}

.hero-title {
  font-size: clamp(2.4rem, 6vw, 3.6rem);
  line-height: 1.15;
  margin-bottom: var(--space-lg);
  color: var(--brand);
}

.hero-subtitle {
  font-size: 1.1rem;
  color: var(--ink-muted);
  max-width: 520px;
  margin-bottom: var(--space-xl);
}

.hero-actions {
  display: flex;
  gap: var(--space-md);
  flex-wrap: wrap;
}

/* Section heading */
.section-title {
  margin-bottom: var(--space-lg);
  color: var(--ink-muted);
  font-size: 0.9rem;
  font-weight: 600;
  border-left: 3px solid var(--gold);
  padding-left: var(--space-sm);
}

/* Initiative grid */
.initiative-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: var(--space-lg);
}

.initiative-card {
  display: block;
  padding: var(--space-lg);
  background: var(--surface-2);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  text-decoration: none;
  color: var(--ink);
  transition: border-color 0.15s, box-shadow 0.15s;
}
.initiative-card:hover {
  border-color: var(--brand);
  box-shadow: 0 4px 16px rgba(139, 26, 26, 0.08);
  color: var(--ink);
}
.initiative-card h3 {
  color: var(--brand);
  margin-bottom: var(--space-xs);
}
.initiative-card p {
  font-size: 0.92rem;
  color: var(--ink-muted);
  margin: 0;
}

/* Event CTA */
.event-cta-inner {
  background: var(--ink);
  border-radius: var(--radius);
  padding: var(--space-xl);
  color: #e8dcc8;
}
.event-cta-inner h2 {
  color: var(--gold);
  margin-bottom: var(--space-md);
}
.event-cta-inner p {
  color: #c8b89a;
  margin-bottom: var(--space-lg);
}
.event-kn {
  font-size: 1.4rem;
  color: var(--gold);
  margin-bottom: var(--space-xs);
}

/* Kannada quote */
.kannada-quote {
  margin: 0 auto;
  max-width: 480px;
  padding: var(--space-xl);
  border: none;
  text-align: center;
}
.kannada-quote p {
  font-size: clamp(1.6rem, 4vw, 2.4rem);
  color: var(--brand);
  margin-bottom: var(--space-sm);
  line-height: 1.4;
}
.kannada-quote footer {
  font-size: 0.95rem;
  color: var(--ink-muted);
  font-style: italic;
}

@media (max-width: 640px) {
  .event-cta-inner { padding: var(--space-lg); }
}
</style>
