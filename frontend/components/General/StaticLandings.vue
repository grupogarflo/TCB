<template>
   <div  :class="['py-16',items.class]">
      <v-container>
         <v-row>
            <v-col sm="12" class="text-center">

                  <h1 :class="['text-h4 font-weight-bold text-capitalize']">
                     {{ items.title }}
                  </h1>
                  <h2 class="text-h6">{{ items.subtitle }}</h2>

            </v-col>
         </v-row>
      </v-container>

   </div>
</template>

<script>
export default {
   props:{
      items:Object
   },
  data(){
    return {
      showLoading: true,

    };

  },
  computed:{

    alto(){
      if (this.isMobile) {
        return 313;
      } else {
        return 313
      }
    },


  },

}
</script>
