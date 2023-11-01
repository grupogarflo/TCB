import colors from 'vuetify/es5/util/colors'

export default {
  // Global page headers: https://go.nuxtjs.dev/config-head
   generate: {
      crawler: false
   },


   fetchOnServer: false,
   ssr: true,
   target:'static',


   loading: {
      color: '#1A2D4E'
   },

   head: {
      titleTemplate: '%s - Cancun Bay ',
      title: 'Cancun Bay',
      htmlAttrs: {
         lang:'en'
      },
      meta: [
         { charset: 'utf-8' },
         { name: 'viewport', content: 'width=device-width, initial-scale=1' },
         { hid: 'description', name: 'description', content: '' },
         { name: 'format-detection', content: 'telephone=no' },
         { "http-equiv": "cache-control", content: "max-age=0" },

            { "http-equiv": "cache-control", content: "no-cache" },

            { "http-equiv": "expires", content: "0" },

            { "http-equiv": "expires", content: "Tue, 01 Jan 1980 1:00:00 GMT" },
            { "http-equiv": "pragma", content: "no-cache" },
            { "http-equiv": "cache-control", content: "max-age=0" },
            {
               hid: 'og-image',
               property: 'og:image',
               content: 'https://cancunbay.com/cancunbay-chichen-itza.jpg',
             },
      ],
      link: [
         { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
      ],

      script:[
         {  src: 'https://sdk.mercadopago.com/js/v2',  type:'text/javascript'},
         { src:'facebook.js', type: 'text/javascript'},

      ]


   },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    "~/assets/sass/site.scss"
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    { src: '~/plugins/vue2-filters' },
    { src: '~/plugins/maskCard' },
    { src: '~plugins/vimeo-player' },
    { src: '~/plugins/general.js' },
    { src: '~/plugins/filters.js' },


    {
      src: '~/plugins/vuex-persist',
      ssr: false,
    },
    { src: '~/plugins/zohoScript.js', mode: 'client' },
    { src: '~/plugins/zohoPop.js', mode: 'client' },
    "~/plugins/googleAnalytics.js",
    "~/plugins/vue2-google-maps.js",
    // "~/plugins/facebook.js",

  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,


   /*
      googleAnalytics: {
      // eslint-disable-next-line require-await
      asyncID: async (context) => {

         if(context.i18n.code=='en')
         {
            return 'G-WQRK93X8SX'
         }
         else{
            return 'G-PD3YRPJ4J8'
         }
      }
      },
   */



  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/vuetify
    '@nuxtjs/vuetify',
    '@nuxtjs/eslint-module',

    /*['nuxt-facebook-pixels-module', {

      pixelId: '469747224989284', // or 'FACEBOOK_PIXEL_ID_1, FACEBOOK_PIXEL_ID_2'
      track: 'PageView',
      autoPageView: true,
      disabled: false,
      debug:true
    }]*/
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    '@nuxtjs/axios',
    '@nuxtjs/i18n'
    // '@nuxtjs/sitemap',
  ],







   i18n: {

      locales: [

         {
            code: 'es',
            name: 'Español',
            iso: 'es-ES',
            domain: 'www.cancunbay.com.mx',

            show:true
         },
         {
            code: 'en',
            iso: 'en-US',
            name: 'English',
            domain: 'www.cancunbay.com',
            show:true
         },


      ],
      defaultLocale: 'en',
      // lazy: true,
      differentDomains: true,
      detectBrowserLanguage: false,

      vueI18n: {
        // locale: 'es',     
        // fallbackLocale:'en',
        fallbackLocale: {

            'es':      ['es-ES'],
            'en':      ['en-US'],

         },
        messages: {
          es: require('./locales/es.json'),
          en: require('./locales/en.json'),
        },
        silentTranslationWarn: true
      }
   },






  // Axios module configuration: https://go.nuxtjs.dev/config-axios
   axios: {
      // baseURL: 'http://localhost/cancunToursNew/backEnd/public/api/',

      // baseURL: 'http://127.0.0.1:8000/api/',

        baseURL:'https://www.cancunbay.com.mx/api/'
   },

   // Vuetify module configuration: https://go.nuxtjs.dev/config-vuetify
   vuetify: {
      customVariables: ['~/assets/sass/variables.scss'],
      theme: {
         dark: false,
      }
   },
   vue: {
      config: {
        productionTip: true,
        devtools: true
      }
    },

   // Build Configuration: https://go.nuxtjs.dev/config-build
   build: {}
}
