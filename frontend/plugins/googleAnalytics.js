import Vue from "vue";
import VueGtag from "vue-gtag";

/*
const id = function(context){
   if(context.i18n.code==='en')
         {
            return 'G-WQRK93X8SX'
         }
         else{
            return 'G-PD3YRPJ4J8'
         }
}
*/

const vueGtag = ({app})=>{

   if(app.i18n.locale ==='es'){
      Vue.use(VueGtag, {

         config: { id: 'G-2Y6P9M8W1M' },

      });
   }
   else{
      Vue.use(VueGtag, {

         config: { id: 'G-D11JD93ZMF' },

      });
   }
}


export default vueGtag;
