<template>
   <div class="slideHomeTop2">

       <v-carousel  cycle
           :height="alto"
           hide-delimiters
           hide-delimiter-background
           :show-arrows="false"
           >
         <v-carousel-item v-for="(item, index) in items" :key="index"
           class="text-center"
         >
           <v-img

               eager
               aspect-ratio="2"
               class="mx-auto  align-center"
               width="100%"
               max-height="500"
               :src="item.full_photo_path"
               :lazy-src="item.full_photo_path"
               :alt="item.name"
             >
               <v-container>
                  <v-row>
                     <v-col cols="6" class="text-left">
                        <div class="textDestinationHeader">{{ item.name }}</div>
                     </v-col>
                  </v-row>
               </v-container>
            </v-img>
         </v-carousel-item>
       </v-carousel>
       <!--<v-img  height="650"  :src="require('~/assets/images/home/banner-home-chichen.jpg')" ></v-img>-->
   </div>
</template>

<script>
export default {
  props:{
     items:Array
  },
 data(){
   return {
     showLoading: true,

   };

 },
 computed:{

   alto(){
     if (this.isMobile) {
       return 'auto';
     } else {
       return 500
     }
   }
 },

}
</script>
