<template>
   <div>

     <div id="paypal-button-container"></div>
     <v-row>
         <v-col cols="12" :class="[!mobile ? 'px-8': '']">
            <v-checkbox

                    required
                    :error-messages="termsErrors"
                    class="mt-0 mb-4 checkbox"
                    v-model="checkboxTerms"
                  >
                    <template v-slot:label>
                      <div>
                        {{ $t('forms.payment.accept') }}
                        <v-tooltip bottom>
                          <template v-slot:activator="{ on }">

                           <v-btn text @click="newTab"  v-on="on" class="px-0 btnTerms">

                              {{ $t('forms.payment.terms_cond') }}
                           </v-btn>
                          </template>
                         {{$t('forms.payment.tooltip')}}
                        </v-tooltip>
                      </div>
                    </template>
                  </v-checkbox>
         </v-col>

      </v-row>
   </div>
 </template>


<script>

import  loadScript  from "load-script";
// import moment from 'moment'
import { validationMixin } from 'vuelidate'
// import { required, email, numeric } from 'vuelidate/lib/validators'

export default {
   mixins: [validationMixin],
   validations: {

      checkboxTerms: {
         checked(val) {
         return val
         },
      },
   },
   props:{
      clientId: null,
      total:null
   },
   data(){
      return {
         checkboxTerms:false,
         radioGroup: 'card',

      }
   },

   computed:{

      currency(){
            if (this.$store.getters['booking/language'] ===2) return 'USD'
            return 'MXN'
         },
      termsErrors() {
         const errors = []
         if (!this.$v.checkboxTerms.$dirty) {
         return errors
         }
         !this.$v.checkboxTerms.checked &&
         errors.push(this.$t('forms.payment.terms_condValidation'))
         return errors
      },
      languageId(){
         return this.$store.getters["booking/language"]
      },
      stateData(){
         return this.$store.getters["booking/getAllStore"]
      },
      language(){
         return this.$store.getters["booking/languageName"]
      },

      languageCode(){
         return this.$store.getters["booking/language"]
      },


   },

   mounted() {
      /*
      this.$nextTick(() => {
         setTimeout(() => {
            this.loadPayPalScript()
         }, 100)
      })
      */

      this.loadPayPalScript()
  },



   methods:{

      loadPayPalScript() {

         /*
         this.total =this.stateData.tours.total_mxn;
                     if(this.language==='ing'){
                        this.total = this.stateData.tours.total_usd;
                     }

                     /// valid if tour has promocode

                     const promocode = this.stateData.tours.promocode;

                     if(typeof promocode !=='undefined' && Object.entries(promocode).length !== 0){
                        this.total = promocode.data_mxn;
                        if(this.language==='ing'){
                           this.total = promocode.data_usd;
                        }
                     }

                     alert(this.total);

               */

                     // sandbox jorge
                  // const paypalScriptUrl = 'https://www.paypal.com/sdk/js?client-id=ASW-fc5l0Q62KlKwCC83hj5YR08w0XnMIaXTJOxcZmZ1FW6l4y4i-MUWhkyNmADyIk9BnZg5t5wumK96&currency=USD'


                   const paypalScriptUrl = 'https://www.paypal.com/sdk/js?client-id=AXd2q-CJsN2MhkW_DOVp4FqKDIgkJYCxc6MrWna_-OURsfdP95XB6K2uzpqpudtulBhTBeZlmnwk5quc&currency='+this.currency


                  const container = document.querySelector('#paypal-button-container')
                  if (!container) {
                     return false;
                  }
                  loadScript(paypalScriptUrl, (err, script) => {
                           if (err) {
                              console.error('Error al cargar el script de PayPal', err)
                              return false;
                           }
                           window.paypal.Buttons({
                              createOrder: async (data, actions) => {
                                 return await actions.order.create({
                                    purchase_units: [{
                                       invoice_id: this.clientId,
                                       description:
                                          'Cancunbay : ' +
                                          ' Code book: ' +this.$store.state.booking.unicoId +
                                          ' Tour reservation: ' +this.$store.state.booking.tours.name +
                                          ' , adults: ' +this.$store.state.booking.tours.adultos +
                                          ' ,  child: ' +this.$store.state.booking.tours.ninos,

                                       amount: {
                                          currency_code: this.stateData.moneda,
                                          value: this.total,
                                          // value: '10.00',
                                       },
                                       locale: (this.language==='ing') ? 'en_US': 'es_MX', // en_US - es_MX - en_MX
                                    }],

                                    // application_context: {
                                    //  shipping_preference: 'NO_SHIPPING',
                                    //  user_action: 'PAY_NOW',
                                    //  enable_request: false,
                                    //  enable_shipping_address: false,
                                    //  enable_funding: false, // added parameter
                                    // },
                                  })
                              },
                              onApprove: async (data, actions) => {
                                 return await actions.order.capture().then((details) => {


                                    console.log('details  paypal back ',[details]);
                                 if (details.status === 'COMPLETED') {
                                    this.dialog = true
                                    this.textErrorBook = ''
                                    this.paymentPP(details.id)
                                 } else if (details.status === 'PROCESSING') {
                                    this.dialog = true
                                    this.textErrorBook =
                                       'Transaction is being processed. Please wait.'
                                 } else if (details.status === 'FAILED') {
                                    this.dialog = true
                                    this.textErrorBook =
                                       'Transaction failed. Please try again later.'
                                 }
                                 })
                              },
                              onError: (err) => {
                                 console.log(err)
                              },
                           }).render('#paypal-button-container')

                     });




      },

      paymentPP(ppId) {
         this.$axios
         .post('/updatePayment', {
            clientId: this.clientId,
               authorization: ppId,
               status: 'complet',
               idioma: this.languageCode,

         })
         .then((response) => {
            // console.log(response)
            this.$nuxt.$emit('confirmation');

         })
         .catch((error) => {
            console.log(error)
            this.textErrorBook = `some error: ${error.response.status} . ${error.response.data.message}`
         })
      },
   },




}
</script>

