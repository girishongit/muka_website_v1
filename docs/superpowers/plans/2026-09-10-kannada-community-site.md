# Kannada Community Munich — Full Site Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use `superpowers:subagent-driven-development` (recommended) or `superpowers:executing-plans` to implement plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Convert the existing Kannada Community Munich content into a polished, mobile-first Nuxt 4 static site with distinctive design using Karnataka-inspired colors, Lora + Tiro Kannada fonts, and static forms (API-ready).

**Architecture:** Nuxt 4 app with `@nuxt/ui` + Tailwind CSS 4, a default layout wrapping all pages, CSS custom properties for the design token system, and per-page Vue components. Forms are static HTML with a clear `TODO: wire up API` comment. Google Fonts loaded via `nuxt.config.ts` `app.head` link tags (CDN — fastest for external fonts).

**Tech Stack:** Nuxt 4, Vue 3, Tailwind CSS 4, @nuxt/ui, @nuxtjs/seo, Google Fonts (Lora + Tiro Kannada)

**Spec:** See brainstorming session (2026-09-10) in conversation — design tokens, typography, layout, and page inventory defined there.

---

## Global Constraints

- All pages must be mobile-first; breakpoint for desktop layout at `768px`
- Kannada Unicode text must render using `Tiro Kannada` font
- English body text uses `Lora` serif
- Color palette anchored to Karnataka crimson (`#8B1A1A`), turmeric gold (`#C9A84C`), parchment (`#FEFCF5`)
- No all-caps labels, no tracked-out eyebrows, no `→` appended to every link
- One page-load hero animation only; no repeated fade-slide-up on cards
- Forms are static with `<!-- TODO: wire up to API endpoint -->` comments and `action="#"` + `method="POST"`
- `nuxt.config.ts` must not be modified until explicitly instructed (per CLAUDE.md — check `.claude-devtools/settings.json` first)
- All pages prerendered (static site output via `nuxt generate`)
- `app/` directory structure (Nuxt 4 convention — pages at `app/pages/`, layouts at `app/layouts/`, components at `app/components/`)

---

## File Map

**Create:**
- `app/assets/css/main.css` — design tokens, base reset, typography scale, utility classes
- `app/layouts/default.vue` — site shell: header + `<slot>` + footer
- `app/components/TheHeader.vue` — sticky nav, hamburger mobile menu
- `app/components/TheFooter.vue` — copyright + privacy/resolutions links
- `app/app.vue` — replace NuxtWelcome with `<NuxtLayout><NuxtPage/></NuxtLayout>`
- `app/pages/index.vue` — home hero + initiative cards + event CTA
- `app/pages/about.vue`
- `app/pages/forerunner.vue`
- `app/pages/contact.vue` — static contact form
- `app/pages/membership.vue`
- `app/pages/membership/register.vue` — static registration form
- `app/pages/events/utsava-2025.vue`
- `app/pages/initiatives/karnataka-cultural.vue`
- `app/pages/initiatives/jnana-deepa.vue`
- `app/pages/initiatives/kannada-kali.vue`
- `app/pages/resolutions.vue`
- `app/pages/privacy-policy.vue`

**Modify:**
- `nuxt.config.ts` — add css, app.head (fonts + SEO meta), prerender routes (LAST task, after settings check)

---

## Task 1: CSS Design Token System

**Files:**
- Create: `app/assets/css/main.css`

**Interfaces:**
- Produces: CSS custom properties, base resets, `.container`, `.btn`, `.page-hero`, `.section`, `.content-card`, `.page-content` used by all subsequent tasks

- [ ] **Step 1: Create the CSS file**

```css
/* app/assets/css/main.css */

/* ── Design Tokens ───────────────────────────────────────── */
:root {
  --brand:        #8B1A1A;   /* Karnataka crimson */
  --brand-hover:  #B22222;
  --gold:         #C9A84C;   /* turmeric / sandalwood */
  --surface:      #FEFCF5;   /* parchment white */
  --surface-2:    #F5EFE0;   /* warmer card background */
  --ink:          #1C1008;   /* warm near-black */
  --ink-muted:    #5C4A2A;   /* brown-toned muted */
  --border:       #DDD0B3;   /* aged parchment crease */

  --font-serif:   'Lora', Georgia, 'Times New Roman', serif;
  --font-kannada: 'Tiro Kannada', serif;

  --space-xs:  4px;
  --space-sm:  8px;
  --space-md:  16px;
  --space-lg:  24px;
  --space-xl:  40px;
  --space-2xl: 64px;

  --radius:    6px;
  --max-read:  680px;   /* reading column */
  --max-wide:  1100px;  /* full-width container */
}

/* ── Base Reset ──────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; }

html { scroll-behavior: smooth; }

body {
  margin: 0;
  font-family: var(--font-serif);
  font-size: 17px;
  line-height: 1.7;
  background: var(--surface);
  color: var(--ink);
  -webkit-font-smoothing: antialiased;
}

img, svg { display: block; max-width: 100%; }

a {
  color: var(--brand);
  text-decoration-thickness: 1px;
  text-underline-offset: 3px;
}
a:hover { color: var(--brand-hover); }

/* ── Typography ──────────────────────────────────────────── */
h1, h2, h3, h4 {
  font-family: var(--font-serif);
  line-height: 1.25;
  color: var(--ink);
  margin: 0 0 var(--space-md);
}

h1 { font-size: clamp(2rem, 5vw, 3.2rem); font-weight: 700; }
h2 { font-size: clamp(1.3rem, 3vw, 1.8rem); font-weight: 600; }
h3 { font-size: 1.15rem; font-weight: 600; }

p { margin: 0 0 var(--space-md); color: var(--ink); }
p:last-child { margin-bottom: 0; }

ul, ol {
  padding-left: var(--space-lg);
  margin: 0 0 var(--space-md);
  color: var(--ink);
}
li { margin-bottom: var(--space-xs); }

.kannada {
  font-family: var(--font-kannada);
  font-size: 1.05em;
}

/* ── Layout Containers ───────────────────────────────────── */
.container {
  width: min(var(--max-wide), 92vw);
  margin: 0 auto;
}

.container-read {
  width: min(var(--max-read), 92vw);
  margin: 0 auto;
}

/* ── Page Structure ──────────────────────────────────────── */
.page-content {
  padding: var(--space-xl) 0 var(--space-2xl);
  min-height: 70vh;
}

.page-title {
  margin-bottom: var(--space-xl);
  padding-bottom: var(--space-md);
  border-bottom: 2px solid var(--gold);
}

.section {
  margin-bottom: var(--space-xl);
}

/* ── Content Card ────────────────────────────────────────── */
.content-card {
  background: var(--surface-2);
  border: 1px solid var(--border);
  border-left: 3px solid var(--gold);
  border-radius: var(--radius);
  padding: var(--space-lg) var(--space-xl);
  margin-bottom: var(--space-lg);
}

.content-card h2,
.content-card h3 {
  margin-bottom: var(--space-sm);
}

/* ── Buttons ─────────────────────────────────────────────── */
.btn {
  display: inline-block;
  padding: 10px 22px;
  border-radius: var(--radius);
  font-family: var(--font-serif);
  font-size: 0.95rem;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  border: none;
  transition: background 0.15s, color 0.15s;
}

.btn-primary {
  background: var(--brand);
  color: #fff;
}
.btn-primary:hover {
  background: var(--brand-hover);
  color: #fff;
}

.btn-outline {
  background: transparent;
  color: var(--brand);
  border: 1.5px solid var(--brand);
}
.btn-outline:hover {
  background: var(--brand);
  color: #fff;
}

/* ── Forms ───────────────────────────────────────────────── */
.form-group {
  margin-bottom: var(--space-lg);
}

.form-label {
  display: block;
  font-weight: 600;
  margin-bottom: var(--space-xs);
  font-size: 0.9rem;
  color: var(--ink-muted);
}

.form-input,
.form-select,
.form-textarea {
  width: 100%;
  padding: 10px 14px;
  font-family: var(--font-serif);
  font-size: 1rem;
  color: var(--ink);
  background: #fff;
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  transition: border-color 0.15s;
  appearance: none;
}
.form-input:focus,
.form-select:focus,
.form-textarea:focus {
  outline: none;
  border-color: var(--brand);
}

.form-textarea { resize: vertical; min-height: 120px; }

/* ── Gold Divider ────────────────────────────────────────── */
.gold-rule {
  border: none;
  border-top: 1.5px solid var(--gold);
  margin: var(--space-xl) 0;
  opacity: 0.6;
}

/* ── Responsive Media Queries ────────────────────────────── */
@media (max-width: 640px) {
  body { font-size: 16px; }
  .page-content { padding: var(--space-lg) 0 var(--space-xl); }
  .content-card { padding: var(--space-md) var(--space-lg); }
}
```

- [ ] **Step 2: Verify file was created**

```bash
ls -la app/assets/css/main.css
```
Expected: file exists with size > 0

- [ ] **Step 3: Commit**

```bash
git add app/assets/css/main.css
git commit -m "feat: add CSS design token system — Karnataka crimson/gold/parchment palette"
```

---

## Task 2: App Shell — app.vue + default layout

**Files:**
- Modify: `app/app.vue` (replace NuxtWelcome)
- Create: `app/layouts/default.vue`

**Interfaces:**
- Consumes: `TheHeader`, `TheFooter` (created in Task 3)
- Produces: `<NuxtLayout>` + `<NuxtPage>` wiring; `.page-content` wrapper in layout slot

- [ ] **Step 1: Replace app.vue**

```vue
<!-- app/app.vue -->
<template>
  <NuxtRouteAnnouncer />
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>
```

- [ ] **Step 2: Create default layout**

```vue
<!-- app/layouts/default.vue -->
<template>
  <div class="site-wrapper">
    <TheHeader />
    <main class="page-content">
      <div class="container">
        <slot />
      </div>
    </main>
    <TheFooter />
  </div>
</template>
```

- [ ] **Step 3: Commit**

```bash
git add app/app.vue app/layouts/default.vue
git commit -m "feat: wire up NuxtLayout/NuxtPage shell and default layout"
```

---

## Task 3: TheHeader component (sticky nav + mobile hamburger)

**Files:**
- Create: `app/components/TheHeader.vue`

**Interfaces:**
- Produces: `<TheHeader />` — sticky top nav, desktop horizontal links, mobile full-screen drawer toggled by hamburger button

- [ ] **Step 1: Create TheHeader.vue**

```vue
<!-- app/components/TheHeader.vue -->
<template>
  <header class="site-header" :class="{ scrolled: isScrolled }">
    <div class="container nav-row">
      <NuxtLink to="/" class="brand" @click="closeMenu">
        <span class="brand-en">Munich Kannadigaru</span>
        <span class="brand-kn kannada">ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು</span>
      </NuxtLink>

      <button
        class="hamburger"
        :class="{ open: menuOpen }"
        aria-label="Toggle navigation menu"
        @click="toggleMenu"
      >
        <span></span>
        <span></span>
        <span></span>
      </button>

      <nav class="nav-links" :class="{ open: menuOpen }" aria-label="Main navigation">
        <NuxtLink to="/about" @click="closeMenu">About</NuxtLink>
        <NuxtLink to="/forerunner" @click="closeMenu">Forerunner</NuxtLink>
        <NuxtLink to="/events/utsava-2025" @click="closeMenu">UTSAVA 2025</NuxtLink>
        <NuxtLink to="/membership" @click="closeMenu">Membership</NuxtLink>
        <NuxtLink to="/contact" @click="closeMenu">Contact</NuxtLink>
      </nav>
    </div>
  </header>
</template>

<script setup>
const menuOpen = ref(false)
const isScrolled = ref(false)

function toggleMenu() { menuOpen.value = !menuOpen.value }
function closeMenu() { menuOpen.value = false }

onMounted(() => {
  window.addEventListener('scroll', () => {
    isScrolled.value = window.scrollY > 10
  }, { passive: true })
})
</script>

<style scoped>
.site-header {
  position: sticky;
  top: 0;
  z-index: 100;
  background: var(--surface);
  border-bottom: 1px solid var(--border);
  transition: box-shadow 0.2s;
}
.site-header.scrolled {
  box-shadow: 0 2px 12px rgba(28, 16, 8, 0.08);
}

.nav-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 64px;
  gap: var(--space-md);
}

.brand {
  display: flex;
  flex-direction: column;
  text-decoration: none;
  line-height: 1.2;
}
.brand-en {
  font-family: var(--font-serif);
  font-size: 1rem;
  font-weight: 700;
  color: var(--brand);
}
.brand-kn {
  font-size: 0.78rem;
  color: var(--ink-muted);
}

.nav-links {
  display: flex;
  gap: var(--space-lg);
  align-items: center;
}
.nav-links a {
  font-family: var(--font-serif);
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--ink);
  text-decoration: none;
  padding-bottom: 2px;
  border-bottom: 2px solid transparent;
  transition: color 0.15s, border-color 0.15s;
}
.nav-links a:hover,
.nav-links a.router-link-active {
  color: var(--brand);
  border-bottom-color: var(--gold);
}

/* Hamburger */
.hamburger {
  display: none;
  flex-direction: column;
  justify-content: center;
  gap: 5px;
  width: 36px;
  height: 36px;
  padding: 4px;
  background: none;
  border: none;
  cursor: pointer;
}
.hamburger span {
  display: block;
  height: 2px;
  background: var(--ink);
  border-radius: 2px;
  transition: transform 0.2s, opacity 0.2s;
}
.hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.hamburger.open span:nth-child(2) { opacity: 0; }
.hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

/* Mobile styles */
@media (max-width: 768px) {
  .hamburger { display: flex; }

  .nav-links {
    display: none;
    position: fixed;
    inset: 64px 0 0 0;
    background: var(--surface);
    flex-direction: column;
    align-items: flex-start;
    padding: var(--space-xl) var(--space-xl);
    gap: var(--space-lg);
    border-top: 1px solid var(--border);
    overflow-y: auto;
  }
  .nav-links.open { display: flex; }
  .nav-links a {
    font-size: 1.2rem;
    border-bottom: none;
    padding-bottom: 0;
  }
  .nav-links a:hover,
  .nav-links a.router-link-active {
    color: var(--brand);
    border-bottom: none;
  }
}
</style>
```

- [ ] **Step 2: Commit**

```bash
git add app/components/TheHeader.vue
git commit -m "feat: add sticky TheHeader with mobile hamburger drawer"
```

---

## Task 4: TheFooter component

**Files:**
- Create: `app/components/TheFooter.vue`

**Interfaces:**
- Produces: `<TheFooter />` — copyright + nav links for privacy policy and resolutions

- [ ] **Step 1: Create TheFooter.vue**

```vue
<!-- app/components/TheFooter.vue -->
<template>
  <footer class="site-footer">
    <div class="container footer-inner">
      <div class="footer-brand">
        <span class="kannada footer-kn">ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು</span>
        <p class="footer-copy">© {{ year }} Kannada Community Munich</p>
      </div>
      <nav class="footer-nav" aria-label="Footer navigation">
        <NuxtLink to="/about">About</NuxtLink>
        <NuxtLink to="/membership">Membership</NuxtLink>
        <NuxtLink to="/contact">Contact</NuxtLink>
        <NuxtLink to="/privacy-policy">Privacy Policy</NuxtLink>
        <NuxtLink to="/resolutions">Resolutions</NuxtLink>
      </nav>
    </div>
    <div class="container footer-bottom">
      <p>Celebrating Kannada culture in Munich and beyond.</p>
    </div>
  </footer>
</template>

<script setup>
const year = new Date().getFullYear()
</script>

<style scoped>
.site-footer {
  background: var(--ink);
  color: #e8dcc8;
  padding: var(--space-xl) 0 var(--space-lg);
  margin-top: var(--space-2xl);
}

.footer-inner {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: var(--space-xl);
  flex-wrap: wrap;
  padding-bottom: var(--space-lg);
  border-bottom: 1px solid rgba(255,255,255,0.1);
}

.footer-kn {
  font-size: 1.3rem;
  color: var(--gold);
  display: block;
  margin-bottom: var(--space-xs);
}

.footer-copy {
  font-size: 0.85rem;
  color: #a89070;
  margin: 0;
}

.footer-nav {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-md) var(--space-lg);
  align-items: center;
}
.footer-nav a {
  color: #e8dcc8;
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.15s;
}
.footer-nav a:hover { color: var(--gold); }

.footer-bottom {
  padding-top: var(--space-md);
  font-size: 0.82rem;
  color: #6b5a3a;
}
.footer-bottom p { margin: 0; }

@media (max-width: 640px) {
  .footer-inner { flex-direction: column; gap: var(--space-lg); }
}
</style>
```

- [ ] **Step 2: Commit**

```bash
git add app/components/TheFooter.vue
git commit -m "feat: add TheFooter with dark Karnataka-toned footer"
```

---

## Task 5: Home Page (index.vue)

**Files:**
- Create: `app/pages/index.vue`

**Interfaces:**
- Consumes: CSS classes from `main.css` (Task 1); layout from `default.vue` (Task 2)
- Produces: `/` route — hero section, initiative summary cards, UTSAVA 2025 event CTA

- [ ] **Step 1: Create app/pages/index.vue**

```vue
<!-- app/pages/index.vue -->
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
    <section class="section event-cta" aria-label="UTSAVA 2025">
      <div class="event-cta-inner">
        <div>
          <p class="kannada event-kn">ಉತ್ಸವ ೨೦೨೫</p>
          <h2>UTSAVA 2025 — Karnataka Rajyotsava</h2>
          <p>
            Join us for a grand celebration of Karnataka Rajyotsava with cultural performances,
            community dinner, and festive activities for all age groups.
          </p>
          <NuxtLink to="/events/utsava-2025" class="btn btn-primary">View event details</NuxtLink>
        </div>
      </div>
    </section>

    <hr class="gold-rule" />

    <!-- Kannada script feature -->
    <section class="section kannada-feature" aria-label="Our values in Kannada">
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
  text-align: left;
}

.hero-inner {
  max-width: 640px;
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

/* Section title */
.section-title {
  margin-bottom: var(--space-lg);
  color: var(--ink-muted);
  font-size: 0.9rem;
  font-weight: 600;
  letter-spacing: 0.03em;
  text-transform: none;
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
  padding: var(--space-xl) var(--space-xl);
  color: #e8dcc8;
}
.event-cta-inner h2 { color: var(--gold); margin-bottom: var(--space-md); }
.event-cta-inner p { color: #c8b89a; margin-bottom: var(--space-lg); }
.event-kn {
  font-size: 1.4rem;
  color: var(--gold);
  margin-bottom: var(--space-xs);
}

/* Kannada quote */
.kannada-feature { text-align: center; }
.kannada-quote {
  margin: 0 auto;
  max-width: 480px;
  padding: var(--space-xl);
  border: none;
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

/* Hero animation — one orchestrated entrance, no repeated fade-slide */
@keyframes hero-in {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}
.hero-inner {
  animation: hero-in 0.6s ease both;
}
@media (prefers-reduced-motion: reduce) {
  .hero-inner { animation: none; }
}

@media (max-width: 640px) {
  .event-cta-inner { padding: var(--space-lg); }
}
</style>
```

- [ ] **Step 2: Commit**

```bash
git add app/pages/index.vue
git commit -m "feat: add home page — hero, initiative grid, UTSAVA CTA, Kannada quote"
```

---

## Task 6: About, Forerunner, Resolutions, Privacy Policy pages

**Files:**
- Create: `app/pages/about.vue`
- Create: `app/pages/forerunner.vue`
- Create: `app/pages/resolutions.vue`
- Create: `app/pages/privacy-policy.vue`

**Interfaces:**
- Consumes: `.page-title`, `.content-card`, `.section`, `.container-read` from `main.css`
- Produces: 4 routed pages

- [ ] **Step 1: Create app/pages/about.vue**

```vue
<!-- app/pages/about.vue -->
<template>
  <div class="container-read">
    <h1 class="page-title">About Us</h1>

    <div class="content-card">
      <h2>Who we are</h2>
      <p>
        Kannada Community Munich is a non-profit cultural and social community formed to bring
        together people with roots in Karnataka and all admirers of the Kannada language and culture.
      </p>
      <p>
        We foster connection, learning, and belonging through events, volunteering, and programs
        for children, youth, and families.
      </p>
    </div>

    <div class="content-card">
      <h2>Vision</h2>
      <p>
        To build a strong, inclusive, future-ready Kannada community in Munich where culture,
        language, and community service thrive across generations.
      </p>
    </div>

    <div class="content-card">
      <h2>Mission</h2>
      <ul>
        <li>Preserve and promote Kannada language, literature, and heritage.</li>
        <li>Organise cultural, educational, and social activities year-round.</li>
        <li>Create a welcoming support network for newcomers and families.</li>
        <li>Encourage volunteering and community-led leadership.</li>
      </ul>
    </div>

    <div class="content-card">
      <p class="kannada about-tagline">ಕನ್ನಡ ನಾಡಿಗೆ ಜಯ — ಸಮುದಾಯಕ್ಕೆ ಹೆಮ್ಮೆ.</p>
    </div>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'About — Munich Kannadigaru',
  description: 'Learn about Kannada Community Munich — our vision, mission, and who we are.'
})
</script>

<style scoped>
.about-tagline {
  font-size: 1.2rem;
  color: var(--brand);
  text-align: center;
  margin: 0;
}
</style>
```

- [ ] **Step 2: Create app/pages/forerunner.vue**

```vue
<!-- app/pages/forerunner.vue -->
<template>
  <div class="container-read">
    <h1 class="page-title">Forerunner</h1>

    <div class="content-card">
      <h2>Community pioneers</h2>
      <p>
        We honor the early contributors and volunteers whose vision and service laid the
        foundation of Kannada Community Munich.
      </p>
      <p>
        Their dedication to culture, education, and social responsibility continues to inspire
        our current and future initiatives.
      </p>
    </div>

    <div class="content-card">
      <h2>Their legacy</h2>
      <p>
        The forerunners established the values this community holds today: openness, service,
        and an unbreakable bond with Karnataka's cultural heritage — even from across the world.
      </p>
    </div>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Forerunner — Munich Kannadigaru',
  description: 'Honoring the early pioneers who built Kannada Community Munich.'
})
</script>
```

- [ ] **Step 3: Create app/pages/resolutions.vue**

```vue
<!-- app/pages/resolutions.vue -->
<template>
  <div class="container-read">
    <h1 class="page-title">Resolutions</h1>

    <div class="content-card">
      <h2>Community governance</h2>
      <p>
        Resolutions passed by the community form the backbone of our governance —
        ensuring transparency, member participation, and accountability.
      </p>
    </div>

    <div class="content-card">
      <h2>Key resolutions</h2>
      <ul>
        <li>Annual general meetings to review community activities.</li>
        <li>Open membership and broad-based participation in decisions.</li>
        <li>Transparent financial reporting for all community events.</li>
      </ul>
    </div>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Resolutions — Munich Kannadigaru',
  description: 'Community governance resolutions of Kannada Community Munich.'
})
</script>
```

- [ ] **Step 4: Create app/pages/privacy-policy.vue**

```vue
<!-- app/pages/privacy-policy.vue -->
<template>
  <div class="container-read">
    <h1 class="page-title">Privacy Policy</h1>

    <div class="content-card">
      <h2>Overview</h2>
      <p>
        Kannada Community Munich takes your privacy seriously. This policy explains what
        information we collect and how we use it.
      </p>
    </div>

    <div class="content-card">
      <h2>Information we collect</h2>
      <ul>
        <li>Contact details — name, email, phone number, address.</li>
        <li>Membership and event registration details you provide voluntarily.</li>
        <li>Basic website usage information for performance and security monitoring.</li>
      </ul>
    </div>

    <div class="content-card">
      <h2>How we use it</h2>
      <ul>
        <li>To manage memberships, events, and community communication.</li>
        <li>To improve community programs and website experience.</li>
        <li>To comply with legal and administrative requirements.</li>
      </ul>
    </div>

    <div class="content-card">
      <h2>Contact</h2>
      <p>
        Questions about privacy or data handling? Reach us at
        <a href="mailto:kannadacommunitymunich@gmail.com">kannadacommunitymunich@gmail.com</a>.
      </p>
    </div>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Privacy Policy — Munich Kannadigaru',
  description: 'Privacy policy for Kannada Community Munich.'
})
</script>
```

- [ ] **Step 5: Commit**

```bash
git add app/pages/about.vue app/pages/forerunner.vue app/pages/resolutions.vue app/pages/privacy-policy.vue
git commit -m "feat: add about, forerunner, resolutions, privacy-policy pages"
```

---

## Task 7: Events and Initiatives pages

**Files:**
- Create: `app/pages/events/utsava-2025.vue`
- Create: `app/pages/initiatives/karnataka-cultural.vue`
- Create: `app/pages/initiatives/jnana-deepa.vue`
- Create: `app/pages/initiatives/kannada-kali.vue`

**Interfaces:**
- Consumes: `.content-card`, `.page-title`, `.container-read`, `.btn` from `main.css`

- [ ] **Step 1: Create app/pages/events/utsava-2025.vue**

```vue
<!-- app/pages/events/utsava-2025.vue -->
<template>
  <div class="container-read">
    <p class="event-eyebrow kannada">ಕರ್ನಾಟಕ ರಾಜ್ಯೋತ್ಸವ</p>
    <h1 class="page-title">UTSAVA 2025</h1>

    <div class="content-card highlight-card">
      <h2>Karnataka Rajyotsava Celebration</h2>
      <p>
        Join us for a grand celebration of Karnataka Rajyotsava with cultural performances,
        community participation, and festive activities for all age groups.
      </p>
    </div>

    <div class="content-card">
      <h2>Event highlights</h2>
      <ul>
        <li>Classical and folk performances</li>
        <li>Kids programs and talent showcase</li>
        <li>Community dinner and networking</li>
        <li>Special segment honoring volunteers and contributors</li>
      </ul>
    </div>

    <div class="content-card">
      <h2>Stay updated</h2>
      <p>
        Final schedule, venue, and registration details will be shared through our community
        channels. Join our membership to receive updates directly.
      </p>
      <NuxtLink to="/membership" class="btn btn-primary">Join to get updates</NuxtLink>
    </div>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'UTSAVA 2025 — Munich Kannadigaru',
  description: 'Join Kannada Community Munich for UTSAVA 2025 — Karnataka Rajyotsava celebration.'
})
</script>

<style scoped>
.event-eyebrow {
  font-size: 1.1rem;
  color: var(--ink-muted);
  margin-bottom: var(--space-xs);
}
.highlight-card {
  border-left-color: var(--brand);
  background: #fdf0f0;
}
</style>
```

- [ ] **Step 2: Create app/pages/initiatives/karnataka-cultural.vue**

```vue
<!-- app/pages/initiatives/karnataka-cultural.vue -->
<template>
  <div class="container-read">
    <p class="initiative-label">Initiative</p>
    <h1 class="page-title">Karnataka Cultural</h1>

    <div class="content-card">
      <h2>About this initiative</h2>
      <p>
        Karnataka Cultural celebrates the rich artistic and traditional heritage of Karnataka
        through performances, festivals, and collaborative events in Munich.
      </p>
    </div>

    <div class="content-card">
      <h2>What we do</h2>
      <ul>
        <li>Host cultural festivals and community celebrations</li>
        <li>Showcase music, dance, drama, and folk traditions</li>
        <li>Encourage participation across all generations</li>
      </ul>
    </div>

    <NuxtLink to="/membership" class="btn btn-outline">Get involved</NuxtLink>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Karnataka Cultural — Munich Kannadigaru',
  description: 'Karnataka Cultural initiative celebrating arts, music, and heritage in Munich.'
})
</script>

<style scoped>
.initiative-label {
  font-size: 0.8rem;
  color: var(--gold);
  font-weight: 700;
  letter-spacing: 0.06em;
  margin-bottom: var(--space-xs);
}
</style>
```

- [ ] **Step 3: Create app/pages/initiatives/jnana-deepa.vue**

```vue
<!-- app/pages/initiatives/jnana-deepa.vue -->
<template>
  <div class="container-read">
    <p class="initiative-label">Initiative</p>
    <h1 class="page-title">Jnana Deepa</h1>

    <div class="content-card">
      <h2>About this initiative</h2>
      <p>
        Jnana Deepa is our knowledge and mentoring initiative focused on learning, personal
        growth, and community-led educational support.
      </p>
    </div>

    <div class="content-card">
      <h2>Focus areas</h2>
      <ul>
        <li>Workshops and interactive learning sessions</li>
        <li>Mentorship for students and young professionals</li>
        <li>Knowledge sharing by domain experts in the community</li>
      </ul>
    </div>

    <NuxtLink to="/membership" class="btn btn-outline">Get involved</NuxtLink>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Jnana Deepa — Munich Kannadigaru',
  description: 'Jnana Deepa — knowledge sharing and mentorship initiative by Munich Kannadigaru.'
})
</script>

<style scoped>
.initiative-label {
  font-size: 0.8rem;
  color: var(--gold);
  font-weight: 700;
  letter-spacing: 0.06em;
  margin-bottom: var(--space-xs);
}
</style>
```

- [ ] **Step 4: Create app/pages/initiatives/kannada-kali.vue**

```vue
<!-- app/pages/initiatives/kannada-kali.vue -->
<template>
  <div class="container-read">
    <p class="initiative-label">Initiative</p>
    <h1 class="page-title">Kannada Kali</h1>

    <div class="content-card">
      <h2>About this initiative</h2>
      <p>
        Kannada Kali is dedicated to Kannada language learning for children and adults through
        structured classes, engaging activities, and community-driven teaching.
      </p>
    </div>

    <div class="content-card">
      <h2>Program highlights</h2>
      <ul>
        <li>Reading, writing, and speaking fundamentals</li>
        <li>Age-appropriate curriculum and learning groups</li>
        <li>Storytelling, poems, and cultural immersion activities</li>
      </ul>
    </div>

    <NuxtLink to="/membership" class="btn btn-outline">Get involved</NuxtLink>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Kannada Kali — Munich Kannadigaru',
  description: 'Kannada Kali — language learning for children and adults in Munich.'
})
</script>

<style scoped>
.initiative-label {
  font-size: 0.8rem;
  color: var(--gold);
  font-weight: 700;
  letter-spacing: 0.06em;
  margin-bottom: var(--space-xs);
}
</style>
```

- [ ] **Step 5: Commit**

```bash
git add app/pages/events/ app/pages/initiatives/
git commit -m "feat: add UTSAVA 2025 event page and three initiative pages"
```

---

## Task 8: Membership pages (static, API-ready)

**Files:**
- Create: `app/pages/membership.vue`
- Create: `app/pages/membership/register.vue`

**Interfaces:**
- Consumes: `.content-card`, `.form-group`, `.form-label`, `.form-input`, `.form-select`, `.btn-primary` from `main.css`
- Produces: `/membership` overview + `/membership/register` static form (no submission logic — `action="#"` placeholder, API hook comment)

- [ ] **Step 1: Create app/pages/membership.vue**

```vue
<!-- app/pages/membership.vue -->
<template>
  <div class="container-read">
    <h1 class="page-title">Membership</h1>

    <div class="content-card">
      <h2>Why join</h2>
      <ul>
        <li>Be part of a warm Kannada network in Munich.</li>
        <li>Access community events, workshops, and family activities.</li>
        <li>Contribute, volunteer, and shape community programs.</li>
        <li>Support and celebrate Karnataka culture abroad.</li>
      </ul>
    </div>

    <div class="content-card">
      <h2>Membership types</h2>
      <ul>
        <li><strong>Individual membership</strong> — open to any individual</li>
        <li><strong>Family membership</strong> — covers the whole household</li>
        <li><strong>Student membership</strong> — reduced rate for students</li>
      </ul>
    </div>

    <div class="content-card">
      <h2>Ready to join?</h2>
      <p>Fill in the registration form to get started. We'll be in touch shortly.</p>
      <NuxtLink to="/membership/register" class="btn btn-primary">Register now</NuxtLink>
    </div>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Membership — Munich Kannadigaru',
  description: 'Join Kannada Community Munich — membership types and registration.'
})
</script>
```

- [ ] **Step 2: Create app/pages/membership/register.vue**

```vue
<!-- app/pages/membership/register.vue -->
<template>
  <div class="container-read">
    <h1 class="page-title">Membership Registration</h1>

    <div class="content-card" v-if="submitted">
      <h2>Thank you!</h2>
      <p>Your registration has been received. We'll be in touch via email shortly.</p>
      <NuxtLink to="/" class="btn btn-outline">Back to home</NuxtLink>
    </div>

    <!-- TODO: wire up to API endpoint — replace @submit.prevent with actual fetch/axios call -->
    <!-- TODO: add CAPTCHA / Turnstile before going live to prevent abuse -->
    <form v-else class="register-form" @submit.prevent="handleSubmit" novalidate>
      <div class="form-group">
        <label class="form-label" for="full-name">Full name</label>
        <input id="full-name" v-model="form.name" class="form-input" type="text" required autocomplete="name" />
      </div>

      <div class="form-group">
        <label class="form-label" for="email">Email address</label>
        <input id="email" v-model="form.email" class="form-input" type="email" required autocomplete="email" />
      </div>

      <div class="form-group">
        <label class="form-label" for="phone">Phone number (optional)</label>
        <input id="phone" v-model="form.phone" class="form-input" type="tel" autocomplete="tel" />
      </div>

      <div class="form-group">
        <label class="form-label" for="membership-type">Membership type</label>
        <select id="membership-type" v-model="form.membershipType" class="form-select" required>
          <option value="" disabled>Select a type</option>
          <option value="individual">Individual</option>
          <option value="family">Family</option>
          <option value="student">Student</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label" for="address">City / address (optional)</label>
        <input id="address" v-model="form.address" class="form-input" type="text" autocomplete="address-level2" />
      </div>

      <p v-if="error" class="form-error">{{ error }}</p>

      <button type="submit" class="btn btn-primary" :disabled="loading">
        {{ loading ? 'Submitting…' : 'Submit registration' }}
      </button>
    </form>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Register — Munich Kannadigaru',
  description: 'Register for membership in Kannada Community Munich.'
})

const form = reactive({
  name: '',
  email: '',
  phone: '',
  membershipType: '',
  address: ''
})

const submitted = ref(false)
const loading = ref(false)
const error = ref('')

async function handleSubmit() {
  error.value = ''
  if (!form.name || !form.email || !form.membershipType) {
    error.value = 'Please fill in your name, email, and membership type.'
    return
  }

  loading.value = true
  try {
    // TODO: replace with real API call
    // await $fetch('/api/membership', { method: 'POST', body: form })
    await new Promise(resolve => setTimeout(resolve, 600)) // simulated delay
    submitted.value = true
  } catch (e) {
    error.value = 'Something went wrong. Please email kannadacommunitymunich@gmail.com directly.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.register-form { max-width: 540px; }

.form-error {
  color: var(--brand);
  font-size: 0.9rem;
  margin-bottom: var(--space-md);
}
</style>
```

- [ ] **Step 3: Commit**

```bash
git add app/pages/membership.vue app/pages/membership/register.vue
git commit -m "feat: add membership overview and static API-ready registration form"
```

---

## Task 9: Contact page (static form, API-ready)

**Files:**
- Create: `app/pages/contact.vue`

**Interfaces:**
- Consumes: form classes from `main.css`, `.content-card`, `.btn-primary`
- Produces: `/contact` route — contact details + static message form with API hook comment

- [ ] **Step 1: Create app/pages/contact.vue**

```vue
<!-- app/pages/contact.vue -->
<template>
  <div class="container-read">
    <h1 class="page-title">Contact Us</h1>

    <div class="contact-layout">
      <div class="contact-info content-card">
        <h2>Get in touch</h2>
        <ul class="contact-list">
          <li>
            <strong>Email</strong>
            <a href="mailto:kannadacommunitymunich@gmail.com">kannadacommunitymunich@gmail.com</a>
          </li>
          <li>
            <strong>Location</strong>
            Munich, Germany
          </li>
        </ul>
      </div>

      <div class="content-card" v-if="submitted">
        <h2>Message sent!</h2>
        <p>Thank you for reaching out. We'll get back to you as soon as possible.</p>
      </div>

      <!-- TODO: wire up to API endpoint — replace @submit.prevent with actual fetch/axios call -->
      <!-- TODO: add CAPTCHA / Turnstile before going live to prevent abuse -->
      <form v-else class="contact-form content-card" @submit.prevent="handleSubmit" novalidate>
        <h2>Send a message</h2>

        <div class="form-group">
          <label class="form-label" for="contact-name">Your name</label>
          <input id="contact-name" v-model="form.name" class="form-input" type="text" required autocomplete="name" />
        </div>

        <div class="form-group">
          <label class="form-label" for="contact-email">Email address</label>
          <input id="contact-email" v-model="form.email" class="form-input" type="email" required autocomplete="email" />
        </div>

        <div class="form-group">
          <label class="form-label" for="contact-message">Message</label>
          <textarea id="contact-message" v-model="form.message" class="form-textarea" required></textarea>
        </div>

        <p v-if="error" class="form-error">{{ error }}</p>

        <button type="submit" class="btn btn-primary" :disabled="loading">
          {{ loading ? 'Sending…' : 'Send message' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Contact — Munich Kannadigaru',
  description: 'Contact Kannada Community Munich — reach us by email or send a message.'
})

const form = reactive({ name: '', email: '', message: '' })
const submitted = ref(false)
const loading = ref(false)
const error = ref('')

async function handleSubmit() {
  error.value = ''
  if (!form.name || !form.email || !form.message) {
    error.value = 'Please fill in all fields.'
    return
  }
  loading.value = true
  try {
    // TODO: replace with real API call
    // await $fetch('/api/contact', { method: 'POST', body: form })
    await new Promise(resolve => setTimeout(resolve, 600))
    submitted.value = true
  } catch (e) {
    error.value = 'Could not send message. Please email us directly at kannadacommunitymunich@gmail.com.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.contact-layout {
  display: grid;
  gap: var(--space-lg);
}

.contact-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: var(--space-md);
}
.contact-list li {
  display: flex;
  flex-direction: column;
  gap: 2px;
  margin: 0;
}
.contact-list strong {
  font-size: 0.8rem;
  color: var(--ink-muted);
  text-transform: none;
}

.form-error {
  color: var(--brand);
  font-size: 0.9rem;
  margin-bottom: var(--space-md);
}
</style>
```

- [ ] **Step 2: Commit**

```bash
git add app/pages/contact.vue
git commit -m "feat: add contact page with static API-ready message form"
```

---

## Task 10: Update nuxt.config.ts (requires autoConfirm check)

**Files:**
- Modify: `nuxt.config.ts`

**MANDATORY CHECK before this task:** Read `.claude-devtools/settings.json`. If `criticalFiles.autoConfirm` is `false` or file is missing, stop and ask the user before proceeding.

**Interfaces:**
- Consumes: `app/assets/css/main.css` (must exist — Task 1)
- Produces: registered CSS, Google Fonts (Lora + Tiro Kannada) in `<head>`, site title/description, prerender routes for all pages

- [ ] **Step 1: Read .claude-devtools/settings.json to check autoConfirm**

```bash
cat .claude-devtools/settings.json 2>/dev/null || echo "FILE_MISSING"
```

If `autoConfirm` is `false` or `FILE_MISSING` → **STOP and ask the user for explicit confirmation before continuing.**

- [ ] **Step 2: Update nuxt.config.ts** (only after confirmation)

```typescript
// nuxt.config.ts
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  css: ['~/assets/css/main.css'],

  app: {
    head: {
      title: 'Munich Kannadigaru — ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು',
      link: [
        { rel: 'preconnect', href: 'https://fonts.googleapis.com' },
        { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' },
        {
          rel: 'stylesheet',
          href: 'https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Tiro+Kannada&display=swap'
        }
      ],
      meta: [
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'A Kannada community in Munich celebrating language, culture, and belonging.' },
        { name: 'theme-color', content: '#8B1A1A' }
      ]
    }
  },

  modules: [
    '@nuxt/content',
    '@nuxt/eslint',
    '@nuxt/hints',
    '@nuxt/image',
    '@nuxt/scripts',
    '@nuxt/test-utils',
    '@nuxt/ui',
    '@ant-design-vue/nuxt',
    '@nuxtjs/seo',
    '@nuxtjs/turnstile',
    '@oro.ad/nuxt-claude-devtools'
  ],

  nitro: {
    prerender: {
      routes: [
        '/',
        '/about',
        '/contact',
        '/forerunner',
        '/membership',
        '/membership/register',
        '/privacy-policy',
        '/resolutions',
        '/events/utsava-2025',
        '/initiatives/karnataka-cultural',
        '/initiatives/jnana-deepa',
        '/initiatives/kannada-kali'
      ]
    }
  }
})
```

- [ ] **Step 3: Commit**

```bash
git add nuxt.config.ts
git commit -m "feat: configure CSS, Google Fonts (Lora + Tiro Kannada), SEO meta, prerender routes"
```

---

## Task 11: Dev server smoke test

**Files:** No changes — verification only

- [ ] **Step 1: Start the dev server**

```bash
cd /Users/I540578/Downloads/kannada-community.preview.emergentagent.com/nuxt_new_site
npm run dev
```

Expected: server starts on `http://localhost:3000` with no fatal errors.

- [ ] **Step 2: Visit each route in browser and verify**

Check each page loads with correct content, nav works, mobile hamburger opens/closes, forms show validation errors when empty, forms show success state on submit.

Routes to verify:
- `http://localhost:3000/` — hero, initiative grid, UTSAVA CTA, Kannada quote
- `http://localhost:3000/about`
- `http://localhost:3000/forerunner`
- `http://localhost:3000/events/utsava-2025`
- `http://localhost:3000/initiatives/karnataka-cultural`
- `http://localhost:3000/initiatives/jnana-deepa`
- `http://localhost:3000/initiatives/kannada-kali`
- `http://localhost:3000/membership`
- `http://localhost:3000/membership/register` — fill form, submit, see success state
- `http://localhost:3000/contact` — fill form, submit, see success state
- `http://localhost:3000/resolutions`
- `http://localhost:3000/privacy-policy`

- [ ] **Step 3: Resize browser to 375px width and verify mobile nav works**

Expected: hamburger button visible, nav links hidden; clicking hamburger opens full-screen drawer; clicking a link closes the drawer and navigates.

- [ ] **Step 4: Run static generation**

```bash
npm run generate
```

Expected: `.output/public/` contains `index.html` plus all prerendered routes — no build errors.

---

## Task 12: Save project memory

**Files:** No code changes — documentation

- [ ] **Step 1: Save memory about the project**

Write to `/Users/I540578/.claude/projects/-Users-I540578-Downloads-kannada-community-preview-emergentagent-com-nuxt-new-site/memory/project_site_structure.md` with a summary of all pages, the CSS class system, and where the API TODOs live.
