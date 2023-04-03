<template>
   <div>

<v-carousel  cycle
    :height="alto"
    hide-delimiters
    hide-delimiter-background
   :show-arrows="false"

   >
  <v-carousel-item v-for="(item, index) in items" :key="index">
      <v-sheet
         :class="['py-16',item.class]"
          height="100%"
          tile

        >
          <div class="d-flex fill-height justify-center align-center">
            <div :class="[item.class+'-title', !mobile ? 'text-h2':'text-h4', 'font-weight-bold text-capitalize']">
              {{ item.title }}

            </div>

          </div>
      </v-sheet>

  </v-carousel-item>
</v-carousel>

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
         return this.isMobile()
      },
    alto(){
      if (this.isMobile()) {
        return 108;
      } else {
        return 313
      }
    },


  },

}
</script>
