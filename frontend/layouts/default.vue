<template>
  <v-app>
    <HeaderPrincipal></HeaderPrincipal>
    <v-main>
      <v-container fluid class="ma-0 pa-0">
        <Nuxt />
      </v-container>
    </v-main>
    <FooterPrincipal></FooterPrincipal>
   
  </v-app>
</template>

<script>
import HeaderPrincipal from "../components/Header/principal.vue";
import FooterPrincipal from "../components/Footer/Principal.vue";
export default {
  name: 'DefaultLayout',
  components: {
    HeaderPrincipal,
    FooterPrincipal
  },
  data () {
    return {
      clipped: false,
      drawer: false,
      fixed: false,
      items: [
        {
          icon: 'mdi-apps',
          title: 'Welcome',
          to: '/'
        },
        {
          icon: 'mdi-chart-bubble',
          title: 'Inspire',
          to: '/inspire'
        }
      ],
      miniVariant: false,
      right: true,
      rightDrawer: false,
      title: 'Vuetify.js'
    }
  }
}
</script>
