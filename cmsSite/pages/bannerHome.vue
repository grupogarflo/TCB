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
    <template v-slot:item.img="{ item }">
      <img
        width="150"
        :src="item.full_photo_path"
        class="my-3 mx-auto"
      />
      <!-- <img
        width="150"
        :src="require(`@/../assets/images/${item.img}`)"
        class="my-3 mx-auto"
      /> -->
    </template>
    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Slider banner home</v-toolbar-title>
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
        <v-dialog persistent v-model="dialog" max-width="100%">
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
              @click="close"
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
                    <BannerHome
                      :idiomaSend="idiomaSend"
                      :idRegistroSend="idRegistro"
                      :imgGet="imgGet"
                      :altSend="alt"
                      :urlSend="urlLink"
                      @cerrarPop="close"
                      @claveAnterior="getClave"
                      :key="componentKey"
                      :count="count"
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
          <v-card-title class="headline">Eliminación del banner</v-card-title>

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
import BannerHome from '~/components/admin/BannerHome'
export default {
  components: {
    BannerHome,
  },
  data() {
    return {
      headers: [
        { text: 'Img', value: 'img' },
        { text: 'Alt', value: 'alt' },
        { text: 'Link banner', value: 'url' },
        { text: 'Idioma', value: 'idioma' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      desserts: [],
      search: '',
      cargandoTabla: true,
      dialog: false,
      idRegistro: 0,
      componentKey: 0,
      dialogoEliminacion: false,
      textoDialogoEliminacion: '',
      idiomaSend: '',
      imgGet: 'n/a',
      alt: '',
      urlLink: '',
      count: 0,
      module: 2,
      btnNuevo: false,
      btnEdit: false,
      btnDelete: false,
    }
  },
  watch: {
    dialog(newVal, oldVal) {
      this.count = this.count + 1
    },
  },
  computed: {
    formTitle() {
      // return this.editedIndex === -1 ? 'Nueva categoria' : 'Editar categoria'
      return this.idRegistro === 0 ? 'Nuevo banner' : 'Editar banner'
    },
  },
  created() {
    this.getRegistros()
    this.getMeLvelUser()
  },
  methods: {
    async getRegistros() {
      try {
        await this.$axios.get('/getAll', {}).then((resp) => {
          this.desserts = resp.data
          this.cargandoTabla = false
        })
      } catch (e) {
        this.error = e.response.data.message
      }
    },

    close() {
      this.idRegistro = 0
      this.imgGet = 'n/a'
      this.idiomaSend = ''
      this.alt = ''
      this.urlLink = ''
      this.getRegistros()
      this.dialog = false
      /*
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.idRegistro = 0
      })
      this.imgGet = 'n/a'
      this.imgBannerGet = 'n/a'
      this.mapGet = ''
      this.galeriaGet = ''
      this.getRegistros() dfasdfasdf
      */
    },
    editItem(item) {
      this.idRegistro = item.id
      this.imgGet = item.full_photo_path
      this.idiomaSend = item.idIdioma
      this.alt = item.alt
      this.urlLink = item.url
      this.dialog = true
    },

    deleteItem(item) {
      this.dialogoEliminacion = true
      this.idRegistro = item.id
      this.textoDialogoEliminacion = `¿Desea realizar la eliminación del banner?`
    },
    getClave(event) {
      // this.claveGet = event
    },
    cancelaEliminacion() {
      this.dialogoEliminacion = false
      this.idRegistro = 0
      this.textoDialogoEliminacion = ''
    },
    continuaEliminacion() {
      this.$axios
        .post('/deleteBannerHome', {
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
            'El banner que esta intentado eliminar no existe o ya fue eliminado previamente'
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
