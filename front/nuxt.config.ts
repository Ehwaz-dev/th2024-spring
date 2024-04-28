// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  css: ['~/assets/scss/main.sass', 'primevue/resources/themes/aura-light-green/theme.css'],
  modules: [
    "@nuxt/image",
    '@vee-validate/nuxt',
    'nuxt-primevue',
    '@vueuse/nuxt',
  ],

  runtimeConfig: {

    public: {
      backendUrl: process.env.BACKEND_URL,
    },
  },
})