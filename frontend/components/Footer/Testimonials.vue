<template>
   <div :style="['width:100%;',  'height:'+height+';']">

         <v-slide-group


            :show-arrows="(!mobile) ? true : false"
            center-active

            :style="'height:'+height2+''"

         >

               <v-slide-item
               v-for="(item,n) in items"
               :key="n"

               >
                  <v-card
                     :class="[(!mobile) ? 'pa-10 ma-2':'pa-6 ma-1']"
                     :height="height"
                     :width="width"

                     elevation="5"
                  >
                     <v-row
                        class="fill-height"
                        align="center"
                        justify="center"
                     >
                     <v-col cols="12">
                        <p class="testimonials-name mb-0">{{ item.author }}</p>
                        <p class="testimonials-date">{{ item.date}}</p>
                        <p class="testimonials-text">"{{ item.text}}"</p>
                        <p class="testimonials-tour mt-8">{{ item.tour }}</p>

                     </v-col>

                     </v-row>
                  </v-card>
               </v-slide-item>

         </v-slide-group>

   </div>
</template>



<script>
export default {
   data(){
      return {
         show:2
      }
   },

   computed:{
      language(){
         return this.$store.getters['booking/language'];
      },


      items(){
         if(this.language===1){
            // esp
            return [
               {
                  tour:"Tour a Holbox desde Playa del Carmen & Tulum",
                  date:"Jul 2023",
                  author:"Raquel Eileen V",
                  link:"https://www.tripadvisor.com/ShowUserReviews-g150812-d2153780-r902318687-Cancun_Bay_Tours-Playa_del_Carmen_Yucatan_Peninsula.html",
                  text:"Excelente tour Todo excelente, la atención por parte del guía Raymundo y el chófer fue excelente, la información respecto a los destinos fue muy interesentante e informativa, recomendadisima experiencia"
               },

               {
                  tour:"Tulum y Coba con Nado en Cenote desde Playa del Carmen",
                  date:"Jul 2023",
                  author:"Hugo G",
                  link:"https://www.tripadvisor.com/ShowUserReviews-g150812-d2153780-r902318484-Cancun_Bay_Tours-Playa_del_Carmen_Yucatan_Peninsula.html",
                  text:"Maravilloso tour Excelente tourq, el guía muy amable, agradable y servicial, todo de maravilla, fue increíble! Volveríamos muchas veces más!"
               },

               {
                  tour:"Chichen Itzá y Coba con Nado en Cenote desde Playa del Carmen",
                  date:"Jul 2023",
                  author:"Alba A",
                  link:"https://www.tripadvisor.com/ShowUserReviews-g150812-d2153780-r902299891-Cancun_Bay_Tours-Playa_del_Carmen_Yucatan_Peninsula.html",
                  text:"Buen servicio y atención al cliente. Bellos lugares, un guía agradable, se respeta el itinerario. Horario accesible y buen precio."
               },

               {
                  tour:"Chichen Itzá Clásico con Cenote Saamal y Valladolid desde Cancun",
                  date:"Jul 2023",
                  author:"MARIO G",
                  link:"https://www.tripadvisor.com/ShowUserReviews-g150812-d2153780-r902299891-Cancun_Bay_Tours-Playa_del_Carmen_Yucatan_Peninsula.html",
                  text:"Experiencia inolvidable El cenote está espectacular me fascino. Los guías muy al pendiente y amables no hacen distinción entre los extranjeros y mexicanos. Excelente servicio por parte de ellos."
               },


            ]
         }
        return [
               {
                  tour:"Chichen Itza Tour Options with Cenote Swim from Playa del Carmen",
                  date:"Jul 2023",
                  author:"Shelby S",
                  link:"https://www.tripadvisor.com/ShowUserReviews-g150812-d2153780-r902462272-Cancun_Bay_Tours-Playa_del_Carmen_Yucatan_Peninsula.html",
                  text:"You should do this. Last minute we thought about seeing the almighty area of Chichen Itza and we decided on this tour through our hotel’s excursion agency. Victor (our tour guide) was awesome."
               },
               {
                  tour:"Chichen Itza Premier Private Tour",
                  date:"Jul 2023",
                  author:"Andi R",
                  link:"https://www.tripadvisor.com/ShowUserReviews-g150812-d2153780-r902336847-Cancun_Bay_Tours-Playa_del_Carmen_Yucatan_Peninsula.html",
                  text:"100% Worth The Trip! We did the Chichen Itza full day tour (with the cenote and Spanish colonial town) and loved it. Chichen Itza is truly incredible, but the tour guides’ vast knowledge and excellent presentation skills"
               },

               {
                  tour:"Chichen Itza Premier Private Tour",
                  date:"Jul 2023",
                  author:"Sean B",
                  link:"https://www.tripadvisor.com/ShowUserReviews-g150812-d2153780-r902302804-Cancun_Bay_Tours-Playa_del_Carmen_Yucatan_Peninsula.html",
                  text:"Loved the detailed explanation of the city of Chichen Itza. The Cenote also was especially beautiful just limited time!"
               },
               {
                  tour:"Chichen Itza Premier Private Tour",
                  date:"Jul 2023",
                  author:"Ivan I",
                  link:"https://www.tripadvisor.com/ShowUserReviews-g150812-d2153780-r902301201-Cancun_Bay_Tours-Playa_del_Carmen_Yucatan_Peninsula.html",
                  text:"Great times Amazing, Victor was an excellent guide. The events were more than amazing but the extra drinks were amazing too."
               }

         ];
      },
      mobile(){
         // console.log(this.$route.name);
         return this.isMobile()
      },

      namebP(){
         return this.$vuetify.breakpoint.name
      },

      height(){
         if(this.$vuetify.breakpoint.name === "xs"){
            return "300px";
         }

         if(this.$vuetify.breakpoint.name === "sm"){
            return "300px";
         }

         return "350px";



      },

      height2(){
         if(this.$vuetify.breakpoint.name === "xs"){
            return "320px";
         }

         if(this.$vuetify.breakpoint.name === "sm"){
            return "320px";
         }

         return "370px";



      },

      width(){

         if(this.$vuetify.breakpoint.name === "xs"){
            const width =  window.innerWidth;
            return width-15
         }

         if(this.$vuetify.breakpoint.name === "sm"){
            const width =  window.innerWidth/2;
            return width-10
         }
         const width =  window.innerWidth/3;
         return width+5;
      },

   }
}
</script>
