<template>
  <v-card class="mx-auto my-8" max-width="600" :loading="cargandoTabla">
    <v-card-title v-if="cargandoTabla">{{loadingText}}</v-card-title>
    <v-list v-show="!cargandoTabla">
      <v-list-item-group v-model="modelItems" multiple>
        <template v-for="(item, i) in items">
          <v-list-item
            :key="`item-${i}`"
            active-class="light-blue darken-3 white--text text--accent-4"
            two-line
            @click="selectCheck(item)"
          >
            <template v-slot:default="{ active }">
              <v-list-item-content>
                <v-list-item-title v-text="item.name"></v-list-item-title>
              </v-list-item-content>

              <v-list-item-action>
                <v-checkbox :input-value="active" color="white accent-4"></v-checkbox>
              </v-list-item-action>
            </template>
          </v-list-item>
          <v-divider v-if="i < items.length-1" :key="`divider-${i}`"></v-divider>
        </template>
      </v-list-item-group>
    </v-list>

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
  </v-card>
</template>
<script>
export default {
  props: ['idRegistroSend', 'claveSend'],
  data: () => ({
    items: [
      { name: 'Tours de Naturaleza y Aventura', id: 1 },
      { name: 'algo', id: 2 }
    ],
    //model: ['Carrots']
    modelItems: [1],
    cargandoTabla: true,
    loadingText: 'Cargando... por favor espere',
    alertMensajes: false,
    typeAlertaMensaje: 'success',
    textoAlertaMesaje: ''
  }),
  methods: {
    selectCheck(item) {
      //console.log(item)
      this.alertaError = false
      this.$axios
        .post('/addRemoveCatTour', {
          idTour: this.idRegistroSend,
          idCategoria: item.category_id,
          claveSend: this.claveSend
        })
        .then(response => {})
        .catch(error => {
          //console.log(error.response.data.message)
          this.alertMensajes = true
          this.typeAlertaMensaje = 'error'
          this.textoAlertaMesaje = `${error.response.data.message}`
        })
    },
    getRegistros() {
      this.$axios
        .post('/getCategoriaTour', {
          id: this.idRegistroSend
        })
        .then(response => {
          this.items = response.data[0].categoria
          this.modelItems = response.data[0].checado
          this.cargandoTabla = false
        })
        .catch(error => {
          //this.textoDialogoEliminacion =
          //  'El tour que esta intentado eliminar no existe o ya fue eliminado previamente'
          alert('Existe un error al intentar recuperar la información')
        })
    }
  },
  created() {
    this.getRegistros()
    //console.log('yupi me creee' + this.idRegistroSend)
  }
}
</script>