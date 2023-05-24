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
                     })" class="option px-4">
                  {{ all_tours.name }}
               </nuxt-link >

               <nuxt-link v-for="(item, i) in items " :key="i"  :to="localePath({
                        name:'slug',
                        params:{
                           slug:item.url
                        }
                     })" class="option px-4">
                  {{ item.name }}
               </nuxt-link >



            </div>
         </v-col>
      </v-row>
   </v-container>
   <v-container v-else>

      <v-row no-gutters>

         <v-col sm="12" >
            <ul class="menu-list pt-5 px-5">
               <li class="my-3">
                     <span v-for="locale in availableLocales" :key="locale.code">
                        <a v-if="locale.show"  class="mx-2 language" :data-lang="language" :data-value="language" :href="domain">{{ locale.name }}</a>
                     </span>
                  <nuxt-link :to="localePath({name:'contact'})" class="language"> <span class="mx-2">{{ $t(('menu.header.contact')) }}</span></nuxt-link>
               </li>
               <li v-for="(item, i) in items " :key="i" class="my-3">
                  <nuxt-link  class="language strong" :to="localePath({
                     name:'tours-slug',
                     params:{
                        slug:item.url
                     }
                  })" >
                     {{ item.name }}
                  </nuxt-link>
               </li>
               <li class="my-3"><nuxt-link class="language slim" :to="localePath({name:'terms'})"> {{ $t(('menu.footer.terms_conditions')) }} </nuxt-link></li>
               <li class="my-3"><nuxt-link class="language slim" :to="localePath({name:'politics'})"> {{ $t(('menu.footer.terms_politics')) }} </nuxt-link></li>
               <li class="my-3"><nuxt-link class="language slim" :to="localePath({name:'about-us'})"> {{ $t(('menu.footer.about-us')) }} </nuxt-link></li>
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
            this.domain =(this.language===1) ? 'http://cancunbay.net' : 'http://cancunbay.mx'

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
            name:'tours-slug',
            params:{
               slug:item
            }
         }))

      }


   }
}
</script>

