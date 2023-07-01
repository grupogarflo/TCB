<template>
  <v-card width="100%" class="rounded-lg tour-card flex d-flex flex-column" elevation="10" @click="goToToursDetails(item)">

      <v-card-text class="tour-card-content flex d-flex flex-column align-stretch">

         <div class="tour-card-img d-block ">
            <img :src="item.full_photo_path" />
            <v-chip color="white" class="text-center ml-5 py-1 discount">
               -{{ item.discount  | discount}}
            </v-chip>
            <v-chip v-if="item.is_private" color="#00A6CE" class="d-block private_button rounded-pill text-center mx-auto">
               {{ $t('general.private') }}
            </v-chip>
         </div>



         <div class=" tour-card-title mt-3 mb-4 ">{{ item.name | truncate(80, '...') }}</div>


         <div class="tour-card-data flex d-flex flex-column flex-grow-1  flex-shrink-1  align-stretch mb-3">
            <Rank class="tour-card-rank mb-3 d-flex flex-row flex-grow-1  flex-shrink-1" :rank="item.rank"></Rank>
            <div class="tour-card-description d-flex flex-row flex-grow-1  flex-shrink-1" v-if="item.description_small!==null" v-html="item.description_small"></div>
            <div class="  my-1 tour-card-duration ">
                        <span class="available ">{{ $t('general.available') }}: </span>
                        <span class=""> {{ item.duration }} </span>
                  </div>
         </div>





      </v-card-text>



      <v-card-actions class="px-5 py-3 mb-auto backActions ">
         <v-row justify="space-between" align="end">
            <v-col cols="6" class="tour-card-text-action">{{ $t('general.from') }}: <span class="d-block">{{  price | currencyFormat(currency) }}</span></v-col>
            <v-col cols="6" class="text-right">
               <v-btn class="tour-card-btn-action py-4 px-4 elevation-0 rounded-pill"  small @click="goToToursDetails(item)">{{ $t('general.book_now') }}</v-btn>

            </v-col>
         </v-row>
      </v-card-actions>
  </v-card>

</template>

<script>

import Rank from './Rank.vue';
export default {
   components: { Rank },
   props:{
      // eslint-disable-next-line vue/require-default-prop
      item:Object,

   },
   computed:{



      price(){

         if(!this.item.is_private)
         {
            if (this.$store.getters['booking/language'] ===2){
               return this.item.price_real_adult;
            }
            else{
               return  this.item.real_adult_mxn
            }
         }
         else{

            // eslint-disable-next-line no-lonely-if
            if (this.$store.getters['booking/language'] ===2){
               return parseInt(this.item.rates[0].rate_from_real);
            }
            else{
               return parseInt(this.item.rates[0].rate_from_real_mxn);
            }
         }
      },

      priceChild(){
         if (this.$store.getters['booking/language'] ===2){
            return this.item.price_real_child;
         }
         else{
            return  this.item.price_real_child_mxn
         }
      },

      currency(){
         if (this.$store.getters['booking/language'] ===2) return 'USD'
         return 'MXN'
      }


   },
   methods:{
      goToToursDetails(item){
         this.$store.commit('booking/destinationTours', {
            url: item.url,
            id: item.id,
         })
         this.$router.push(this.localePath({
            name:'slug',
            params:{
               slug:item.url
            }
         }))

      }
   }

}
</script>
