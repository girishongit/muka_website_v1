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
        :aria-expanded="String(menuOpen)"
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

@media (max-width: 768px) {
  .hamburger { display: flex; }

  .nav-links {
    display: none;
    position: fixed;
    inset: 64px 0 0 0;
    background: var(--surface);
    flex-direction: column;
    align-items: flex-start;
    padding: var(--space-xl);
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
