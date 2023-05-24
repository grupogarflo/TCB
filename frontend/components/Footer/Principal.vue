<template>
    <v-container fluid id="Footer">
        <v-row>
            <v-container :class="[ !mobile ? 'py-7': 'pt-14 ']">
                <v-row  align="center" justify=center>
                    <v-col sm="8" class="text-center">
                        <img class="logo mx-auto" src="/images/layout/logo.svg" />
                        <div class="text-center mt-6 links">
                              <span class="d-block">
                                 <nuxt-link class="mx-2 menu" :to="localePath({name:'terms'})"> {{ $t(('menu.footer.terms_conditions')) }} </nuxt-link>
                                 | <nuxt-link class="mx-2 menu" :to="localePath({name:'politics'})"> {{ $t(('menu.footer.terms_politics')) }} </nuxt-link>
                                 | <nuxt-link class="mx-2 menu" :to="localePath({name:'about-us'})"> {{ $t(('menu.footer.about-us')) }} </nuxt-link>
                              </span>
                              <span class="d-block"> Cancun Bay Tours, 2023</span>


                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-row>
    </v-container>
</template>

<script>
export default {
   computed:{
      mobile(){
         return this.isMobile()
      }
   }
}
</script>
