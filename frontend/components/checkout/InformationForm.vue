<template>
   <div>
      <v-row>
         <v-col cols="12" class="text-center">

            <SectionTitle :titleText="$t('forms.info.title')"></SectionTitle>
         </v-col>
      </v-row>
      <v-row>
         <v-col cols="12" :class="[!mobile ? 'px-8': '']">
            <v-text-field
               v-model="name"
               :placeholder="$t('forms.info.nameField')"
               dense
               class="form-field rounded-lg"
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
               :placeholder="$t('forms.info.emailField')"
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
         <v-col cols="12" :class="[!mobile ? 'px-8': '']">
            <v-select
                  v-model="selectedCountry"
                  solo
                  single-line
                  filled
                  flat
                  hide-details="auto"
                  :error-messages="selectedCountryErrors"
                  item-text="name"
                  item-value="isoCode"
                  class="form-field rounded-lg"
                  :items="countries"
                  :label="$t('forms.info.country')"
                  required
                  @input="$v.selectedCountry.$touch()"

                  @change="getState"

                ></v-select>

         </v-col>
      </v-row>

      <v-row>
         <v-col cols="12" :class="[!mobile ? 'px-8': '']">
            <v-select
                        class="rounded-lg"
                        :items="states"
                        :label="$t('forms.info.state')"
                        solo
                        single-line
                        filled
                        flat
                        hide-details="auto"
                        item-text="name"
                        item-value="isoCode"
                        v-model="selectedState"
                        return-object
                     ></v-select>
         </v-col>
      </v-row>


      <v-row>
         <v-col cols="12" :class="[!mobile ? 'px-8': '']">
            <v-text-field
               v-model="city"
               :placeholder="$t('forms.info.city')"
               dense
               class="form-field rounded-lg "
               solo
               single-line
               filled
               flat
               hide-details="auto"

            ></v-text-field>
         </v-col>
      </v-row>


      <v-row>
         <v-col cols="12" :class="[!mobile ? 'px-8': '']">
            <v-text-field
               v-model="phone"
               :placeholder="$t('forms.info.phoneField')"
               dense
               class="form-field rounded-lg "
               solo
               single-line
               filled
               flat
               hide-details="auto"
               :error-messages="phoneErrors"
               @input="$v.phone.$touch()"

            ></v-text-field>
         </v-col>
      </v-row>
      <v-row>
         <v-col cols="12" :class="[!mobile ? 'px-8': '']">
            <v-text-field
               v-model="hotel"
               :placeholder="$t('forms.info.hotelField')"
               dense
               class="form-field rounded-lg "
               solo
               single-line
               filled
               flat
               hide-details="auto"

            ></v-text-field>
            <span >{{ $t('forms.info.alertHotel') }}</span>
         </v-col>
      </v-row>
      <v-row>
         <v-col cols="7" md="8" :class="[!mobile ? 'px-8': '']">
            <v-text-field
               v-model="promoCode"
               :placeholder="$t('forms.info.promoCodeField')"
               dense
               class="form-field rounded-lg "
               solo
               single-line
               filled
               flat
               hide-details="auto"

            ></v-text-field>
            <v-alert
                    color="orange"
                    dismissible
                    elevation="2"
                    type="info"
                    v-model="alertPromo"
                    >{{ textAlertPromo }}</v-alert
                  >
         </v-col>
         <v-col cols="5" md="4" :class="[!mobile ? 'px-8': '']">
            <v-btn elevation="0" class="bookBtn rounded-lg py-5" :loading="loadingPromo" block @click="validatePromcode"> {{ $t('forms.info.btnPromo') }}</v-btn>
         </v-col>
      </v-row>
      <v-row>
         <v-col cols="12" :class="[!mobile ? 'px-8': '']">
            <v-btn block elevation="0" class="bookBtn rounded-lg py-5" :loading="loading"  @click="triggerPayment">
               {{ $t('forms.info.btnSubmit') }}
            </v-btn>
         </v-col>
      </v-row>
   </div>
</template>


<script>
import { Country, State} from 'country-state-city';
import { validationMixin } from 'vuelidate'
import { required, email } from 'vuelidate/lib/validators'
import SectionTitle from '~/components/General/SectionTitle.vue'



export default {
   components:{ SectionTitle},
   mixins: [validationMixin],
   validations: {
      name: { required },
      email: { required, email },
      phone: { required },
      selectedCountry: { required },
      // selectedState:{required}



   },

   data(){

      return {
         name:'',
         email:'',
         phone:'',
         hotel:'',
         promoCode:'',
         // total:0
         alertPromo: false,
         textAlertPromo:'',
         loading:false,
         loadingPromo:false,
         total:null,
         radioGroup: 'card',
         countries:Country.getAllCountries(),
         states:[],
         cities:[],
         selectedCountry: '',
         selectedState: '',
         city:''



      }
   },



   computed:{
      selectedCountryErrors() {
         const errors = []
         if (!this.$v.selectedCountry.$dirty) {
            return errors
         }
         !this.$v.selectedCountry.required &&
         errors.push(
            this.$store.state.booking.idioma === 2
               ? 'Country is required.'
               : 'Se requiere el país.'
         )
         return errors
      },

      nameErrors(){
         const errors = []
         if (!this.$v.name.$dirty) {
            return errors
         }
         !this.$v.name.required && errors.push(this.$t('forms.info.nameValidation'));
         return errors
      },
      emailErrors() {
         const errors = []
         if (!this.$v.email.$dirty) return errors
         !this.$v.email.email && errors.push(this.$t('forms.info.emailValidation'))
         !this.$v.email.required && errors.push(this.$t('forms.info.emailValidation2'))
         return errors
      },
      phoneErrors() {
         const errors = []
         if (!this.$v.phone.$dirty) {
            return errors
         }
         !this.$v.phone.required && errors.push(this.$t('forms.info.phoneValidation'))
         return errors
      },
      hotelErrors() {
         const errors = []
         if (!this.$v.hotel.$dirty) {
         return errors
         }
         !this.$v.hotel.required && errors.push(this.$t('forms.info.hotelValidation'))
         return errors
      },
      language(){
         return this.$store.getters["booking/languageName"]
      },
      tourDetail(){
        return this.$store.getters["booking/tours"];
      },
      currency(){
        return this.$store.getters["booking/currency"];
      },

      totals(){
         if(this.language === 'ing') return this.$store.getters["booking/bookingTotal"].usd;

         return this.$store.getters["booking/bookingTotal"].mxn
      },
      mobile(){
         return this.isMobile()
      },

      languageId(){
         return this.$store.getters["booking/language"]
      },


      stateData(){
         return this.$store.getters["booking/getAllStore"]
      },


      languageCode(){
         return this.$store.getters["booking/language"]
      },


      countrySend(){

         const  country = this.selectedCountry;

         const aux = this.countries.map(element=> element.isoCode).indexOf(country);

         return (aux!==false) ? this.countries[aux] : {}

      }


   },



   methods:{






      triggerPayment(){
         this.$v.$touch()
         if (this.$v.$invalid) {
            return false;
         }
         /*
            this.$store.commit('booking/addTotal', {
               total: this.total,
            })
         */




         this.$store.commit('booking/addClient', {
            name: this.name,
            email: this.email,
            phone: this.phone,
            hotel: this.hotel,
            country: this.countrySend,
            state: this.selectedState,
            city:this.city
         })
         this.sendPreBook()

      },
      sendPreBook() {
         this.loading=true;
         this.$axios
         .post('/preBookEmail', {
            name: this.name,
            email: this.email,
            phone: this.phone,
            language: this.language,
            amount: this.totals.usd,
            currency: this.currency,
            toursInfo: this.tourDetail,
            hotel: this.hotel,
            country: this.countrySend,
            state: this.selectedState,
            city:this.city
         })
         .then((response) => {

            this.createPayment();
         })
         .catch((error) => {
            console.log(error)
            this.loading = false;
            this.textErrorBook = `some error: ${error.response.status} . ${error.response.data.message}`
         })
      },

      createPayment(){
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
               this.$axios
                  .post('/addPayment', {
                     code_book: this.stateData.unicoId,
                     site_book: 'yalku.tours',
                     status: 'pending',
                     name: this.stateData.client.name,
                     email: this.stateData.client.email,
                     phone: this.stateData.client.phone,
                     language: this.language,
                     amount: this.total ,
                     currency: this.stateData.moneda,
                     hotel: this.stateData.client.hotel,
                     toursInfo: this.stateData.tours,
                     merch: this.radioGroup,
                     country: this.countrySend,
                     state: this.selectedState,
                     city:this.city
                  })
                  .then((response) => {
                     // se dispara el proceso del cobro
                     // console.log('response ',response)
                     this.loading=false;
                     this.$nuxt.$emit('goPaymentEvent',{client:response.data.data.clientId, total:this.total, });

                  });
      },

      validatePromcode() {
         this.alertPromo = false
         this.textAlertPromo = ''
         this.loadingPromo = true;
         let ban = true
         if (this.promoCode === '') {
            this.alertPromo = true
            this.textAlertPromo = this.$t('forms.info.validPromoCode')
            ban = false
            this.loadingPromo = false;
         }
         if (ban) {
            this.$axios
               .post('getPromoCode', {
                  id: this.tourDetail.tour_id,
                  date: this.tourDetail.date,
                  promocode: this.promoCode,
                  adult: this.tourDetail.adultos,
                  child: this.tourDetail.ninos,
                  idioma: this.languageId,
                  moneda: this.currency,
                  is_private: this.tourDetail.isPrivate,
                  private_rate: (this.tourDetail.isPrivate) ? this.tourDetail.pax : null
            })
            .then((response) => {
               /*
                  this.diferencia = response.data.discount
               this.lastPrice = response.data.last
               this.total = response.data.data
               this.textPromo = response.data.promocode
               */

               this.loadingPromo = false;

               this.$store.dispatch('booking/setPromocode',response.data);



            })
            .catch((error) => {
               console.log(error.response.data.data)
               this.alertPromo = true
               this.textAlertPromo = error.response.data.data
               this.loadingPromo = false;

            })
         }
      },

      getState(value) {
        // alert(value);
         this.states = State.getStatesOfCountry(value)
      },

   }



}
</script>
