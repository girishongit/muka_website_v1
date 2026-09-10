<template>
  <div class="container-read">
    <h1 class="page-title">About Us</h1>

    <div class="content-card">
      <h2>Who we are</h2>
      <p>
        Kannada Community Munich is a non-profit cultural and social community formed to bring
        together people with roots in Karnataka and all admirers of the Kannada language and culture.
      </p>
      <p>
        We foster connection, learning, and belonging through events, volunteering, and programs
        for children, youth, and families.
      </p>
    </div>

    <div class="content-card">
      <h2>Vision</h2>
      <p>
        To build a strong, inclusive, future-ready Kannada community in Munich where culture,
        language, and community service thrive across generations.
      </p>
    </div>

    <div class="content-card">
      <h2>Mission</h2>
      <ul>
        <li>Preserve and promote Kannada language, literature, and heritage.</li>
        <li>Organise cultural, educational, and social activities year-round.</li>
        <li>Create a welcoming support network for newcomers and families.</li>
        <li>Encourage volunteering and community-led leadership.</li>
      </ul>
    </div>

    <div class="content-card tagline-card">
      <p class="kannada about-tagline">ಕನ್ನಡ ನಾಡಿಗೆ ಜಯ — ಸಮುದಾಯಕ್ಕೆ ಹೆಮ್ಮೆ.</p>
    </div>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'About — Munich Kannadigaru',
  description: 'Learn about Kannada Community Munich — our vision, mission, and who we are.'
})
</script>

<style scoped>
.tagline-card {
  text-align: center;
  border-left-color: var(--brand);
}
.about-tagline {
  font-size: 1.2rem;
  color: var(--brand);
  margin: 0;
}
</style>
