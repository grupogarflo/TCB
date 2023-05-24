<template>
  <v-card width="100%" class="rounded-lg tour-card  " elevation="10" @click="goToToursDetails(item)">

      <v-card-text class="px-0 tour-card-content pt-5 px-5">

         <v-img class="rounded-md" height="207" :src="item.full_photo_path"></v-img>

         <div class=" d-flex flex-column justify-space-between mt-2 ">

               <div :class="[ 'flex-grow-1', 'flex-shrink-0',  'tour-card-title', 'mb-4']">{{ item.name }}</div>


               <div class="d-flex flex-column align-center flex-grow-1, flex-shrink-1  tour-card-data">
                  <Rank class="tour-card-rank mb-3 " :rank="item.rank"></Rank>
                  <p v-if="item.description_small!==null" v-html="item.description_small"></p>
                  <p class="  my-1 tour-card-duration d-block ">
                     <span class="available d-block">{{ $t('general.available') }}: </span>
                     <span class="d-block"> {{ item.duration }} </span>
                  </p>



               </div>


         </div>

      </v-card-text>
      <v-spacer></v-spacer>
      <v-card-actions class="px-5 py-3 mb-auto backActions">
         <v-row justify="space-between" align="baseline">
            <v-col cols="8" class="tour-card-text-action">{{ $t('general.from') }}: {{  price | currencyFormat(currency) }}</v-col>
            <v-col cols="4">
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
         if (this.$store.getters['booking/language'] ===2){
            return this.item.price_fake_adult;
         }
         else{
            return  this.item.fake_adult_mxn
         }
      },

      priceChild(){
         if (this.$store.getters['booking/language'] ===2){
            return this.item.price_fake_child;
         }
         else{
            return  this.item.price_fake_child_mxn
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
