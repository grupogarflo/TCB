<template>
   <div>
      <v-row class="my-5">
         <v-col sm="12" justify="center" align="center">
            <section-title :titleText="$t('confirmation.title1')" class="mt-5"></section-title>

            <v-icon size="20rem" color="#1A2D4E" class="my-5">
            mdi-check-decagram
            </v-icon>

            <section-title :titleText="$t('confirmation.title2')" class="mt-5"></section-title>

         </v-col>
      </v-row>
   </div>
</template>


<script>
import SectionTitle from '../General/SectionTitle.vue'
export default {
  components: { SectionTitle },

}
</script>
