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
               v-else-if="typeof video !== 'object' && video !== null && video !== '' && video !== 'n/a' && index==0"
               eager
               aspect-ratio="2"
               class="mx-auto"
               width="100%"
               :height="alto"
               :src="item.full_photo_path"
               :lazy-src="item.full_photo_path"
               :alt="item.alt"
               @click="openVideo"
            >
               <v-container style="height: 10%;">
                  <v-row justify="center" align="center" style="height: 10%;" >
                     <v-col cols="5" md="2" class="text-center px-10 alignIcon" >
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 200 200" style="enable-background:new 0 0 200 200;" xml:space="preserve">
                           <style type="text/css">
	                           .st0{clip-path:url(#SVGID_00000154400207435928516630000004903932723039771581_);}
	                           .st1{fill:#FFFFFF;}
                           </style>
                           <g>
                              <defs>
                                 <rect id="SVGID_1_" width="200" height="200"/>
                              </defs>
                              <clipPath id="SVGID_00000065070939103592269360000003605451252844773818_">
                                 <use xlink:href="#SVGID_1_"  style="overflow:visible;"/>
                              </clipPath>
                              <g style="clip-path:url(#SVGID_00000065070939103592269360000003605451252844773818_);">
                                 <path class="st1" d="M133,96.1L91.5,65.9c-1.5-1.1-3.5-1.3-5.2-0.4c-1.7,0.9-2.7,2.6-2.7,4.5v60.4c0,1.9,1.1,3.6,2.7,4.5
                                    c0.7,0.4,1.5,0.5,2.3,0.5c1,0,2.1-0.3,2.9-1l41.5-30.2c1.3-1,2.1-2.5,2.1-4C135.1,98.5,134.3,97,133,96.1z"/>
                                 <path class="st1" d="M100,0C44.8,0,0,44.8,0,100c0,55.2,44.8,100,100,100c55.2,0,100-44.8,100-100C200,44.8,155.2,0,100,0z
                                    M100,183.3c-46,0-83.3-37.3-83.3-83.3c0-46,37.3-83.3,83.3-83.3c46,0,83.3,37.3,83.3,83.3C183.3,146,146,183.3,100,183.3z"/>
                              </g>
                           </g>
                        </svg>

                     </v-col>
                  </v-row>
               </v-container>

            </v-img>
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

   alto(){
     if (this.isMobile) {
       return 430;
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
