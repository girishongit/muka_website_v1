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
