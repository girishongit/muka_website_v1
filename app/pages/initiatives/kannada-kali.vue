<template>
  <div class="container-read">
    <p class="initiative-label">Initiative</p>
    <h1 class="page-title">Kannada Kali</h1>

    <div class="content-card">
      <h2>About this initiative</h2>
      <p>
        Kannada Kali is dedicated to Kannada language learning for children and adults through
        structured classes, engaging activities, and community-driven teaching.
      </p>
    </div>

    <div class="content-card">
      <h2>Program highlights</h2>
      <ul>
        <li>Reading, writing, and speaking fundamentals</li>
        <li>Age-appropriate curriculum and learning groups</li>
        <li>Storytelling, poems, and cultural immersion activities</li>
      </ul>
    </div>

    <NuxtLink to="/membership" class="btn btn-outline">Get involved</NuxtLink>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Kannada Kali — Munich Kannadigaru',
  description: 'Kannada Kali — language learning for children and adults in Munich.'
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
