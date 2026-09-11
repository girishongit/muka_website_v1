<template>
  <div>
    <section class="page-header">
      <div class="section-container">
        <span class="tag animate-item">Our Leaders</span>
        <h1 class="animate-item delay-1">Forerunners</h1>
        <p class="kannada-text animate-item delay-2">ನಮ್ಮ ಮುನ್ನುಡಿಗಾರರು</p>
        <p class="animate-item delay-3">Our elected executive committee members — chosen by fellow members to serve and represent the community.</p>
      </div>
    </section>

    <!-- Founding Story -->
    <section class="section founders-section">
      <div class="section-container">
        <div class="founders-intro animate-observe">
          <span class="tag">About Forerunners</span>
          <h2>Elected by Members,<br><span class="red">Serving the Community</span></h2>
          <p>The executive committee of Munich Kannadigaru is elected democratically by the community's general membership. Forerunners are always an odd number — five or seven — ensuring decisions are always decisive and fair.</p>
          <p>Each term, they take on the responsibility of organising events, managing finances, liaising with members, and steering the community's vision. Their mandate comes directly from the community they serve.</p>
        </div>
      </div>
    </section>

    <!-- Current Committee -->
    <section class="section committee-section">
      <div class="section-container">
        <div class="section-header animate-observe">
          <span class="tag">Current Leadership</span>
          <h2 class="section-title">
            <span class="black">Our</span>
            <span class="red"> Committee</span>
          </h2>
        </div>

        <div class="committee-grid">
          <div class="committee-card animate-observe">
            <div class="member-photo">
              <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&q=80" alt="President" />
            </div>
            <div class="member-info">
              <div class="member-role">President</div>
              <h3>ಅಧ್ಯಕ್ಷರು</h3>
              <p>Leading the community with vision and dedication, ensuring we stay true to our mission while growing stronger each day.</p>
            </div>
          </div>

          <div class="committee-card animate-observe delay-1">
            <div class="member-photo">
              <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&q=80" alt="Vice President" />
            </div>
            <div class="member-info">
              <div class="member-role">Vice President</div>
              <h3>ಉಪಾಧ್ಯಕ್ಷರು</h3>
              <p>Supporting the president and taking charge of key initiatives that drive community engagement and growth.</p>
            </div>
          </div>

          <div class="committee-card animate-observe delay-2">
            <div class="member-photo">
              <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&q=80" alt="Secretary" />
            </div>
            <div class="member-info">
              <div class="member-role">Secretary</div>
              <h3>ಕಾರ್ಯದರ್ಶಿ</h3>
              <p>Managing day-to-day operations and ensuring smooth communication within the community.</p>
            </div>
          </div>

          <div class="committee-card animate-observe">
            <div class="member-photo">
              <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80" alt="Treasurer" />
            </div>
            <div class="member-info">
              <div class="member-role">Treasurer</div>
              <h3>ಖಜಾಂಚಿ</h3>
              <p>Overseeing financial matters and ensuring transparent management of community resources.</p>
            </div>
          </div>
          <div class="committee-card animate-observe delay-1">
            <div class="member-photo">
              <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200&q=80" alt="Cultural Coordinator" />
            </div>
            <div class="member-info">
              <div class="member-role">Cultural Coordinator</div>
              <h3>ಸಾಂಸ್ಕೃತಿಕ ಸಮನ್ವಯಕಾರರು</h3>
              <p>Planning and organising cultural programs, festivals, and community events throughout the year.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section cta-section">
      <div class="section-container">
        <div class="cta-box animate-observe">
          <h2>Join Our Community</h2>
          <p>Be part of a growing family of Kannadigas in Munich. Together we are stronger.</p>
          <div class="cta-buttons">
            <NuxtLink to="/membership/register" class="btn btn-primary">Become a Member</NuxtLink>
            <NuxtLink to="/contact" class="btn btn-outline">Contact Us</NuxtLink>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Forerunners | Munich Kannadigaru',
  description: 'Meet the visionary leaders who built and continue to lead the Munich Kannadigaru community.'
})
onMounted(() => {
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); obs.unobserve(e.target) } })
  }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' })
  document.querySelectorAll('.animate-observe').forEach(el => obs.observe(el))
})
</script>

<style scoped>
.animate-item { opacity: 0; transform: translateY(24px); animation: fadeUp 0.7s ease forwards; }
.delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; } .delay-3 { animation-delay: 0.3s; }
@keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
.animate-observe { opacity: 0; transform: translateY(28px); transition: opacity 0.7s ease, transform 0.7s ease; }
.animate-observe.delay-1 { transition-delay: 0.12s; } .animate-observe.delay-2 { transition-delay: 0.24s; }
.animate-observe.is-visible { opacity: 1; transform: translateY(0); }
@media (prefers-reduced-motion: reduce) {
  .animate-item, .animate-observe { animation: none !important; transition: none !important; opacity: 1 !important; transform: none !important; }
}

.page-header { padding: 80px 0 60px; background: var(--cream); text-align: center; }
.page-header h1 { font-size: clamp(36px, 5vw, 56px); margin-bottom: 15px; font-family: 'Playfair Display', serif; }
.page-header .kannada-text { font-size: 22px; color: var(--primary-red); margin-bottom: 15px; display: block; }
.page-header p { color: var(--text-light); font-size: 16px; }

.founders-section { background: var(--cream); }
.founders-intro { max-width: 750px; margin: 0 auto; text-align: center; }
.founders-intro .tag { margin-bottom: 20px; display: inline-block; }
.founders-intro h2 { font-size: clamp(30px, 4vw, 44px); font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 25px; line-height: 1.2; }
.founders-intro h2 .red { color: var(--primary-red); }
.founders-intro p { color: var(--text-light); font-size: 16px; line-height: 1.8; }

.committee-section { background: var(--cream-dark); }
.committee-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; }
.committee-card {
  background: var(--white);
  border-radius: 20px;
  padding: 35px;
  display: flex;
  gap: 25px;
  align-items: flex-start;
  transition: box-shadow 0.3s, transform 0.3s;
}
.committee-card:hover { box-shadow: 0 15px 40px rgba(0,0,0,0.08); transform: translateY(-4px); }
.member-photo { width: 80px; height: 80px; border-radius: 50%; overflow: hidden; flex-shrink: 0; }
.member-photo img { width: 100%; height: 100%; object-fit: cover; }
.member-role {
  display: inline-block;
  padding: 4px 12px;
  background: rgba(196,30,58,0.08);
  color: var(--primary-red);
  border-radius: 20px;
  font-size: 11px;
  font-weight: 600;
  font-family: 'Manrope', sans-serif;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 8px;
}
.member-info h3 { font-size: 20px; font-family: 'Noto Sans Kannada', sans-serif; color: var(--text-dark); margin-bottom: 10px; }
.member-info p { color: var(--text-light); font-size: 14px; line-height: 1.7; margin-bottom: 0; }

.cta-section { background: var(--cream); }
.cta-box { text-align: center; max-width: 700px; margin: 0 auto; }
.cta-box h2 { font-size: clamp(28px, 4vw, 42px); font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 15px; }
.cta-box p { color: var(--text-light); margin-bottom: 35px; }
.cta-buttons { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; }

@media (max-width: 1024px) { .committee-grid { grid-template-columns: 1fr; } }
@media (max-width: 768px) { .committee-card { flex-direction: column; } }
</style>
