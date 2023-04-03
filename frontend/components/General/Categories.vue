<template>
   <div>
      <v-row>
         <v-col v-for="(item, i) in items " :key="i" cols="6"  md="3">
            <v-btn block outlined elevation="0" :class="['rounded-lg', 'no-text-transform', 'category-btn', !mobile ? 'px-16':'', !mobile ? 'py-9':'py-6',  item.class_apply ] " @click="goTo(item.url)">
               {{ item.name }}
            </v-btn>

         </v-col>
      </v-row>
   </div>
</template>


<script>
export default {

   data(){
      return {
         items:{},
         class:''
      }
   },
   computed:{
      mobile(){
            return this.isMobile();
      }
   },

   created(){
      this.init();
   },


   methods:{
      async init() {
         try {
            await this.$axios
               .post('/getAllCategories', {

                  idioma: this.$store.getters['booking/language'],
               })
               .then((resp) => {
                  this.items = resp.data.data



                  this.loading = false
               })
            } catch (e) {
               this.error = e.response.data.message

            }
      },

      goTo(item){
         this.$router.push(this.localePath({
            name:'tours-slug',
            params:{
               slug:item
            }
         }))

      }


   }
}
</script>
