<template>
  <v-data-table
    :headers="headers"
    :items="desserts"
    sort-by="calories"
    class="elevation-1"
    :search="search"
    item-key="name"
    :loading="cargandoTabla"
    loading-text="Cargando... por favor espere"
  >
    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Promocode</v-toolbar-title>
        <v-divider class="mx-4" inset vertical />
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
          class="mx-5"
        />
        <v-divider class="mx-4" inset vertical />
        <v-dialog v-model="dialog" max-width="100%">
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              v-show="btnNuevo"
              color="primary"
              dark
              class="mb-2"
              v-bind="attrs"
              v-on="on"
              >Nuevo</v-btn
            >
          </template>

          <v-card>
            <v-btn
              fab
              small
              dark
              color="primary"
              depressed
              :elevation="8"
              class="float-right mt-3 mr-5"
              @click="dialog = false"
              style="position: fixed; right: 2%; z-index: 90"
            >
              <v-icon>mdi-close</v-icon>
            </v-btn>

            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>
            <v-container fluid>
              <v-row>
                <v-col cols="12">
                  <v-card>
                    <formularioPromocode
                      :idRegistroSend="idRegistro"
                      :editBandera="editBandera"
                      @cerrarPop="close"
                      :key="componentKey"
                    />
                  </v-card>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
        </v-dialog>
      </v-toolbar>

      <v-dialog v-model="dialogoEliminacion" max-width="450">
        <v-card>
          <v-card-title class="headline"
            >Eliminación del promocode</v-card-title
          >

          <v-card-text>{{ textoDialogoEliminacion }}</v-card-text>

          <v-card-actions>
            <v-spacer />

            <v-btn color="green darken-1" text @click="cancelaEliminacion"
              >Cancelar</v-btn
            >

            <v-btn
              v-if="idRegistro != 0"
              color="green darken-1"
              text
              @click="continuaEliminacion"
              >Continuar</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-dialog>
    </template>

    <template v-slot:item.actions="{ item }">
      <v-icon v-show="btnEdit" small desnse class="mr-2" @click="editItem(item)"
        >mdi-pencil</v-icon
      >
      <v-icon v-show="btnDelete" small dense @click="deleteItem(item)"
        >mdi-delete</v-icon
      >
    </template>
  </v-data-table>
</template>

<script>
import formularioPromocode from '~/components/admin/formularioPromocode'

export default {
  // layout: 'admin',
  components: { formularioPromocode },

  data() {
    return {
      isLoading: false,
      cargandoTabla: true,
      alertMensajes: false,
      typeAlertaMensaje: 'success',
      textoAlertaMesaje: '',
      show3: false,
      error: null,
      dialog: false,
      dialogoEliminacion: false,
      textoDialogoEliminacion: '',
      search: '',
      idRegistro: 0,

      claveGet: '',

      headers: [
        { text: 'Nombre', value: 'code' },
        { text: 'Tipo', value: 'type_discount' },
        { text: 'Acción', value: 'actions', sortable: false },
      ],
      desserts: [],
      componentKey: 0,
      formTitle: 'Agregar bloqueo',
      editBandera: 0,
      module: 16,
      btnNuevo: false,
      btnEdit: false,
      btnDelete: false,
    }
  },

  watch: {
    dialog(val) {
      val || this.close()
      this.alertMensajes = false
      this.forceRerender()
    },
    dialogoEliminacion(val) {
      val || this.close()
    },
  },

  created() {
    this.getRegistros()
    this.getMeLvelUser()
  },

  methods: {
    async getRegistros() {
      try {
        await this.$axios.get('/getAllPromoCode', {}).then((resp) => {
          this.desserts = resp.data
          this.cargandoTabla = false
        })
      } catch (e) {
        this.error = e.response.data.message
      }
    },
    addItem(item) {
      this.idRegistro = 0
      this.editBandera = 0
      this.dialog = true
    },
    editItem(item) {
      this.idRegistro = item.id
      this.editBandera = 1
      this.dialog = true
    },

    deleteItem(item) {
      this.dialogoEliminacion = true
      this.idRegistro = item.id
      this.textoDialogoEliminacion = `¿Desea realizar la eliminación del promocode : ${item.code}?`
    },

    close() {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.idRegistro = 0
      })

      this.getRegistros()
    },

    continuaEliminacion() {
      this.$axios
        .post('/deletePromoCode', {
          id: this.idRegistro,
        })
        .then((response) => {
          this.cancelaEliminacion()
          this.getRegistros()
        })
        .catch((error) => {
          console.log(error)
          this.idRegistro = 0
          this.textoDialogoEliminacion =
            'El tour que esta intentado eliminar no existe o ya fue eliminado previamente'
        })
    },

    cancelaEliminacion() {
      this.dialogoEliminacion = false
      this.idRegistro = 0
      this.textoDialogoEliminacion = ''
    },

    forceRerender() {
      this.componentKey += 1
    },
    getMeLvelUser() {
      try {
        this.$axios
          .post('/getMeLevelUser', {
            idUser: this.$store.state.auth.user.id,
            idModule: this.module,
          })
          .then((resp) => {
            const aux = resp.data.data
            for (let a = 0; a < aux.length; a++) {
              if (aux[a].cms_actions_id === 1) {
                this.btnNuevo = true
              }
              if (aux[a].cms_actions_id === 2) this.btnEdit = true
              if (aux[a].cms_actions_id === 4) this.btnDelete = true
            }
          })
      } catch (e) {
        this.error = e.response.data.message
        console.log('error' + e)
      }
    },
  },
}
</script>
<style scoped>
#wrapImg {
  height: auto;
}
</style>
