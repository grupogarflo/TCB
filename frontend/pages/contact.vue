<template>
   <div class="contact my-12" id="checkout">
     <v-container>
       <v-row>
         <v-col cols="12">
           <h1>{{ $t('contact.title') }}</h1>
           <div v-html="$t('contact.text')"></div>

         </v-col>
       </v-row>
       <v-row>
         <v-col cols="12">
           <div class="wrap">
             <div class="form">
               <v-form>
                 <v-text-field
                   class="form-field rounded-lg"
                   v-model="name"
                   :placeholder="$t('forms.info.nameField')"
                   outlined
                   required
                   :error-messages="nameErrors"
                   @input="$v.name.$touch()"
                 ></v-text-field>
                 <v-text-field
                   class="rounded-lg"
                   v-model="email"
                   :placeholder="$t('forms.info.emailField')"
                   outlined
                   required
                   :error-messages="emailErrors"
                   @input="$v.email.$touch()"
                 ></v-text-field>
                 <v-text-field
                   class="rounded-lg"
                   v-model="phone"
                   :placeholder="$t('forms.info.phoneField')"
                   outlined
                   required
                   :error-messages="phoneErrors"
                   @input="$v.phone.$touch()"
                 ></v-text-field>
                 <v-textarea
                   v-model="coment"
                   name="input-7-1"
                   :placeholder="$t('contact.comments')"
                   outlined
                   required
                   @input="$v.coment.$touch()"
                   :error-messages="messagesErrors"
                 ></v-textarea>

                 <v-progress-linear
                   v-show="showLoading"
                   indeterminate
                   color="#1A5632"
                 ></v-progress-linear>
                 <v-btn block elevation="0" class="bookBtn rounded-lg py-5"  @click="nextStep">{{ $t('contact.btn') }}</v-btn>


               </v-form>
             </div>

           </div>
         </v-col>
       </v-row>

       <!--
       <v-row >
         <v-col cols="12" md="6">

         </v-col>
         <v-col md="1" class="pa-0"></v-col>
         <v-col cols="12" md="5" class="wrapCousto rounded-lg mt-3">

         </v-col>
       </v-row>
       -->
     </v-container>
   </div>
 </template>
 <script>
 import { validationMixin } from 'vuelidate'
 import { required, email } from 'vuelidate/lib/validators'
 export default {
   mixins: [validationMixin],
   validations: {
     name: { required },
     email: { required, email },
     coment: { required },
     phone: { required },
   },
   data() {
     return {
       name: '',
       email: '',
       phone: '',
       coment: '',
       showLoading: false,
       showBtnSend: true,
       showLoadingText: false,
       labelSendEmail:
         this.$store.state.booking.idioma === 2
           ? 'Sending information one moment please'
           : 'Enviando información un momento por favor',
     }
   },
   head() {
     return {
       title: 'Contact us | Cancuntours',
       meta: [
         {
           title: 'Contact us | Cancuntours',
           keywords:
             'Turtles, Travel Agency, Tour Operator, Playa del Carmen, Book, Tours, English',
           description:
             'Contact us at  Tours Riviera Maya Nature Scapes  for natural adventures in paradise',
         },
       ],
     }
   },
   computed: {
     nameErrors() {
       const errors = []
       if (!this.$v.name.$dirty) {
         return errors
       }
       !this.$v.name.required &&
         errors.push(
           this.$store.state.booking.idioma === 2
             ? 'Name is required.'
             : 'Se requiere el nombre.'
         )
       return errors
     },
     emailErrors() {
       const errors = []
       if (!this.$v.email.$dirty) return errors
       !this.$v.email.email &&
         errors.push(
           this.$store.state.booking.idioma === 2
             ? 'Must be valid e-mail'
             : 'Debe ser un correo electrónico válido'
         )
       !this.$v.email.required &&
         errors.push(
           this.$store.state.booking.idioma === 2
             ? 'E-mail is required'
             : 'Correo electronico es requerido'
         )
       return errors
     },
     phoneErrors() {
       const errors = []
       if (!this.$v.phone.$dirty) {
         return errors
       }
       !this.$v.phone.required &&
         errors.push(
           this.$store.state.booking.idioma === 2
             ? 'Phone is required.'
             : 'Teléfono es requerido.'
         )
       return errors
     },
     messagesErrors() {
       const errors = []
       if (!this.$v.coment.$dirty) {
         return errors
       }
       !this.$v.coment.required &&
         errors.push(
           this.$store.state.booking.idioma === 2
             ? 'Your message is important.'
             : 'Su mensaje es importante'
         )
       return errors
     },
   },
   methods: {
     nextStep() {
       this.$v.$touch()
       if (!this.$v.$invalid) {
         this.showBtnSend = false
         this.showLoading = true
         this.showLoadingText = true
         this.$axios
           .post('/emailContacto', {
             name: this.name,
             email: this.email,
             phone: this.phone,
             message: this.coment,
             language: 'ing',
           })
           .then((response) => {
             this.showLoading = false
             this.$store.state.booking.idioma === 2
               ? (this.labelSendEmail =
                   'your information has been sent, we will contact you shortly')
               : (this.labelSendEmail =
                   'su informacion ha sido enviado, en breve le contactaremos')
           })
           .catch((error) => {
             console.log(error)
             this.showBtnSend = true
             this.showLoading = false
             this.showLoadingText = false
             this.textErrorBook = `some error: ${error.response.status} . ${error.response.data.message}`
           })
       }
     },
   },
 }
 </script>
 <style scoped>
 h1 {
   font-size: 1.5rem !important;
 }
 h2 {
   font-size: 1.1rem;
   color: rgba(0, 0, 0, 0.87);
   font-weight: normal;
 }
 </style>
