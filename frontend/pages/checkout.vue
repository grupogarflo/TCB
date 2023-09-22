<template>
   <v-container id="checkout" class="py-16">
      <v-row justify="center">
         <v-col sm="12" md="6" >
            <v-stepper class="steps" flat outlined v-model="steps">
                  <v-stepper-header>
                     <v-stepper-step
                        step="1"
                        :complete="steps>1"
                        color="white"


                     >
                        {{ $t('checkout.steps.information') }}

                     </v-stepper-step>

                     <v-divider class="mx-3 divider mr-5"></v-divider>
                     <v-stepper-step
                        step="2"
                        :complete="steps>2"
                        color="white"

                     >
                        {{ $t('checkout.steps.pay') }}

                     </v-stepper-step>

                     <v-divider class="mx-3 divider ml-5"></v-divider>

                     <v-stepper-step
                        step="3"
                        :complete="steps>3"
                        color="white"


                     >
                        {{ $t('checkout.steps.ticket') }}

                     </v-stepper-step>


                  </v-stepper-header>
            </v-stepper>

         </v-col>
      </v-row>

      <v-row class="mt-10">
         <v-col cols="12" md="6" :order="(mobile) ? '2' :'1' ">
               <v-card width="100%" :class="['py-5', (steps==3) ? 'elevation-0' : '']" >
                  <v-card-text>
                     <InformationForm :class="[(steps==1) ? 'd-block' : 'd-none' ]"></InformationForm>
                     <!--<payment-form :class="[(steps==2) ? 'd-block' : 'd-none' ]"></payment-form>
                     <paypal :class="[(steps==2) ? 'd-block' : 'd-none' ]" :clientId="clientId" :total="total"></paypal>-->

                     <meradoPago :clientId="clientId" :total="total" > </meradoPago>

                     <confirmation :class="[(steps==3) ? 'd-block' :'d-none' ]"></confirmation>
                  </v-card-text>
               </v-card>

               <v-row v-if="mobile" align="baseline" :class="(!route) ? 'd-none' :'d-flex mt-10 text-center'" no-gutters>
                  <v-col cols="12" class="text-center">
                     <img class="logo mx-4 d-inline-flex" src="/images/layout/visa.svg" :width="(!mobile) ? '18%' : '14%'" />

                     <img class="logo mx-4 d-inline-flex" src="/images/layout/mastercard.svg" :width="(!mobile) ? '8%' : '8%'"/>

                     <img class="logo mx-4 d-inline-flex" src="/images/layout/americanexpress.svg" :width="(!mobile) ? '18%' : '14%'" />

                     <img class="logo mx-4 d-inline-flex" src="/images/layout/paypal.svg" :width="(!mobile) ? '18%' : '14%'" />
                  </v-col>
               </v-row>
               <v-row v-if="mobile" :class="(!route) ? 'd-none' :'d-flex'">
                  <v-col cols="12" class="text-center">
                  <client-only>
                        <img  class="logo mx-auto" :src="img" :width="(!mobile) ? '82%' : '100%'" />
                     </client-only>

                  </v-col>
               </v-row>
         </v-col>
         <v-col cols="12" md="6" :order="(mobile) ? '1' :'2' ">
            <TourDetail :tour="tourDetail"  ></TourDetail>

            <v-row v-if="!mobile" align="baseline" :class="(!route) ? 'd-none' :'d-flex mt-10 text-center'" no-gutters>
               <v-col cols="12" class="text-center">
                  <img class="logo mx-4 d-inline-flex" src="/images/layout/visa.svg" :width="(!mobile) ? '18%' : '14%'" />

                  <img class="logo mx-4 d-inline-flex" src="/images/layout/mastercard.svg" :width="(!mobile) ? '8%' : '8%'"/>

                  <img class="logo mx-4 d-inline-flex" src="/images/layout/americanexpress.svg" :width="(!mobile) ? '18%' : '14%'" />

                  <img class="logo mx-4 d-inline-flex" src="/images/layout/paypal.svg" :width="(!mobile) ? '18%' : '14%'" />
               </v-col>
            </v-row>
            <v-row v-if="!mobile" :class="(!route) ? 'd-none' :'d-flex'">
               <v-col cols="12" class="text-center">
               <client-only>
                     <img  class="logo mx-auto" :src="img" :width="(!mobile) ? '82%' : '100%'" />
                  </client-only>

               </v-col>
            </v-row>

         </v-col>
      </v-row>



   </v-container>
</template>


<script>

import InformationForm from '~/components/checkout/InformationForm';
// import PaymentForm from '~/components/checkout/PaymentForm.vue';
import TourDetail from '~/components/checkout/TourDetail.vue';
// import Categories from '~/components/General/Categories.vue';
// import SectionTitle from '~/components/General/SectionTitle.vue';
import Confirmation from '~/components/checkout/Confirmation.vue';
import Paypal from '~/components/checkout/paypal.vue';
export default {

   components:{InformationForm,  TourDetail, Confirmation, Paypal},
   data(){
      return {
         steps :1,
         total:0,
         clientId:0,
         img:(this.$i18n.locale==='es') ? '/images/layout/100_reembolsable.svg' :'/images/layout/100_money-back.svg'
      }
   },


   computed:{
      tourDetail(){
        return this.$store.getters["booking/tours"];
      },
      alto(){
         if (this.isMobile) {
         return 500;
         } else {
         return 468
         }
      },
      route(){

         // console.log(this.$route.name);

         if(this.$route.name.includes('checkout')){
            return true
         }
         return false
      },

      mobile(){
         // console.log(this.$route.name);
         return this.isMobile()
      },
   },

   mounted(){
      this.getTotalTour();

      this.$nuxt.$on('goPaymentEvent', (id)=>{
         this.clientId = id.client;
         this.total=id.total;
         this.steps=2
      })
      this.$nuxt.$on('confirmation', ()=>{
         this.steps=3

      })


   },
   methods:{
      async getTotalTour() {
         try {
            if(!this.tourDetail.isPrivate){
               await this.$axios
                  .post('/getTotal', {
                     id: this.tourDetail.id,
                     adult: this.tourDetail.adultos,
                     child: this.tourDetail.ninos,
                     idioma: this.$store.getters['booking/language']
                  })
                  .then((resp) => {


                        this.total =
                        this.$store.dispatch('booking/setTotal',{ usd: resp.data.data, mxn:resp.data.data_mxn });

                  });
            }
            else{
                  const rates = this.tourDetail.rates;

                  const pos = rates.map(element=>element.pax).indexOf(this.tourDetail.pax);

                  if(pos!==false){
                     this.total =
                     this.$store.dispatch('booking/setTotal',{ usd: parseFloat(rates[pos].real_price), mxn:parseFloat(rates[pos].real_price_mxn) });
                  }
            }
         } catch (e) {
            this.error = e.response.data.message
            console.log('error' + e)
            }
      },
   }

}
</script>
