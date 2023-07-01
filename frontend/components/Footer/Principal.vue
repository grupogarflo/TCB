<template>
   <div>
      <v-container class="mb-5">
         <v-row>
            <v-col cols="12" class="text-center">
               <img class="logo mx-auto" src="/images/layout/tripadvisor-isotipo.svg" width="15%" />
            </v-col>
         </v-row>
         <v-row align="baseline">
            <v-col cols="3" class="text-center">
               <img class="logo mx-auto" src="/images/layout/visa.svg" width="32%" />
            </v-col>
            <v-col cols="3" class="text-center">
               <img class="logo mx-auto" src="/images/layout/mastercard.svg" width="22%" />
            </v-col>
            <v-col cols="3" class="text-center">
               <img class="logo mx-auto" src="/images/layout/americanexpress.svg" width="32%" />
            </v-col>
            <v-col cols="3" class="text-center">
               <img class="logo mx-auto" src="/images/layout/paypal.svg" width="32%" />
            </v-col>
         </v-row>
         <v-row>
            <v-col cols="12" class="text-center">
               <img v-if="language==2" class="logo mx-auto" src="/images/layout/100_money-back.svg" width="60%" />
               <img v-else class="logo mx-auto" src="/images/layout/100_reembolsable.svg" width="60%" />
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
   computed:{
      mobile(){
         return this.isMobile()
      },
      language(){
         return  this.$store.getters['booking/language']
      },
   }
}
</script>
