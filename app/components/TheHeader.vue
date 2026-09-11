<template>
  <header class="site-header" :class="{ scrolled: isScrolled }">
    <div class="header-container">
      <!-- Logo -->
      <NuxtLink to="/" class="logo" @click="closeMenu">
        <div class="logo-icon">
          <img
            src="https://images.unsplash.com/photo-1582510003544-4d00b7f74220?w=200&q=80"
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
            <NuxtLink to="/events/utsava-2025" @click="closeMenu">UTSAVA 2025</NuxtLink>
          </div>
        </div>
        <div class="nav-item">
          <span class="nav-link">Initiatives <i class="chevron">›</i></span>
          <div class="dropdown">
            <NuxtLink to="/initiatives/karnataka-cultural" @click="closeMenu">Karnataka Cultural</NuxtLink>
            <NuxtLink to="/initiatives/jnana-deepa" @click="closeMenu">Jnana Deepa</NuxtLink>
            <NuxtLink to="/initiatives/kannada-kali" @click="closeMenu">Kannada Kali</NuxtLink>
          </div>
        </div>
        <div class="nav-item">
          <span class="nav-link">Membership <i class="chevron">›</i></span>
          <div class="dropdown">
            <NuxtLink to="/membership" @click="closeMenu">Info</NuxtLink>
            <NuxtLink to="/membership/register" @click="closeMenu">Registration</NuxtLink>
          </div>
        </div>
      </nav>

      <NuxtLink to="/contact" class="btn btn-primary header-cta" @click="closeMenu">Contact Us</NuxtLink>

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

/* CTA */
.header-cta {
  flex-shrink: 0;
  padding: 12px 28px;
  font-size: 14px;
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
  .header-cta { display: none; }

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
