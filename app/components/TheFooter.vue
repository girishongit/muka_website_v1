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
