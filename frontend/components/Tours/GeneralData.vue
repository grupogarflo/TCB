<template>
   <v-row>
      <v-col cols="12">
         <Rank :rank="rank" :home="false"></Rank>
      </v-col>
      <v-col cols="6" class="generalData" >
         <v-icon class="icon-cronometro generalData mr-4" size="25" ></v-icon>
         <span class="pt-1">{{ duration }}</span>
      </v-col>
      <v-col cols="6" class="generalData">
         <v-icon class="icon-calendario generalData mr-4" size="25" ></v-icon>
         <span class="pt-1">{{ available }}</span>
      </v-col>
   </v-row>
</template>

<script>

import Rank from '~/components/General/Rank.vue';
export default {
   components:{Rank},
   props:{
      // eslint-disable-next-line vue/require-default-prop
      rank:{
         type:Number,
         default:0
      },
      // eslint-disable-next-line vue/require-default-prop
      duration:{
         type:String,
         default:''
      },
      // eslint-disable-next-line vue/require-default-prop
      available:{
         type:String,
         default:''
      },

   }
}
</script>
