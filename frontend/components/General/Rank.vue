<template>
   <div>
      <v-icon v-for="(r,i) in rank" :key="i" size="15"  class="icon-estrella mr-1"></v-icon>
   </div>
</template>

<script>
export default {

   props:{
      rank: {
         type: Number,
         default: 0
      }
   },



}
</script>
