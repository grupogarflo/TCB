<template>
   <div>
      <StaticLandings  :items="item"></StaticLandings>
      <v-container class="mt-5">
         <v-row>
            <v-col sm="12" class="my-16" >
               <div v-html="content"></div>
            </v-col>
         </v-row>
      </v-container>
   </div>
</template>

<script>
import StaticLandings from '~/components/General/StaticLandings.vue'
export default {
   components: { StaticLandings },
   data(){
      return{
         item:{
            title:this.$t('politics.title'),
            subtitle:this.$t('politics.subTitle'),
            class:'mayan-ruins'
         }
      }
   },
   computed:{
      content(){
         return this.$t('politics.content');
      }
   }

}
</script>
