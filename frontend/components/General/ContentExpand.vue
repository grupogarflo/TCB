<template>
   <v-expansion-panels

      class="expandContent"
      :accordion="true"
      flat
      :value="open"

    >
      <v-expansion-panel expand >
         <v-expansion-panel-header class="expandContent-title px-0" actions >
            {{ title }}
            <template v-slot:actions>
               <v-icon color="#EB008B" class="icon-abrir-formulario" size="10">
               </v-icon>
            </template>
         </v-expansion-panel-header>
            <v-expansion-panel-content v-if="is_html" class="expandContent-info px-0 mt-5" >


               <div v-html="content"></div>
            </v-expansion-panel-content>
            <v-expansion-panel-content v-else class="expandContent-info px-0 mt-5" >
               <div>{{ content }}}</div>
            </v-expansion-panel-content>

         </v-expansion-panel>
    </v-expansion-panels>
</template>

<script>
export default {
   props:{
      title:{
         type: String,
         default: ''
      },
      content:{
         type: String,
         default:''
      },
      // eslint-disable-next-line vue/prop-name-casing
      is_html:{
         type:Boolean,
         default:false
      }
   },
   data(){
      return {
         open:0
      }
   }
}
</script>
