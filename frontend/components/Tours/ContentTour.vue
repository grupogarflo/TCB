<template>
   <div v-if="!mobile">
      <client-only>
         <Gallery :items="itemsGallery" :video="video"></Gallery>


            <!--<div id="search-engine"  class="search_engine py-10">

               <SearchEngine  :openPax="openPax"> </SearchEngine>
            </div>-->



         <v-container class="my-5 px-5">
            <v-row>
               <v-col cols="12"  class="px-8 ">
                  <v-breadcrumbs
                     :items="breadcrumbs"
                     divider=">"
                     class="px-0"
                  ></v-breadcrumbs>
               </v-col>
            </v-row>
            <v-row >
               <v-col cols="12" sm="6" class="px-8" :order="(mobile) ? '2' :'1' ">

                  <SectionTitle :title-text="name" class="mt-5 d-inline" :discount="item.discount"></SectionTitle>

                  <general-data :rank="item.rank" :duration="item.duration" :available="item.avaible" :id="item.tour_id" class="mt-5"></general-data>

                  <content-expand :title="$t('tours.description')" :content="description" :is_html="true" class="mt-5"></content-expand>
                  <content-expand :title="$t('tours.includes')" :content="include" :is_html="true" class="mt-5"></content-expand>
                  <content-expand :title="$t('tours.recommendations')" :content="suggestion" :is_html="true" class="mt-5"></content-expand>


               </v-col>
               <v-col cols="12" sm="6" class="px-8" :order="(mobile) ? '1' :'2' ">
                  <detail-action :item="item" :video="video"></detail-action>
                  <SearchEngine  :openPax="openPax" :open="0" class="mt-5" :tourVentrataId="ventrataId"> </SearchEngine>

                  <div v-if="map!=='' " v-html="map" class="mx-0 mt-5"></div>

                  <content-expand :title="$t('tours.not_included')" :content="not_included" :is_html="true" class="mt-5"></content-expand>

                  <content-expand :title="$t('tours.notes')" :content="note" :is_html="true" class="mt-5"></content-expand>


               </v-col>
            </v-row>


         </v-container>





      </client-only>
   </div>
   <div v-else>
      <client-only>
         <Gallery :items="itemsGallery" :video="video"></Gallery>
         <v-container class="my-5 px-1">
            <v-row>
               <v-col cols="12"  class="px-8 ">
                  <v-breadcrumbs
                     :items="breadcrumbs"
                     divider=">"
                     class="px-0"
                  ></v-breadcrumbs>
               </v-col>
            </v-row>
            <v-row >
               <v-col cols="12" md="6" class="px-8">

                  <SectionTitle :title-text="name" class="mt-5"></SectionTitle>
                  <general-data :rank="item.rank" :duration="item.duration" :available="item.avaible" class="my-5"></general-data>
                  <detail-action :item="item" :video="video"></detail-action>
                  <SearchEngine  :openPax="openPax" :open="0" class="mt-5" :tourVentrataId="ventrataId"> </SearchEngine>

                  <content-expand :title="$t('tours.description')" :content="description" :is_html="true" class="mt-5"></content-expand>
                  <content-expand :title="$t('tours.includes')" :content="include" :is_html="true" class="mt-5"></content-expand>
                  <content-expand :title="$t('tours.not_included')" :content="not_included" :is_html="true" class="mt-5"></content-expand>
                  <content-expand :title="$t('tours.recommendations')" :content="suggestion" :is_html="true" class="mt-5"></content-expand>
                  <content-expand :title="$t('tours.notes')" :content="note" :is_html="true" class="mt-5"></content-expand>











               </v-col>
            </v-row>


         </v-container>
         <div v-if="map!=='' " v-html="map" style="width: 100%;"></div>
      </client-only>
   </div>

</template>


<script>
import SearchEngine from '~/components/General/SearchEngine.vue'
import SectionTitle from '~/components/General/SectionTitle.vue'
import Gallery from '~/components/Tours/Gallery.vue'
import DetailAction from '~/components/Tours/DetailAction.vue'
// import Categories from '~/components/General/Categories.vue';
import GeneralData from '~/components/Tours/GeneralData.vue'
import ContentExpand from '~/components/General/ContentExpand.vue'
// import Location from '~/components/Tours/Location.vue'
export default {
   components:{
      Gallery,
      SearchEngine,
      SectionTitle,
      DetailAction,
      // Categories,
      GeneralData,
      ContentExpand,
      // Location

   },

   data(){
      return {
         itemsGallery:[],
         name:'',
         description:'',
         suggestion:'',
         note:'',
         include:'',
         img:'',
         item:{},
         openPax:false,
         titleMeta:'',
         descriptionMeta:'',
         keywordsMeta:'',
         map:'',
         not_included:'',
         video :null,
         ventrataId:null

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

               ]
            }

   },


   computed:{
      mobile(){
            return this.isMobile();
      },
      breadcrumbs(){
         return [
            {
              text: 'Home',
              disabled: false,
              href: '/',
            },
            {
              text: this.name,
              disabled: true,

            },

         ]
      },

      language(){
         return this.$store.getters['booking/language'];
      }

   },

   /*

   created(){
      this.getDataTour();

   },
   */

   mounted(){
      this.getDataTour();

      this.$nuxt.$on('bookEvent',()=>{

         document.getElementById("search-engine").scrollIntoView({behavior:"smooth"});
         this.openPax=true

      })
   },



   methods:{
      async getDataTour() {
         try {
         await this.$axios
            .post('/getTourData', {
               id: this.$route.params.slug,
               idioma:this.language
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
               this.video = resp.data.data[0].video

               this.titleMeta = resp.data.data[0].meta_title;
                     this.descriptionMeta= resp.data.data[0].meta_description;
                     this.keywordsMeta=resp.data.data[0].meta_keywords;

               this.map= resp.data.data[0].map;
                this.not_included= resp.data.data[0].not_include;
                this.ventrataId = resp.data.data[0].ventrata_product_id;
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
                  isPrivate:resp.data.data[0].is_private,
                  rates: resp.data.data[0].rates,
                  pax:null,
                  namePax:'',
                  tour_id: resp.data.data[0].tour_id


               })
               this.reRender = this.reRender + 1
            })
         } catch (err) {
            this.error = err.response.data.message
            // console.log('error' + e)
         }
      },





   }

}
</script>
