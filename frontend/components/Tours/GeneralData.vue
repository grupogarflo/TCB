<template>
   <v-row>
      <v-col cols="12">
         <Rank :rank="rank" :home="false"></Rank>
      </v-col>
      <v-col cols="12" class="generalData" >
         <div class="d-flex flex-row flex-wrap justify-start align-start">
            <div class="d-flex mr-5 mt-4">
               <v-icon class="icon2-duracion generalData mr-2" size="25" ></v-icon>
               <span class="pt-2">{{ duration }}</span>
            </div>
            <div class="d-flex mr-5 mt-4 ">
               <v-icon class="icon2-disponibilidad generalData mr-2" size="25" ></v-icon>
               <span class="pt-2">{{ available }}</span>
            </div>

            <div class="d-flex mr-5 mt-4">
               <v-icon class="icon2-idiomas generalData mr-2" size="25" ></v-icon>
               <span class="pt-2">{{ $t('tours.languages') }}</span>
            </div>
            <div class="d-flex  mr-5 mt-4">
               <v-icon class="icon2-voucher generalData mr-2" size="25" ></v-icon>
               <span class="pt-2">{{ $t('tours.digitalBoucher') }}</span>
            </div>
            <div class="d-flex mr-5 mt-4 " v-if="id!==9 && id!==4">
               <v-icon class="icon2-transportacion generalData mr-2" size="25" ></v-icon>
               <span class="pt-2">{{ $t('tours.transportation') }}</span>
            </div>



         </div>
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
      id:{
         type:Number,
         default:0
      }

   }
}
</script>
