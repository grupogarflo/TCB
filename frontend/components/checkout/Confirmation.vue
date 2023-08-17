<template>
   <div>
      <v-row class="my-5">
         <v-col sm="12" justify="center" align="center">


            <v-icon size="6rem" color="#4CAF50" class="mt-16">
            mdi-checkbox-marked-circle-outline
            </v-icon>

            <paragraph :text="$t('confirmation.title1')" class="mt-5"></paragraph>
            <paragraph :text="$t('confirmation.title2')"></paragraph>

         </v-col>
      </v-row>
   </div>
</template>


<script>
import Paragraph from '../General/Paragraph.vue'

export default {
  components: { Paragraph },

}
</script>
