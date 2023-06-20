<template>
    <div :class="['d-block', 'mb-2', !mobile ? 'text-right':'text-center']">

        <a href="https://api.whatsapp.com/send?phone=529841062511" ><span class="mx-2 social icon-whatsapp"></span></a>
        <a href="https://www.instagram.com/cancunbaytours/"><span class="mx-2 social icon-instagram"></span></a>
        <a href="https://www.facebook.com/cancunbaytours/"><span class="mx-2 social icon-facebook"></span></a>

        <!--<span class="mx-2 social icon-carrito" v-if="!mobile"></span>-->


    </div>
</template>

<script>
export default {
   computed:{
      language(){
         return  this.$store.getters['booking/language']
      },
      mobile(){
            return this.isMobile();
      }


   },
}
</script>
