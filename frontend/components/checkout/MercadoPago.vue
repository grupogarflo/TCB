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

               <div id="paymentBrick_container"></div>

               <v-alert
                  v-if="showAlert"
                  border="right"
                  colored-border
                  type="error"
                  elevation="2"
                  class="mt-3"
                  dismissible
               >
                  {{ textErrorBook }}
            </v-alert>
            <v-alert
                  v-if="showAlertPending"
                  border="right"
                  colored-border
                  type="warning"
                  elevation="2"
                  class="mt-3"
                  dismissible
               >
                  {{ textErrorBookPending }}
            </v-alert>
         </v-col>
      </v-row>
   </div>
 </template>


<script>

// import  loadScript  from "load-script";
// import moment from 'moment'
import { validationMixin } from 'vuelidate'
// import { required, email, numeric } from 'vuelidate/lib/validators'
// import { loadMercadoPago } from "@mercadopago/sdk-js"
 import { loadMercadoPago } from "@mercadopago/sdk-js";;
// import { Payment } from '@mercadopago/sdk-js';

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
         mp: null,

         bricksBuilder:null,
         accessToken:null,
         client_info:null,
         showAlert:false,
         textErrorBook:'',
         showAlertPending:false,
         textErrorBookPending:''


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

      bookingTotal(){

         /*   if (this.$store.getters['booking/language'] ===2) return 'USD'
            return 'MXN'
         */
        const total =this.$store.getters["booking/bookingTotal"];
        const formatter = new Intl.NumberFormat('en-US');
        console.log(total);

        return (this.$store.getters['booking/language'] ===2) ? formatter.format(Math.ceil(total.usd.toFixed(2)))  : formatter.format(Math.ceil(total.mxn.toFixed(2)))
      },

   },

   mounted() {

      this.$nextTick(() => {
         setTimeout(() => {
            this.init();

         }, 500)
      })

      // this.loadPayPalScript()
      // this.loadMercadoPago();


  },



   methods:{


      init(){
         this.$axios.post('/mercado-pago/getToken',{
            tourData:this.stateData.tours,
            currency:this.currency,
            total:this.bookingTotal,
            clientId:this.clientId
         }).then(response=>{

            // console.log(response.data);
            this.token = response.data.token_id;

            this.renderPaymentBrick();
         }).catch(error=>console.log(error));
      },


      async renderPaymentBrick(){
         loadMercadoPago();
         this.mp = new window.MercadoPago('APP_USR-63cac48c-ec59-45f7-be27-bb160c6bf4fa', {
            locale: 'es-AR'
         });
         this.bricksBuilder = this.mp.bricks();
         const settings={
               initialization:{
                  amount: this.bookingTotal,
                  preferenceId:this.token,
                  marketplace: true,
               },
               customization: {
                  visual: {
                     style: {
                        theme: "default",
                     }
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
                  }
               },
               callbacks: {
                  onReady: () => {
                     /*
                     Callback llamado cuando el Brick está listo.
                     Aquí puede ocultar cargamentos de su sitio, por ejemplo.
                     */
                    alert('ready');
                  },
                  onSubmit: ({ selectedPaymentMethod, formData }) => {
                     // callback llamado al hacer clic en el botón enviar datos
                     /*
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
                           console.log(error);
                           reject(error);
                        });
                     });
                     */

                     // console.log('selected method and formData',[selectedPaymentMethod, formData]);
                     // this.paymentPP()

                     /// proceso de pago
                     alert(selectedPaymentMethod);
                     this.$axios.post('/mercado-pago/process_payment',{
                        formData,
                        method:selectedPaymentMethod,
                        clientId:this.clientId,
                        tour:this.stateData.tours,
                     }).then(response=>{

                        console.log(response);
                        if(response.data.status!=="approved" && response.data.status!=="pending")
                        {
                           /// error
                           this.showAlert=true;
                           this.textErrorBook = this.$t('forms.payment.error');
                        }

                        this.paymentPP(response.data.id, response.data.status, selectedPaymentMethod, response.data);





                     }).catch(error=>{console.log(error) });




                  },
                  onError: (error) => {
                     // callback llamado para todos los casos de error de Brick
                     console.error(error);
                  },
               },

         };
         await this.bricksBuilder.create(
            "payment",
            "paymentBrick_container",
            settings
         );


      },

      /*
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
      */
      paymentPP(ppId, statusP, selectedPaymentMethod, returnData) {
         this.$axios
         .post('/updatePayment', {
            clientId: this.clientId,
            authorization: String(ppId),
            status: statusP,
            idioma: this.languageCode,
            merch:selectedPaymentMethod

         })
         .then((response) => {
            // console.log(response)
            if(selectedPaymentMethod!=='ticket'){
               this.$nuxt.$emit('confirmation');
            }
            else{
               this.showAlertPending=true
               this.textErrorBookPending = this.$t('forms.payment.redirect');

               setTimeout(()=>{
                  window.location.href=returnData.details.external_resource_url;
               },4000);

            }

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
