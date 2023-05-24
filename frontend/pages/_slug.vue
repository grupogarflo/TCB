<template>
   <div>

      <content-tour  v-if="pageType=='tour'"></content-tour>
      <content-category v-if="pageType=='category'"></content-category>

      <!--
      <client-only>
         <Slide :items="itemsGallery"></Slide>

            <div id="search-engine"  class="search_engine py-10">

               <SearchEngine  :openPax="openPax"> </SearchEngine>
            </div>



         <v-container class="my-5">
            <v-row>
               <v-col cols="12" md="7">
                  <SectionTitle :title-text="name"></SectionTitle>
                  <div class="mt-3" v-html="description"></div>
               </v-col>
               <v-col cols="12" md="5">
                  <detail-action :item="item"></detail-action>
               </v-col>
            </v-row>
         </v-container>
         <div :class="[!mobile ? 'background-grey':'', 'py-5']">
            <v-container>
               <v-row justify="space-between">
                  <v-col cols="12" md="3">
                     <SectionTitle :title-text="$t('tours.includes')"></SectionTitle>
                     <div v-html="include"></div>
                  </v-col>
                  <v-col sm="12" md="3">
                     <SectionTitle :title-text="$t('tours.recommendations')"></SectionTitle>
                     <div v-html="suggestion"></div>
                  </v-col>
                  <v-col sm="12" md="3">
                     <SectionTitle :title-text="$t('tours.notes')"></SectionTitle>
                     <div v-html="note"></div>
                  </v-col>
               </v-row>
               <Categories v-if="mobile" class="mb-10"></Categories>
            </v-container>
         </div>


      </client-only>

      -->
   </div>

</template>

<script>
import ContentTour from '../components/Tours/ContentTour.vue';
import ContentCategory from '../components/Categories/ContentCategory.vue';

// import SearchEngine from '~/components/General/SearchEngine.vue'
// import SectionTitle from '~/components/General/SectionTitle.vue'
// import Slide from '~/components/Home/Slide.vue'
// import DetailAction from '~/components/Tours/DetailAction.vue'
// import Categories from '~/components/General/Categories.vue';

export default {
  components: { ContentTour,ContentCategory },
  /*
   components:{
      Slide,
      SearchEngine,
      SectionTitle,
      DetailAction,
      Categories

   },
   */

   data(){
      return {

         pageType: null,
         contentData:null,
      }
   },
   computed:{
      mobile(){
            return this.isMobile();
      },
      language(){
         return this.$store.getters['booking/language'];
      }

   },



   created(){
      // this.getDataTour();

      this.searchPageType();
   },
   /*
   mounted(){
      this.getDataTour();

      this.$nuxt.$on('bookEvent',()=>{

         document.getElementById("search-engine").scrollIntoView({behavior:"smooth"});
         this.openPax=true

      })
   },
*/


   methods:{
      async getDataTour() {
         try {
         await this.$axios
            .post('/getTourData', {
               id: this.$route.params.slug,
               idioma: this.$store.getters['booking/language'],
            })
            .then((resp) => {
               // this.data = resp.data
               /*
               this.img = resp.data.data[0].img

               this.subtitle = resp.data.data[0].sub_title


               */
               this.item = resp.data.data[0];
               this.name = resp.data.data[0].name
               this.itemsGallery = resp.data.gallery
               this.description = resp.data.data[0].description
               this.suggestion = resp.data.data[0].bring
               this.note = resp.data.data[0].note
               this.include = resp.data.data[0].includes
               this.img = resp.data.data[0].full_photo_path
               /*

               this.notInclude = resp.data.data[0].not_include

               this.price = resp.data.price

               this.metaTitle = resp.data.data[0].meta_title
               this.metaDescription = resp.data.data[0].meta_description
               this.avaible = resp.data.data[0].avaible
               this.duration = resp.data.data[0].duration
               this.videoID = resp.data.data[0].video
               this.showMe = true
               this.dialog = false
               this.publico = resp.data.data[0].public
               this.bring = resp.data.data[0].bring
               this.descriptionSmall = resp.data.data[0].description_small
               // console.log(this.publico + ' <<<<------')
               if (Object.keys(this.itemsGallery).length > 0) {
               this.loadingGallery = false
               } */

               this.$store.commit('booking/dataTours', {
                  id: resp.data.data[0].tour_id,
                  name: this.name,
                  url: resp.data.data[0].url,
                  img: this.img,
                  duration: resp.data.data[0].duration,
               })
               this.reRender = this.reRender + 1
            })
         } catch (err) {
            this.error = err.response.data.message
            // console.log('error' + e)
         }
      },


      async searchPageType(){
            await this.$axios.post('/pageType',{url:this.$route.params.slug, language: this.language})
                  .then(response =>{
                     // alert('response');
                     console.log(response.data);
                     this.pageType = response.data.val;
                     // this.contentData = response.data.data

                  }).catch(response =>{
                     console.log('error', response);
                  })

      }





   }
}
</script>
