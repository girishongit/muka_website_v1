<template>
  <footer class="site-footer">
    <div class="footer-container">
      <div class="footer-grid">
        <!-- Brand column -->
        <div class="footer-brand">
          <NuxtLink to="/" class="footer-logo">
            <div class="footer-logo-icon">
              <img
                src="https://images.unsplash.com/photo-1582510003544-4d00b7f74220?w=200&q=80"
                alt="Munich Kannadigaru"
              />
            </div>
            <div>
              <span class="footer-logo-en">Munich Kannadigaru</span>
              <span class="footer-logo-kn kannada-text">ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು</span>
            </div>
          </NuxtLink>
          <p>A vibrant community dedicated to uniting all Kannada speakers in and around Munich, Germany.</p>
          <p class="footer-quote kannada-text">"ಎಲ್ಲಾದರು ಇರು ಎಂತಾದರು ಇರು<br>ಎಂದೆಂದಿಗೂ ನೀ ಕನ್ನಡವಾಗಿರು.."</p>
        </div>

        <!-- Quick Links -->
        <div class="footer-column">
          <h4>Quick Links</h4>
          <ul>
            <li><NuxtLink to="/about">About Us</NuxtLink></li>
            <li><NuxtLink to="/events/utsava-2025">Events</NuxtLink></li>
            <li><NuxtLink to="/membership">Membership</NuxtLink></li>
            <li><NuxtLink to="/contact">Contact</NuxtLink></li>
          </ul>
        </div>

        <!-- Initiatives -->
        <div class="footer-column">
          <h4>Our Initiatives</h4>
          <ul>
            <li class="yellow"><NuxtLink to="/initiatives/karnataka-cultural">Karnataka Cultural</NuxtLink></li>
            <li class="yellow"><NuxtLink to="/initiatives/jnana-deepa">Jnana Deepa</NuxtLink></li>
            <li class="yellow"><NuxtLink to="/initiatives/kannada-kali">Kannada Kali</NuxtLink></li>
          </ul>
          <div class="footer-legal">
            <h4>Legal</h4>
            <ul>
              <li><NuxtLink to="/privacy-policy">Privacy Policy</NuxtLink></li>
              <li><NuxtLink to="/resolutions">Resolutions</NuxtLink></li>
            </ul>
          </div>
        </div>

        <!-- Contact -->
        <div class="footer-column footer-contact">
          <h4>Reach Us</h4>
          <p>
            <span class="icon"><i>📍</i></span>
            MunichKannadigaru e.V.<br>Verein Number: VR103964<br>Munich, Germany
          </p>
          <p>
            <span class="icon"><i>✉</i></span>
            <a href="mailto:info@munichkannadigaru.org">info@munichkannadigaru.org</a>
          </p>
          <p>
            <span class="icon"><i>🌐</i></span>
            <a href="https://munichkannadigaru.org">munichkannadigaru.org</a>
          </p>
        </div>
      </div>

      <!-- Bottom -->
      <div class="footer-bottom">
        <p>Copyright © {{ year }}, MunichKannadigaru e.V. All rights reserved</p>
        <p>
          <span class="karnataka-flag-dots">
            <span class="yellow"></span>
            <span class="red"></span>
          </span>
          Made with pride in Karnataka
        </p>
      </div>
    </div>
  </footer>
</template>

<script setup>
const year = new Date().getFullYear()
</script>

<style scoped>
.site-footer {
  background: var(--footer-bg);
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23FFB800' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  color: var(--white);
  padding: 60px 0 30px;
}

.footer-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 40px;
}

.footer-grid {
  display: grid;
  grid-template-columns: 1.5fr 1fr 1.5fr 1.5fr;
  gap: 50px;
  margin-bottom: 50px;
}

/* Brand */
.footer-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
  margin-bottom: 25px;
}
.footer-logo-icon {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  overflow: hidden;
  flex-shrink: 0;
}
.footer-logo-icon img { width: 100%; height: 100%; object-fit: cover; }
.footer-logo-en {
  display: block;
  font-family: 'Playfair Display', serif;
  font-size: 18px;
  color: var(--white);
  font-weight: 600;
}
.footer-logo-kn {
  display: block;
  font-size: 12px;
  color: var(--primary-red);
}

.footer-brand p {
  color: rgba(255,255,255,0.7);
  font-size: 14px;
  margin-bottom: 20px;
  line-height: 1.7;
}
.footer-quote {
  color: var(--gold) !important;
  font-size: 15px !important;
  line-height: 1.8 !important;
}

/* Columns */
.footer-column h4 {
  color: var(--white);
  font-size: 18px;
  margin-bottom: 25px;
  font-family: 'Playfair Display', serif;
  font-weight: 600;
}
.footer-column ul {
  list-style: none;
  padding: 0;
  margin-bottom: 30px;
}
.footer-column ul li {
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 10px;
}
.footer-column ul li::before {
  content: '';
  width: 6px;
  height: 6px;
  background: var(--primary-red);
  border-radius: 50%;
  flex-shrink: 0;
}
.footer-column ul li.yellow::before { background: var(--gold); }
.footer-column ul li a {
  color: rgba(255,255,255,0.7);
  text-decoration: none;
  font-size: 14px;
  transition: color 0.3s;
}
.footer-column ul li a:hover { color: var(--gold); }

.footer-legal h4 {
  font-size: 12px;
  color: rgba(255,255,255,0.5);
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 15px;
}
.footer-legal ul li::before { background: rgba(255,255,255,0.3); }

/* Contact column */
.footer-contact p {
  color: rgba(255,255,255,0.7);
  font-size: 14px;
  margin-bottom: 12px;
  display: flex;
  align-items: flex-start;
  gap: 10px;
  line-height: 1.6;
}
.footer-contact .icon {
  flex-shrink: 0;
  font-style: normal;
}
.footer-contact a {
  color: var(--gold);
  text-decoration: none;
}
.footer-contact a:hover { color: var(--gold-dark); }

/* Bottom bar */
.footer-bottom {
  border-top: 1px solid rgba(255,255,255,0.1);
  padding-top: 25px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 12px;
}
.footer-bottom p {
  color: rgba(255,255,255,0.5);
  font-size: 13px;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 4px;
}

.karnataka-flag-dots {
  display: inline-flex;
  gap: 6px;
  margin-right: 8px;
}
.karnataka-flag-dots span {
  width: 10px;
  height: 10px;
  border-radius: 50%;
}
.karnataka-flag-dots .yellow { background: var(--gold); }
.karnataka-flag-dots .red { background: var(--primary-red); }

/* Responsive */
@media (max-width: 1024px) {
  .footer-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
  .footer-container { padding: 0 20px; }
  .footer-grid { grid-template-columns: 1fr; gap: 40px; }
  .footer-bottom { flex-direction: column; align-items: flex-start; }
}
</style>
