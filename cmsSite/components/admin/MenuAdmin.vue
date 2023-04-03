<template>
  <div class="appBar mb-5">
    <v-app-bar app color>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

      <v-toolbar-title>Yalku CMS</v-toolbar-title>

      <v-spacer></v-spacer>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" app temporary>
      <v-list nav dense>
        <v-list-item-group
          v-model="group"
          active-class="deep-purple--text text--accent-4"
        >
          <v-list-item
            v-for="item in items"
            :key="item.title"
            link
            :to="item.link"
          >
            <v-list-item-icon>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>

<script>
export default {
  data() {
    return {
      drawer: false,
      group: null,
      items: [
        {
          title: 'Banner home',
          icon: 'mdi-image-area',
          link: '/bannerHome',
        },
        {
          title: 'Catálogo Icons',
          icon: 'mdi-book-open-variant',
          link: '/iconsCatalogo',
        },
        {
          title: 'Categorías',
          icon: 'mdi-book-open-variant',
          link: '/categorias',
        },
        {
          title: 'Destinos',
          icon: 'mdi-map-plus',
          link: '/destinos',
        },
        {
          title: 'Categorías/Destinos',
          icon: 'mdi-vector-link',
          link: '/relacionCategoriaDestino',
        },
        {
          title: 'Tours',
          icon: 'mdi-island',
          link: '/tours',
        },
        {
          title: 'Usuarios',
          icon: 'mdi-account-group-outline',
          link: '/usuarios',
        },
        {
          title: 'Idiomas',
          icon: 'mdi-translate',
          link: '/idiomas',
        },
        {
          title: 'Tipo de cambio',
          icon: 'mdi-currency-usd',
          link: '/tipoCambio',
        },
        {
          title: 'Bloqueo de fechas,diás',
          icon: 'mdi-timer-off',
          link: '/bloqueoFechas',
        },
        {
          title: 'Promocodes',
          icon: 'mdi-sale',
          link: '/promocode',
        },

        {
          title: 'Reservas',
          icon: 'mdi-cash-register',
          link: '/reservas',
        },
      ],
    }
  },

  watch: {
    group() {
      this.drawer = false
    },
  },
}
</script>
<style scoped>
a {
  text-decoration: none;
  color: inherit;
}
</style>

<!--<template>
  <v-card>
    <v-navigation-drawer
      v-model="drawer"
      v-show(drawer)
      :mini-variant.sync="mini"
      app
      expand-on-hover
    >
      <v-list-item class="px-2">
        <v-list-item-avatar>
          <v-img src="https://randomuser.me/api/portraits/men/85.jpg" />
        </v-list-item-avatar>

        <v-list-item-title>John Leider</v-list-item-title>

        <v-btn icon @click.stop="mini = !mini">
          <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
      </v-list-item>

      <v-divider />

      <v-list dense>
        <v-list-item v-for="item in items" :key="item.title" link>
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
  </v-card>
</template>-->

<!--
<script>
// import { mapState } from 'vuex'
export default {
  data() {
    return {
      drawer: false,
      items: [
        { title: 'Home', icon: 'mdi-home-city' },
        { title: 'My Account', icon: 'mdi-account' },
        { title: 'Users', icon: 'mdi-account-group-outline' }
      ],
      mini: true
    }
  },
  computed: {
    // ...mapState(['drawer'])
  }
}
</script>
-->
