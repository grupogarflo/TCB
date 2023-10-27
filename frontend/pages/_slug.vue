<template>
   <div>


         <content-tour  v-if="pageType=='tour'"></content-tour>
         <content-category v-if="pageType=='category'"></content-category>
         <content-destination v-if="pageType=='destination'"></content-destination>


   </div>

</template>

<script>
import ContentTour from '../components/Tours/ContentTour.vue';
import ContentCategory from '../components/Categories/ContentCategory.vue';
import ContentDestination from '../components/Destinations/ContentDestination.vue';


// import SearchEngine from '~/components/General/SearchEngine.vue'
// import SectionTitle from '~/components/General/SectionTitle.vue'
// import Slide from '~/components/Home/Slide.vue'
// import DetailAction from '~/components/Tours/DetailAction.vue'
// import Categories from '~/components/General/Categories.vue';

export default {
  components: { ContentTour,ContentCategory, ContentDestination },
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
         titleMeta:'',
         descriptionMeta:'',
         keywordsMeta:'',
         imgMeta:''
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
               {
                  hid: 'og:image',
                  name: 'og:image',
                  content:this.imgMeta
               }

            ]
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
   mounted(){
      const name = window.location.href
         // alert(name);
         const language = (name.includes('cancunbay.com.mx')) ? 1 : 2;
         const currency = (name.includes('cancunbay.com.mx')) ? 'MXN' : 'USD';

         this.$store.dispatch('booking/setLanguage',language);
         this.$store.dispatch('booking/currency',currency);
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
                  id: resp.data.data[0].id,
                  name: this.name,
                  url: resp.data.data[0].url,
                  img: this.img,
                  duration: resp.data.data[0].duration,
                  tour_id: resp.data.data[0].tour_id
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
                     // console.log(response.data);
                     this.pageType = response.data.val;
                     // this.contentData = response.data.data

                     if(response.data.val==='category'){
                        this.titleMeta=response.data.data[0].meta_title
                        this.descriptionMeta=response.data.data[0].meta_description
                        this.keywordsMeta=response.data.data[0].meta_keywords
                        this.imgMeta=require('~/assets/images/home/cancunbay-chichen-itza.jpg')
                     }

                     if(response.data.val==='destination'){
                        this.titleMeta=response.data.data[0].meta_title
                        this.descriptionMeta=response.data.data[0].meta_description
                        this.keywordsMeta=response.data.data[0].meta_keywords
                        this.imgMeta=require('~/assets/images/home/cancunbay-chichen-itza.jpg')
                     }

                     if(response.data.val==='tour'){
                        this.titleMeta=response.data.data.tour[0].meta_title
                        this.descriptionMeta=response.data.data.tour[0].meta_description
                        this.keywordsMeta=response.data.data.tour[0].meta_keywords
                        this.imgMeta = response.data.data.tour[0].full_photo_path
                     }



                  }).catch(response =>{
                     // console.log('error', response);
                  })

      }





   }
}
</script>
