import createPersistedState from 'vuex-persistedstate'

export default ({ store }) => {
  new createPersistedState({
    paths: ['booking', 'cart', 'payment'],
  })(store)
}
