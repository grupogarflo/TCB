<template>
   <div class="slideHomeTop">

       <v-carousel
           :height="alto"
           hide-delimiters
           hide-delimiter-background
           >
         <v-carousel-item v-for="(item, index) in items" :key="index"
           class="text-center"
         >
           <NuxtLink
             v-if="item.url != '' && item.url != null"
             :to="item.url"
           >
             <v-img
               eager
               aspect-ratio="2"
               class="mx-auto"
               width="100%"
               :height="alto"
               :src="item.full_photo_path"
               :lazy-src="item.full_photo_path"
               :alt="item.alt"
             ></v-img>
           </NuxtLink>

           <v-img
               v-else
               eager
               aspect-ratio="2"
               class="mx-auto"
               width="100%"
               :height="alto"
               :src="item.full_photo_path"
               :lazy-src="item.full_photo_path"
               :alt="item.alt"
             ></v-img>
         </v-carousel-item>
       </v-carousel>
       <!--<v-img  height="650"  :src="require('~/assets/images/home/banner-home-chichen.jpg')" ></v-img>-->

       <!-- video
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


      -->

   </div>
</template>

<script>
export default {
  props:{
     items:Array,
     video:String
  },
 data(){
   return {
      showLoading: true,
      dialogVideo:false,
      playerReady:false,
      height:360

   };

 },
 computed:{

   mobile(){
            return this.isMobile();
      },
   alto(){
     if (this.mobile) {
      // alert('mobile')
      const width =  window.innerWidth;

       return width;
     } else {
       return 500
     }
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
   openVideo() {
         this.dialogVideo = true
      },
      onReady() {
         this.playerReady = true
      },
  }

}
</script>
<style lang="scss" scoped>
.alignIcon{
   margin-top: 10.5rem;
   cursor: pointer;
}

@media only screen and (max-width: 1263.98px){
   .alignIcon{
      margin-top: 9rem;
      cursor: pointer;
      //z-index: 90;
   }
}
</style>
