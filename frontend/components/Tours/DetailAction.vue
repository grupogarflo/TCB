<!-- eslint-disable no-unused-vars -->
<!-- eslint-disable no-unused-vars -->
<template>
   <div>
      <v-row v-if="!mobile" class="mb-4">
         <v-col v-if="video !== null && video !== '' && video !== 'n/a' " cols="4" class="text-left" >
            <div class="iconWrap d-flex mx-2"  @click="openVideo"  >
               <a>
                  <v-icon class="icon2-video generalData mr-2" size="25" ></v-icon>
                  <span class="pt-2">{{ $t('general.see_video') }}</span>
               </a>
            </div>
         </v-col>
         <v-col cols="4" class="text-center" >
            <div class="iconWrap d-flex mx-2">
               <input id="example" v-model="urlShare" ref="message" type="hidden" />
               <a  @click="copyURL" >
                  <v-icon class="icon2-share generalData mr-2" size="25" ></v-icon>
                  <span class="pt-2">{{ $t('general.share') }}</span>
               </a>
            </div>
         </v-col>
         <v-col cols="4" class="text-right">
            <div class="iconWrap d-flex mx-2">
               <a href="https://api.whatsapp.com/send?phone=529841062511" target="_blank">
                  <v-icon class="icon2-whatsapp generalData mr-2" size="25" ></v-icon>
                  <span class="pt-2">{{ $t('general.chat') }}</span>
               </a>
            </div>
         </v-col>
         <v-col cols="12" class="px-0 py-0">
            <v-alert
               v-model="snackbar"

               color="amber lighten-1 elevation-0"
               rounded="pill"
               elevation="0"
               dense
               class="text-center black--text font-weight-bold mt-0 py-3 mx-auto my-0"
               width="95%"

            >
               {{ $t('general.copied') }}


            </v-alert>
         </v-col>
      </v-row>
      <div class="tour-detail">
         <div v-if="!item.is_private" :class="['blue-rectangle', (mobile) ? 'px-4':'px-10', (mobile)? 'py-5':'py-10','text-center']">
            <p>
               <span class="price-fake d-block">{{ $t('general.adults') }}: {{ adultFake | currencyFormat(currency) }}</span>
               <span class="price-fake d-block">{{ $t('general.children') }}: {{ childFake | currencyFormat(currency) }}</span>


            </p>
            <p class="mt-5">

               <span class="price-real d-block">{{ $t('general.adults') }}: {{  realAdult | currencyFormat(currency) }}</span>
               <span class="price-real d-block">{{ $t('general.children') }}:  {{ realChild  | currencyFormat(currency)}}</span>

            </p>




         </div>

         <div v-else :class="['blue-rectangle', (mobile) ? 'px-4':'px-10', (mobile)? 'py-5':'py-10', 'text-center']">
            <p>
               <span class="price-fake d-block">{{ $t('general.from') }}: {{ adultFake | currencyFormat(currency) }}</span>

            </p>
            <p class="mt-5">

               <span class="price-real d-block ">{{ $t('general.from') }}: {{  realAdult | currencyFormat(currency) }}</span>
            </p>





         </div>


      </div>

      <v-row v-if="mobile" >
         <v-col cols="12" >
            <div class="icons d-flex flex-column flex-wrap justify-center align-stretch">
               <div v-if="video !== null && video !== '' && video !== 'n/a' " class="iconWrap d-flex mx-2"  @click="openVideo"  >
                  <a class="d-flex">
                     <v-icon class="icon2-video generalData mr-2" size="25" ></v-icon>
                     <span class="pt-2">{{ $t('general.see_video') }}</span>
                  </a>
               </div>
               <div class="iconWrap d-flex mx-2">
                  <input id="example" v-model="urlShare" ref="message" type="hidden" />
                  <a  @click="copyURL" class="d-flex" >
                     <v-icon class="icon2-share generalData mr-2" size="25" ></v-icon>
                     <span class="pt-2">{{ $t('general.share') }}</span>
                  </a>
               </div>
               <div class="iconWrap d-flex mx-2">
                  <a href="https://api.whatsapp.com/send?phone=529841062511" target="_blank" class="d-flex">
                     <v-icon class="icon2-whatsapp generalData mr-2" size="25" ></v-icon>
                     <span class="pt-2">{{ $t('general.chat') }}</span>
                  </a>
               </div>
            </div>
         </v-col>
         <v-col cols="12" class="px-0 py-0">
            <v-alert
               v-model="snackbar"

               color="amber lighten-1 elevation-0"
               rounded="pill"
               elevation="0"
               dense
               class="text-center black--text font-weight-bold mt-0 py-3 mx-auto my-0"
               width="95%"

            >
               {{ $t('general.copied') }}


            </v-alert>
         </v-col>
      </v-row>



        <!-- video -->
        <v-dialog v-model="dialogVideo" attach :width="videoWidth" class="pa-0 elevation-0"  content-class="elevation-0"  >
         <div class="px-1 px-md-10 wraperVideo">
               <v-btn
                  fab
                  small
                  dark
                  color="#EB008B"
                  depressed
                  :elevation="8"
                  class="button-closeVideo"
                  @click="dialogVideo = false"
               >
                  <v-icon>mdi-close</v-icon>
               </v-btn>


               <vimeo-player
                  ref="player"
                  :video-id="video"
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
      item:{},
      video:String
   },
   data(){

      return {
         dialogVideo:false,
         playerReady:false,
         height:360,
         urlShare:window.location.href,
         snackbar:false

      }
   },

   computed:{
      mobile(){
            return this.isMobile();
      },

      adultFake(){
         if (this.$store.getters['booking/language'] ===2){
            return (this.item.is_private) ? parseFloat(this.item.rates[0].rate_from_fake) : this.item.price_fake_adult;
         }
         else{
            return  (this.item.is_private) ? parseFloat(this.item.rates[0].rate_from_fake_mxn) : this.item.fake_adult_mxn
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
            return (this.item.is_private) ? parseFloat(this.item.rates[0].rate_from_real) :  this.item.price_real_adult;
         }
         else{
            return  (this.item.is_private) ? this.item.rates[0].rate_from_real_mxn : this.item.real_adult_mxn
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
      copyURL() {
         // eslint-disable-next-line no-unused-vars

         navigator.clipboard.writeText(this.urlShare);
         this.snackbar=true

         setTimeout(()=>{
            this.snackbar=false
         },1500)

      }
   }

}
</script>
<style scoped>
.iconWrap{
   cursor: pointer;

}
.iconWrap a{
   text-decoration: none !important;
   color: #00A6CE;
}

.icons{
   width: 100%;
   overflow:auto;
   height: 60px;

 }
</style>
