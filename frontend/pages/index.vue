<template>
   <div id="homePage">
      <client-only>
         <div class="sliderCo">
            <div v-if="$i18n.locale!=='es'">
               <img v-if="!mobile" src="/images/homeBanners/default-web-eng_.jpg" :height="imgHeight" width="100%" />
               <img v-else src="/images/homeBanners/default-mobile-eng.jpg" :height="imgHeight" width="100%" />
            </div>
            <div v-else>
               <img v-if="!mobile" src="/images/homeBanners/default-web-esp.jpg" :height="imgHeight" width="100%" />
               <img v-else src="/images/homeBanners/default-mobile-esp_.jpg" :height="imgHeight" width="100%" />

            </div>

               <div class="search_container" v-if="this.$i18n.locale==='es'">
                  <v-container>
                        <v-row justify="center">
                           <v-col cols="12" sm="10" md="8">
                              <div class="search_engine py-10">
                                 {{ showsearch }}
                                 <SearchEngine></SearchEngine>
                              </div>
                           </v-col>
                        </v-row>
                     </v-container>
               </div>
         </div>

         <v-container class="sliderPosition">

            <v-row justify="center" no-gutters>
               <v-col cols="12" sm="12" md="8">
                  <Slide :items="items"></Slide>

               </v-col>
            </v-row>
         </v-container>

         <v-container>

            <!-- <v-row v-if="$i18n.locale==='es'" class="mb-15">
               <v-col cols="12" >
                  <p class="text-center">
                     <span class="meses-sin-intereses d-block"> HASTA EN 18 MESES SIN INTERESES</span>
                     <span class="d-block meses-sin-intereses-down">Con cualquier banco, con todas las tarjetas *</span>
                  </p>


               </v-col>
               <v-col cols="12" class="text-center mt-5">
                  <img src="/images/homeBanners/bbva.svg"  :width="(!mobile) ? '7%': '15%' " :class="(!mobile) ? 'mx-5' : 'mx-2 my-2'"/>
                  <img src="/images/homeBanners/santander.svg"  :width="(!mobile) ? '14%': '26%' " :class="(!mobile) ? 'mx-5' : 'mx-2 my-2'" />
                  <img src="/images/homeBanners/citibanamex.svg"  :width="(!mobile) ? '14%' : '26%' " :class="(!mobile) ? 'mx-5' : 'mx-2 my-2'" />
                  <img src="/images/homeBanners/banorte.svg"  :width="(!mobile) ? '14%': '26%' " :class="(!mobile) ? 'mx-5' : 'mx-2 my-2'"/>
                  <img src="/images/homeBanners/scotiabank.svg"  :width="(!mobile) ? '14%':'26%' " :class="(!mobile) ? 'mx-5' : 'mx-2 my-2'"/>
                  <img src="/images/homeBanners/hsbc.svg"  :width="(!mobile) ? '9%':'17%' " :class="(!mobile) ? 'mx-5' : 'mx-2 my-2'" />


               </v-col>
            </v-row> -->

            <SectionTitle :title-text="$t('home.title1')" title="1"></SectionTitle>
            <Paragraph :text="$t('home.paragraph1')"></Paragraph>

            <destinations class="mb-10"></destinations>


            <SectionTitle :title-text="$t('home.subtitle2')" title="2" ></SectionTitle>

               <Paragraph :text="$t('home.paragraph2')"></Paragraph>

            <div id="home_tours_cards" class="mt-16 mb-16">
                  <v-row justify="start" >
                        <v-col v-for="(item, i) in homeTourList" :key="i" cols="12" sm="6" md="4" lg="3" class="d-flex">
                           <TourCard :item="item"></TourCard>
                        </v-col>
                  </v-row>
                  <v-row justify="center" class="mt-15">
                     <v-col cols="12" md="6" class="text-center">
                        <nuxt-link :to="localePath({
                              name:'slug',
                              params:{
                                 slug:all_tours.url
                              }
                           })" class="all-toursHome px-4">
                        {{ all_tours.name }}
                     </nuxt-link >
                     </v-col>


                  </v-row>
                  <v-row justify="center" class="mt-15" v-if="this.$i18n.locale!=='es'">
                     <v-col cols="8" md="3" class="text-center">
                        <v-btn depressed class="bookBtn rounded-lg py-6" block

                           @click="openVentrata" >
                           {{ $t('general.book_now') }}
                        </v-btn>
                     </v-col>
                  </v-row>

            </div>






            <SectionTitle :title-text="$t('home.subtitle3')" title="2"></SectionTitle>

            <!--<Paragraph :text="$t('home.paragraph3')"></Paragraph>-->
            <div v-html="parrafo"></div>

         </v-container>
      </client-only>
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
               backgroundImage:'',
               titleMeta:'',
               descriptionMeta:'',
               keywordsMeta:'',
               // showsearch: this.$i18n.locale==='es'
               // imgMeta:require('~/assets/images/home/cancunbay-chichen-itza.jpg')
         }
      },

      head(){

         return {

               // eslint-disable-next-line object-shorthand
               title: this.titleMeta,
               htmlAttrs: {
                  lang: this.$i18n.locale
               },
               meta:[
                  {
                     hid: 'og:description',
                     name: 'og:description',
                     // content: context.$i18n.messages[context.$i18n.locale].meta.home.description
                     content: this.descriptionMeta


                  },
                  {
                     hid: 'og:keywords',
                     name: 'og:keywords',
                     content: this.keywordsMeta


                  },


                  {
                     hid: 'og:title',
                     name: 'og:title',
                     content: this.titleMeta


                  },
                 /*
                 {
                     hid: 'og:image',
                     name: 'og:image',
                     content:this.imgMeta
                  }
                  */

               ],

            }

      },

      computed:{


         parrafo(){
            return this.$t('home.paragraph3');
         },
         mobile(){
            return this.isMobile();
         },
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
               return 450;
            } else {
               return 500
            }
         }
      },

      created(){
         this.$store.commit('booking/resetStoreData', {})
         this.titleMeta = this.$t('meta.home.title');
         this.descriptionMeta= this.$t('meta.home.description');
         this.keywordsMeta=this.$t('meta.home.description');
         this.init();
         this.getHomeToursList()



      },

      mounted(){

         /*
         alert(this.$i18n.locale==='es');
         if(this.$i18n.locale==='es'){
               this.showsearch= false;
            }
            else{
               this.showsearch = true;
            }
            */
      },
      methods:{
         openVentrata(){

            /*
            const ventrata1 = (this.tourVentrataId!=null) ? {
               "lang":"en",
               "referrer": "cancunbay",
               "productID":this.tourVentrataId
            } :{
               "lang":"en",
               "referrer": "cancunbay"
            }
            // const ventrataVars =
            const name = window.location.href
            if ((this.$i18n.locale==='es')) {
               // español

               this.clickCard()
            }else{
               // ingles
               window.Ventrata(ventrata1)
            }
            */

            window.Ventrata({
               "lang":"en",
               "referrer": "cancunbay"
            })


         },
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

@media only screen and (max-width: 1263.98px){

   .search_container{

      position:relative;
      z-index: 1;
      margin-top:  -475px;

   }

}
</style>
