<template>
   <div class="category-detail">
      <HeaderDestination :items="categoryItem"></HeaderDestination>


      <v-container class="my-5">
         <v-row>
            <v-col col="12">
               <v-breadcrumbs
                     :items="breadcrumbs"
                     divider=">"
                     class="px-0"
                  ></v-breadcrumbs>

               <!-- <SectionTitle :title-text="name" class="mt-5"></SectionTitle> -->
               <div v-html="description"></div>
               <div id="home_tours_cards" class="mt-16 mb-16">
                  <v-row justify="start" >
                        <v-col v-for="(item, i) in dataTour" :key="i" cols="12" sm="6" md="4" lg="3" class="d-flex flex-grow-1 flex-shrink-1 align-stretch">
                           <TourCard :item="item"></TourCard>
                        </v-col>
                  </v-row>
               </div>
              <div v-html="descriptionFooter"></div>



            </v-col>
         </v-row>

         <!--

         <div v-html="description"></div>

         <div id="home_tours_cards" class="mt-16 mb-16">
            <v-row justify="start" >
                  <v-col v-for="(item, i) in dataTour" :key="i" sm="4" md="3" class="d-flex flex-grow-1 flex-shrink-1 align-stretch">
                     <TourCard :item="item"></TourCard>
                  </v-col>
            </v-row>

        </div>

        <div v-html="descriptionFooter"></div>

        <Categories class="my-16"></Categories>-->

      </v-container>

   </div>

</template>

<script>


// import SearchEngine from '~/components/General/SearchEngine.vue'
import HeaderDestination from '~/components/Tours/HeaderDestination.vue';
import TourCard from '~/components/General/TourCard.vue';
// import Categories from '~/components/General/Categories.vue';

export default {
   components:{

      // SearchEngine,
      HeaderDestination,
      TourCard,
      // Categories


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
      categoryItem:[],
      name:''

    }
  },
  computed:{
      language(){
         return this.$store.getters['booking/language'];
      },

      breadcrumbs(){
         return [
            {
              text: 'Home',
              disabled: false,
              href: '/',
            },
            {
              text: this.title,
              disabled: true,

            },

         ]
      }
  },


   created(){
      this.getDataCategory();
   },



   methods:{
      async getDataCategory() {

            try {
               await this.$axios
                  .post('/getDestination', {
                     idioma: this.language,
                     url: this.$route.params.slug,
                  })
                  .then((resp) => {
                     const aux = resp.data.destination.destination_id
                     this.name = resp.data.destination.name
                     this.title = resp.data.destination.title
                     this.description = resp.data.destination.description
                     this.descriptionFooter = resp.data.destination.description_footer
                     this.metaTitle = resp.data.destination.meta_title
                     this.metaKey = resp.data.destination.meta_description
                     this.metaDescription = resp.data.destination.meta_keywords

                     this.categoryItem.push({
                        full_photo_path: resp.data.destination.full_photo_path,
                        name:resp.data.destination.name
                     })
                     this.getRegistros(aux)

                     this.dialog = false
                     this.showMe = true

                  })
               } catch (e) {
                  this.error = e.response.data.message
                  console.log('error' + e)
            }


      },
      getRegistros(id) {
         try {
            this.$axios
               .post('/getTourByDestination', {
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
