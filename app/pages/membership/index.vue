<template>
  <div>
    <section class="membership-hero">
      <div class="section-container">
        <span class="tag hero-tag animate-item">Join Our Community</span>
        <h1 class="animate-item delay-1">Become a Member</h1>
        <p class="hero-kannada kannada-text animate-item delay-2">ಸದಸ್ಯರಾಗಿ</p>
        <p class="hero-desc animate-item delay-3">Join our vibrant community and enjoy exclusive benefits, events, and connections with fellow Kannadigas in Munich.</p>
        <NuxtLink to="/membership/register" class="btn btn-white animate-item delay-4">Register Now <span aria-hidden="true">→</span></NuxtLink>
      </div>
    </section>

    <!-- Pricing -->
    <section class="section pricing-section">
      <div class="section-container">
        <div class="section-header animate-observe">
          <span class="tag">Membership Plans</span>
          <h2 class="section-title">
            <span class="black">Choose Your</span>
            <span class="red"> Plan</span>
          </h2>
        </div>

        <div class="pricing-grid">
          <div class="pricing-card animate-observe">
            <h3>Individual</h3>
            <p class="plan-kannada kannada-text">ವ್ಯಕ್ತಿಗತ</p>
            <div class="price">€30<span>/year</span></div>
            <ul>
              <li>Access to all community events</li>
              <li>Newsletter subscription</li>
              <li>Member-only WhatsApp group</li>
              <li>Voting rights in general assembly</li>
              <li>Discount on event tickets</li>
            </ul>
            <NuxtLink to="/membership/register?type=individual" class="btn btn-outline btn-full">Select Plan</NuxtLink>
          </div>

          <div class="pricing-card popular animate-observe delay-1">
            <span class="popular-badge">Most Popular</span>
            <h3>Family</h3>
            <p class="plan-kannada kannada-text">ಕುಟುಂಬ</p>
            <div class="price">€50<span>/year</span></div>
            <ul>
              <li>All Individual benefits</li>
              <li>Includes spouse and children</li>
              <li>Priority event registration</li>
              <li>Free entry to UTSAVA</li>
              <li>Family activities and workshops</li>
            </ul>
            <NuxtLink to="/membership/register?type=family" class="btn btn-primary btn-full">Select Plan</NuxtLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Benefits -->
    <section class="section benefits-section">
      <div class="section-container">
        <div class="section-header animate-observe">
          <span class="tag">Member Benefits</span>
          <h2 class="section-title">
            <span class="black">What You</span>
            <span class="red"> Get</span>
          </h2>
        </div>

        <div class="benefits-grid">
          <div class="benefit-card animate-observe">
            <div class="benefit-icon">📅</div>
            <h4>Exclusive Events</h4>
            <p>Access to member-only events, workshops, and cultural programs throughout the year.</p>
          </div>
          <div class="benefit-card animate-observe delay-1">
            <div class="benefit-icon">👥</div>
            <h4>Community Network</h4>
            <p>Connect with fellow Kannadigas, build friendships, and expand your social circle.</p>
          </div>
          <div class="benefit-card animate-observe delay-2">
            <div class="benefit-icon">❤️</div>
            <h4>Support System</h4>
            <p>Get support and assistance from the community for settling in Munich.</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Membership | Munich Kannadigaru',
  description: 'Join Munich Kannadigaru and enjoy exclusive benefits, events, and connections with fellow Kannadigas.'
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
.delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; } .delay-3 { animation-delay: 0.3s; } .delay-4 { animation-delay: 0.4s; }
@keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
.animate-observe { opacity: 0; transform: translateY(28px); transition: opacity 0.7s ease, transform 0.7s ease; }
.animate-observe.delay-1 { transition-delay: 0.12s; } .animate-observe.delay-2 { transition-delay: 0.24s; }
.animate-observe.is-visible { opacity: 1; transform: translateY(0); }
@media (prefers-reduced-motion: reduce) {
  .animate-item, .animate-observe { animation: none !important; transition: none !important; opacity: 1 !important; transform: none !important; }
}

.membership-hero {
  background: linear-gradient(135deg, var(--primary-red) 0%, var(--primary-red-dark) 100%);
  color: var(--white);
  padding: 100px 0;
  text-align: center;
}
.hero-tag { background: rgba(255,255,255,0.15); color: var(--white); margin-bottom: 25px; }
.membership-hero h1 { font-size: clamp(40px, 6vw, 68px); font-family: 'Playfair Display', serif; color: var(--white); margin-bottom: 15px; }
.hero-kannada { font-size: 24px; color: var(--gold); margin-bottom: 20px; display: block; }
.hero-desc { font-size: 18px; opacity: 0.9; max-width: 600px; margin: 0 auto 30px; color: var(--white); }

/* Pricing */
.pricing-section { background: var(--cream); }
.pricing-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; max-width: 800px; margin: 0 auto; }
.pricing-card {
  background: var(--white);
  border-radius: 24px;
  padding: 45px 40px;
  position: relative;
  box-shadow: 0 5px 20px rgba(0,0,0,0.04);
  transition: box-shadow 0.3s, transform 0.3s;
}
.pricing-card:hover { box-shadow: 0 20px 60px rgba(0,0,0,0.1); transform: translateY(-5px); }
.pricing-card.popular { background: var(--primary-red); color: var(--white); }
.popular-badge {
  position: absolute;
  top: -14px;
  left: 50%;
  transform: translateX(-50%);
  background: var(--gold);
  color: var(--text-dark);
  padding: 6px 20px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 700;
  font-family: 'Manrope', sans-serif;
  white-space: nowrap;
}
.pricing-card h3 { font-size: 28px; font-family: 'Playfair Display', serif; margin-bottom: 6px; }
.pricing-card.popular h3 { color: var(--white); }
.plan-kannada { font-size: 16px; margin-bottom: 20px; }
.pricing-card.popular .plan-kannada { color: rgba(255,255,255,0.8); }
.price { font-size: 52px; font-weight: 700; font-family: 'Playfair Display', serif; margin-bottom: 30px; }
.pricing-card.popular .price { color: var(--white); }
.price span { font-size: 18px; font-weight: 400; opacity: 0.7; }
.pricing-card ul { list-style: none; padding: 0; margin-bottom: 35px; }
.pricing-card ul li {
  padding: 10px 0;
  border-bottom: 1px solid rgba(0,0,0,0.06);
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 10px;
  color: var(--text-light);
}
.pricing-card.popular ul li { color: rgba(255,255,255,0.85); border-bottom-color: rgba(255,255,255,0.15); }
.pricing-card ul li::before { content: '✓'; color: var(--primary-red); font-weight: 700; flex-shrink: 0; }
.pricing-card.popular ul li::before { color: var(--gold); }
.btn-full { width: 100%; justify-content: center; }
.pricing-card.popular .btn-outline { border-color: var(--white); color: var(--white); }
.pricing-card.popular .btn-outline:hover { background: var(--white); color: var(--primary-red); }

/* Benefits */
.benefits-section { background: var(--cream-dark); }
.benefits-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
.benefit-card {
  background: var(--white);
  border-radius: 20px;
  padding: 40px 35px;
  text-align: center;
  transition: box-shadow 0.3s, transform 0.3s;
}
.benefit-card:hover { box-shadow: 0 15px 40px rgba(0,0,0,0.08); transform: translateY(-4px); }
.benefit-icon { font-size: 36px; margin-bottom: 20px; }
.benefit-card h4 { font-size: 22px; font-family: 'Playfair Display', serif; color: var(--text-dark); margin-bottom: 12px; }
.benefit-card p { color: var(--text-light); font-size: 15px; line-height: 1.7; margin-bottom: 0; }

@media (max-width: 768px) {
  .pricing-grid { grid-template-columns: 1fr; max-width: 420px; }
  .benefits-grid { grid-template-columns: 1fr; }
}
</style>
