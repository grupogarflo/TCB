<template>
    <div>

        <v-carousel  cycle
            :height="alto"
            hide-delimiters
            hide-delimiter-background
            show-arrows-on-hover>
          <v-carousel-item v-for="(item, index) in items" :key="index"

          >
            <NuxtLink
              v-if="item.url != '' && item.url != null"
              :to="item.url"
            >
              <v-img
                eager
                aspect-ratio="2"
                width="auto"
                max-height="500"
                :src="item.full_photo_path"
                :lazy-src="item.full_photo_path"
                :alt="item.alt"
              ></v-img>
            </NuxtLink>
            <v-img
                v-else
                eager
                aspect-ratio="2"
                width="auto"
                max-height="500"
                :src="item.full_photo_path"
                :lazy-src="item.full_photo_path"
                :alt="item.alt"
              ></v-img>
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
        return 468
      }
    }
  },

}
</script>
