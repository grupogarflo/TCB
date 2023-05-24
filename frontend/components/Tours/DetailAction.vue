<template>
   <div class="tour-detail">
      <div class="blue-rectangle mt-16 pt-10">
         <p>
            <span class="price-fake">{{ $t('general.adults') }}: {{ adultFake | currencyFormat(currency) }}</span>

            <span class="price-real ml-2">{{ $t('general.adults') }}: {{  realAdult | currencyFormat(currency) }}</span>
         </p>
         <p>
            <span class="price-fake">{{ $t('general.children') }}: {{ childFake | currencyFormat(currency) }}</span>

            <span class="price-real ml-2">{{ $t('general.children') }}:  {{ realChild  | currencyFormat(currency)}}</span>

         </p>




      </div>


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
