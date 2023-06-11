<template>
  <div id="homePage">
      <div class="sliderCo">
         <img src="/images/homeBanners/vista-al-mar-desde-tulum.jpg" :height="imgHeight" width="100%" />

            <div class="search_container">
               <v-container>
                     <v-row justify="center">
                        <v-col cols="12" sm="10" md="8">
                           <div class="search_engine py-10">
                              <SearchEngine></SearchEngine>
                           </div>
                        </v-col>
                     </v-row>
                  </v-container>
            </div>
      </div>

      <v-container class="sliderPosition">
         <v-row justify="center">
            <v-col cols="12" sm="10" md="8">
               <Slide :items="items"></Slide>

            </v-col>
         </v-row>
      </v-container>

      <v-container class="py-10">
         <SectionTitle :title-text="$t('home.title1')" title="1"></SectionTitle>
         <Paragraph :text="$t('home.paragraph1')"></Paragraph>

         <destinations></destinations>

         <div id="home_tours_cards" class="mt-16 mb-16">
               <v-row justify="start" >
                     <v-col v-for="(item, i) in homeTourList" :key="i" sm="4" md="3" class="d-flex">
                        <TourCard :item="item"></TourCard>
                     </v-col>
               </v-row>
               <v-row justify="center" class="mt-15">
                  <v-col cols="12" md="6" class="text-center">
                     <nuxt-link :to="localePath({
                           name:'tours-slug',
                           params:{
                              slug:all_tours.url
                           }
                        })" class="all-toursHome px-4">
                     {{ all_tours.name }}
                  </nuxt-link >
                  </v-col>
               </v-row>

         </div>




         <SectionTitle :title-text="$t('home.subtitle2')" title="2"></SectionTitle>

         <Paragraph :text="$t('home.paragraph2')"></Paragraph>

         <SectionTitle :title-text="$t('home.subtitle3')" title="2"></SectionTitle>

         <Paragraph :text="$t('home.paragraph3')"></Paragraph>

      </v-container>

  </div>
</template>

<script>


   import SearchEngine from '~/components/General/SearchEngine.vue';
   import Slide from '~/components/Home/Slide';
   import SectionTitle from '~/components/General/SectionTitle.vue';
   import Paragraph from '~/components/General/Paragraph.vue';
   import TourCard from '~/components/General/TourCard.vue';
   import Destinations from '~/components/Home/Destinations'
  // import Categories from '~/components/General/Categories.vue';


   export default {
      name: 'IndexPage',
      components:{  SearchEngine, Slide, SectionTitle, Paragraph, TourCard, Destinations },

      middleware:['clearStore'],
      // eslint-disable-next-line vue/no-unused-components
      data(){
         return{
               homeTourList:[],
               items:[],
               backgroundImage:''
         }
      },
      computed:{

         tours_state(){
            return this.$store.getters["booking/tours"];
         },
         all_tours(){
            return {
               url:this.$t('menu.categories.url'),
               name:this.$t('menu.categories.name')
            }
         },

         imgHeight(){
            if (this.isMobile) {
               return 'auto';
            } else {
               return 500
            }
         }
      },

      created(){
         this.$store.commit('booking/resetStoreData', {})
         this.init();
         this.getHomeToursList()
      },

      methods:{
         async getHomeToursList() {
            try {
               await this.$axios
                  .post('/getTourListHome', {
                     idioma: this.$store.getters['booking/language'],
               }).then((resp) => {
                     this.homeTourList = resp.data.data
                     this.loading = false
               })
            } catch (e) {
               this.error = e.response.data.message
            }
         },

         init(){
             const language = (this.$i18n.locale==='es') ? 1 : 2;
             const currency = (this.$i18n.locale==='es') ? 'MXN' : 'USD';
             this.$store.dispatch('booking/setLanguage',language);
             this.$store.dispatch('booking/setCurrency',currency);

             this.$axios
             .post('/getBannerHome',{ idioma: this.$store.getters['booking/language'], })
             .then(response=>{
                this.items= response.data.data;
                this.showLoading = false;
             })
             .catch(error=>{
                this.error = error.response.data.message;
                // console.log('error' + error);
             });
         }
      }
   }
</script>


<style lang="scss" scoped>

.search_container{

   position:relative;
   z-index: 99;
   margin-top:  -475px;

}
.v-menu{
   position: relative;
   z-index: 999;
}
</style>
