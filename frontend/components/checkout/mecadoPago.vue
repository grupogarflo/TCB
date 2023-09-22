<template>
   <div>
      <v-row>
         <v-col cols="12" :class="[!mobile ? 'px-8': '']">
            <v-checkbox

                    required
                    :error-messages="termsErrors"
                    class="mt-0 mb-4 checkbox"
                    v-model="checkboxTerms"
                    :label="$t('forms.payment.accept')"
                  >
            </v-checkbox>
            <p>{{ $t('forms.payment.terms_cond') }}</p>

            <div v-html="$t('book_politics.content')" class="overflow-y-auto politics_show"></div>
         </v-col>

      </v-row>
      <v-row :class="[!checkboxTerms ? 'd-none':'d-block', 'mt-5']">
         <v-col cols="12">
            <div id="mecadoPago-button-container" ></div>
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

      loadMercadoPago(){
         const mp = new MercadoPago('TEST-29c17866-2c38-4c95-ac23-91c7073f4959', {
            locale: 'es-AR'
         });
         const bricksBuilder = mp.bricks();
         const renderPaymentBrick = async (bricksBuilder) => {
            const settings = {
               initialization: {
               /*
                  "amount" es el monto total a pagar por todos los medios de pago con excepción de la Cuenta de Mercado Pago y Cuotas sin tarjeta de crédito, las cuales tienen su valor de procesamiento determinado en el backend a través del "preferenceId"
               */
               amount: 1000000000000,
               preferenceId: "<PREFERENCE_ID>",
               payer: {
                  firstName: "",
                  lastName: "",
                  email: "",
               },
         },
         customization: {
            visual: {
               style: {
               theme: "default",
               },
            },
            paymentMethods: {
               creditCard: "all",
                                 debitCard: "all",
                                 ticket: "all",
                                 bankTransfer: "all",
                                 atm: "all",
                                 onboarding_credits: "all",
                                 wallet_purchase: "all",
               maxInstallments: 12
            },
         },
         callbacks: {
            onReady: () => {
               /*
               Callback llamado cuando el Brick está listo.
               Aquí puede ocultar cargamentos de su sitio, por ejemplo.
               */
            },
            onSubmit: ({ selectedPaymentMethod, formData }) => {
               // callback llamado al hacer clic en el botón de envío de datos
               return new Promise((resolve, reject) => {
               fetch("/process_payment", {
                  method: "POST",
                  headers: {
                     "Content-Type": "application/json",
                  },
                  body: JSON.stringify(formData),
               })
                  .then((response) => response.json())
                  .then((response) => {
                     // recibir el resultado del pago
                     resolve();
                  })
                  .catch((error) => {
                     // manejar la respuesta de error al intentar crear el pago
                     reject();
                  });
               });
            },
            onError: (error) => {
               // callback llamado para todos los casos de error de Brick
               console.error(error);
            },
         },
      };
         window.paymentBrickController = await bricksBuilder.create(
            "payment",
            "paymentBrick_container",
            settings
         );
    };
    renderPaymentBrick(bricksBuilder);
      },

      loadPayPalScript() {




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
                                          value: this.total.toFixed(2),
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

<style lang="scss" scoped>
 .politics_show{
   height: 20rem !important ;
 }
</style>
