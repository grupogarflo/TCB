<template>
   <div class="tour-detail">
      <div class="blue-rectangle py-16 px-10">
            <p class="price-fake">{{ $t('general.adults') }}: {{ adultFake | currencyFormat(currency) }}</p>
            <p class="price-fake">{{ $t('general.children') }}: {{ childFake | currencyFormat(currency) }}</p>

            <p class="price-real">{{ $t('general.adults') }}: {{  realAdult | currencyFormat(currency) }}</p>
            <p class="price-real">{{ $t('general.children') }}:  {{ realChild  | currencyFormat(currency)}}</p>

            <p class="data">{{ $t('general.available') }}: {{ item.avaible }} </p>
            <p class="data">{{ $t('general.duration') }}:  {{ item.duration }} </p>


      </div>
      <div
         class="videoIcon d-flex justify-center align-center my-5" @click="openVideo"
            v-if="typeof item.video !== 'object' && item.video !== '' && item.video !== 'n/a'"
      >
            <v-icon color="#1A2D4E" large size=""
                >mdi-video-outline</v-icon
               ><span>{{ $t('general.seeVideo') }}</span>

      </div>
      <div class="buttons">

         <v-btn block  class="my-3 elevation-0 detail" @click="goTop()">{{ $t('general.book_now') }}</v-btn>

      </div>

      <v-dialog v-model="dialogVideo" attach :width="videoWidth" class="pa-0 elevation-0"  content-class="elevation-0"  >
         <div class="px-1 px-md-10 wraperVideo">
               <v-btn
                  fab
                  small
                  dark
                  color="#E1AC66"
                  depressed
                  :elevation="8"
                  class="button-closeVideo"
                  @click="dialogVideo = false"
               >
                  <v-icon>mdi-close</v-icon>
               </v-btn>


               <vimeo-player
                  ref="player"
                  :video-id="item.video"
                  :autoplay="true"
                  @ready="onReady"
                  class="embeded-video px-3"
                  :player-height="height"
               />

         </div>


      </v-dialog>
   </div>
</template>


<script>
export default {

   props:{
      // eslint-disable-next-line vue/require-default-prop
      item:{}
   },
   data(){

      return {
         dialogVideo:false,
         playerReady:false,
         height:360
      }
   },

   computed:{

      adultFake(){
         if (this.$store.getters['booking/language'] ===2){
            return this.item.price_fake_adult;
         }
         else{
            return  this.item.price_fake_adult_mxn
         }
      },
      childFake(){
         if (this.$store.getters['booking/language'] ===2){
            return this.item.price_fake_child;
         }
         else{
            return  this.item.price_fake_child_mxn
         }
      },
      realAdult(){
         if (this.$store.getters['booking/language'] ===2){
            return this.item.price_real_adult;
         }
         else{
            return  this.item.price_real_adult_mxn
         }
      },
      realChild(){
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
      },

      videoWidth(){
         return this.isMobile() ? '90%' :'1024'
      },




   },
   watch: {
    dialogVideo(newVal, oldVal) {
      if (!this.dialogVideo) {
        this.$refs.player.pause()
      }
    },
  },

   methods:{
      goTop() {
         this.$nuxt.$emit('bookEvent');

      },

      openVideo() {
         this.dialogVideo = true
      },
      onReady() {
         this.playerReady = true
      },
   }

}
</script>
