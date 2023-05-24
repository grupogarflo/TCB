<template>
   <div>
      <div v-if="!mobile">
         <v-container id="Header" fluid >
            <v-row>
               <v-container class="py-7">
                  <v-row  align="end">
                     <v-col md="3">
                        <img class="logo" src="/images/layout/logo.svg"  @click="goToHome"/>
                     </v-col>
                     <v-col md="7" offset-md="2">
                        <v-row no-gutters align="end" justify="end">
                           <v-col sm="8" class="details text-right" align-self="end">
                              <div class="d-inline">
                                 {{ $t('header.call_free') }}
                                 <v-select
                                    :items="items"
                                    background-color="transparent"
                                    class="phone-numbers hide-details rounded-0 ml-3"
                                    :hide-details=true
                                    dense
                                    solo
                                    flat
                                    label="Desde México  01 (984) 242 - 0070"
                                 >
                                    <template #item="{item}">
                                       <span class="phone-numbers-options">{{ item }}</span>
                                    </template>
                                 </v-select>
                              </div>
                           </v-col>
                           <v-col sm="4"  align-self="end">
                              <RedesSociales></RedesSociales>
                              <Language></Language>
                           </v-col>
                        </v-row>
                     </v-col>
                  </v-row>
               </v-container>
            </v-row>

         </v-container>
         <v-container id="categoriesMenu" fluid  >
            <MenuCategories></MenuCategories>
         </v-container>
      </div>

      <v-container v-else  id="Header" fluid >
         <v-row>
            <v-container class="pt-10">
               <v-row align="end" no-gutters>
                  <v-col sm="4" align-self="end" class="text-left "></v-col>
                  <!--
                     <span class="mx-2 social icon-carrito"></span>
                  </v-col>-->

                  <v-col sm="3" class="text-center " >
                     <v-img class="logo" src="/images/layout/logotipo.png" @click="goToHome"/>
                  </v-col>
                  <v-col sm="5" class="text-right ">

                     <v-icon color="#E1AC66" @click.stop="menu = !menu">mdi-menu</v-icon>


                  </v-col>
               </v-row>

               <v-navigation-drawer v-model="menu" app class="menuMobile">
                  <v-row no-gutters class="pr-3 pt-5">
                     <v-col sm="12" class="text-right">
                        <v-icon color="#E1AC66"  @click.stop="menu = false" >mdi-close-circle</v-icon>
                     </v-col>
                  </v-row>
                  <MenuCategories></MenuCategories>

                  <RedesSociales></RedesSociales>
               </v-navigation-drawer>
            </v-container>
         </v-row>
      </v-container>
   </div>
</template>

<script>
import RedesSociales from './RedesSociales.vue';
import Language from './Language.vue';
import MenuCategories from './MenuCategories.vue';

export default {
    components: { RedesSociales, Language, MenuCategories},
    data: () => ({
        items: [
            "Desde México 01 (984) 242 - 0070 ",
            "USA / CAN  (888) 257 5547",
        ],
        menu:false
    }),
    computed:{
         mobile(){
            return this.isMobile();
         }
    },

    methods:{
      goToHome(){
         this.$router.push('/')
      }
    }
}
</script>

<style  scoped>

   .v-select-list{
      background-color: #EB008B !important;
      border-radius: 0px !important;
      color:  white !important;

   }
   .v-list .v-list-item--active .v-list-item__title {
      color: white !important;
   }

   .v-list-item__title:hover{
      color: #ffd54f !important;
   }



</style>




