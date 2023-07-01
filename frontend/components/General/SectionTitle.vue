<template>
   <span>
      <h1  v-if="title=='1'" class="title-section ">{{ titleText }} </h1>
      <h2 v-if="title=='2'" class="title-section ">{{ titleText }}</h2>
      <p v-if="title=='0'" class="title-section ">{{ titleText }} <v-chip v-if="discount!==null" color="#EB008B" class="text-center ml-5 py-1 discount-detail d-flex-inline">
         -{{ discount  | discount}}
      </v-chip> </p>

  </span>
</template>


<script>
export default {

  props:{
    // eslint-disable-next-line vue/require-default-prop, vue/require-prop-type-constructor
    titleText:"",
    title:{
      type:String,
      default:"0"
    },
    discount:{
      type:String,
      default:null
    }
  }

}
</script>
