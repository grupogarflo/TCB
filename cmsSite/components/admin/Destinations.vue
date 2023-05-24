<template>
  <div class="mx-auto my-8" max-width="600" :loading="cargandoTabla">
    <v-container>
      <v-row>
        <v-col col="12"> <p v-if="cargandoTabla">{{loadingText}}</p> </v-col>
      </v-row>
      <v-row>
          <v-col col="6" sm="4" md="3" v-for="(item, i) in items" :key="i">
              <v-checkbox v-model="modelItems" :value="item.id"  :label="item.name"></v-checkbox>

          </v-col>
      </v-row>

      <v-row>
          <v-col col="12" class="text-right">
            <v-btn  color="blue darken-1" text @click="selectCheck"
            >Guardar</v-btn>
          </v-col>
      </v-row>

      <v-alert
        v-model="alertMensajes"
        close-text="Close Alert"
        border="right"
        colored-border
        elevation="8"
        dismissible
        class="text-center my-5"
        :type="typeAlertaMensaje"
      >{{ textoAlertaMesaje }}</v-alert>
    </v-container>
  </div>
</template>
<script>
export default {
  props: ['idRegistroSend', 'claveSend'],
  data: () => ({
    items: [],
    // model: ['Carrots']
    modelItems: [],
    cargandoTabla: true,
    loadingText: 'Cargando... por favor espere',
    alertMensajes: false,
    typeAlertaMensaje: 'success',
    textoAlertaMesaje: ''
  }),

  created() {
    this.getRegistros()
    // console.log('yupi me creee' + this.idRegistroSend)
  },

  methods: {
    selectCheck(){
      // console.log(item)
      this.alertaError = false
      this.$axios
        .post('/addRemoveDestinationTour', {
          idTour: this.idRegistroSend,
          destinations: this.modelItems,
          claveSend: this.claveSend
        })
        .then(response => {

          this.alertMensajes = true
          this.typeAlertaMensaje = 'success'
          this.textoAlertaMesaje = `El tour se vinculo a los destinos seleccionados`

        })
        .catch(error => {
          // console.log(error.response.data.message)
          this.alertMensajes = true
          this.typeAlertaMensaje = 'error'
          this.textoAlertaMesaje = `${error.response.data.message}`
        })
    },
    getRegistros() {

      this.$axios
        .post('/getDestinationsCMS', {
          id: this.idRegistroSend
        })
        .then(response => {
          // this.items = response.data[0].categoria

           response.data.destinations.forEach(element=>{
               this.items.push({ id: element.id, name:element.name});
           })

          this.modelItems = response.data.checked;
          this.cargandoTabla = false
        }).catch(error => {
          //  this.textoDialogoEliminacion =
          //  'El tour que esta intentado eliminar no existe o ya fue eliminado previamente'
            alert('Existe un error al intentar recuperar la información')
            console.log(error);
        })
    }
  },

}
</script>
