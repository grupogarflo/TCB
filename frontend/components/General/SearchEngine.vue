<!-- eslint-disable vue/no-side-effects-in-computed-properties -->
<template>
  <v-container id="search-engine-content" class="px-0" >

   <v-row>
      <v-col cols="12">
         <v-expansion-panels focusable :value="open">
            <v-expansion-panel>
               <v-expansion-panel-header class="text-center py-5">
                  <span class="text-center">{{ $t('searchEngine.title') }}</span>
                  <template v-slot:actions>
                     <v-icon color="#EB008B" class="icon-abrir-formulario" size="10">

                     </v-icon>
                  </template>
               </v-expansion-panel-header>
               <v-expansion-panel-content class="py-5 px-5">
                  <v-row>
                     <v-col cols="12" :md="(open==1) ? '6' : '12'">

                     <v-select
                           v-model="modelSelectTour"
                           class="rounded-md  input-color"
                           dense
                           solo
                           flat
                           :items="items"
                           item-text="name"
                           item-value="id"
                           :placeholder="$t('general.tours')"
                           hide-details
                           @change="tourChange"
                           :menu-props="{
                              'offset-y':true
                           }"

                        >
                        <template #prepend >


                           <v-icon size="22" color="#EB008B" class="icon-estrella px-1 pl-2 py-2 "></v-icon>

                        </template>

                        <template #append>
                           <v-icon size="10"  color="#EB008B" class="icon-abrir-formulario "></v-icon>
                        </template>

                     </v-select>
                     </v-col>
                     <v-col cols="12" :md="(open==1) ? '6' : '12'">
                        <v-menu
                           v-if="!showPax"
                           v-model="showMenu"
                           dense
                           solo
                           bottom
                           offset-y
                           :close-on-content-click="false"
                           max-width="auto"







                        >
                           <template #activator="{ on, attrs }">
                              <v-btn
                                 id="pax-button"
                                 v-bind="attrs"
                                 block
                                 outlined
                                 color="white"
                                 class="rounded-md button-pax  px-0 text-center"
                                 plain
                                 v-on="on"
                              >
                                 <v-icon size="20" color="#EB008B"  class="mr-auto ml-2 icon-pax"> </v-icon>
                                 <span class="d-none d-sm-block text-center">
                                    {{ countAdults }} {{ $t('general.adults') }}, {{ countChild }} {{ $t('general.children') }}
                                 </span>
                                 <span class="d-sm-none d-block">{{ totalPax }} {{ $t('general.people') }}</span>
                                 <v-icon size="10" color="#EB008B" class="icon-abrir-formulario  ml-auto mr-5"></v-icon>
                              </v-btn>
                           </template>

                           <v-list>
                              <v-list-item>
                                 <v-list-item-title><strong>Persons:</strong></v-list-item-title>
                              </v-list-item>
                              <v-list-item>
                                 <v-list-item-content>
                                    <v-list-item-title>{{ $t('general.adults') }}: </v-list-item-title>
                                 </v-list-item-content>
                                 <v-list-item-action>
                                    <div class="d-inline-flex">
                                       <v-btn icon>
                                          <v-icon color="#EB008B" class="colorMasMenos" @click="lessAdults">mdi-minus-circle</v-icon>
                                       </v-btn>
                                       <p class="paxNumberSet">{{ countAdults }}</p>
                                       <v-btn icon>
                                          <v-icon color="#EB008B" class="colorMasMenos" @click="moreAdults">mdi-plus-circle</v-icon>
                                       </v-btn>
                                    </div>
                                 </v-list-item-action>
                              </v-list-item>
                              <v-list-item>
                                 <v-list-item-content>
                                    <v-list-item-title>{{ $t('general.children') }}: </v-list-item-title>
                                 </v-list-item-content>
                                 <v-list-item-action>
                                    <div class="d-inline-flex">
                                       <v-btn icon>
                                          <v-icon color="#EB008B" class="colorMasMenos" @click="lessChild">mdi-minus-circle</v-icon>
                                       </v-btn>
                                       <p class="paxNumberSet">{{ countChild }}</p>
                                       <v-btn icon>
                                          <v-icon color="#EB008B" class="colorMasMenos" @click="moreChild">mdi-plus-circle</v-icon>
                                       </v-btn>
                                    </div>
                                 </v-list-item-action>
                              </v-list-item>
                           </v-list>
                        </v-menu>

                        <v-select
                           v-if="showPax"
                           v-model="modelSelectPax"
                           class="rounded-md  input-color"
                           dense
                           solo
                           flat
                           :items="paxRanges"
                           item-text="name"
                           item-value="id"
                           :placeholder="$t('general.tours')"

                           hide-details
                           @change="tourChange"
                           :menu-props="{
                              'offset-y':true
                           }"

                        >
                           <template #prepend >


                              <v-icon size="20" color="#EB008B"  class="mr-auto ml-2 mt-2 icon-pax"> </v-icon>

                           </template>

                           <template #append>
                              <v-icon size="10"  color="#EB008B" class="icon-abrir-formulario "></v-icon>
                           </template>

                        </v-select>



                     </v-col>
                     <v-col cols="12"  :md="(open==1) ? '6' : '12'">
                        <v-menu
                           v-model="dateSelect"
                           :close-on-content-click="false"
                           transition="scale-transition"
                           offset-y
                           min-width="auto"
                           attach
                        >
                           <template #activator="{ on, attrs }">
                           <v-text-field
                              v-model="date"
                              flat
                              :min="minDate"
                              readonly
                              v-bind="attrs"
                              hide
                              solo
                              dense
                              class="rounded-md input-color"
                              hide-details
                              :placeholder="$t('general.dates')"
                              v-on="on"
                           >
                           <template #prepend >


                              <v-icon size="22" color="#EB008B" class="icon-calendario px-1 pl-2 py-2 "></v-icon>

                           </template>
                           <template #append>
                              <v-icon size="10" color="#EB008B" class="icon-abrir-formulario "></v-icon>
                           </template>
                           </v-text-field>
                           </template>
                           <v-date-picker
                           v-model="date"
                           no-title
                           :min="minDate"
                           :allowed-dates="allowedDates"
                           :locale="locale"
                           @input="dateSelect = false"
                           ></v-date-picker>
                        </v-menu>
                     </v-col>

                     <v-col cols="12" :md="(open==1) ? '6' : '12'">


                        <!--<div class="d-block" id="checkout-wrapper" v-if="showScript">-->

                           <v-btn depressed class="bookBtn rounded-lg" block

                              @click="openVentrata" >
                              {{ $t('general.book_now') }}
                           </v-btn>

                           <!-- <v-btn depressed class="bookBtn rounded-lg" block  >  </v-btn>
                        </div>-->

                        <div v-html="script"></div>
                     </v-col>

                  </v-row>

                  <v-row>
                     <v-col cols="12">
                        <v-alert type="error" v-model="dialog">
                           <span class="d-block">{{ errorTour }}</span>
                           <span class="d-block">{{ errorDate  }}</span>
                        </v-alert>
                     </v-col>
                  </v-row>

               </v-expansion-panel-content>
            </v-expansion-panel>
         </v-expansion-panels>
      </v-col>
   </v-row>



  </v-container>
</template>

<script>

import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
// import  loadScript  from "load-script";


export default {
   mixins: [validationMixin],
  // eslint-disable-next-line vue/require-prop-types
  props:{
      // eslint-disable-next-line vue/require-default-prop, vue/require-prop-type-constructor
      openPax: false,
      open:{
         type: Number,
         default:1
      },

      tourVentrataId: null,
  },

   validations: {
      tour: { required },
      date: {required}


      // selectedState:{required}
   },

  data() {
    return {
      modelSelectTour: [],
      items: [],
      // date: new Date(Date.now() - new Date().getTimezoneOffset() * 60000).toISOString().substr(0, 10),
      date: '',
      minDate: '',
      dateSelect: false,
      dialog: false,
      datesBlock: [],
      daysBlock: [],
      textAlertBook: '',
      paxRanges:[],
      modelSelectPax:null,
      showPax:false,
      errorTour:'',
      errorDate:'',
      showScript:false,



    }
  },


  computed: {


   tourError(){
      const errors = []
         if (!this.$v.tour.$dirty) return errors
         !this.$v.tour.required && errors.push(this.$t('general.selectTours'))
         return errors
   },
   dateError(){
      const errors = []
         if (!this.$v.date.$dirty) return errors
         !this.$v.date.required && errors.push(this.$t('general.selectDate'))
         return errors
   },
   showMenu(){

      if(this.openPax){
            // eslint-disable-next-line vue/no-side-effects-in-computed-properties
            this.dateSelect=true;
            return false;
      }

      return false
   },
    tours_state(){
      return this.$store.getters["booking/tours"];
    },
    countAdults() {
      return this.tours_state.adultos;


    },
    countChild() {
      return this.tours_state.ninos;
    },
    totalPax() {
      return (
        parseInt(this.tours_state.adultos) +
        parseInt(this.tours_state.ninos)
      )
    },
    locale(){
      return (this.$store.getters["booking/language"]===1) ? 'es' : 'en';

    }

    // validate if tour es atv dobles to show fields
    /*
        showFieldAtv() {
        // this.$store.state.booking.tours.id

        // id = 7  sencillo Admission + Single Ride
        // id= 11  sencillo Admission + Single Ride + transportation
        // id = 10 doble Double Ride ATV Tour
        // id=  12 doble Double Ride ATV Tour tranportation
        if (
          this.$store.state.booking.tours.id === 45 ||
          this.$store.state.booking.tours.id === 44
        ) {
          return false
        } else {
          return true
        }
      },
    */
  },



  created() {


      // set la fecha
      // console.log('fecha dels store ' + this.$store.state.booking.tours.url)
      const res = new Date()
      const day = res.getUTCDate()
      const month = res.getUTCMonth() + 1

      if ((day === 23 && month === 12) || (day === 30 && month === 12)) {
         res.setDate(res.getDate() + 3)
      } else {
         res.setDate(res.getDate() + 2)
      }

      this.minDate = res.toISOString().slice(0, 10)
      // this.date = res.toISOString().slice(0, 10)

      // this.getDatesBlock()

      // si tiene alguna fecha en el store se cambia
      if (this.tours_state.date !== '') {
         this.date = this.tours_state.date
      }
      /*
      // se valida si tiene algun tour seleccionado se dispara el metodo para recuperar las fechas y dias bloqueados
      if (this.$store.state.booking.tours.id !== '') {
         this.getDatesBlock()
      }
      */
     // this.render = this.render+1
  },

   mounted(){
      this.$nuxt.$on('')
      this.getRegistros()
      this.paxRange()

       // this.showScript = true
      /*
      const script  = document.createElement("script");
      script.type= "module";
      script.src = 'https://cdn.checkout.ventrata.com/v3/production/ventrata-checkout.min.js'
      script.setAttribute("data-config",'{"apiKey":"e98cbbf7-6ed8-4bfa-b192-500eaba4ebbb","env": "test" }')

      const wrapper = document.getElementById("checkout-wrapper")

      alert(wrapper);
   */


      // wrapper.innerHTML(script)
      // document.body.appendChild(script);
      // import  loadScript  from "load-script";
  },



  methods: {

   openVentrata(){


         // español

         this.clickCard()


   },
    tourChange() {
      // console.log('ite ' , this.modelSelectTour);
      // console.log('full ', this.items);
      const aux = this.items.find((item) => item.id === this.modelSelectTour)
      /*
      this.$store.commit('booking/destinationTours', {
        url: this.modelSelectTour,
        id: aux.id,
      })
      this.getDatesBlock()
      */

      if(aux.is_private){
         this.showPax=true;
      }
      else{
         this.showPax=false;
      }
      const  pay = {
        url: aux.name,
        id: aux.id,
      }

      const   aux2 =(this.modelSelectPax!==null) ?  this.paxRanges.map(element => element.id).indexOf(this.modelSelectPax): false;
      const namePax = (aux2!==false) ? this.paxRanges[aux2].name : '';

      this.$store.dispatch('booking/setDestinationTour',pay)
      this.$store.commit('booking/dataTours', {
                  id: aux.id,
                  name: aux.name,
                  url: aux.url,
                  img: aux.full_photo_path,
                  duration: aux.duration,
                  isPrivate:aux.is_private,
                  rates: aux.rates,
                  pax:this.modelSelectPax,
                  namePax,
                  tour_id: aux.tour_id

               })

      this.getDatesBlock();


    },
    clickCard() {
      // valida que este seleccionado un tour

      /*
      window.Ventrata({
	      "lang":"en",
	   })
      */

      let flag=false;

      if(this.date ===''){
         this.errorDate = this.$t('general.selectDate');
         flag= true;
      }
      if(this.modelSelectTour.length===0){
         this.errorTour  = this.$t('general.selectTour');
         flag=true;
      }

      if(flag){
         this.dialog=true;
         return false;
      }


        // set de los datos de promocode
        this.$store.commit('booking/addPromocode', {})

        // set la fecha seleccionada en el calendario
        this.$store.commit('booking/dateTours', {
          date: this.date,
        })

        // console.log(this.$route.name)
        // valida si estoy dentro de la ficha del tour entonces se manda al checkout
        // de lo contrario se manda a la ficha del tourr



      this.$router.push(this.localePath({
               name: 'checkout',

            } ))
    },
    moreAdults() {
      if (this.countAdults <= 24) {
        this.$store.commit('booking/addPaxTours', {
          adultos: true,
          ninos: false,
        })
      }
    },
    lessAdults() {
      if (this.countAdults >= 2) {
        this.$store.commit('booking/removePaxTours', {
          adultos: true,
          ninos: false,
        })
      }
    },
    moreChild() {
      if (this.countChild <= 24) {
        this.$store.commit('booking/addPaxTours', {
          adultos: false,
          ninos: true,
        })
      }
    },
    lessChild() {
      if (this.countChild >= 1) {
        this.$store.commit('booking/removePaxTours', {
          adultos: false,
          ninos: true,
        })
      }
    },
    async getRegistros() {

      try {
        await this.$axios
          .post('/getTourListSelect', {
            idioma: this.$store.getters['booking/language'],
          })
          .then((resp) => {
            this.items = resp.data.data
            // set select tour si tiene algo en el store
            // console.log(this.items);
            // console.log('name route ',this.$route.name);
            // console.log('locale ', this.$i18n.locale);
            // console.log(this.tours_state.url)
            // if(this.$route.name === 'slug___es'){
               if (
                  this.tours_state.url !== '' &&
                  typeof this.tours_state.url !== 'undefined'
               ){


                  const pos = this.items.map(element => element.url).indexOf(this.tours_state.url);



                  this.modelSelectTour = (pos!==false) ? this.items[pos].id : '';
                  this.getDatesBlock();

                  // alert(this.tours_state.isPrivate);
                  if(this.tours_state.isPrivate){
                     this.showPax=true;
                  }
                  else{
                     this.showPax=false;
                  }
               }
               else if (typeof this.$route.params.slug !== 'undefined') {
                  this.modelSelectTour = this.$route.params.slug;
                  if(this.tours_state.isPrivate){
                     this.showPax=true;
                  }
                  else{
                     this.showPax=false;
                  }
                  this.tourChange();
                  setTimeout(()=>{
                     this.getDatesBlock();

                  },500)
               }

            // }
            /*

            if(this.$route.name !== 'slug___'+this.$i18n.locale){
               if (
                  this.tours_state.url !== '' &&
                  typeof this.tours_state.url !== 'undefined'
               )
               {
                  // alert('pass');
                  this.modelSelectTour = this.tours_state.url
               }

               else if (typeof this.$route.params.slug !== 'undefined') {
                  const aux = this.items.find(
                     (item) => item.value === this.$route.params.slug
                  )
                  this.$store.commit('booking/destinationTours', {
                     url: aux.value,
                     id: aux.id,
                  })

                     this.modelSelectTour = aux.value
                     alert('this');
                     this.getDatesBlock2(aux.id)


               }
            }
            else{
               alert('ddddd')

            }
            */

          })
      } catch (e) {
        this.error = e.response.data.message
        // eslint-disable-next-line no-console
        console.log('error' + this.error);
      }
    },

      async paxRange() {

         try {
         await this.$axios
            .post('/getPaxtRange', {
               idioma: this.$store.getters['booking/language'],
            })
            .then((resp) => {
               // this.paxRange

               const aux = resp.data.data

               // console.log('pax ', aux);

               const wait=[];
               aux.forEach(element => {
                  let name='';
                  if( this.$store.getters['booking/language']===2)
                  { name = element.name_eng}
                  else{ name= element.name_esp  }

                  wait.push({
                     id:element.id,
                     name
                  })
               });

               // console.log('pax  w', wait);

               this.paxRanges = wait;




            })
         } catch (e) {

            // eslint-disable-next-line no-console
            console.log('error', e);
         }
      },


    /*
    getAllowedDates(value) {
      const date = moment(value)
      const day = date.format('dddd').toLowerCase()
      return this.days.includes(day)
    },
    */

    allowedDates(a) {
      const auxArr = []
      if (
        Object.keys(this.datesBlock).length !== 0 ||
        Object.keys(this.daysBlock).length !== 0
      ) {
        // valida si cae dentro de los dias bloqueados
        if (Object.keys(this.daysBlock).length !== 0) {
          const auxiliarDias = this.daysBlock.split(',').map(function (item) {
            return parseInt(item, 10)
          })

          if (auxiliarDias.includes(new Date(a).getDay())) {
            auxArr.push(a)
          }
        }
        // valida si tiene fechas bloqueadas
        if (Object.keys(this.datesBlock).length !== 0) {
          for (let a = 0; a < this.datesBlock.length; a++) {
            // console.log(this.datesBlock[a])
            auxArr.push(this.datesBlock[a])
          }
        }
        return !auxArr.includes(a)
      } else {
        return a
      }
      /*
      const dates = ['2022-03-10', '2022-03-20']
      if (new Date(a).getDay() === 0) dates.push(a)
      // const day = [1, 6]
      // const arrReturn = []

      // arrReturn.push(!dates.includes(a))
      // arrReturn.push(!day.includes(new Date(a).getDay()))

      // for (let i = 0; i < dates.length; i++) {
      //  if (!dates[i].includes(a)) {
      //    arrReturn.push(obj)
      //  }
      // }

      // return !dates.includes(a)
      // return !day.includes(new Date(a).getDay())
      // return ![0, 1].includes(new Date(a).getDay())
      console.log(dates)
      return !dates.includes(a)
      */
    },

    getDatesBlock() {
      this.$axios
        .post('getBlockDates', {
          id: this.tours_state.tour_id,
        })
        .then((response) => {
          // console.log(response)
          this.datesBlock = response.data.date
          this.daysBlock = response.data.days
        })
        .catch((error) => {
          return `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`
        })
    },

    getDatesBlock2(id) {
      this.$axios
        .post('getBlockDates', {
          id,
        })
        .then((response) => {
          // console.log(response)
          this.datesBlock = response.data.date
          this.daysBlock = response.data.days
        })
        .catch((error) => {
          return `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`
        })
    },
  }




}
</script>
<style lang="scss" >
.v-menu__content{
   z-index: 99 !important;
  //position: relative;
}
</style>
