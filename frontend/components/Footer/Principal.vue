<template>
   <div>
      <v-container class="mb-5 mt-3">
         <v-row>
            <v-col cols="12" class="text-center">
               <img class="logo mx-auto" src="/images/layout/TC_L_2023.svg" :width="(!mobile) ? '20%' : '50%' " />
            </v-col>
         </v-row>
         <v-row align="baseline" :class="(route) ? 'd-none' :'d-flex'">
            <v-col cols="3" class="text-center">
               <img class="logo mx-auto" src="/images/layout/visa.svg" :width="(!mobile) ? '32%' : '80%'" />
            </v-col>
            <v-col cols="3" class="text-center">
               <img class="logo mx-auto" src="/images/layout/mastercard.svg" :width="(!mobile) ? '22%' : '50%'"/>
            </v-col>
            <v-col cols="3" class="text-center">
               <img class="logo mx-auto" src="/images/layout/americanexpress.svg" :width="(!mobile) ? '32%' : '80%'" />
            </v-col>
            <v-col cols="3" class="text-center">
               <img class="logo mx-auto" src="/images/layout/paypal.svg" :width="(!mobile) ? '32%' : '80%'" />
            </v-col>
         </v-row>
         <v-row :class="(route) ? 'd-none' :'d-flex'">
            <v-col cols="12" class="text-center">
              <client-only>
                  <img  class="logo mx-auto" :src="img" :width="(!mobile) ? '60%' : '100%'" />
               </client-only>

            </v-col>
         </v-row>
      </v-container>


      <v-container fluid id="Footer">
         <v-row>
               <v-container :class="[ !mobile ? 'py-7': 'pt-14 ']">
                  <v-row  align="center" justify=center>
                     <v-col sm="8" class="text-center">

                           <img class="logo mx-auto" src="/images/layout/logo.svg" />
                           <div class="text-center mt-6 links">
                                 <span class="d-block">
                                    <nuxt-link class="mx-2 menu" :to="localePath({name:'terms'})"> {{ $t(('menu.footer.terms_conditions')) }} </nuxt-link>
                                    | <nuxt-link class="mx-2 menu" :to="localePath({name:'politics'})"> {{ $t(('menu.footer.terms_politics')) }} </nuxt-link>
                                    | <nuxt-link class="mx-2 menu" :to="localePath({name:'about-us'})"> {{ $t(('menu.footer.about-us')) }} </nuxt-link>
                                 </span>
                                 <span class="d-block"> Cancun Bay Tours, 2023</span>


                           </div>
                     </v-col>
                  </v-row>
               </v-container>
         </v-row>
      </v-container>
   </div>
</template>

<script>
export default {
   data(){
      return {
         img:(this.$i18n.locale==='es') ? '/images/layout/100_reembolsable.svg' :'/images/layout/100_money-back.svg'
      }
   },

   computed:{
      mobile(){
         // console.log(this.$route.name);
         return this.isMobile()
      },
      language(){
         return  this.$store.getters['booking/language']
      },


      route(){

         // console.log(this.$route.name);

         if(this.$route.name.includes('checkout')){
            return true
         }
         return false
      }

   },
   watch:{
      img(){
         const language = (this.$i18n.locale==='es') ? 1 : 2;
         if(language === 1){
            this.img = '/images/layout/100_money-back.svg'
         }
         else{
            this.img='/images/layout/100_reembolsable.svg'
         }

      }
   }


}
</script>
