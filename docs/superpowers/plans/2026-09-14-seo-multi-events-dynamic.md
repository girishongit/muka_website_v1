# SEO, Multi-Event Pages & Dynamic Event Data — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add comprehensive SEO to all pages, create dedicated event pages for Ugadi and Food Festival (PHP form), a dynamic `[slug].vue` catch-all for future events (Google Form iframe), a PHP/JSON data layer driving the nav Events dropdown, and a `robots.txt` sitemap pointer.

**Architecture:** Event content lives in `php/data/events.json` on the server (edited via SSH/FTP). A `php/events.php` endpoint serves it. The nav fetches event list via `useAsyncData` at SSR time, merging dynamic entries with the three hardcoded dedicated-page links. Each page gets rich `useSeoMeta` + JSON-LD structured data.

**Tech Stack:** Nuxt 4 (`app/` directory), Vue 3, `@nuxtjs/seo` (already installed), plain PHP, PDO/MySQL for form endpoints.

## Global Constraints

- Nuxt 4 `app/` directory — all pages under `app/pages/`, components under `app/components/`
- Design tokens: `--primary-red: #C41E3A`, `--gold: #FFB800`, `--cream: #FDF8F3`, `--cream-dark: #F5EFE8`, `--text-dark: #1A1A1A`, `--text-light: #6B6B6B`, `--border-light: #E5E5E5`
- Fonts: Playfair Display (headings), Manrope (UI), Noto Sans Kannada (Kannada text)
- `site.url = 'https://munichkannadigaru.org'`, `site.name = 'Munich Kannadigaru'`
- OG fallback image: `https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png`
- `nuxt.config.ts` edits require reading `.claude-devtools/settings.json` first per CLAUDE.md — **ask user before modifying**
- PHP endpoints: include `_cors.php`, `_turnstile.php` (if form), `Database.php` at top; return JSON; PDO with `ERRMODE_EXCEPTION`
- Modal close: X-button only (no backdrop click-to-close) — matches existing modal pattern
- `<NuxtTurnstile v-model="turnstileToken" class="form-turnstile" />` above submit on all PHP-backed forms
- No comments in code unless WHY is non-obvious

---

## Task 1 — `robots.txt` sitemap pointer

**Files:**
- Modify: `public/_robots.txt`

**What:** Append `Sitemap:` directive so crawlers discover the auto-generated sitemap.

- [ ] **Step 1: Edit `public/_robots.txt`**

Replace the entire file content with:

```
User-Agent: *
Disallow:

Sitemap: https://munichkannadigaru.org/sitemap.xml
```

- [ ] **Step 2: Verify the file looks correct**

Read `public/_robots.txt` and confirm all three lines are present.

- [ ] **Step 3: Commit**

```bash
git add public/_robots.txt
git commit -m "seo: add sitemap URL to robots.txt"
```

---

## Task 2 — Rich SEO on homepage (`app/pages/index.vue`)

**Files:**
- Modify: `app/pages/index.vue`

**What:** Expand the sparse `useSeoMeta` call to full OG + Twitter meta, add Organization JSON-LD via `useHead`.

- [ ] **Step 1: Replace the existing `useSeoMeta` block**

Find (around line 267):
```js
useSeoMeta({
  title: 'Munich Kannadigaru — ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು',
  description: 'A vibrant community of Kannadigas in Munich celebrating language, culture, and belonging.'
})
```

Replace with:
```js
useSeoMeta({
  title: 'Munich Kannadigaru — ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು',
  description: 'Munich Kannadigaru is a vibrant community of Kannada speakers in Munich, Germany — celebrating language, culture, and belonging through festivals, classes, and membership.',
  ogTitle: 'Munich Kannadigaru — ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು',
  ogDescription: 'A Kannada community in Munich celebrating heritage through Utsava, Kannada Kali classes, and cultural events. Join us.',
  ogImage: 'https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/',
  twitterCard: 'summary_large_image',
  twitterTitle: 'Munich Kannadigaru — Kannada Community in Munich',
  twitterDescription: 'Celebrating Karnataka culture in Munich through festivals, language classes, and community events.',
  twitterImage: 'https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png',
})

useHead({
  script: [{
    type: 'application/ld+json',
    children: JSON.stringify({
      '@context': 'https://schema.org',
      '@type': 'Organization',
      name: 'Munich Kannadigaru',
      alternateName: 'ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು',
      url: 'https://munichkannadigaru.org',
      logo: 'https://api.munichkannadigaru.org/public/assets/mk-logo.ico',
      foundingDate: '2019',
      description: 'A Kannada-speaking community in Munich, Germany, celebrating language, culture, and belonging through events, classes, and membership.',
      email: 'info@munichkannadigaru.org',
      location: { '@type': 'Place', address: { '@type': 'PostalAddress', addressLocality: 'Munich', addressCountry: 'DE' } },
      sameAs: [
        'https://www.facebook.com/groups/munichkannadigaru',
        'https://www.instagram.com/munich.kannadigaru/',
        'https://www.youtube.com/channel/UCjgYmtw7GmGs1NXoNa3oZIQ'
      ]
    })
  }]
})
```

- [ ] **Step 2: Commit**

```bash
git add app/pages/index.vue
git commit -m "seo: add rich meta + Organization JSON-LD to homepage"
```

---

## Task 3 — Rich SEO on all existing non-event pages

**Files:**
- Modify: `app/pages/about.vue`
- Modify: `app/pages/forerunner.vue`
- Modify: `app/pages/contact.vue`
- Modify: `app/pages/membership/index.vue`
- Modify: `app/pages/initiatives/kannada-kali.vue`
- Modify: `app/pages/resolutions.vue`
- Modify: `app/pages/privacy-policy.vue`
- Modify: `app/pages/events/utsava-2025.vue`

**What:** Expand every `useSeoMeta` call to include `ogTitle`, `ogDescription`, `ogImage`, `ogType`, `ogUrl`, `twitterCard`, `twitterTitle`, `twitterDescription`. Add Event JSON-LD on `utsava-2025.vue`.

**Exact replacements — apply each one independently:**

### `app/pages/about.vue`

- [ ] **Step 1: Replace `useSeoMeta`**

```js
useSeoMeta({
  title: 'About Us | Munich Kannadigaru',
  description: 'Learn about Munich Kannadigaru — a vibrant community dedicated to uniting Kannada speakers in Munich, Germany, since 2019.',
  ogTitle: 'About Munich Kannadigaru',
  ogDescription: 'Founded in 2019, Munich Kannadigaru unites Kannada-speaking families across Munich through culture, language, and celebration.',
  ogImage: 'https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/about',
  twitterCard: 'summary_large_image',
  twitterTitle: 'About Munich Kannadigaru',
  twitterDescription: 'Uniting Kannada speakers in Munich since 2019 — culture, language, and community.',
})
```

### `app/pages/forerunner.vue`

- [ ] **Step 2: Replace `useSeoMeta`**

```js
useSeoMeta({
  title: 'Forerunners | Munich Kannadigaru',
  description: 'Meet the visionary leaders who founded and continue to build the Munich Kannadigaru community.',
  ogTitle: 'Forerunners — Munich Kannadigaru Leadership',
  ogDescription: 'The founding members and current leadership of Munich Kannadigaru — the people who keep our community vibrant.',
  ogImage: 'https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/forerunner',
  twitterCard: 'summary_large_image',
  twitterTitle: 'Munich Kannadigaru — Forerunners',
  twitterDescription: 'Meet the founders and leaders of the Munich Kannada community.',
})
```

### `app/pages/contact.vue`

- [ ] **Step 3: Replace `useSeoMeta`**

```js
useSeoMeta({
  title: 'Contact Us | Munich Kannadigaru',
  description: "Get in touch with Munich Kannadigaru. Reach us by email, social media, or the contact form for any enquiries.",
  ogTitle: 'Contact Munich Kannadigaru',
  ogDescription: "Have a question or want to get involved? Contact the Munich Kannadigaru team — we'd love to hear from you.",
  ogImage: 'https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/contact',
  twitterCard: 'summary_large_image',
  twitterTitle: 'Contact Munich Kannadigaru',
  twitterDescription: "Get in touch with the Munich Kannada community.",
})
```

### `app/pages/membership/index.vue`

- [ ] **Step 4: Replace `useSeoMeta`**

```js
useSeoMeta({
  title: 'Membership | Munich Kannadigaru',
  description: 'Join Munich Kannadigaru. Student, Single, and Family membership plans from €8/year. Be part of Munich\'s Kannada community.',
  ogTitle: 'Join Munich Kannadigaru — Membership',
  ogDescription: 'Become a member of Munich\'s Kannada community. Student €8/yr, Single Adult €12/yr, Family €20/yr.',
  ogImage: 'https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/membership',
  twitterCard: 'summary_large_image',
  twitterTitle: 'Munich Kannadigaru Membership',
  twitterDescription: 'Join the Munich Kannada community. Plans from €8/year.',
})
```

### `app/pages/initiatives/kannada-kali.vue`

- [ ] **Step 5: Replace `useSeoMeta`**

```js
useSeoMeta({
  title: 'Kannada Kali | Munich Kannadigaru',
  description: 'Kannada Kali — free weekly Kannada language classes for children in Munich, run by Munich Kannadigaru volunteers.',
  ogTitle: 'Kannada Kali — Kannada Classes in Munich',
  ogDescription: 'Free weekly Kannada language classes for kids in Munich. Enrol your child in Kannada Kali today.',
  ogImage: 'https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/initiatives/kannada-kali',
  twitterCard: 'summary_large_image',
  twitterTitle: 'Kannada Kali — Free Kannada Classes in Munich',
  twitterDescription: 'Free Kannada language classes for children in Munich, run by Munich Kannadigaru.',
})
```

### `app/pages/resolutions.vue`

- [ ] **Step 6: Replace `useSeoMeta`** (there are two calls — replace only the first one that sets the page title; leave the second if it exists, or check and consolidate into one)

```js
useSeoMeta({
  title: 'Resolutions | Munich Kannadigaru',
  description: 'Official resolutions and decisions of Munich Kannadigaru — a record of our community commitments and governance.',
  ogTitle: 'Munich Kannadigaru — Resolutions',
  ogDescription: 'Community governance resolutions and official decisions of Munich Kannadigaru.',
  ogImage: 'https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/resolutions',
  twitterCard: 'summary_large_image',
  twitterTitle: 'Munich Kannadigaru — Resolutions',
  twitterDescription: 'Official resolutions of the Munich Kannadigaru community.',
})
```

### `app/pages/privacy-policy.vue`

- [ ] **Step 7: Replace `useSeoMeta`**

```js
useSeoMeta({
  title: 'Privacy Policy | Munich Kannadigaru',
  description: 'Privacy policy for Munich Kannadigaru — how we collect, use, and protect your personal data in line with GDPR.',
  ogTitle: 'Privacy Policy | Munich Kannadigaru',
  ogDescription: 'Our GDPR-compliant privacy policy explaining how we handle your personal data.',
  ogImage: 'https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/privacy-policy',
  twitterCard: 'summary',
  twitterTitle: 'Privacy Policy | Munich Kannadigaru',
  twitterDescription: 'GDPR privacy policy for Munich Kannadigaru.',
})
```

### `app/pages/events/utsava-2025.vue`

- [ ] **Step 8: Replace `useSeoMeta` and add Event JSON-LD**

```js
useSeoMeta({
  title: 'UTSAVA 2025 | Munich Kannadigaru',
  description: "UTSAVA 2025 — Munich Kannadigaru's flagship annual cultural festival celebrating Karnataka's heritage through music, dance, and food in Munich.",
  ogTitle: 'UTSAVA 2025 — Kannada Cultural Festival Munich',
  ogDescription: "Join Munich Kannadigaru for UTSAVA 2025 — a celebration of Karnataka culture with music, dance, food, and community.",
  ogImage: 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=1200&q=80',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/events/utsava-2025',
  twitterCard: 'summary_large_image',
  twitterTitle: 'UTSAVA 2025 — Munich Kannadigaru',
  twitterDescription: "Karnataka cultural festival in Munich — music, dance, food, and community.",
})

useHead({
  script: [{
    type: 'application/ld+json',
    children: JSON.stringify({
      '@context': 'https://schema.org',
      '@type': 'Event',
      name: 'UTSAVA 2025',
      description: "Munich Kannadigaru's flagship annual cultural festival celebrating Karnataka's rich heritage.",
      startDate: '2025-05-15T10:00:00+02:00',
      endDate: '2025-05-15T20:00:00+02:00',
      location: { '@type': 'Place', name: 'Munich Community Center', address: { '@type': 'PostalAddress', addressLocality: 'Munich', addressCountry: 'DE' } },
      organizer: { '@type': 'Organization', name: 'Munich Kannadigaru', url: 'https://munichkannadigaru.org' },
      image: 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=1200&q=80',
      eventStatus: 'https://schema.org/EventScheduled',
      eventAttendanceMode: 'https://schema.org/OfflineEventAttendanceMode',
    })
  }]
})
```

- [ ] **Step 9: Commit all SEO changes**

```bash
git add app/pages/about.vue app/pages/forerunner.vue app/pages/contact.vue \
        app/pages/membership/index.vue app/pages/initiatives/kannada-kali.vue \
        app/pages/resolutions.vue app/pages/privacy-policy.vue \
        app/pages/events/utsava-2025.vue
git commit -m "seo: add rich og/twitter meta and Event JSON-LD to all pages"
```

---

## Task 4 — PHP event data layer (`php/events.php` + `php/data/events.json`)

**Files:**
- Create: `php/data/events.json`
- Create: `php/events.php`

**What:** A read-only PHP endpoint that serves event data from a JSON file. Supports `?slug=x` for a single event and no params for the full list. The JSON `pinToTop: true` field makes an entry appear above the three hardcoded nav links.

**Interfaces:**
- Produces: `GET /php/events.php` → `Event[]` (JSON array, sorted: pinned first, then by `navOrder` asc)
- Produces: `GET /php/events.php?slug=ugadi-2026` → single `Event` object or 404
- `Event` shape: `{ slug, title, kannadaTitle, navLabel, date, time, venue, heroImage, galleryImages[], contentHtml, registrationStatus ("open"|"closed"|"coming_soon"), googleFormUrl, tag, featured, navOrder, pinToTop }`

- [ ] **Step 1: Create `php/data/events.json`**

```json
[
  {
    "slug": "ugadi-2026",
    "title": "Ugadi 2026",
    "kannadaTitle": "ಯುಗಾದಿ ೨೦೨೬",
    "navLabel": "Ugadi 2026",
    "date": "2026-03-30",
    "time": "11:00 AM – 7:00 PM",
    "venue": "Munich Community Hall",
    "heroImage": "https://images.unsplash.com/photo-1605640840605-14ac1855827b?w=1200&q=80",
    "galleryImages": [
      "https://images.unsplash.com/photo-1605640840605-14ac1855827b?w=600&q=80",
      "https://images.unsplash.com/photo-1541753866388-0b3c701627d3?w=600&q=80"
    ],
    "contentHtml": "<p>Ugadi marks the beginning of the new year in the Karnataka lunar calendar — a time for fresh starts, reflection, and togetherness. Join Munich Kannadigaru for an evening of traditional rituals, classical performances, and the iconic Bevu-Bella (neem and jaggery) ceremony.</p><p>The event features cultural performances by community members, traditional Karnataka food, and activities for children.</p>",
    "registrationStatus": "coming_soon",
    "googleFormUrl": "",
    "tag": "New Year Festival",
    "featured": false,
    "navOrder": 1,
    "pinToTop": false
  },
  {
    "slug": "food-festival-2026",
    "title": "Karnataka Food Festival 2026",
    "kannadaTitle": "ಕರ್ನಾಟಕ ಆಹಾರ ಉತ್ಸವ",
    "navLabel": "Food Festival 2026",
    "date": "2026-06-14",
    "time": "12:00 PM – 8:00 PM",
    "venue": "Munich City Garden",
    "heroImage": "https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=1200&q=80",
    "galleryImages": [
      "https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=600&q=80",
      "https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600&q=80"
    ],
    "contentHtml": "<p>A celebration of Karnataka's rich culinary tradition — from Bisi Bele Bath and Masala Dosa to Mysore Pak and Filter Coffee. Experience the diversity of Karnataka cuisine prepared by families from across the community.</p><p>Cooking demonstrations, food stalls, and cultural performances make this a full-day family event.</p>",
    "registrationStatus": "coming_soon",
    "googleFormUrl": "",
    "tag": "Food & Culture",
    "featured": false,
    "navOrder": 2,
    "pinToTop": false
  },
  {
    "slug": "ganesha-chaturthi-2026",
    "title": "Ganesha Chaturthi 2026",
    "kannadaTitle": "ಗಣೇಶ ಚತುರ್ಥಿ ೨೦೨೬",
    "navLabel": "Ganesha Chaturthi",
    "date": "2026-08-23",
    "time": "10:00 AM – 6:00 PM",
    "venue": "Munich Community Hall",
    "heroImage": "https://images.unsplash.com/photo-1567450297892-c9b3c1cb4c85?w=1200&q=80",
    "galleryImages": [
      "https://images.unsplash.com/photo-1567450297892-c9b3c1cb4c85?w=600&q=80"
    ],
    "contentHtml": "<p>Join Munich Kannadigaru in celebrating the beloved festival of Ganesha Chaturthi — welcoming Lord Ganesha with traditional puja, bhajans, and prasad.</p><p>The event includes community prayers, cultural programmes, and a festive meal. All are welcome.</p>",
    "registrationStatus": "coming_soon",
    "googleFormUrl": "https://docs.google.com/forms/d/e/YOUR_FORM_ID/viewform?embedded=true",
    "tag": "Religious Festival",
    "featured": false,
    "navOrder": 3,
    "pinToTop": false
  }
]
```

- [ ] **Step 2: Create `php/events.php`**

```php
<?php
/**
 * GET /php/events.php          → full event list (sorted: pinToTop first, then navOrder asc)
 * GET /php/events.php?slug=x   → single event object or 404
 *
 * Read-only — no Turnstile, no DB. Content lives in php/data/events.json.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: https://munichkannadigaru.org');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$file = __DIR__ . '/data/events.json';

if (!file_exists($file)) {
    http_response_code(500);
    echo json_encode(['error' => 'Event data unavailable']);
    exit;
}

$events = json_decode(file_get_contents($file), true) ?? [];

$slug = trim($_GET['slug'] ?? '');

if ($slug !== '') {
    foreach ($events as $event) {
        if ($event['slug'] === $slug) {
            echo json_encode($event);
            exit;
        }
    }
    http_response_code(404);
    echo json_encode(['error' => 'Event not found']);
    exit;
}

// Sort: pinToTop=true first, then navOrder ascending
usort($events, function ($a, $b) {
    $aPin = !empty($a['pinToTop']) ? 0 : 1;
    $bPin = !empty($b['pinToTop']) ? 0 : 1;
    if ($aPin !== $bPin) return $aPin - $bPin;
    return ($a['navOrder'] ?? 99) - ($b['navOrder'] ?? 99);
});

echo json_encode($events);
```

- [ ] **Step 3: Commit**

```bash
git add php/events.php php/data/events.json
git commit -m "feat: add PHP events data layer (events.php + data/events.json)"
```

---

## Task 5 — Update nav to include dynamic events (`app/components/TheHeader.vue`)

**Files:**
- Modify: `app/components/TheHeader.vue`

**What:** Fetch dynamic events from `php/events.php` at SSR time. Merge them with the three hardcoded dedicated-page links. Pinned events appear above the hardcoded three; unpinned appear below.

**Interfaces:**
- Consumes: `GET /php/events.php` → `Event[]` (from Task 4)
- The three hardcoded entries are: UTSAVA 2025 (`/events/utsava-2025`), Ugadi 2026 (`/events/ugadi-2026`), Food Festival 2026 (`/events/food-festival-2026`)
- Dynamic entries link to `/events/<slug>` for the catch-all page

- [ ] **Step 1: Add `useRuntimeConfig` and `useAsyncData` fetch in `<script setup>`**

In the `<script setup>` block, after the existing `useRuntimeConfig` line, add:

```js
const eventsApiUrl = `${apiBaseUrl.replace(/\/$/, '')}/events.php`

const { data: dynamicEvents } = await useAsyncData('nav-events', () =>
  $fetch(eventsApiUrl).catch(() => [])
)
```

- [ ] **Step 2: Add computed nav event list**

```js
const HARDCODED_EVENTS = [
  { navLabel: 'UTSAVA 2025', path: '/events/utsava-2025', pinToTop: false },
  { navLabel: 'Ugadi 2026',  path: '/events/ugadi-2026',  pinToTop: false },
  { navLabel: 'Food Festival 2026', path: '/events/food-festival-2026', pinToTop: false },
]

const navEvents = computed(() => {
  const dynamic = (dynamicEvents.value ?? []).map(e => ({
    navLabel: e.navLabel,
    path: `/events/${e.slug}`,
    pinToTop: !!e.pinToTop,
  }))
  const pinned    = dynamic.filter(e => e.pinToTop)
  const unpinned  = dynamic.filter(e => !e.pinToTop)
  return [...pinned, ...HARDCODED_EVENTS, ...unpinned]
})
```

- [ ] **Step 3: Replace the hardcoded Events dropdown in the template**

Find:
```html
<div class="nav-item">
  <span class="nav-link">Events <i class="chevron">›</i></span>
  <div class="dropdown">
    <NuxtLink to="/events/utsava-2025" @click="closeMenu">UTSAVA 2025</NuxtLink>
  </div>
</div>
```

Replace with:
```html
<div class="nav-item">
  <span class="nav-link">Events <i class="chevron">›</i></span>
  <div class="dropdown">
    <NuxtLink
      v-for="event in navEvents"
      :key="event.path"
      :to="event.path"
      @click="closeMenu"
    >{{ event.navLabel }}</NuxtLink>
  </div>
</div>
```

- [ ] **Step 4: Commit**

```bash
git add app/components/TheHeader.vue
git commit -m "feat: nav Events dropdown merges hardcoded + dynamic events from PHP"
```

---

## Task 6 — Ugadi 2026 dedicated page (`app/pages/events/ugadi-2026.vue`)

**Files:**
- Create: `app/pages/events/ugadi-2026.vue`
- Create: `php/ugadi-register.php`

**What:** A full event page modelled on `utsava-2025.vue`. Custom PHP registration form (firstName, lastName, email, phone, adults, children, dietary) with Turnstile. No countdown (date is still coming soon). `registrationStatus` controls the button state.

**Interfaces:**
- Produces: `/events/ugadi-2026` route
- Consumes: `POST /php/ugadi-register.php`

**DB schema (in `php/ugadi-register.php` comment):**
```sql
CREATE TABLE ugadi_registrations (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  first_name  VARCHAR(80)  NOT NULL,
  last_name   VARCHAR(80)  NOT NULL,
  email       VARCHAR(200) NOT NULL,
  phone       VARCHAR(30)  NOT NULL DEFAULT '',
  adults      TINYINT UNSIGNED NOT NULL DEFAULT 1,
  children    TINYINT UNSIGNED NOT NULL DEFAULT 0,
  dietary     VARCHAR(200) NOT NULL DEFAULT '',
  message     TEXT,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

- [ ] **Step 1: Create `php/ugadi-register.php`**

Follow the exact same pattern as `php/utsava-register.php` — same fields (firstName, lastName, email, phone, adults, children, dietary, message), same Turnstile + validation + PDO insert pattern. Change table name to `ugadi_registrations`. Include DB schema comment at top.

- [ ] **Step 2: Create `app/pages/events/ugadi-2026.vue`**

Model the layout after `utsava-2025.vue`. Key differences:
- No countdown (Ugadi 2026 is a future event — omit the countdown block)
- `registrationStatus = 'coming_soon'` — the Register button shows "Registration Opens Soon" and is disabled
- When status is `'open'`, button opens a dialog modal with the PHP registration form
- SEO meta:

```js
useSeoMeta({
  title: 'Ugadi 2026 | Munich Kannadigaru',
  description: "Celebrate Ugadi — the Kannada New Year — with Munich Kannadigaru. Traditional rituals, performances, and Karnataka food in Munich.",
  ogTitle: 'Ugadi 2026 — Kannada New Year Festival Munich',
  ogDescription: "Join Munich Kannadigaru for Ugadi 2026 — traditional rituals, cultural performances, and Karnataka cuisine in Munich.",
  ogImage: 'https://images.unsplash.com/photo-1605640840605-14ac1855827b?w=1200&q=80',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/events/ugadi-2026',
  twitterCard: 'summary_large_image',
  twitterTitle: 'Ugadi 2026 — Munich Kannadigaru',
  twitterDescription: 'Kannada New Year celebration in Munich — rituals, performances, and food.',
})

useHead({
  script: [{
    type: 'application/ld+json',
    children: JSON.stringify({
      '@context': 'https://schema.org',
      '@type': 'Event',
      name: 'Ugadi 2026',
      description: "Ugadi celebration hosted by Munich Kannadigaru — Kannada New Year festival with traditional rituals, performances, and Karnataka cuisine.",
      startDate: '2026-03-30T11:00:00+02:00',
      endDate: '2026-03-30T19:00:00+02:00',
      location: { '@type': 'Place', name: 'Munich Community Hall', address: { '@type': 'PostalAddress', addressLocality: 'Munich', addressCountry: 'DE' } },
      organizer: { '@type': 'Organization', name: 'Munich Kannadigaru', url: 'https://munichkannadigaru.org' },
      image: 'https://images.unsplash.com/photo-1605640840605-14ac1855827b?w=1200&q=80',
      eventStatus: 'https://schema.org/EventScheduled',
      eventAttendanceMode: 'https://schema.org/OfflineEventAttendanceMode',
    })
  }]
})
```

- Registration button logic:
```html
<!-- in template, after about-event content -->
<button
  v-if="registrationStatus === 'open'"
  class="btn btn-primary"
  @click="showDialog = true"
>Register Now <span aria-hidden="true">→</span></button>
<button
  v-else-if="registrationStatus === 'coming_soon'"
  class="btn btn-secondary"
  disabled
>Registration Opens Soon</button>
<button
  v-else
  class="btn btn-secondary"
  disabled
>Registration Closed</button>
```

- The modal and form are identical to `utsava-2025.vue` — copy the dialog template block and the form `reactive({})` / `submitForm()` function, changing the API URL to `ugadi-register.php`.

- [ ] **Step 3: Commit**

```bash
git add app/pages/events/ugadi-2026.vue php/ugadi-register.php
git commit -m "feat: add Ugadi 2026 event page with PHP registration form"
```

---

## Task 7 — Food Festival 2026 dedicated page (`app/pages/events/food-festival-2026.vue`)

**Files:**
- Create: `app/pages/events/food-festival-2026.vue`
- Create: `php/food-festival-register.php`

**What:** Same pattern as Task 6 but for the Food Festival.

**Interfaces:**
- Produces: `/events/food-festival-2026` route
- Consumes: `POST /php/food-festival-register.php`

**DB schema (in comment):**
```sql
CREATE TABLE food_festival_registrations (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  first_name  VARCHAR(80)  NOT NULL,
  last_name   VARCHAR(80)  NOT NULL,
  email       VARCHAR(200) NOT NULL,
  phone       VARCHAR(30)  NOT NULL DEFAULT '',
  adults      TINYINT UNSIGNED NOT NULL DEFAULT 1,
  children    TINYINT UNSIGNED NOT NULL DEFAULT 0,
  dietary     VARCHAR(200) NOT NULL DEFAULT '',
  message     TEXT,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

- [ ] **Step 1: Create `php/food-festival-register.php`**

Same pattern as `php/utsava-register.php`. Table: `food_festival_registrations`.

- [ ] **Step 2: Create `app/pages/events/food-festival-2026.vue`**

Same layout as `ugadi-2026.vue`. Key differences:
- Event title: "Karnataka Food Festival 2026", Kannada: "ಕರ್ನಾಟಕ ಆಹಾರ ಉತ್ಸವ"
- Date: June 14 2026, Time: 12:00 PM – 8:00 PM, Venue: Munich City Garden
- Tag: "Food & Culture"
- Hero image: `https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=1200&q=80`
- `registrationStatus = 'coming_soon'`
- SEO meta:

```js
useSeoMeta({
  title: 'Karnataka Food Festival 2026 | Munich Kannadigaru',
  description: "Savour the flavours of Karnataka at Munich Kannadigaru's Food Festival 2026 — Bisi Bele Bath, Masala Dosa, Mysore Pak and more.",
  ogTitle: 'Karnataka Food Festival 2026 — Munich',
  ogDescription: "A day-long celebration of Karnataka cuisine in Munich — cooking demos, food stalls, and cultural performances.",
  ogImage: 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=1200&q=80',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/events/food-festival-2026',
  twitterCard: 'summary_large_image',
  twitterTitle: 'Karnataka Food Festival 2026 — Munich Kannadigaru',
  twitterDescription: 'Karnataka food, culture, and community in Munich. 14 June 2026.',
})

useHead({
  script: [{
    type: 'application/ld+json',
    children: JSON.stringify({
      '@context': 'https://schema.org',
      '@type': 'Event',
      name: 'Karnataka Food Festival 2026',
      description: "A celebration of Karnataka's culinary traditions — food stalls, cooking demonstrations, and cultural performances.",
      startDate: '2026-06-14T12:00:00+02:00',
      endDate: '2026-06-14T20:00:00+02:00',
      location: { '@type': 'Place', name: 'Munich City Garden', address: { '@type': 'PostalAddress', addressLocality: 'Munich', addressCountry: 'DE' } },
      organizer: { '@type': 'Organization', name: 'Munich Kannadigaru', url: 'https://munichkannadigaru.org' },
      image: 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=1200&q=80',
      eventStatus: 'https://schema.org/EventScheduled',
      eventAttendanceMode: 'https://schema.org/OfflineEventAttendanceMode',
    })
  }]
})
```

- [ ] **Step 3: Commit**

```bash
git add app/pages/events/food-festival-2026.vue php/food-festival-register.php
git commit -m "feat: add Food Festival 2026 event page with PHP registration form"
```

---

## Task 8 — Dynamic catch-all event page (`app/pages/events/[slug].vue`)

**Files:**
- Create: `app/pages/events/[slug].vue`

**What:** Renders any event from `events.json` that doesn't have a dedicated Vue page. Fetches event data server-side, renders `contentHtml` safely with `v-html`, shows Google Form in an iframe modal for registration.

**Interfaces:**
- Consumes: `GET /php/events.php?slug=<slug>` (from Task 4)
- `registrationStatus` drives button label/state
- `googleFormUrl` is loaded into the iframe `src`

- [ ] **Step 1: Create `app/pages/events/[slug].vue`**

```vue
<template>
  <div v-if="event">
    <section class="event-hero">
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
          <div v-if="event.heroImage" class="event-images-grid animate-observe delay-1">
            <img :src="event.heroImage" :alt="event.title" class="event-hero-img" />
            <img
              v-for="(img, i) in event.galleryImages?.slice(0,2)"
              :key="i"
              :src="img"
              :alt="event.title + ' gallery'"
              class="event-gallery-img"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Google Form modal -->
    <Teleport to="body">
      <div v-if="showDialog" class="dialog-overlay" role="dialog" :aria-label="event.title + ' Registration'">
        <div class="dialog-panel dialog-panel--wide">
          <div class="dialog-header">
            <h2>Register — {{ event.title }}</h2>
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

// Block scroll when dialog open
watch(showDialog, open => {
  if (process.client) document.body.style.overflow = open ? 'hidden' : ''
})

if (event.value) {
  useSeoMeta({
    title: `${event.value.title} | Munich Kannadigaru`,
    description: event.value.contentHtml.replace(/<[^>]+>/g, '').slice(0, 160),
    ogTitle: event.value.title,
    ogDescription: event.value.contentHtml.replace(/<[^>]+>/g, '').slice(0, 160),
    ogImage: event.value.heroImage || 'https://api.munichkannadigaru.org/assets/misc/MembershipProcess.png',
    ogType: 'website',
    ogUrl: `https://munichkannadigaru.org/events/${route.params.slug}`,
    twitterCard: 'summary_large_image',
    twitterTitle: event.value.title,
    twitterDescription: event.value.contentHtml.replace(/<[^>]+>/g, '').slice(0, 160),
  })

  useHead({
    script: [{
      type: 'application/ld+json',
      children: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'Event',
        name: event.value.title,
        description: event.value.contentHtml.replace(/<[^>]+>/g, '').slice(0, 300),
        startDate: event.value.date,
        location: { '@type': 'Place', name: event.value.venue, address: { '@type': 'PostalAddress', addressLocality: 'Munich', addressCountry: 'DE' } },
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
/* Reuse event-hero, about-event, section, section-container styles from utsava-2025.vue */
/* These are component-scoped additions only */

.event-hero {
  background: linear-gradient(135deg, var(--primary-red) 0%, #8B0000 100%);
  color: white;
  padding: 80px 40px 60px;
  text-align: center;
}
.event-hero-container { max-width: 800px; margin: 0 auto; }
.event-hero h1 { font-family: 'Playfair Display', serif; font-size: clamp(2rem, 5vw, 3.5rem); margin: 16px 0 8px; }
.event-kannada { font-family: 'Noto Sans Kannada', sans-serif; font-size: 1.2rem; opacity: 0.85; margin-bottom: 24px; }
.event-info { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; margin-top: 20px; }
.event-info-item { display: flex; align-items: center; gap: 8px; font-size: 0.95rem; opacity: 0.9; }

.about-event-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: start; }
.about-event-content { display: flex; flex-direction: column; gap: 16px; }
.event-content-html p { margin-bottom: 12px; line-height: 1.7; color: var(--text-dark); }
.about-event-buttons { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 8px; }
.status-btn { opacity: 0.6; cursor: not-allowed; }

.event-images-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.event-hero-img { grid-column: 1 / -1; width: 100%; height: 240px; object-fit: cover; border-radius: 12px; }
.event-gallery-img { width: 100%; height: 160px; object-fit: cover; border-radius: 8px; }

/* Dialog */
.dialog-overlay {
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.55);
  z-index: 9000;
  display: flex; align-items: center; justify-content: center;
  padding: 16px;
}
.dialog-panel {
  background: var(--white);
  border-radius: 16px;
  width: 100%;
  max-width: 700px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}
.dialog-panel--wide { max-width: 760px; }
.dialog-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 20px 24px;
  border-bottom: 1px solid var(--border-light);
  flex-shrink: 0;
}
.dialog-header h2 { font-family: 'Playfair Display', serif; font-size: 1.3rem; margin: 0; }
.dialog-close {
  background: none; border: none; font-size: 1.2rem;
  cursor: pointer; color: var(--text-light);
  padding: 4px 8px; border-radius: 6px;
  transition: background 0.2s, color 0.2s;
}
.dialog-close:hover { background: rgba(196,30,58,0.07); color: var(--primary-red); }
.dialog-iframe-wrap { flex: 1; overflow-y: auto; }
.dialog-iframe-wrap iframe { display: block; min-height: 560px; }

.loading-state, .error-state { text-align: center; padding: 80px 40px; }
.error-state h1 { font-family: 'Playfair Display', serif; margin-bottom: 12px; }

@media (max-width: 768px) {
  .event-hero { padding: 60px 20px 40px; }
  .about-event-grid { grid-template-columns: 1fr; gap: 32px; }
  .event-images-grid { grid-template-columns: 1fr; }
  .event-hero-img { height: 200px; }
  .event-gallery-img { height: 140px; }
  .dialog-panel { max-height: 95vh; border-radius: 12px; }
  .dialog-iframe-wrap iframe { min-height: 480px; }
}
</style>
```

- [ ] **Step 2: Commit**

```bash
git add app/pages/events/[slug].vue
git commit -m "feat: add dynamic [slug].vue catch-all event page with Google Form modal"
```

---

## Task 9 — Add new routes to prerender list in `nuxt.config.ts`

**Files:**
- Modify: `nuxt.config.ts`

**IMPORTANT:** Read `.claude-devtools/settings.json` first per CLAUDE.md. If `autoConfirm` is false or file is missing, STOP and ask the user before proceeding.

**What:** Add `/events/ugadi-2026` and `/events/food-festival-2026` to the `nitro.prerender.routes` array.

- [ ] **Step 1: Check `.claude-devtools/settings.json`**

Read the file. If `criticalFiles.autoConfirm` is not explicitly `true`, stop and ask the user: "I need to add two routes to `nuxt.config.ts`. May I proceed?"

- [ ] **Step 2: Update `nuxt.config.ts`** (only after confirmation)

In the `nitro.prerender.routes` array, add:
```
'/events/ugadi-2026',
'/events/food-festival-2026',
```

- [ ] **Step 3: Commit**

```bash
git add nuxt.config.ts
git commit -m "build: add Ugadi and Food Festival routes to prerender list"
```

---

## Verification checklist

- [ ] `curl https://munichkannadigaru.org/robots.txt` includes the `Sitemap:` line
- [ ] `curl https://munichkannadigaru.org/sitemap.xml` lists all pages including new event routes
- [ ] Homepage `<head>` contains `og:title`, `og:image`, `<script type="application/ld+json">` with `@type: Organization`
- [ ] `/events/utsava-2025` `<head>` contains `<script type="application/ld+json">` with `@type: Event`
- [ ] `/events/ugadi-2026` loads, shows "Registration Opens Soon" (disabled button)
- [ ] `/events/food-festival-2026` loads, shows "Registration Opens Soon" (disabled button)
- [ ] `/events/ganesha-chaturthi-2026` loads via `[slug].vue` dynamic route
- [ ] Nav Events dropdown shows: UTSAVA 2025, Ugadi 2026, Food Festival 2026, Ganesha Chaturthi (in that order)
- [ ] Setting `pinToTop: true` on any JSON event moves it above the three hardcoded links
- [ ] Ganesha Chaturthi "Register" button (once `registrationStatus` is `open`) opens iframe modal with Google Form
- [ ] Modal on mobile fills screen, iframe is scrollable
