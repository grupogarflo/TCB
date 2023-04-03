export const state = () => {
    return {
        valorAbreMenuMovilAdmin: false
        // drawer: false
    }
}

export const getters = {
    regresoMenuAdmin(state) {
        return state.valorAbreMenuMovilAdmin
    }
}

export const mutations = {
    openMenuAdmin(state) {
        // state.valorAbreMenuMovilAdmin = !state.valorAbreMenuMovilAdmin
        state.valorAbreMenuMovilAdmin = !state.valorAbreMenuMovilAdmin
    }

}

export const accion = {
    clickOpenMenuMovil({ commit }) {
        commit('abreMenuMovilAdmin')
    }
}