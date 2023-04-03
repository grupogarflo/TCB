<template>
   <div>
     <HeaderCategory :items="categoryItem"></HeaderCategory>
      <div class="search_engine py-10">
            <SearchEngine></SearchEngine>
      </div>

      <v-container class="my-5">

         <div v-html="description"></div>

         <div id="home_tours_cards" class="mt-16 mb-16">
            <v-row justify="start" >
                  <v-col v-for="(item, i) in dataTour" :key="i" sm="4" md="3" class="d-flex flex-grow-1 flex-shrink-1 align-stretch">
                     <TourCard :item="item"></TourCard>
                  </v-col>
            </v-row>

        </div>

        <div v-html="descriptionFooter"></div>

        <Categories class="my-16"></Categories>

      </v-container>

   </div>

</template>

<script>
import SearchEngine from '~/components/General/SearchEngine.vue'
import HeaderCategory from '~/components/Tours/HeaderCategory.vue';
import TourCard from '~/components/General/TourCard.vue';
import Categories from '~/components/General/Categories.vue';

export default {
   components:{

      SearchEngine,
      HeaderCategory,
      TourCard,
      Categories


   },
   data() {
    return {
      showMe: false,
      dialog: true,

      title: '',
      description: '',
      descriptionFooter: '',
      metaTitle: '',
      metaKey: '',
      metaDescription: '',
      dataTour: [],
      class:'',
      categoryItem:[]

    }
  },
  computed:{
      language(){
         return this.$store.getters['booking/language'];
      }
  },


   created(){
      this.getDataCategory();
   },



   methods:{
      async getDataCategory() {
         if(this.$route.params.slug!=='all-tours' && this.$route.params.slug!=='todos-tours'){
            try {
               await this.$axios
                  .post('/getCategory', {
                     idioma: this.language,
                     url: this.$route.params.slug,
                  })
                  .then((resp) => {
                     const aux = resp.data.data[0].category_id
                     this.title = resp.data.data[0].title
                     this.description = resp.data.data[0].description
                     this.descriptionFooter = resp.data.data[0].description_footer
                     this.metaTitle = resp.data.data[0].meta_title
                     this.metaKey = resp.data.data[0].meta_description
                     this.metaDescription = resp.data.data[0].meta_keywords
                     this.class= resp.data.data[0].class_apply

                     this.categoryItem.push({
                        class:this.class,
                        title:this.title
                     });

                     this.getRegistros(aux)

                     this.dialog = false
                     this.showMe = true
                  })
               } catch (e) {
                  this.error = e.response.data.message
                  console.log('error' + e)
            }
         }
         else{
            this.categoryItem.push({
                  class:'all-tours',
                  title:this.$t('menu.categories.name')
             });
             this.getAllTours()
         }
      },
      getRegistros(id) {
         try {
            this.$axios
               .post('/getTourByCategory', {
                  // .post(this.urlSend, {
                  lenguage: this.language,
                  idCategory: id,
               })
               .then((resp) => {
                  this.dataTour = resp.data.data
               })
            } catch (e) {
               this.error = e.response.data.message
               console.log('error' + e)
            }
      },

      getAllTours(){
         try {
            this.$axios
               .post('/getAllTours', {
                  // .post(this.urlSend, {
                  language: this.language,
               })
               .then((resp) => {
                  this.dataTour = resp.data.data
               })
            } catch (e) {
               this.error = e.response.data.message
               console.log('error' + e)
            }

      }
   }
}
</script>
