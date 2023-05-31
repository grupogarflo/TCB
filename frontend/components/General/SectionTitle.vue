<template>
   <span>
      <h1  v-if="title=='1'" class="title-section ">{{ titleText }} </h1>
      <h2 v-if="title=='2'" class="title-section ">{{ titleText }}</h2>
      <p v-if="title=='0'" class="title-section ">{{ titleText }} </p>
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
    }
  }

}
</script>
