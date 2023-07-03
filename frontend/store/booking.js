export const state = () => ({
  // controla el idioma a usar  1 = español 2 = ingles
  // idioma: 1,
  // moneda: 'Mxn',
  idioma: null,
  moneda: null,
  unicoId: new Date().getUTCMilliseconds(),
  tours: {
    adultos: 2,
    ninos: 0,
    date: '',
    // tour: '',
    id: '',
    name: '',
    url: '',
    img: '',
    duration: '',
    promocode: {},
    descuento: 0,
    lastPrice: 0,
    total_usd: '',
    total_mxn:'',
    publico: '',
    isPrivate:false,
    rates:[],
    pax:0,
    namePax:''
  },
  client: {
    name: '',
    email: '',
    phone: '',
    hotel: '',
    country:'',
    state:'',
    city:''
  },
  payment: {
    status: '',
    id: '',
  },
})

export const mutations = {
   setLanguage(state, payload=1){
      state.idioma = payload
   },
   setCurrency(state,payload='MXN'){
      state.moneda = payload
   },
   setTotal(state, payload={usd:0, mxn:0}){
      state.tours.total_usd  = payload.usd;
     state.tours.total_mxn = payload.mxn;
   },
  addPaxTours(state, payload = {}) {
    if (payload.adultos) {
      // validate if one of this tours to increment in 2 pax
      if (state.tours.id === 45 || state.tours.id === 44) {
        state.tours.adultos = state.tours.adultos + 2
      } else {
        state.tours.adultos = state.tours.adultos + 1
      }
    }
    if (payload.ninos) {
      state.tours.ninos = state.tours.ninos + 1
    }
  },

  setPaxTours(state, payload = {}) {
    if (payload.adultos) {
      state.tours.adultos = payload.adultos
    }
    if (payload.ninos) {
      state.tours.ninos = payload.ninos
    }
  },

  removePaxTours(state, payload = {}) {
    if (payload.adultos) {
      // validate if one of this tours to less in 2 pax
      if (state.tours.id === 45 || state.tours.id === 44) {
        if (state.tours.adultos !== 2)
          state.tours.adultos = state.tours.adultos - 2
      } else {
        state.tours.adultos = state.tours.adultos - 1
      }
    }
    if (payload.ninos) {
      // let edades = state.hoteles.habitaciones.splice(payload, 1);
      state.tours.ninos = state.tours.ninos - 1
    }
  },

  dateTours(state, payload = {}) {
    state.tours.date = payload.date
  },

  destinationTours(state, payload = '') {
    state.tours.url = payload.url
    state.tours.id = payload.id
    // console.log("pay destinos tours" + JSON.stringify(payload));
  },

  dataTours(state, payload = '') {
    state.tours.id = payload.id
    state.tours.name = payload.name
    state.tours.url = payload.url
    state.tours.img = payload.img
    state.tours.duration = payload.duration
    state.tours.publico = payload.publico
    state.tours.isPrivate = payload.isPrivate
    state.tours.rates=payload.rates
    state.tours.pax=payload.pax
    state.tours.namePax = payload.namePax


  },

  addClient(state, payload = '') {
    state.client.name = payload.name
    state.client.email = payload.email
    state.client.phone = payload.phone
    state.client.hotel = payload.hotel
    state.client.country = payload.country
    state.client.state  = payload.state
    state.client.city = payload.city
  },

  addPromocode(state, payload = {}) {
    state.tours.promocode = payload

  },

  addTotal(state, payload = '') {
    state.tours.total = payload.total
  },

  reNewUnicoId(state) {
    state.unicoId = new Date().getUTCMilliseconds()
  },

  addDataPayment(state, payload = '') {
    state.payment.status = payload.status
    state.payment.id = payload.id
  },

  resetStoreData(state, payload = '') {
    state.unicoId = new Date().getUTCMilliseconds()
    state.tours.adultos = 2
    state.tours.ninos = 0
    state.tours.date = ''
    state.tours.url = ''
    state.tours.id = ''
    state.tours.id = ''
    state.tours.name = ''
    state.tours.url = ''
    state.tours.img = ''
    state.tours.duration = ''
    state.client.name = ''
    state.client.email = ''
    state.client.phone = ''
    state.client.hotel = ''
    state.tours.promocode = {}
    state.tours.descuento = 0
    state.tours.lastPrice = 0
    state.tours.total = ''
    state.tours.total_usd= ''
    state.tours.total_mxn=''
    state.payment.status = ''
    state.payment.id = ''
    state.tours.isPrivate=false
    state.tours.rates=[]
    state.tours.pax=0
    state.tours.namePax=''
  },


}

export const actions = {
   setLanguage (state,payload){
      state.commit('setLanguage',payload);

   },

   setCurrency(state,payload){
      state.commit('setCurrency',payload);
   },

   setTotal(state, payload){
      state.commit('setTotal', payload);
   },

   setPromocode(state,payload){
      state.commit('addPromocode', payload);
   },

   setDestinationTour(state,payload){


         state.commit('destinationTours',payload);

   }





}

export const getters = {

   tours(state){
      return state.tours;
   },

   language(state){
      return state.idioma;
   },
   currency(state){
      return state.moneda;
   },

   bookingTotal(state){
      return { usd: state.tours.total_usd, mxn:state.tours.total_mxn}
   },

   client(state){
      return state.client
   },

   languageName(state){
      if(state.idioma===2)
         return 'ing';
      return'esp';
   },

   getAllStore(state){
      return state
   }


}
