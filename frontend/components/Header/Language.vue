<template>
    <div class="d-block links mb-2 text-right" v-if="!mobile">
        <!--<nuxt-link v-for="locale in availableLocales" :key="locale.code"  class="mx-2 language" target="_blank" :to="switchLocalePath(locale.code)" >{{ locale.name }}</nuxt-link>-->

         <span v-for="locale in availableLocales" :key="locale.code">
            <a   v-if="locale.show" class="mx-2 language" :href="domain">{{ locale.name }}</a>
         </span>


        <nuxt-link :to="localePath({name:'contact'})" class="language"> <span class="mx-2">{{ $t(('menu.header.contact')) }}</span></nuxt-link>
    </div>

   <div  v-else >
      <span v-for="locale in availableLocales" :key="locale.code">
         <a v-if="locale.show"  class="mx-2 language" :href="domain">{{ locale.name }}</a>
      </span>
      <nuxt-link :to="localePath({name:'contact'})" class="language"> <span class="mx-2">{{ $t(('menu.header.contact')) }}</span></nuxt-link>

   </div>

</template>

<script>
export default {
   computed: {
      availableLocales(){

        const $code =[];

         this.$i18n.locales.forEach(element => {
               if(element.show && element.code!==this.$i18n.locale){

                  console.log(element);
                  //console.log(this.$i18n);
                  $code.push(element);
               }
         });

         console.log($code);

         return $code;



         /*
         return this.$i18n.locales.filter((i) =>{
            console.log(i);
            return i.show &&( i.code !== this.$i18n.locale)
         });
         */
      },

      domain(){

         console.log('lang ',this.$store.getters['booking/language']);
         return (this.$store.getters['booking/language']===1) ? 'https://www.yalku.tours' : 'https://www.yalkutours.com.mx';
      },

      mobile(){
            return this.isMobile();
      }
    },
   watch:{
      availableLocales(){
         const language = (this.$i18n.locale==='es') ? 1 : 2;
         const currency = (this.$i18n.locale==='es') ? 1 : 2;

         this.$store.dispatch('booking/setLanguage',language);
         this.$store.dispatch('booking/currency',currency);

      }
   }
}
</script>
