<template>
   <v-container>
         <v-row>
            <v-col cols="6">
               <v-card class="rounded-lg" width="100%"  @click="goToToursDetails(first)">
                  <v-img :src="first.photo"  class="destinationsImg rounded-lg align-end" gradient="to bottom, rgba(255,255,255,0) 80%, rgba(0,0,0,0.75)" >
                     <v-card-title  class="img-name justify-start">{{ first.name }}</v-card-title>
                  </v-img>
               </v-card>
            </v-col>
            <v-col cols="6">
               <v-card class="rounded-lg" width="100%" @click="goToToursDetails(second)">
                  <v-img :src="second.photo"  class="destinationsImg rounded-lg align-end" gradient="to bottom, rgba(255,255,255,0) 80%, rgba(0,0,0,0.75)">
                     <v-card-title  class="img-name justify-start">{{ second.name }}</v-card-title>
                  </v-img>
               </v-card>
            </v-col>
         </v-row>
         <v-row v-for="(item, i) in items" :key="i">
            <v-col v-for="(ele,j) in item" :key="j" :cols="(j==0) ? '6':'3'">
               <v-card class="rounded-lg" width="100%" @click="goToToursDetails(ele)">
                  <v-img :src="ele.photo" class="destinationsImg rounded-lg align-end" gradient="to bottom, rgba(255,255,255,0) 80%, rgba(0,0,0,0.75)">
                     <v-card-title  class="img-name justify-start">{{ ele.name }}</v-card-title>

                  </v-img>
               </v-card>

            </v-col>

         </v-row>
   </v-container>
</template>


<script>
export default {

   data(){
      return {

            first:{},
            second:{},
            items:[]
      }

   },
   computed:{
      language(){
         return this.$store.getters['booking/language'];
      },
   },


   mounted(){
      this.init();
   },

   methods:{

      init(){

         this.$axios.get('/getDestinationsAll').then(response=>{

            const destinations = response.data.destinations;

            // console.log('destinations ', response);
            let count = 1;
            let totalItem =[];

            
            destinations.forEach(element=>{
               
               // eslint-disable-next-line camelcase
               let name_element = '';

               let url='';

               if(this.language===1){
                  // eslint-disable-next-line camelcase
                  name_element = element.destination_content_esp.name
                  url = element.destination_content_esp.url
               }
               else{
                  // eslint-disable-next-line camelcase
                  name_element = element.destination_content_eng.name
                  url=element.destination_content_eng.url;
               }

               if(count===1){
                  this.first= {
                     id:element.id,
                     name:name_element,
                     photo: element.destination_content_esp.full_photo_path,
                     url

                  }
               }
               else if(count===2){
                  this.second= {
                     id:element.id,
                     name:name_element,
                     photo: element.destination_content_esp.full_photo_path,
                     url

                  }
               }
               else{

                  if(totalItem.length<3  && count<=destinations.length){

                     totalItem.push({
                        id:element.id,
                        name:name_element,
                        photo: element.destination_content_esp.full_photo_path,
                        url
                     })
                  }
                  if(totalItem.length===3 || count===destinations.length){
                     this.items.push(totalItem)
                     totalItem=[];
                  }

               }

               count++;

            })





         })
      },
      goToToursDetails(item){

         this.$router.push(this.localePath({
            name:'slug',
            params:{
               slug:item.url
            }
         }))

      }
   }

}
</script>
