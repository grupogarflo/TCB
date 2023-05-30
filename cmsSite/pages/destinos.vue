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
        <v-toolbar-title>Destinos</v-toolbar-title>
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
                      <v-tab @click="clickImgTap()">Media</v-tab>
                    </v-tabs>
                    <div id="wrapForm" v-show="wrapForm">
                      <formularioDestinosIdiomas
                        :idioma="idioma"
                        :idRegistroSend="idRegistro"
                        :idiomaTamano="languages.length"
                        @cerrarPop="close"
                        @claveNueva="claveGet = $event"
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
                    </div>
                    <div id="wrapImg" v-show="wrapImg">
                      <imgUpload
                        :claveSend="claveGet"
                        :idiomaSend="idioma"
                        :idRegistroSend="idRegistro"
                        :imgGet="imgGet"
                        :urlAddImg="urlAddImg"
                        @cerrarPop="close"
                        :key="componentKey"
                      />
                    </div>
                  </v-card>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
        </v-dialog>
      </v-toolbar>

      <v-dialog v-model="dialogoEliminacion" max-width="450">
        <v-card>
          <v-card-title class="headline">Eliminación de detinos</v-card-title>

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
import formularioDestinosIdiomas from '~/components/admin/formularioDestinosIdiomas'
import imgUpload from '~/components/admin/imgUpload'

export default {
  // layout: 'admin',
  components: { formularioDestinosIdiomas, imgUpload },

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
      wrapImg: false,
      wrapForm: true,
      claveGet: '',
      imgGet: 'n/a',
      urlAddImg: 'addImgDestination',
      headers: [
        { text: 'Name', value: 'name' },
        { text: 'Url', value: 'url' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      desserts: [],
      editedItem: {
        name: '',
        titulo: '',
        url: '',
        metaTitle: '',
        metaDescription: '',
        metaKeyWord: '',
      },
      defaultItem: {
        name: '',
        titulo: '',
        url: '',
        metaTitle: '',
        metaDescription: '',
        metaKeyWord: '',
      },
      idioma: '1',
      componentKey: 0,
      languages: '',
      module: 5,
      btnNuevo: false,
      btnEdit: false,
      btnDelete: false,
    }
  },

  computed: {
    formTitle() {
      // return this.editedIndex === -1 ? 'Nueva categoria' : 'Editar categoria'
      return this.idRegistro === 0 ? 'Nuevo destinio' : 'Editar destino'
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
        await this.$axios.get('/getAllDestination', {}).then((resp) => {
          this.desserts = resp.data
          this.cargandoTabla = false
        })
      } catch (e) {
        this.error = e.response.data.message
      }
    },

    editItem(item) {
      this.idRegistro = item.id
      this.imgGet = item.full_photo_path
      this.idioma = 1
      this.dialog = true
    },

    deleteItem(item) {
      this.dialogoEliminacion = true
      this.idRegistro = item.id
      this.textoDialogoEliminacion = `¿Desea realizar la eliminación del destino: ${item.name}?`
    },

    close() {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.idRegistro = 0
      })
      this.wrapImg = false
      this.wrapForm = true
      this.imgGet = 'n/a'
      // this.$v.$reset()
      this.getRegistros()
    },

    continuaEliminacion() {
      this.$axios
        .post('/deleteDestination', {
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
            'El destino que esta intentado eliminar no existe o ya fue eliminado previamente'
        })
    },

    cancelaEliminacion() {
      this.dialogoEliminacion = false
      this.idRegistro = 0
      this.textoDialogoEliminacion = ''
    },

    clickTap(valor) {
      this.idioma = valor
      this.wrapForm = true
      this.wrapImg = false
    },

    clickImgTap() {
      this.wrapForm = false
      this.wrapImg = true
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

          // console.log(this.languages)
          // console.log(this.languages.name)
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
<style scoped>
#wrapImg {
  height: auto;
}
</style>
No newline at end of file
