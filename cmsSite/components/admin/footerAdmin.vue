<template>
  <v-footer absolute app class="font-weight-medium backfondo white--text">
    <v-col class="text-center" cols="12">
      {{ new Date().getFullYear() }} —
      <strong>TTA</strong>
    </v-col>
  </v-footer>
</template>


<style scoped>
.backfondo {
  background: #1d2848;
}
#logo {
  margin: 3rem 0 5rem 0;
}
#logo img {
  width: 10%;
}
@media screen and (max-width: 768px) {
  #logo img {
    width: 40%;
  }
}
</style>
