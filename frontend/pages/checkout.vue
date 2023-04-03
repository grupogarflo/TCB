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
         <v-col cols="12" md="6">
               <v-card width="100%" :class="['py-5', (steps==3) ? 'elevation-0' : '']" >
                  <v-card-text>
                     <InformationForm :class="[(steps==1) ? 'd-block' : 'd-none' ]"></InformationForm>
                     <payment-form :class="[(steps==2) ? 'd-block' : 'd-none' ]"></payment-form>
                     <confirmation :class="[(steps==3) ? 'd-block' :'d-none' ]"></confirmation>
                  </v-card-text>
               </v-card>
         </v-col>
         <v-col cols="12" md="6">
            <TourDetail :tour="tourDetail"  ></TourDetail>
         </v-col>
      </v-row>

      <v-row justify="center">
         <v-col cols="12" md="12" >
            <SectionTitle :titleText="$t('ourTours')" class="mt-16"></SectionTitle>
            <Categories class="mt-5"></Categories>
         </v-col>
      </v-row>


   </v-container>
</template>


<script>

import InformationForm from '~/components/checkout/InformationForm';
import PaymentForm from '~/components/checkout/PaymentForm.vue';
import TourDetail from '~/components/checkout/TourDetail.vue';
import Categories from '~/components/General/Categories.vue';
import SectionTitle from '~/components/General/SectionTitle.vue';
import Confirmation from '~/components/checkout/Confirmation.vue';
export default {

   components:{InformationForm, PaymentForm, TourDetail, Categories, SectionTitle, Confirmation},
   data(){
      return {
         steps :1,
         total:0
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
   },

   mounted(){
      this.getTotalTour();

      this.$nuxt.$on('goPaymentEvent', ()=>{
         this.steps=2
      })
      this.$nuxt.$on('confirmation', ()=>{
         this.steps=3

      })


   },
   methods:{
      async getTotalTour() {
         try {
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
               // this.tourName = this.tourDetail.name
               // this.tourImg = this.tourDetail.img
               // this.tourDate = this.tourDetail.date
               // this.tourAdults = this.tourDetail.adultos
               // this.tourChild = this.tourDetail.ninos
               // this.tourDuration = this.tourDetail.duration
            })
         } catch (e) {
         this.error = e.response.data.message
         console.log('error' + e)
         }
      },
   }

}
</script>
