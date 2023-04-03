<template>
  <div>
    <Slide :items="items"></Slide>
    <div class="search_engine py-10">
         <SearchEngine></SearchEngine>
    </div>
    <v-container class="py-10">
        <SectionTitle :title-text="$t('home.title1')"></SectionTitle>
        <Paragraph :text="$t('home.paragraph1')"></Paragraph>

        <div id="home_tours_cards" class="mt-16 mb-16">
            <v-row justify="start" >
                  <v-col v-for="(item, i) in homeTourList" :key="i" sm="4" md="3" class="d-flex flex-grow-1 flex-shrink-1 align-stretch">
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

        <Categories class="my-16"></Categories>


        <SectionTitle :title-text="$t('home.subtitle2')"></SectionTitle>
        <div class="my-16">
            <v-row>
               <v-col class="text-center px-2" cols="6" md="3">
                  <img src="/images/icons/piramide.svg" />
                  <p class="icons-text">{{ $t('home.icons.tours') }}</p>
               </v-col>
               <v-col class="text-center px-2" cols="6" md="3">
                  <img src="/images/icons/guia.svg" />

                  <p class="icons-text">{{ $t('home.icons.guides') }}</p>
               </v-col>
               <v-col class="text-center px-2" cols="6" md="3">
                  <img src="/images/icons/camioneta.svg" />
                  <p class="icons-text">{{ $t('home.icons.transportation') }}</p>
               </v-col>
               <v-col class="text-center px-2" cols="6" md="3">
                  <img src="/images/icons/grupos.svg" />
                  <p class="icons-text">{{ $t('home.icons.groups') }}</p>
               </v-col>

            </v-row>
        </div>

        <Paragraph :text="$t('home.paragraph2')"></Paragraph>

    </v-container>
  </div>
</template>

<script>

import Slide from '~/components/Home/Slide';
import SearchEngine from '~/components/General/SearchEngine.vue';
import SectionTitle from '~/components/General/SectionTitle.vue';
import Paragraph from '~/components/General/Paragraph.vue';
import TourCard from '~/components/General/TourCard.vue';
import Categories from '~/components/General/Categories.vue';

export default {
  name: 'IndexPage',
  components:{ Slide, SearchEngine, SectionTitle, Paragraph, TourCard, Categories },

  middleware:['clearStore'],

  // eslint-disable-next-line vue/no-unused-components

  data(){
      return{
            homeTourList:[],
            items:[]
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
               })
               .then((resp) => {
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
