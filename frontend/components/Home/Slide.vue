<template>
    <div class="slideHomeTop">

        <v-carousel  cycle
            :height="alto"
            :hide-delimiters="!mobile"
            hide-delimiter-background
            :show-arrows="!mobile"
            class="rounded-lg pa-1"
            >
          <v-carousel-item v-for="(item, index) in items" :key="index"
            class="text-center rounded-lg"
          >
            <NuxtLink
              v-if="item.url != '' && item.url != null"
              :to="item.url"
            >
              <img
                eager
                class="mx-auto rounded-lg img-class"
                width="99%"
                max-height="300"
                min-height="150"
                :src="item.full_photo_path"
                :lazy-src="item.full_photo_path"
                :alt="item.alt"
              />
            </NuxtLink>
            <img
                v-else
                eager
                class="mx-auto rounded-lg img-class"
                width="99%"
                max-height="300"
                min-height="150"
                :src="item.full_photo_path"
                :lazy-src="item.full_photo_path"
                :alt="item.alt"
              />
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
      mobile(){
            return this.isMobile();
      },
    alto(){

      if (this.mobile) {
        return 175;
      } else {
        return 300
      }
    }
  },

}
</script>
