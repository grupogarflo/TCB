<template>
  <div class="wrap">
    <v-card class="mx-auto my-1" max-width="100%" :loading="cargandoTabla">
      <v-card-title v-if="cargandoTabla">{{ loadingText }}</v-card-title>
      <v-container>
        <v-row>
          <v-col v-for="(item, i) in items" :key="i" cols="12" class="py-0">
            <v-checkbox
              v-model="selected"
              :label="item.name_esp"
              :value="item.id"
              color="primary accent-4"
              @click="clickCheck(item)"
              :multiple="true"
            ></v-checkbox>
            <v-divider v-if="i < items.length - 1"></v-divider>
          </v-col>
        </v-row>
      </v-container>
    </v-card>
  </div>
</template>

<script>
export default {
  props: ['idRegistroSend', 'claveSend'],
  data: () => ({
    items: [],
    // model: ['Carrots']
    // modelItems: [1],
    cargandoTabla: true,
    loadingText: 'Cargando... por favor espere',
    alertMensajes: false,
    typeAlertaMensaje: 'success',
    textoAlertaMesaje: '',
    selected: [],
  }),
  methods: {
    /* selectCheck(item) {

      console.log(this.$refs.rolesSelected);
      this.$emit("aplicaTour", item);
    },
    */
    clickCheck(item) {
      // this.$emit('aplicaTour', this.selected)
      // console.log(item)
      this.alertaError = false
      this.$axios
        .post('/addRemoveIconTour', {
          idTour: this.idRegistroSend,
          idCategoria: item.id,
          claveSend: this.claveSend,
        })
        .then((response) => {})
        .catch((error) => {
          // console.log(error.response.data.message)
          this.alertMensajes = true
          this.typeAlertaMensaje = 'error'
          this.textoAlertaMesaje = `${error.response.data.message}`
        })
    },
    getRegistros() {
      this.$axios
        .post('/getIcons', {
          id: this.idRegistroSend,
        })
        .then((response) => {
          this.items = response.data[0].tours
          this.selected = response.data[0].checado
          this.cargandoTabla = false
        })
        .catch((error) => {
          console.log(error)
          // this.textoDialogoEliminacion =
          //  'El tour que esta intentado eliminar no existe o ya fue eliminado previamente'
          alert('Existe un error al intentar recuperar la información')
        })
      /*
      this.$axios
        .post("/getAllToursPromocode", {
          id: this.idRegistroSend,
        })
        .then((response) => {
          this.items = response.data[0].tours;
          this.selected = response.data[0].checado;
          this.cargandoTabla = false;
        })
        .catch((error) => {
          console.log(error);
          // this.textoDialogoEliminacion =
          //  'El tour que esta intentado eliminar no existe o ya fue eliminado previamente'
          alert("Existe un error al intentar recuperar la información");
        });
        */
    },
  },
  mounted() {
    this.getRegistros()
    // console.log('click aqui mounted')
  },
  created() {
    this.getRegistros()
    // console.log('click aqui creado')
  },
  /* watch: {
    cont(newVal, oldVal) {
      this.modelItems = []
    },
  }, */
}
</script>
