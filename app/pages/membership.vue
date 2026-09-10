<template>
  <div class="container-read">
    <h1 class="page-title">Membership</h1>

    <div class="content-card">
      <h2>Why join</h2>
      <ul>
        <li>Be part of a warm Kannada network in Munich.</li>
        <li>Access community events, workshops, and family activities.</li>
        <li>Contribute, volunteer, and shape community programs.</li>
        <li>Support and celebrate Karnataka culture abroad.</li>
      </ul>
    </div>

    <div class="content-card">
      <h2>Membership types</h2>
      <ul>
        <li><strong>Individual membership</strong> — open to any individual</li>
        <li><strong>Family membership</strong> — covers the whole household</li>
        <li><strong>Student membership</strong> — reduced rate for students</li>
      </ul>
    </div>

    <div class="content-card">
      <h2>Ready to join?</h2>
      <p>Fill in the registration form to get started. We'll be in touch shortly.</p>
      <NuxtLink to="/membership/register" class="btn btn-primary">Register now</NuxtLink>
    </div>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Membership — Munich Kannadigaru',
  description: 'Join Kannada Community Munich — membership types and registration.'
})
</script>
