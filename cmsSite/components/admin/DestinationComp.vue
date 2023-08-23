<template>
  <v-row>
    <v-col cols="8">
      <v-checkbox v-model="check" :input-value="check"   :label="item.name" @change="addDestinationValue"></v-checkbox>
    </v-col>
    <v-col cols="4">
      <v-text-field  v-show="check"
        v-model="order"
        label="Orden "
        @change="addOrder"
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

  mounted(){

      const pos = this.fromDatabase.map(element=>element.check).indexOf(this.item.id);

      if(pos===-1){
        return false;
      }

      this.check = true;
      this.order = this.fromDatabase[pos].order;
      this.$nuxt.$emit('addDestinationValue', this.item.id);
      this.$nuxt.$emit('addDestinationOrder',{value:this.item.id, order:this.order});
  },




  methods:{


    addDestinationValue(){
      if(this.check){
        this.$nuxt.$emit('addDestinationValue', this.item.id);
      }
      else{
        this.$nuxt.$emit('removeDestinationSelected', this.item.id);
      }
    },

    addOrder(){
      if(this.check){
        this.$nuxt.$emit('addDestinationOrder',{value:this.item.id, order:this.order});
      }
    }
  }



}


</script>
