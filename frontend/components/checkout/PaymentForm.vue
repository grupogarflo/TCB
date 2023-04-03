<template>
   <div>
      <v-row>
         <v-col cols="12" class="text-center">

            <SectionTitle :titleText="$t('forms.payment.title')"></SectionTitle>
         </v-col>
      </v-row>
      <v-row>
         <v-col cols="12" :class="[!mobile ? 'px-8': '']">
            <v-text-field
               v-model="name"
               :placeholder="$t('forms.payment.nameField')"
               dense
               class="form-field rounded-lg "
               solo
               single-line
               filled
               flat
               hide-details="auto"
               :error-messages="nameErrors"
               @input="$v.name.$touch()"
            ></v-text-field>
         </v-col>
      </v-row>
      <v-row>
         <v-col cols="12" :class="[!mobile ? 'px-8': '']">
            <v-text-field
               v-model="email"
               :placeholder="$t('forms.payment.emailField')"
               dense
               class="form-field rounded-lg "
               solo
               single-line
               filled
               flat
               hide-details="auto"
               :error-messages="emailErrors"
               @input="$v.email.$touch()"
            ></v-text-field>
         </v-col>
      </v-row>
      <v-row>
         <v-col cols="12"  :class="[!mobile ? 'px-8': '']">
            <v-text-field
               v-model="card"
               :placeholder="$t('forms.payment.cardField')"
               dense
               class="form-field rounded-lg "
               solo
               single-line
               filled
               flat
               hide-details="auto"
               :error-messages="cardErrors"
               @input="$v.card.$touch()"
            ></v-text-field>
         </v-col>
      </v-row>
      <v-row>
         <v-col cols="6" :class="[!mobile ? 'px-8': '']">
            <v-select
               v-model="month"
               :items="months"
               :placeholder="$t('forms.payment.monthField')"
               dense
               class="form-field rounded-lg "
               solo
               single-line
               filled
               flat
               hide-details="auto"
               :error-messages="monthErrors"
               @input="$v.month.$touch()"
            ></v-select>
         </v-col>
         <v-col cols="6" :class="[!mobile ? 'px-8': '']">
            <v-select
               v-model="year"
               :items="years"
               :placeholder="$t('forms.payment.yearField')"
               dense
               class="form-field rounded-lg "
               solo
               single-line
               filled
               flat
               hide-details="auto"
               :error-messages="yearErrors"
               @input="$v.year.$touch()"
            ></v-select>
         </v-col>
      </v-row>
      <v-row>
         <v-col cols="12" :class="[!mobile ? 'px-8': '']">
            <v-text-field
               v-model="cvv"
               :placeholder="$t('forms.payment.cvvField')"
               dense
               class="form-field rounded-lg "
               solo
               single-line
               filled
               flat
               hide-details="auto"
               type="password"
               :error-messages="cvvErrors"
               @input="$v.cvv.$touch()"
            ></v-text-field>
         </v-col>

      </v-row>
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
      <v-row>
         <v-col cols="12" :class="[!mobile ? 'px-8': '']">
            <v-btn block elevation="0" class="bookBtn rounded-lg py-5" :loading="loading" @click="nextStep">{{  $t('forms.payment.btnSubmit') }}</v-btn>

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

         </v-col>
      </v-row>
   </div>
</template>


<script>
import moment from 'moment'
import { validationMixin } from 'vuelidate'
import { required, email, numeric } from 'vuelidate/lib/validators'


import SectionTitle from '~/components/General/SectionTitle.vue';



export default {
   components:{ SectionTitle},
   mixins: [validationMixin],
   validations: {
      name: { required },
      email: { required, email },
      card: { required },
      month: { required },
      year: { required },
      cvv: { required, numeric },
      checkboxTerms: {
         checked(val) {
         return val
         },
      },
   },

   data(){
      return {
            name:'',
            email:'',
            card:'',
            month:'',
            year:'',
            cvv:'',
            checkboxTerms:false,
            radioGroup: 'card',
            showAlert: false,
            loading:false
      }
   },

   computed:{
      months(){

         // eslint-disable-next-line prefer-const
         let  array = [];
         for(let i=1;  i<13; i++){
            let value = i
            if(i<10)  value='0'+value
            array.push(value);
         }

         return array
      },

      years(){

         // eslint-disable-next-line camelcase, prefer-const
         let current_year = moment().year();
         // eslint-disable-next-line camelcase, prefer-const
         let last_year = moment().add(30,'y').year();
         // eslint-disable-next-line prefer-const
         let  array = [];
         // eslint-disable-next-line camelcase
         for(let i=current_year;  i<last_year; i++){
            array.push(i);
         }

         return array
      },

      nameErrors() {
      const errors = []
      if (!this.$v.name.$dirty) {
        return errors
      }
      !this.$v.name.required && errors.push(this.$t('forms.payment.nameValidation'))
      return errors
      },
      emailErrors() {
         const errors = []
         if (!this.$v.email.$dirty) return errors
         !this.$v.email.email && errors.push(this.$t('forms.info.emailValidation'))
            !this.$v.email.required && errors.push(this.$t('forms.info.emailValidation2'))
         return errors
      },
      cardErrors() {
         const errors = []
         if (!this.$v.card.$dirty) return errors

         !this.$v.card.required && errors.push(this.$t('forms.payment.cardValidation'))
         return errors
      },
      monthErrors() {
         const errors = []
         if (!this.$v.month.$dirty) return errors
         !this.$v.month.required && errors.push(this.$t('forms.payment.monthValidation'))
         return errors
      },
      yearErrors() {
         const errors = []
         if (!this.$v.year.$dirty) return errors
         !this.$v.year.required && errors.push(this.$t('forms.payment.yearValidation'))
         return errors
      },
      cvvErrors() {
         const errors = []
         if (!this.$v.cvv.$dirty) {
         return errors
         }
         !this.$v.cvv.required && errors.push(this.$t('forms.payment.cvvValidation'))
         return errors
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

      stateData(){
         return this.$store.getters["booking/getAllStore"]
      },

      language(){
         return this.$store.getters["booking/languageName"]
      },

      languageCode(){
         return this.$store.getters["booking/language"]
      },

      mobile(){
         return this.isMobile()
      },

   },

   methods:{

      newTab() {

         const url = this.localePath({name: 'terms'});
         window.open(url, '_blank', 'noreferrer');
      },


      nextStep() {
         this.loading=true;
         this.showAlert = false;
         this.$v.$touch()
         if (!this.$v.$invalid || !this.showCampos) {
            this.dialog = true
            this.textErrorBook = ''

            let total =this.stateData.tours.total_mxn;
            if(this.language==='ing'){
               total = this.stateData.tours.total_usd;
            }

            // valid if tour has promo code

            const promocode = this.stateData.tours.promocode;

            if(typeof promocode !=='undefined' && Object.entries(promocode).length !== 0){
               total = promocode.data_mxn;
               if(this.language==='ing'){
                  total = promocode.data_usd;
               }
            }



            this.$axios
               .post('/addPayment', {
                  code_book: this.stateData.unicoId,
                  site_book: 'yalku.tours',
                  status: 'pending',
                  name: this.stateData.client.name,
                  email: this.stateData.client.email,
                  phone: this.stateData.client.phone,
                  language: this.language,
                  amount: total ,
                  currency: this.stateData.moneda,
                  hotel: this.stateData.client.hotel,
                  toursInfo: this.stateData.tours,
                  merch: this.radioGroup,
               })
               .then((response) => {
                  // se dispara el proceso del cobro
                  // console.log('response ',response)

                  this.makePay(response.data.data.clientId)
               })
               .catch((error) => {
                  console.log(error)
                  this.textErrorBook = `some error: ${error.response.status} . ${error.response.data.message}`
                  this.showAlert=true;
                  this.loading=false;
                  /*
                     this.isLoading = false
                     this.alertMensajes = true
                     this.typeAlertaMensaje = 'error'
                     this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`
                  */
               })
         }
      },

      makePay(clientId) {
         let url
         if (this.radioGroup === 'card') {
            url = '/paymentProcess'
         } else {
            url = '/paymentTransferBank'
         }

         let total =this.stateData.tours.total_mxn;
         if(this.language==='ing'){
            total = this.stateData.tours.total_usd;
         }

         /// valid if tour has promocode

         const promocode = this.stateData.tours.promocode;

         if(typeof promocode !=='undefined' && Object.entries(promocode).length !== 0){
            total = promocode.data_mxn;
            if(this.language==='ing'){
               total = promocode.data_usd;
            }
         }

         this.$axios
            .post(url, {
               card_no: this.card,
               expiry_mounth: this.month,
               expiry_year: this.year,
               cvc: this.cvv,
               name: this.name,
               email: this.email,
               uniqueId: this.stateData.unicoId,
               currency: this.stateData.moneda,
               descriptionItem:
                 'YALKU: Tour reservation ' +
                 this.stateData.tours.name +
                 ' adults: ' +
                 this.stateData.tours.adultos +
                 ' child: ' +
                 this.stateData.tours.ninos,
               amount: total,
               paymentMethodType: 'card',
               clientidd: clientId,
               language: this.languageCode,
            })
            .then((response) => {
               // se dispara el proceso del cobro
               // console.log(response)
               if (response.data.status === 200) {
                 // valida el tipo de pago seleccionado para las acciones correctas
                 this.updatePayment(clientId, response.data.id)

               } else {
                 this.textErrorBook = `error: ${response.data.code} . ${response.data.message}`
                 this.showAlert=true;
                 this.loading=false;

               }
            })
            .catch((error) => {
               console.log(error)
               this.textErrorBook = `error: ${error.response.status} . ${error.response.data.message}`
               this.showAlert=true;
               this.loading=false;
               /*
                 this.isLoading = false
                 this.alertMensajes = true
                 this.typeAlertaMensaje = 'error'
                 this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`
               */
            })
      },

      updatePayment(client, payment) {
         this.$axios
            .post('/updatePayment', {
               clientId: client,
               authorization: payment,
               status: 'complet',
               idioma: this.languageCode,
            })
            .then((response) => {
               this.loading=false
               this.$nuxt.$emit('confirmation');
            })
            .catch((error) => {
               console.log(error)
               this.textErrorBook = `some error: ${error.response.status} . ${error.response.data.message}`
               this.showAlert=true;
               this.loading=false;
               /*
                 this.isLoading = false
                 this.alertMensajes = true
                 this.typeAlertaMensaje = 'error'
                 this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`
               */
            })
      },

      clickRadio() {
         console.log(this.radioGroup)
         if (this.radioGroup === 'card') {
         this.textBoton = 'Pay'
         this.showCampos = true
         } else {
         this.textBoton = 'Continue'
         this.showCampos = false
         }
      },
   }
}
</script>
