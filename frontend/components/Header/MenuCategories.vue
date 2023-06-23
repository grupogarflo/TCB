<template>
   <v-container class="py-1 " v-if="!mobile">
      <v-row justify="center" align="center" no-gutters>
         <v-col cols="12" class="text-center">
            <div class="d-inline ">

               <nuxt-link :to="localePath({
                        name:'slug',
                        params:{
                           slug:all_tours.url
                        }
                     })" class="sub2 px-4">
                  {{ all_tours.name }}
               </nuxt-link >


               <div  v-for="(item, i) in items " :key="i"  class="d-inline option">
                  <v-menu

                     v-if="item.destinations_related!=null"
                     open-on-hover
                     bottom
                     offset-y
                     tile
                     flat
                     class="border-0"
                     elevation="0"
                  >
                     <template v-slot:activator="{ on, attrs }">

                        <v-btn  class=" sub_menu d-inline px-4 elevation-0"
                              v-bind="attrs"
                              v-on="on"
                              plain
                              color="white"

                              @click="goTo(item.url)"

                              >

                              {{ item.name }}

                        </v-btn >
                     </template>

                     <v-list class="listSub" elevation="0" flat>
                        <v-list-item
                           v-for="(related, j) in item.destinations_related"
                           :key="j"
                        >
                           <v-list-item-title>
                              <nuxt-link  :to="localePath({
                                 name:'slug',
                                 params:{
                                    slug:related.url
                                 }
                              })"
                              class="sub2"> {{  language ==2 ? related.name_en : related.name_es }}</nuxt-link>
                           </v-list-item-title>
                        </v-list-item>
                     </v-list>
                  </v-menu>

                  <nuxt-link v-else  class="sub2 px-4"
                              :to="localePath({
                                 name:'slug',
                                 params:{
                                    slug:item.url
                                 }
                              })"  v-bind="attrs"
                                    v-on="on"
                              >
                           {{ item.name }}


                        </nuxt-link >
               </div>





            </div>
         </v-col>
      </v-row>
   </v-container>
   <v-container v-else class="mt-0 pt-0">

      <v-row no-gutters>

         <v-col sm="12" >
            <ul class="menu-list pt-2 px-2">
               <li class="my-2">
                     <span v-for="locale in availableLocales" :key="locale.code">
                        <a v-if="locale.show"  class=" language" :data-lang="language" :data-value="language" :href="domain">{{ locale.name }}</a>
                     </span>

               </li>
               <li class="my-2">
                  <nuxt-link :to="localePath({
                        name:'slug',
                        params:{
                           slug:all_tours.url
                        }
                     })" class="sub2">
                     {{ all_tours.name }}
                  </nuxt-link >
               </li>
               <li v-for="(item, i) in items " :key="i" class="my-2">
                  <div v-if="item.destinations_related!=null"  >
                     <nuxt-link
                        class="sub2 "
                              :to="localePath({
                                 name:'slug',
                                 params:{
                                    slug:item.url
                                 }
                              })"  v-bind="attrs"
                                    v-on="on"

                              >

                              {{ item.name }}

                        </nuxt-link>

                     <v-list class="listSub pl-2" elevation="0" flat>
                        <v-list-item
                           v-for="(related, j) in item.destinations_related"
                           :key="j"
                        >
                           <v-list-item-title>
                              <nuxt-link  :to="localePath({
                                 name:'slug',
                                 params:{
                                    slug:related.url
                                 }
                              })"
                              class="sub2"> {{  language ==2 ? related.name_en : related.name_es }}</nuxt-link>
                           </v-list-item-title>
                        </v-list-item>
                     </v-list>
                  </div>

                  <nuxt-link v-else  class="sub2 "
                              :to="localePath({
                                 name:'slug',
                                 params:{
                                    slug:item.url
                                 }
                              })"  v-bind="attrs"
                                    v-on="on"
                              >
                           {{ item.name }}


                        </nuxt-link >



               </li>
               <li class="my-2"> <nuxt-link :to="localePath({name:'contact'})" class="language slim"> {{ $t(('menu.header.contact')) }}</nuxt-link></li>
               <li class="my-2"><nuxt-link class="language slim" :to="localePath({name:'terms'})"> {{ $t(('menu.footer.terms_conditions')) }} </nuxt-link></li>
               <li class="my-2"><nuxt-link class="language slim" :to="localePath({name:'politics'})"> {{ $t(('menu.footer.terms_politics')) }} </nuxt-link></li>
               <li class="my-2"><nuxt-link class="language slim" :to="localePath({name:'about-us'})"> {{ $t(('menu.footer.about-us')) }} </nuxt-link></li>
            </ul>
         </v-col>
      </v-row>

   </v-container>
</template>


<script>
// import Language from './Language.vue';

export default {
   // components: {  Language, },
   data(){
      return {
         items:{},
         class:'',

         // change to cancun bay urls
         domain:(this.language===1) ? 'http://cancunbay.net' : 'http://cancunbay.mx',
         show_menu_mobile:true
      }
   },
   computed:{
      all_tours(){
         return {
            url:this.$t('menu.categories.url'),
            name:this.$t('menu.categories.name')
         }
      },
      language(){
         return  this.$store.getters['booking/language']
      },
      availableLocales(){

            const $code =[];

            this.$i18n.locales.forEach(element => {
                  if(element.show && element.code!==this.$i18n.locale){

                     console.log(element);
                     // console.log(this.$i18n);
                     $code.push(element);
                  }
            });

            // console.log($code);

            return $code;



            /*
            return this.$i18n.locales.filter((i) =>{
               console.log(i);
               return i.show &&( i.code !== this.$i18n.locale)
            });
            */
      },

      /*
      domain(){

            console.log('lang ',this.$store.getters['booking/language']);
            return (this.language===1) ? 'https://www.yalku.tours' : 'https://www.yalkutours.com.mx'

      },

      */
      mobile(){
            return this.isMobile();
      }


   },

   watch:{
      language(){
         this.init()
      }
   },


   mounted(){
      this.init();
   },

   methods:{
      async init() {

         try {
            this.domain =(this.language===1) ? 'http://cancunbay.net' : 'http://www.cancunbay.com.mx'

            await this.$axios
               .post('/getAllCategories', {

                  idioma:this.language,
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
            name:'slug',
            params:{
               slug:item
            }
         }))

      }


   }
}
</script>

<style lang="css" scoped>
.v-menu__content{
   box-shadow: none !important;
}
</style>

