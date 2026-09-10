<template>
  <div class="container-read">
    <p class="initiative-label">Initiative</p>
    <h1 class="page-title">Karnataka Cultural</h1>

    <div class="content-card">
      <h2>About this initiative</h2>
      <p>
        Karnataka Cultural celebrates the rich artistic and traditional heritage of Karnataka
        through performances, festivals, and collaborative events in Munich.
      </p>
    </div>

    <div class="content-card">
      <h2>What we do</h2>
      <ul>
        <li>Host cultural festivals and community celebrations</li>
        <li>Showcase music, dance, drama, and folk traditions</li>
        <li>Encourage participation across all generations</li>
      </ul>
    </div>

    <NuxtLink to="/membership" class="btn btn-outline">Get involved</NuxtLink>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Karnataka Cultural — Munich Kannadigaru',
  description: 'Karnataka Cultural initiative celebrating arts, music, and heritage in Munich.'
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
