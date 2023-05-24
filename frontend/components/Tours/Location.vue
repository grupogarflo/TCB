<template>
   <div class="GoogleMap">
     <GmapMap :center="center" :zoom="15" map-type-id="roadmap" :style="'height:'+height">
       <GmapMarker
         goo
         :position="{ lat:parseFloat(lat), lng: parseFloat(lng) }"
         :clickable="true"
         :draggable="true"
       />
     </GmapMap>
     <div
       v-if="height!='450px'"
       :class="['text-center', 'white pa-1', { 'rounded-lg': !isMobile() }]"
     >

     </div>
   </div>
 </template>
 <script>
 export default {
   props: {
     lat: 0.0,
     lng: 0.0,
     height: {
       type: String, // not 'string'
       required: false,
       default: "450px"
     }
   },
   computed: {
     center() {
       return { lat: parseFloat(this.lat), lng: parseFloat(this.lng) };
     }
   }
 };
 </script>
