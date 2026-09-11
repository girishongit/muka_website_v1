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
          href: 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Manrope:wght@300;400;500;600;700&family=Noto+Sans+Kannada:wght@400;500;600&display=swap'
        }
      ],
      meta: [
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'A Kannada community in Munich celebrating language, culture, and belonging.' },
        { name: 'theme-color', content: '#C41E3A' }
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

  site: {
    url: 'https://munichkannadigaru.org',
    name: 'Munich Kannadigaru'
  },

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
