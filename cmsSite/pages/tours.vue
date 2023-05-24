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
        <v-toolbar-title>Tours</v-toolbar-title>
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
                      v-model="tab"
                      id="tabs"
                    >
                      <v-tab
                        v-for="item in languages"
                        v-bind:key="item.id"
                        @click="clickTap(item.id)"
                        :href="'#' + item.name"
                        :ref="item.name"
                        >{{ item.name }}</v-tab
                      >
                      <v-tab href="#wrapPrecios"> Precios </v-tab>
                      <v-tab :disabled="sinClick" href="#wrapBanner">
                        Galeria
                      </v-tab>
                      <v-tab :disabled="sinClick" href="#wrapImg"
                        >Imagen del tour</v-tab
                      >
                      <v-tab href="#wrapVideo"> Video </v-tab>
                      <v-tab :disabled="sinClick" href="#wrapMap"> Mapa </v-tab>
                      <v-tab :disabled="sinClick" href="#wrapCategorias">
                        Categorias
                      </v-tab>

                      <v-tab :disabled="sinClick" href="#wrapSugerencia">
                        Sugerencias
                      </v-tab>

                      <v-tab :disabled="sinClick" href="#wrapDestinations">
                          Destinos
                      </v-tab>

                      <!--
                      <v-tab :disabled="sinClick" href="#wrapGalleria">
                        Galeria
                      </v-tab>
                      -->
                    </v-tabs>
                    <v-tabs-items v-model="tab">
                      <v-tab-item value="Español">
                        <formularioToursIdiomas
                          :idioma="1"
                          :idRegistroSend="idRegistro"
                          :idiomaTamano="languages.length"
                          :uid="uid"
                          @cerrarPop="close"
                          @claveAnterior="getClave"
                          @openTabs="openTabs"
                          @crearNuevaClave="crearNuevaClave"
                          @nextIdioma="nextIdioma"
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
                      </v-tab-item>
                      <v-tab-item value="Ingles">
                        <formularioToursIdiomas
                          :idioma="2"
                          :idRegistroSend="idRegistro"
                          :idiomaTamano="languages.length"
                          :uid="uid"
                          @cerrarPop="close"
                          @claveAnterior="getClave"
                          @openTabs="openTabs"
                          @crearNuevaClave="crearNuevaClave"
                          @nextIdioma="nextIdioma"
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
                      </v-tab-item>
                      <v-tab-item value="wrapPrecios">
                        <FormularioPrecioTour
                          :claveSend="claveGet"
                          :idRegistroSend="idRegistro"
                          @cerrarPop="close"
                          :key="componentKey"
                        />
                      </v-tab-item>
                      <v-tab-item value="wrapBanner">
                        <BannerTour
                          :claveSend="claveGet"
                          :idiomaSend="idioma"
                          :idRegistroSend="idRegistro"
                          :imgBannerGet="imgBannerGet"
                          @cerrarPop="close"
                          :key="componentKey"
                        />
                      </v-tab-item>
                      <v-tab-item value="wrapImg">
                        <imgUpload
                          :claveSend="claveGet"
                          :idiomaSend="idioma"
                          :idRegistroSend="idRegistro"
                          :imgGet="imgGet"
                          :urlAddImg="urlAddImg"
                          @cerrarPop="close"
                          :key="componentKey"
                        />
                      </v-tab-item>
                      <v-tab-item value="wrapVideo">
                        <FormularioVideoTour
                          :claveSend="claveGet"
                          :idRegistroSend="idRegistro"
                          :videoGet="videoGet"
                          @cerrarPop="close"
                          :key="componentKey"
                        />
                      </v-tab-item>
                      <v-tab-item value="wrapMap">
                        <mapas
                          :claveSend="claveGet"
                          :idiomaSend="idioma"
                          :idRegistroSend="idRegistro"
                          :mapGet="mapGet"
                          :urlAddMap="urlAddMap"
                          @cerrarPop="close"
                          :key="componentKey"
                        />
                      </v-tab-item>
                      <v-tab-item value="wrapCategorias">
                        <CategoriaTour
                          :claveSend="claveGet"
                          :idRegistroSend="idRegistro"
                          :key="componentKey"
                        />
                      </v-tab-item>

                      <v-tab-item value="wrapSugerencia">
                        <SugerenciasIconTours
                          :claveSend="claveGet"
                          :idRegistroSend="idRegistro"
                          :key="componentKey"
                        />
                      </v-tab-item>

                      <v-tab-item value="wrapDestinations">
                        <destinations
                          :claveSend="claveGet"
                          :idRegistroSend="idRegistro"
                          :key="componentKey"
                        />


                      </v-tab-item>


                    </v-tabs-items>
                  </v-card>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
        </v-dialog>
      </v-toolbar>

      <v-dialog v-model="dialogoEliminacion" max-width="450">
        <v-card>
          <v-card-title class="headline">Eliminación de tour</v-card-title>

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

      <v-dialog v-model="dialogoApagar" max-width="450">
        <v-card>
          <!--<v-card-title class="headline">Apagar tour</v-card-title>-->

          <v-card-text>{{ textoDialogoApagar }}</v-card-text>

          <v-card-actions>
            <v-spacer />

            <v-btn color="green darken-1" text @click="cancelaApagar"
              >Cancelar</v-btn
            >

            <v-btn
              v-if="idRegistro != 0"
              color="green darken-1"
              text
              @click="continuaApagar"
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
      <v-icon v-show="btnTunOff" small @click="publicItem(item)"
        >mdi-web</v-icon
      >
    </template>
  </v-data-table>
</template>

<script>
import formularioToursIdiomas from '~/components/admin/formularioToursIdiomas'
import imgUpload from '~/components/admin/imgUpload'
import mapas from '~/components/admin/mapas'
// import galerias from '~/components/admin/galerias'
import CategoriaTour from '~/components/admin/CategoriaTour'
import SugerenciasIconTours from '~/components/admin/SugerenciasIconTours'
import BannerTour from '~/components/admin/BannerTour'
import FormularioPrecioTour from '~/components/admin/FormularioPrecioTour'
import FormularioVideoTour from '~/components/admin/FormularioVideoTour'
import Destinations from '~/components/admin/Destinations'

export default {
  // layout: 'admin',
  components: {
    formularioToursIdiomas,
    imgUpload,
    mapas,
    CategoriaTour,
    // galerias,
    SugerenciasIconTours,
    BannerTour,
    FormularioPrecioTour,
    FormularioVideoTour,
    Destinations
  },

  data() {
    return {
      tab: null,
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
      dialogoApagar: false,
      textoDialogoApagar: '',
      search: '',
      idRegistro: 0,
      tourPublic: '',
      wrapImg: false,
      wrapForm: true,
      wrapMap: false,
      wrapGalleria: false,
      wrapCategorias: false,
      wrapSugerencia: false,
      claveGet: '',
      imgGet: 'n/a',
      imgBannerGet: 'n/a',
      mapGet: '',
      videoGet: '',
      galeriaGet: '',
      urlAddImg: 'addImgTour',
      urlAddMap: 'addMapTour',
      urlAddGaleria: 'addGaleriaTour',
      headers: [
        { text: 'Name', value: 'name' },
        { text: 'Url', value: 'url' },
        { text: 'Publicado', value: 'publicoShow' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      desserts: [],
      idioma: '1',
      componentKey: 0,
      languages: '',
      sinClick: true,
      uid: '',
      module: 7,
      btnNuevo: false,
      btnEdit: false,
      btnDelete: false,
      btnTunOff: false,
    }
  },

  computed: {
    formTitle() {
      // return this.editedIndex === -1 ? 'Nueva categoria' : 'Editar categoria'
      return this.idRegistro === 0 ? 'Nuevo tour' : 'Editar tour'
    },
  },

  watch: {
    dialog(val) {
      val || this.close()
      this.alertMensajes = false
      this.forceRerender()
      this.tab = [0]

      // si abre la ventana con el boton de nuevo se crea la clave que usara para el nuevo contenido
      if (this.idRegistro === 0) {
        this.creaId()
        this.sinClick = true
      }
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
        await this.$axios.get('/getAllTour', {}).then((resp) => {
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
      this.imgBannerGet = item.banner
      this.mapGet = item.map
      this.galeriaGet = item.gallery
      this.videoGet = item.video
      this.idioma = 1
      this.sinClick = false
      this.dialog = true
    },

    deleteItem(item) {
      this.dialogoEliminacion = true
      this.idRegistro = item.id
      this.textoDialogoEliminacion = `¿Desea realizar la eliminación del tour: ${item.name}?`
    },

    publicItem(item) {
      this.dialogoApagar = true
      this.idRegistro = item.id
      this.tourPublic = item.public

      this.textoDialogoApagar =
        item.public === 1
          ? `¿Desea apagar el tour: ${item.name} del sitio?`
          : `¿Desea activar el tour: ${item.name} en el sitio?`
    },

    close() {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.idRegistro = 0
      })
      /*
      this.wrapImg = false
      this.wrapMap = false
      this.wrapGalleria = false
      this.wrapCategorias = false
      this.wrapSugerencia = false
      this.wrapForm = true
      */
      this.imgGet = 'n/a'
      this.imgBannerGet = 'n/a'
      this.mapGet = ''
      this.galeriaGet = ''
      this.getRegistros()
    },

    continuaEliminacion() {
      this.$axios
        .post('/deleteTour', {
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

    continuaApagar() {
      this.$axios
        .post('/removePublic', {
          id: this.idRegistro,
          public: this.tourPublic,
        })
        .then((response) => {
          this.cancelaApagar()
          this.getRegistros()
        })
        .catch((error) => {
          console.log(error)
          this.idRegistro = 0
          this.tourPublic = ''
          this.textoDialogoApagar =
            'El tour que esta intentado apagar esta ya apagado'
        })
    },

    cancelaApagar() {
      this.dialogoApagar = false
      this.idRegistro = 0
      this.tourPublic = ''
      this.textoDialogoApagar = ''
    },

    clickTap(valor) {
      this.idioma = valor
      if (this.idRegistro === 0) {
        this.sinClick = true
      } else {
        this.sinClick = false
      }
      this.forceRerender()
      /* this.wrapForm = true
      this.wrapImg = false
      this.wrapMap = false
      this.wrapGalleria = false
      this.wrapCategorias = false
      this.wrapSugerencia = false
      this.sinClick = false */
    },
    /*
    clickImgTap() {
      this.wrapMap = false
      this.wrapGalleria = false
      this.wrapForm = false
      this.wrapCategorias = false
      this.wrapSugerencia = false
      this.wrapImg = true
      this.componentKey = this.componentKey + 1
    },

    clickMapaTap() {
      this.wrapForm = false
      this.wrapImg = false
      this.wrapGalleria = false
      this.wrapCategorias = false
      this.wrapSugerencia = false
      this.wrapMap = true
      this.componentKey = this.componentKey + 1
    },

    clickGaleriaTap() {
      this.wrapForm = false
      this.wrapImg = false
      this.wrapMap = false
      this.wrapCategorias = false
      this.wrapSugerencia = false
      this.wrapGalleria = true
      this.componentKey = this.componentKey + 1
    },
    clickCategoriaTap() {
      this.wrapForm = false
      this.wrapImg = false
      this.wrapMap = false
      this.wrapGalleria = false
      this.wrapSugerencia = false
      this.wrapCategorias = true
      this.componentKey = this.componentKey + 1
    },
    clickSugerenciaTap() {
      this.wrapForm = false
      this.wrapImg = false
      this.wrapMap = false
      this.wrapGalleria = false
      this.wrapCategorias = false
      this.wrapSugerencia = true

      this.componentKey = this.componentKey + 1
    },
    */

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
    getClave(event) {
      this.claveGet = event
    },

    openTabs(event) {
      this.sinClick = event
    },

    creaId() {
      this.uid = new Date().getTime().toString(36)
    },
    crearNuevaClave(event) {
      this.creaId()
    },
    nextIdioma(event) {
      // this.tab = 'Ingles'
      this.tab = event
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
              if (aux[a].cms_actions_id === 8) this.btnTunOff = true
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
