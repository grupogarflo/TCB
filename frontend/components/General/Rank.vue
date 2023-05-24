<template>
   <div>
      <v-icon v-for="(r,i) in 5" :key="i" :size="(home) ? 15 : 16 "  :class="[ (i<rank) ? 'colorRank':'', 'icon-estrella', (home) ? 'mr-1':'mr-6']"></v-icon>
   </div>
</template>

<script>
export default {

   props:{
      rank: {
         type: Number,
         default: 0
      },
      home:{
         type:Boolean,
         default:true
      }
   },



}
</script>
