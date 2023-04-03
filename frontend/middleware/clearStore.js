export default function(context) {
   context.store.commit('booking/resetStoreData', {})
   return false;
}
