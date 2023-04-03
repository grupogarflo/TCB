<template>
  <v-app>
    <MenuAdmin v-show="user" />
    <v-main>
      <v-container fluid>
        <nuxt />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import MenuAdmin from '~/components/admin/MenuAdmin'

export default {
  components: { MenuAdmin },
   middleware: 'auth-admin',

  data() {
    return {
      isLoading: false
    }
  },
  computed: {
    user() {
      return this.$store.state.auth.loggedIn
    }
  },

  created() {
    // console.log(' logeado === ' + this.$store.state.auth.loggedIn)
    // console.log(this.$store.state.auth.user.type_user)
    // let vari = 'hola'
    if (this.$store.state.auth.loggedIn) {
      this.isLoading = !this.isLoading
    }
  }
}
</script>

<style scoped>
#app {
  background-color: #f1f1f1;
}
</style>