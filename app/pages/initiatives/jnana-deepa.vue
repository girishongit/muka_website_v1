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
