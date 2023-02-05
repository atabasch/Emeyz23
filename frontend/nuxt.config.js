import colors from 'vuetify/es5/util/colors'

export default {
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    titleTemplate: '%s - Emeyz',
    title: 'Emeyz Web',
    htmlAttrs: {
      lang: 'tr'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      // { rel: 'manifest', href: '/manifest.json' ,},
    ],
    script: [
      // {src: '/js/pwa.js'}
    ]
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [

  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    {src: '@/plugins/colorPalette/index.js', mode: 'client', ssr:false},
    {src: '@/plugins/getUrl.js'},
    {src: '@/plugins/global-const.js'},
    {src: '@/plugins/global-helpers.js'},
    {src: '@/plugins/global-components.js'}
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/vuetify
    '@nuxtjs/vuetify',
    '@nuxtjs/pwa'
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    '@nuxtjs/toast',
    'vue2-editor/nuxt'
  ],

  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    // Workaround to avoid enforcing hard-coded localhost:3000: https://github.com/nuxt-community/axios-module/issues/308
    // baseURL: 'https://api.emeyz.com/',
    baseURL: process.env.API_URL || 'https://api.emeyz.com/',
    headers: {
      // 'Access-Control-Allow-Origin': '*',
      // 'Access-Control-Allow-Methods':  '*',
      // 'Access-Control-Allow-Headers': ''
      'x-emeyz-key': process.env.API_KEY || 'emeyz',
      'x-dev-key': process.env.DEV_KEY || 'emeyzdev'

    }

  },

  // Vuetify module configuration: https://go.nuxtjs.dev/config-vuetify
  vuetify: {
    customVariables: ['~/assets/variables.scss'],
    theme: {
      dark: false,

      themes: {
        light: {
          primary:"#03a9f4",
          accent:"#0288d1",
          secondary:"#8bc34a",
          info:"#607d8b",
          warning:"#FF9800",
          error:"#F44336",
          success:"#8bc34a",
        },
        dark: {
          primary: colors.blue.darken2,
          accent: colors.grey.darken3,
          secondary: colors.amber.darken3,
          info: colors.teal.lighten1,
          warning: colors.amber.base,
          error: colors.deepOrange.accent4,
          success: colors.green.accent3
        }
      }
    }
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
  },

  // loading: '~/components/panel/child/PageLoading.vue',

  env: {
    url: {
      api: 'https://api.emeyz.com',
      base: '/',
      panel: '/aswpanel/'
    }
  },


  pwa: {
    manifest: {
      name: '#Emeyz',
      lang: 'tr',
      useWebmanifestExtension: false,
      description: 'Emeyz Hayat Rehberi. Ne, nedir ve nasıl yapılır?',
      start_url: '/',
      display: 'fullscreen',
      background_color: '#1786C8',
      theme_color: '#1786C8',

    },
    meta: {
      name: "#Emeyz",
      start_url: '/',
      description: 'Emeyz Hayat Rehberi. Ne, nedir ve nasıl yapılır?',
      theme_color: '#1786C8',
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
      lang: 'tr',
    },
    icons: {
      src: '/icon.png',
      sizes: [64, 120, 144, 152, 192, 384, 512]
    }

  },

  auth: {
    redirect: {
      login: '/login',
      logout: '/',
      callback: '/login',
      home: '/'
    },
    watchLoggedIn: false,
    rewriteRedirects: false,

    strategies: {

      local: {
        tokenType: '',
        token: {
          property: 'token',
          global: true,
          maxAge: 60*60 // 1 saat
        },
        user: {
          property: 'user',
          autoFetch: false
        },
        endpoints: {
          login:      { url: '/account/login',      method:'post' },    /// login işleminde gidilir
          register:   { url: '/account/register',   method:'post' }, // register işlemi
          logout:     { url: '/account/logout',     method:'post' },    // logout
          refresh:    { url: '/account/refresh',    method:'post' },  // token yenileme
          user:       { url: '/account/me',         method:'post' },         // kullanıcı oturum açtığı zaman sayfa yenilenince çalışır
        }
      }

    } // strategies

  }, // auth

  toast: {
    position: 'top-center',
    duration: 2500,
    keepOnHover: true,
    iconPack: 'mdi',
    containerClass: 'vueToastContainer',
    className: 'vueToast',
    theme: 'bubble'
  },
}
