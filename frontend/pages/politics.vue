<template>
   <div>
      <StaticLandings  :items="item"></StaticLandings>
      <v-container class="mt-5">
         <v-row>
            <v-col sm="12" class="my-16" >
               <div v-html="content"></div>
            </v-col>
         </v-row>
      </v-container>
   </div>
</template>

<script>
import StaticLandings from '~/components/General/StaticLandings.vue'
export default {
   components: { StaticLandings },
   data(){
      return{
         item:{
            title:this.$t('politics.title'),
            subtitle:this.$t('politics.subTitle'),
            class:'mayan-ruins',
            titleMeta:'',
               descriptionMeta:'',
               keywordsMeta:'',
         }
      }
   },
   head(){

      return {

            // eslint-disable-next-line object-shorthand
            title: this.titleMeta,
            htmlAttrs: {
               lang: this.$i18n.locale
            },
            meta:[
               {
                  hid: 'og:description',
                  name: 'og:description',
                  // content: context.$i18n.messages[context.$i18n.locale].meta.home.description
                  content: this.descriptionMeta


               },
               {
                  hid: 'og:keywords',
                  name: 'og:keywords',
                  content: this.keywordsMeta


               },


               {
                  hid: 'og:title',
                  name: 'og:title',
                  content: this.titleMeta


               },
            /*
            {
                  hid: 'og:image',
                  name: 'og:image',
                  content:this.imgMeta
               }
               */

            ]
         }

   },
   computed:{
      content(){
         return this.$t('politics.content');
      }
   },

   created(){
         this.titleMeta = this.$t('meta.politics.title');
         this.descriptionMeta= this.$t('meta.politics.description');
         this.keywordsMeta=this.$t('meta.politics.description');
   },

}
</script>
