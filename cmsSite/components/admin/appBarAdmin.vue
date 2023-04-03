<!--<template>
  <v-app-bar color dense app hide-on-scroll>
    <v-app-bar-nav-icon @click="openMenuAdmin" />

    <v-toolbar-title>The Tour Agency CMS</v-toolbar-title>

    <v-spacer />

    <v-menu left bottom>
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon v-bind="attrs" v-on="on">
          <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>
      </template>

      <v-list>
        <v-list-item v-for="items in item" :key="items.title" link>
          <v-list-item-title>
            <nuxt-link :to="items.link" :prefetch="false">{{ items.title }}</nuxt-link>
          </v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </v-app-bar>
</template>
<script>
import { mapState, mapMutations } from 'vuex'

export default {
  data() {
    return {
      item: [
        { title: 'Home', link: 'home' },
        { title: 'My Account', link: 'profile' },
        { title: 'Users', link: 'algo' }
      ]
    }
  },
  computed: {
    ...mapState(['valorAbreMenuMovilAdmin'])
  },
  methods: {
    ...mapMutations(['openMenuAdmin'])
  }
}
</script>

<style scoped>
a {
  text-decoration: none;
  color: inherit;
}
</style>-->
