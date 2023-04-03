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
        <v-toolbar-title>Relación categoría/destino</v-toolbar-title>
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
                <v-col cols="12" v-show="showTapIdiomas">
                  <v-card>
                    <v-tabs
                      background-color="blue darken-3"
                      center-active
                      dark
                      :key="componentKey"
                    >
                      <v-tab
                        v-for="item in languages"
                        v-bind:key="item.id"
                        @click="clickTap(item.id)"
                        >{{ item.name }}</v-tab
                      >
                    </v-tabs>
                  </v-card>
                </v-col>
              </v-row>
            </v-container>
            <formularioRelacionCateDesIdiomas
              :idioma="idioma"
              :idRegistroSend="idRegistro"
              :idiomaTamano="languages.length"
              @cerrarPop="close"
              :key="componentKey"
            />
            <v-alert
              v-model="alertMensajes"
              close-text="Close Alert"
              border="right"
              colored-border
              elevation="8"
              dismissible
              class="text-center"
              :type="typeAlertaMensaje"
              >{{ textoAlertaMesaje }}</v-alert
            >

            <v-progress-linear
              v-show="isLoading"
              indeterminate
              height="5"
              striped
              color="cyan"
            />
          </v-card>
        </v-dialog>
      </v-toolbar>

      <v-dialog v-model="dialogoEliminacion" max-width="450">
        <v-card>
          <v-card-title class="headline">Eliminación</v-card-title>

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
      <v-icon v-show="btnEdit" small class="mr-2" @click="editItem(item)"
        >mdi-pencil</v-icon
      >
      <v-icon v-show="btnDelete" small @click="deleteItem(item)"
        >mdi-delete</v-icon
      >
    </template>
  </v-data-table>
</template>

<script>
// import { validationMixin } from 'vuelidate'
// import { required, email } from 'vuelidate/lib/validators'
import formularioRelacionCateDesIdiomas from '~/components/admin/formularioRelacionCateDesIdiomas'

export default {
  // layout: 'admin',
  components: { formularioRelacionCateDesIdiomas },

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
      headers: [
        { text: 'Url', value: 'url' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      desserts: [],
      editedItem: {},
      defaultItem: {},
      idioma: '1',
      componentKey: 0,
      languages: '',
      showTapIdiomas: true,
      module: 6,
      btnNuevo: false,
      btnEdit: false,
      btnDelete: false,
    }
  },

  computed: {
    formTitle() {
      // return this.editedIndex === -1 ? 'Nueva categoria' : 'Editar categoria'
      return this.idRegistro === 0 ? 'Nuevo' : 'Editar'
      /*
      if (this.idRegistro === 0) {
        this.showTapIdiomas = true
        return 'Nuevo'
      } else {
        return 'Editar'
      }
      */
    },
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
    this.tapLanguages()
    this.getMeLvelUser()
  },

  methods: {
    async getRegistros() {
      try {
        await this.$axios.get('/getAllCategoriDestination', {}).then((resp) => {
          this.desserts = resp.data
          this.cargandoTabla = false
        })
      } catch (e) {
        this.error = e.response.data.message
      }
    },

    editItem(item) {
      this.idRegistro = item.category_destination_content_id
      this.idioma = item.language_id
      this.dialog = true
      this.showTapIdiomas = false
    },

    deleteItem(item) {
      this.dialogoEliminacion = true
      this.idRegistro = item.id
      this.textoDialogoEliminacion = `¿Desea realizar la eliminación: ${item.url}?`
    },

    close() {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.idRegistro = 0
      })
      // this.$v.$reset()
      this.getRegistros()
    },

    continuaEliminacion() {
      this.$axios
        .post('/deleteCategoriDestination', {
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
            'La relacion que esta intentado eliminar no existe o ya fue eliminado previamente'
        })
    },

    cancelaEliminacion() {
      this.dialogoEliminacion = false
      this.idRegistro = 0
      this.textoDialogoEliminacion = ''
    },

    clickTap(valor) {
      this.idioma = valor
    },

    forceRerender() {
      this.componentKey += 1
    },

    tapLanguages() {
      this.$axios
        .get('/getAllLanguage', {})
        .then((response) => {
          // this.languages = Object.assign({}, response.data[0])
          this.languages = response.data
        })
        .catch((error) => {
          this.alertMensajes = true
          this.typeAlertaMensaje = 'error'
          this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} no hay informacion lenguajes`
        })
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
