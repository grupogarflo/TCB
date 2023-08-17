export const state = () => {
    return {
        valorAbreMenuMovilAdmin: false,

        tourDestinations:[]
    }
}

export const getters = {
    regresoMenuAdmin(state) {
        return state.valorAbreMenuMovilAdmin
    },

    toursDestinations(state){
      return state.tourDestinations;
   },
}

export const mutations = {
    openMenuAdmin(state) {
        // state.valorAbreMenuMovilAdmin = !state.valorAbreMenuMovilAdmin
        state.valorAbreMenuMovilAdmin = !state.valorAbreMenuMovilAdmin
    },

    addTourDestination(state, payload={}){
      state.tourDestinations.push({
        check:payload.check,
        order:payload.order
      });
    },

    updateOrder(state, payload={}){

      state.tourDestinations[payload.pos].order = payload.order;
    },

    clearTourDestination(state){
      state.tourDestinations = [];
    },

    removeTourDestination(state,payload){

        // alert(payload);
        state.tourDestinations.splice(payload,1);
        // state.tourDestinations = [];
        // state.tourDestinations = temp;

    }



}

export const actions = {
    clickOpenMenuMovil({ commit }) {
        commit('abreMenuMovilAdmin')
    },

    addTourDestination(state,payload){
      state.commit('addTourDestination',payload);
    },

    updateOrder(state,payload){

       // const pos = state.tourDestinations.indexOf(payload.check);

       state.commit('updateOrder',{
          pos:payload.pos,
          order:payload.order
       })

    },
    clearTourDestination(state){
      state.commit('clearTourDestination');
    },

    removeTourDestination(state,payload){

        state.commit('removeTourDestination',payload.pos);
    }


}
