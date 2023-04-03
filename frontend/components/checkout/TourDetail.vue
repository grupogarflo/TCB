<template>
    <v-card width="100%" class="rounded-lg tour-card" elevation="0">
      <v-img class="" height="278"  :src="tour.img"></v-img>
      <v-card-text class="px-4  py-0 mt-3 background-grey ">
         <v-row class="px-5 py-5 tour-detail" >
            <v-col sm="12">
               <p>{{ $t('general.tour') }}: <span class="d-block title">{{ tour.name }}</span></p>
               <p>{{ $t('general.date') }}: {{ tour.date}}</p>
               <p>{{  $t('general.adults') }}: {{ tour.adultos }}</p>
               <p v-if="tour.ninos>0">{{ $t('general.children') }}: {{ tour.ninos }}</p>
               <p>{{ $t('general.duration') }}: {{ tour.duration }}</p>
               <p v-if="promocode!=null"> Promocode: {{ promocode.promocode }}</p>
               <p>{{ $t('general.total') }}: {{ bookingTotal  | currencyFormat(currency)}}</p>


            </v-col>
         </v-row>
      </v-card-text>
   </v-card >
</template>


<script>
   export default {
      props:{
         tour:Object
      },
      computed:{
         // eslint-disable-next-line vue/return-in-computed-property
         bookingTotal(){

            const dataInfo = this.$store.getters["booking/bookingTotal"];
            const promoCode = this.$store.getters['booking/tours'].promocode ;
            if (this.$store.getters['booking/language'] ===2){

               if(typeof promoCode !=='undefined' && Object.entries(promoCode).length !== 0)
                  return promoCode.data_usd;
               else
                  return dataInfo.usd;

            }


               if(typeof promoCode !=='undefined' && Object.entries(promoCode).length !== 0)
                  return promoCode.data_mxn
               else
                  return dataInfo.mxn;



         },
         currency(){
            if (this.$store.getters['booking/language'] ===2) return 'USD'
            return 'MXN'
         },

         promocode(){
            const promoCode = this.$store.getters['booking/tours'].promocode;
            // console.log('promo code ', promoCode);
            return typeof promoCode !=='undefined' && Object.entries(promoCode).length !== 0  ? promoCode : null;
         }
      }
   }
</script>
