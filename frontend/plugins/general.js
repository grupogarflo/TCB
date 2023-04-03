import Vue from "vue";

Vue.mixin({
  methods: {
    isMobile() {
      return this.$vuetify.breakpoint.name == "xs" ||
        this.$vuetify.breakpoint.name == "sm"
        ? true
        : false;
    },

    route() {
      return this.$route.name;
    },
    back() {
      // valido que todas las habitaciones del bloque se hayan hecho
      this.$router.go(-1);
    },
    loadSessionCms() {
      if (this.$store.state.usercms.access_token == null) {
        this.$router.push("/cms");
      }
    },
  },

});
