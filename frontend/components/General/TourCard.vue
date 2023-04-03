<template>
  <v-card width="100%" class="rounded-lg tour-card" elevation="10" @click="goToToursDetails(item)">
      <v-img class="" height="207" :src="item.full_photo_path"></v-img>
      <v-divider :class="[(item.category!=null) ? item.category.class_apply: '','py-1']"></v-divider>
      <v-card-text class="px-4  py-0 mt-3 tour-card-content">
         <div class=" d-flex flex-column justify-space-between ">

               <div :class="[ (item.category!=null) ? item.category.class_apply+'-text': '', 'flex-grow-1', 'flex-shrink-0',  'tour-card-title', 'mb-4']">{{ item.name }}</div>


               <div class="d-block">
                  <Rank class="tour-card-rank mb-3 " :rank="item.rank"></Rank>




                           <p class="mb-0">{{ $t('general.adults') }}: {{  price | currencyFormat(currency) }}</p>
                           <p class="mb-0" v-if="item.price_fake_child>0 ">{{ $t('general.children') }}: {{ priceChild | currencyFormat(currency) }}</p>

                           <p class="  my-5 tour-card-duration d-block ">{{ $t('general.duration') }}: {{ item.duration }} </p>



               </div>


         </div>

      </v-card-text>
      <v-card-actions class="px-4 pb-5 mt-1">
         <div class="d-block tour-card-btn">
                  <v-btn class="tour-card-btn-action py-4 px-4 elevation-0"  small @click="goToToursDetails(item)">{{ $t('general.book_now') }}</v-btn>
               </div>
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
