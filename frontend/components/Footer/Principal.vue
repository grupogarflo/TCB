<template>
   <div>
      <v-container class="mb-5 mt-3">

         <v-row>
            <v-col cols="12">
               <p class="colorRank font-weight-bold">{{$t('footer.we_are_in')  }}</p>
            </v-col>
            <v-col cols="12" class="justify-content-center text-center">
               <img class="mx-1 mx-md-6 my-2" src="/images/layout/reserllers/getyourguide.svg" :width="(!mobile) ? '6%' : '14%' " alt="Get your guide" />
               <img class="mx-6 mx-md-6 my-2" src="/images/layout/reserllers/viator.svg" :width="(!mobile) ? '12%' : '25%' "  alt="Viator"/>
               <img class="mx-1 mx-md-6 my-2" src="/images/layout/reserllers/google.svg" :width="(!mobile) ? '12%' : '25%' " alt="Google" />
               <img class="mx-1 mx-md-6 my-2" src="/images/layout/reserllers/tripadvisor.svg" :width="(!mobile) ? '15%' : '25%' " alt="Trip advisor" />
               <img class="mx-6 mx-md-6 my-2" src="/images/layout/reserllers/yelp.svg" :width="(!mobile) ? '12%' : '25%' "  alt="Yep"/>
               <img class="mx-1 mx-md-6 my-2" src="/images/layout/reserllers/expedia.svg" :width="(!mobile) ? '15%' : '25%' " alt="Expedia" />

            </v-col>

         </v-row>
         <v-row>
            <v-col cols="12">
               <p class="colorRank font-weight-bold">{{$t('footer.our_clients_opinion')  }}</p>
            </v-col>
            <v-col cols="12" :class="[(!mobile) ? 'px-10' : 'px-1']">


               <client-only>
                  <testimonials></testimonials>
               </client-only>

            </v-col>
         </v-row>



         <v-row align="center">
            <v-col cols="12"><h4 class="title-section2 text-center" v-html="$t('footer.certificate')"></h4></v-col>
            <v-col cols="12" class="d-flex text-center align-content-center justify-center">

               <img class="logo logoTrip" src="/images/layout/tripadvisor-2021.svg" :width="(!mobile) ? '10%' : '20%' "  alt="Trip advisor 2021"/>


               <img class="logo pointer" src="/images/layout/TC_L_2023.svg" :width="(!mobile) ? '20%' : '50%' " @click="openLink"  style="cursor: pointer" alt="Trip Advisor 2023"/>

               <img class="logo logoTrip " src="/images/layout/tripadvisor-2022.svg" :width="(!mobile) ? '10%' : '20%' "  alt="Trip advisor 2022"/>

            </v-col>
         </v-row>
         <v-row align="baseline" :class="(route) ? 'd-none' :'d-flex'">
            <v-col cols="3" class="text-center">
               <img class="logo mx-auto" src="/images/layout/visa.svg" :width="(!mobile) ? '32%' : '80%'" alt="Visa" />
            </v-col>
            <v-col cols="3" class="text-center">
               <img class="logo mx-auto" src="/images/layout/mastercard.svg" :width="(!mobile) ? '22%' : '50%'" alt="Master card"/>
            </v-col>
            <v-col cols="3" class="text-center">
               <img class="logo mx-auto" src="/images/layout/americanexpress.svg" :width="(!mobile) ? '32%' : '80%'" alt="American Express" />
            </v-col>
            <v-col cols="3" class="text-center">
               <img class="logo mx-auto" src="/images/layout/paypal.svg" :width="(!mobile) ? '32%' : '80%'"  alt="Paypal"/>
            </v-col>
         </v-row>
         <v-row :class="(route) ? 'd-none' :'d-flex'">
            <v-col cols="12" class="text-center">
              <client-only>
                  <img  class="logo mx-auto" :src="img" :width="(!mobile) ? '60%' : '100%'" :alt="alt" />
               </client-only>

            </v-col>
         </v-row>
      </v-container>


      <v-container fluid id="Footer">
         <v-row>
               <v-container :class="[ !mobile ? 'py-7': 'pt-14 ']">
                  <v-row  align="center" justify=center>
                     <v-col sm="8" class="text-center">

                           <img class="logo mx-auto" src="/images/layout/logo.svg" alt="Cancun Bay"/>
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
import Testimonials from './Testimonials.vue'
export default {
  components: { Testimonials },
   data(){
      return {
         img:(this.$i18n.locale==='es') ? '/images/layout/100_reembolsable.svg' :'/images/layout/100_money-back.svg',
         alt:(this.$i18n.locale==='es') ? '100% reembolsable' : '100% money back'
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
   },
   methods:{

      openLink(){

         window.open('https://www.tripadvisor.com/Attraction_Review-g150812-d2153780-Reviews-Cancun_Bay_Tours-Playa_del_Carmen_Yucatan_Peninsula.html', '_blank', 'noreferrer');
      }
   }


}
</script>
<style lang="scss" scoped>
 .link{
      height: rem;
      width: 3rem;
      display: block;
 }
</style>
