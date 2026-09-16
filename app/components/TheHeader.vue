<template>
  <header class="site-header" :class="{ scrolled: isScrolled }">
    <div class="header-container">
      <!-- Logo -->
      <NuxtLink to="/" class="logo" @click="closeMenu">
        <div class="logo-icon">
          <img
            :src="mklogo"
            alt="Munich Kannadigaru"
          />
        </div>
        <div class="logo-text">
          <span class="logo-en">Munich Kannadigaru</span>
          <span class="logo-kn kannada-text">ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು</span>
        </div>
      </NuxtLink>

      <!-- Desktop Nav -->
      <nav class="nav" :class="{ active: menuOpen }" aria-label="Main navigation">
        <div class="nav-item">
          <span class="nav-link">About <i class="chevron">›</i></span>
          <div class="dropdown">
            <NuxtLink to="/about" @click="closeMenu">About Us</NuxtLink>
            <NuxtLink to="/forerunner" @click="closeMenu">Forerunner</NuxtLink>
            <NuxtLink to="/privacy-policy" @click="closeMenu">Privacy Policy</NuxtLink>
            <NuxtLink to="/resolutions" @click="closeMenu">Resolutions</NuxtLink>
          </div>
        </div>
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
        <div class="nav-item">
          <NuxtLink to="/initiatives/kannada-kali" class="nav-link nav-direct" @click="closeMenu">Kannada Kali</NuxtLink>
        </div>
        <div class="nav-item">
          <NuxtLink to="/membership" class="nav-link nav-direct" @click="closeMenu">Membership</NuxtLink>
        </div>
      </nav>

      <div class="social-links">
        <a href="mailto:info@munichkannadigaru.org" class="social-btn" aria-label="Email us" title="Email us">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
        </a>
        <a href="https://www.facebook.com/groups/munichkannadigaru" class="social-btn" target="_blank" rel="noopener" aria-label="Facebook" title="Facebook">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        <a href="https://www.instagram.com/munich.kannadigaru/" class="social-btn" target="_blank" rel="noopener" aria-label="Instagram" title="Instagram">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" stroke="none"/></svg>
        </a>
        <a href="https://www.youtube.com/channel/UCjgYmtw7GmGs1NXoNa3oZIQ" class="social-btn" target="_blank" rel="noopener" aria-label="YouTube" title="YouTube">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.96-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="white"/></svg>
        </a>
      </div>

      <button
        class="mobile-menu-btn"
        :class="{ active: menuOpen }"
        :aria-expanded="String(menuOpen)"
        aria-label="Toggle navigation menu"
        @click="toggleMenu"
      >
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
      </button>
    </div>
  </header>
</template>

<script setup>
const menuOpen = ref(false)
const isScrolled = ref(false)

const { public: { apiBaseUrl } } = useRuntimeConfig()
const mklogo = `${apiBaseUrl}/assets/mk-logo.ico`
const eventsApiUrl = `${apiBaseUrl.replace(/\/$/, '')}/events.php`

const { data: dynamicEvents } = await useAsyncData('nav-events', () =>
  $fetch(eventsApiUrl).catch(() => [])
)

const STATIC_EVENTS = [
  { navLabel: 'UTSAVA',         path: '/events/utsava',        pinToTop: false },
  { navLabel: 'Ugadi',          path: '/events/ugadi',         pinToTop: false },
  { navLabel: 'Food Festival',  path: '/events/food-festival', pinToTop: false },
]

const navEvents = computed(() => {
  const dynamic = (dynamicEvents.value ?? [])
    .map(e => ({
      navLabel: e.navLabel,
      path: `/events/${e.slug}`,
      pinToTop: !!e.pinToTop,
    }))
  // Merge: dynamic entries override static ones by path; static ones fill any gaps
  const dynamicPaths = new Set(dynamic.map(e => e.path))
  const merged = [
    ...dynamic,
    ...STATIC_EVENTS.filter(e => !dynamicPaths.has(e.path)),
  ]
  const pinned   = merged.filter(e => e.pinToTop)
  const unpinned = merged.filter(e => !e.pinToTop)
  return [...pinned, ...unpinned]
})

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
  background: var(--cream);
  position: sticky;
  top: 6px; /* below the karnataka ribbon */
  z-index: 1000;
  box-shadow: none;
  transition: box-shadow 0.3s ease;
}
.site-header.scrolled {
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.header-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 15px 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
}

/* Logo */
.logo {
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
  flex-shrink: 0;
}
.logo-icon {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  overflow: hidden;
  flex-shrink: 0;
}
.logo-icon img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.logo-text {
  display: flex;
  flex-direction: column;
  gap: 2px;
}
.logo-en {
  font-family: 'Playfair Display', serif;
  font-size: 20px;
  color: var(--text-dark);
  font-weight: 600;
  line-height: 1.2;
}
.logo-kn {
  font-size: 13px;
  color: var(--primary-red);
  line-height: 1.2;
}

/* Nav */
.nav {
  display: flex;
  align-items: center;
  gap: 40px;
}
.nav-item {
  position: relative;
}
.nav-link {
  color: var(--text-dark);
  font-size: 15px;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 10px 0;
  cursor: default;
  transition: color 0.3s;
  white-space: nowrap;
  font-family: 'Manrope', sans-serif;
}
.nav-link:hover { color: var(--primary-red); }
.nav-direct {
  text-decoration: none;
  cursor: pointer;
}
.nav-direct.router-link-active { color: var(--primary-red); }
.chevron {
  font-style: normal;
  font-size: 12px;
  transform: rotate(90deg);
  display: inline-block;
  transition: transform 0.3s;
}
.nav-item:hover .chevron { transform: rotate(-90deg); }

/* Dropdown */
.dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  background: var(--white);
  min-width: 200px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.1);
  border-radius: 8px;
  padding: 10px 0;
  opacity: 0;
  visibility: hidden;
  transform: translateY(10px);
  transition: all 0.3s;
}
.nav-item:hover .dropdown {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}
.dropdown a {
  display: block;
  padding: 10px 20px;
  color: var(--text-dark);
  text-decoration: none;
  font-size: 14px;
  font-family: 'Manrope', sans-serif;
  transition: all 0.3s;
}
.dropdown a:hover,
.dropdown a.router-link-active {
  background: rgba(196, 30, 58, 0.05);
  color: var(--primary-red);
}

/* Social links */
.social-links {
  display: flex;
  align-items: center;
  gap: 6px;
  flex-shrink: 0;
}
.social-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  color: var(--text-light);
  transition: color 0.2s, background 0.2s;
  text-decoration: none;
}
.social-btn svg {
  width: 18px;
  height: 18px;
}
.social-btn:hover {
  color: var(--primary-red);
  background: rgba(196,30,58,0.07);
}

/* Hamburger */
.mobile-menu-btn {
  display: none;
  flex-direction: column;
  justify-content: center;
  gap: 5px;
  width: 40px;
  height: 40px;
  padding: 6px;
  background: none;
  border: none;
  cursor: pointer;
  margin-left: auto;
}
.hamburger-line {
  display: block;
  height: 2px;
  background: var(--text-dark);
  border-radius: 2px;
  transition: transform 0.2s, opacity 0.2s;
}
.mobile-menu-btn.active .hamburger-line:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.mobile-menu-btn.active .hamburger-line:nth-child(2) { opacity: 0; }
.mobile-menu-btn.active .hamburger-line:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

/* Responsive */
@media (max-width: 768px) {
  .header-container {
    padding: 15px 20px;
    gap: 15px;
  }
  .logo-icon { width: 40px; height: 40px; }
  .logo-en { font-size: 16px; }
  .logo-kn { font-size: 11px; }
  .mobile-menu-btn { display: flex; }

  .nav {
    position: fixed;
    top: 70px;
    left: 0;
    right: 0;
    background: var(--cream);
    flex-direction: column;
    align-items: stretch;
    gap: 0;
    padding: 20px 0;
    display: none;
    border-bottom: 1px solid var(--border-light);
    z-index: 999;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  }
  .nav.active { display: flex; }

  .nav-item { width: 100%; }
  .nav-link {
    padding: 15px 20px;
    width: 100%;
    font-size: 16px;
    cursor: pointer;
  }

  .dropdown {
    position: static;
    background: var(--cream-dark);
    min-width: auto;
    box-shadow: none;
    border-radius: 0;
    padding: 5px 0 10px 40px;
    opacity: 1;
    visibility: visible;
    transform: none;
    display: none;
  }
  .nav-item:hover .dropdown { display: block; }
  .dropdown a { padding: 10px 0; font-size: 15px; }
}
</style>
