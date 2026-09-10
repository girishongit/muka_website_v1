// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  css: ['~/assets/css/main.css'],

  app: {
    head: {
      title: 'Munich Kannadigaru — ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು',
      link: [
        { rel: 'preconnect', href: 'https://fonts.googleapis.com' },
        { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' },
        {
          rel: 'stylesheet',
          href: 'https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Tiro+Kannada&display=swap'
        }
      ],
      meta: [
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'A Kannada community in Munich celebrating language, culture, and belonging.' },
        { name: 'theme-color', content: '#8B1A1A' }
      ]
    }
  },

  modules: [
    '@nuxt/content',
    '@nuxt/eslint',
    '@nuxt/hints',
    '@nuxt/image',
    '@nuxt/scripts',
    '@nuxt/test-utils',
    '@nuxt/ui',
    '@ant-design-vue/nuxt',
    '@nuxtjs/seo',
    '@nuxtjs/turnstile',
    '@oro.ad/nuxt-claude-devtools'
  ],

  nitro: {
    prerender: {
      routes: [
        '/',
        '/about',
        '/contact',
        '/forerunner',
        '/membership',
        '/membership/register',
        '/privacy-policy',
        '/resolutions',
        '/events/utsava-2025',
        '/initiatives/karnataka-cultural',
        '/initiatives/jnana-deepa',
        '/initiatives/kannada-kali'
      ]
    }
  }
})
