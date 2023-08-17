<template>
  <v-row>
    <v-col cols="8">
      <v-checkbox v-model="check" :input-value="check"   :label="item.name" @change="addState"></v-checkbox>
    </v-col>
    <v-col cols="4">
      <v-text-field  v-show="check"
        v-model="order"
        label="Orden "
        @change="addState"
      ></v-text-field>
    </v-col>
  </v-row>
</template>


<script>
export default {
  props: ['item', 'fromDatabase'],

  data(){
    return {
      check:false,
      order:0

    }
  },
  computed:{

      toursDestinations(){
        // eslint-disable-next-line dot-notation
        return this.$store.getters['toursDestinations'];
      }
  },

  mounted(){
    const pos = this.fromDatabase.map(element=> element.check).indexOf(this.item.id);

    // alert(pos);

    if(pos!==-1){

      this.check=true;
      this.order = this.fromDatabase[pos].order

    }



  },


  methods:{
    addState(){

      const pos = this.toursDestinations.map(element=> element.check).indexOf(this.item.id);


      if(this.check){

        if(pos===-1){
          this.$store.dispatch('addTourDestination',{
              check:this.item.id,
              order:this.order
          });
        }
        else{
          // alert('edit');
          this.$store.dispatch('updateOrder',{
              pos,
              order:this.order
          })
        }
      }
      else{
        /// remove from state
        this.$store.dispatch('removeTourDestination',{
            pos,
        })

      }




    }
  }



}


</script>
