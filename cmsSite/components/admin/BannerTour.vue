<template>
  <div>
    <v-container fluid>
      <v-row>
        <!--
        <v-col cols="12">
          <label>Imagen del banner para el tour:</label>
        </v-col>
        <v-col cols="12">
          <v-file-input
            dense
            outlined
            multiple
            v-model="imageSlider"
            prepend-icon="mdi-camera"
            show-size
            @change="selectImage"
            loading:true
            class="mb-5 mt-5 mr-5 ml-5"
            @click:clear="limpiaImgs"
          ></v-file-input>
        </v-col>
        <v-col cols="12">
          <div v-if="previewSlider.src != null" class="mb-5 mt-5 mr-5 ml-5">
            <p>
              Dimensiones {{ previewSlider.width }}px X
              {{ previewSlider.height }}px
            </p>
            <v-img
              width="150"
              :src="previewSlider.src"
              class="my-3 mx-auto"
            ></v-img>
          </div>
          <img
            id="wrapImgBaseDatos"
            v-if="showImgGet"
            :src="require(`@/../assets/images/${imgBannerGet}`)"
          />
        </v-col>
        -->

        <v-col cols="12">
          <label>Imagenes para la galeria del tour:</label>
        </v-col>
        <v-col cols="4">
          <v-file-input
            accept="image/*"
            label="Select files"
            prepend-icon="mdi-camera"
            multiple
            small-chips
            show-size
            clearable
            color="pink"
            v-model="files"
            @change="addFiles"
          >
            <template v-slot:selection="{ text, index }">
              <v-chip
                small
                text-color="white"
                color="#295671"
                close
                @click:close="remove(index)"
              >
                {{ text }}
              </v-chip>
            </template></v-file-input
          >
        </v-col>
        <v-col cols="4">
            <v-text-field
              v-model="order"

              required
              label="Orden"

              >

            </v-text-field>
        </v-col>
      </v-row>
        <v-row>
          <v-col sm="4" v-for="(file, f) in files" :key="f">
            {{ file.name }}
            <img
              :ref="'file'"
              src="//placehold.it/400/99cc77"
              class="img-fluid"
              :title="'file' + f"
            />
          </v-col>
        </v-row>
        <v-col cols="12">
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
        </v-col>
        <v-col cols="12">
          <v-card-actions>
            <v-spacer />
            <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
            <v-btn v-show="btnSave" color="blue darken-1" text @click="saveImg"
              >Guardar</v-btn
            >
          </v-card-actions>
        </v-col>
      </v-row>
      <v-divider class="my-7"></v-divider>
      <v-row>
        <v-col cols="12">
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
                <v-toolbar-title>Imagenes</v-toolbar-title>
                <v-divider class="mx-4" inset vertical />
                <v-text-field
                  v-model="search"
                  append-icon="mdi-magnify"
                  label="Search"
                  single-line
                  hide-details
                  class="mx-5"
                />
              </v-toolbar>

              <v-dialog v-model="dialogoEliminacion" max-width="450">
                <v-card>
                  <v-card-title class="headline"
                    >Eliminación de tour</v-card-title
                  >

                  <v-card-text>{{ textoDialogoEliminacion }}</v-card-text>

                  <v-card-actions>
                    <v-spacer />

                    <v-btn
                      color="green darken-1"
                      text
                      @click="cancelaEliminacion"
                      >Cancelar</v-btn
                    >

                    <v-btn
                      v-if="idRegistroSend != 0"
                      color="green darken-1"
                      text
                      @click="continuaEliminacion"
                      >Continuar</v-btn
                    >
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </template>

            <template v-slot:item.img="{ item }">
              <!--
              <img
                width="150"
                :src="require(`@/../assets/images/${item.img}`)"
                class="my-3 mx-auto"
              />
              -->
              <img
                width="150"
                :src="item.full_photo_path"
                class="my-3 mx-auto"
              />
            </template>

            <template v-slot:item.actions="{ item }">
              <v-icon v-show="btnDelete" small @click="deleteItem(item)"
                >mdi-delete</v-icon
              >
            </template>
          </v-data-table>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
export default {
  props: {
    claveSend: { required: true },
    idiomaSend: { required: true },
    idRegistroSend: { required: true },
    imgBannerGet: { required: true },
  },
  data: () => ({
    imageSlider: null,
    previewSlider: {
      src: null,
      width: null,
      height: null,
    },
    isLoading: false,
    alertMensajes: false,
    typeAlertaMensaje: 'success',
    textoAlertaMesaje: '',
    showImgGet: false,
    order:'',
    // nuevo
    files: [],
    readers: [],
    aux: [],

    headers: [
      { text: 'Img', value: 'img' },
      { text: 'Orden', value:'order'},
      { text: 'Actions', value: 'actions', sortable: false },
    ],
    desserts: [],
    search: '',
    cargandoTabla: true,
    dialogoEliminacion: false,
    textoDialogoEliminacion: '',
    idImg: '',
    urlDeleteImg: '',
    module: 9,
    btnSave: false,
    btnDelete: false,
  }),
  watch: {
    idRegistroSend(newVal, oldVal) {
      this.setconf()
    },
  },
  mounted() {
    this.setconf()
    this.getRegistros()
    this.getMeLvelUser()
  },
  methods: {
    selectImage() {
      this.previewSlider = {
        src: null,
        width: null,
        height: null,
      }
      // console.log(this.imageSlider);
      if (this.imageSlider == null) {
        console.log('error el archivo no es valido ')
        return false
      }

      const reader = new FileReader()
      reader.readAsDataURL(this.imageSlider)
      reader.onload = (event) => {
        // console.log(event.target);
        this.previewSlider.src = event.target.result
        const img = new Image()
        img.onload = () => {
          this.previewSlider.width = img.width
          this.previewSlider.height = img.height
          this.showImgGet = false
        }
        img.src = this.previewSlider.src
      }
      reader.onerror = (evt) => {
        console.error(evt)
      }

      // this.$emit('change', {
      //  image: this.imageSlider,
      //  type: this.$props.typeImage
      // })
    },

    limpiaImgs() {
      this.showImgGet = true
    },

    saveImg() {
      if (Object.keys(this.files).length !== 0) {
        // console.log(this.aux)
        this.isLoading = true
        // const url = '/addImgTourBanner'
        const url = '/addGalleyTour'
        const formData = new FormData()
        // formData.append('image', this.imageSlider)
        // files

        for (const file of this.files) {
          formData.append('image[]', file, file.name)
        }
        // console.log(this.aux)

        // formData.append('image', this.aux)
        formData.append('idioma', this.idiomaSend)
        formData.append('clave', this.claveSend)
        formData.append('id', this.idRegistroSend)
        formData.append('order',this.order)
        this.$axios
          .post(url, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })
          .then((response) => {
            this.files = []
            this.order=''
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'success'
            this.textoAlertaMesaje =
              this.idRegistroSend === 0
                ? 'Se a agregado la imagen'
                : 'Se a modificado la imagen'

            if (this.idRegistroSend === 0) {
              // this.idRegistroSend = 0
            } else {
              this.textoBoton = 'Cerra'
            }

            setTimeout(() => (this.alertMensajes = false), 4500)
            setTimeout(() => this.getRegistros(), 4000)
          })
          .catch((error) => {
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'error'
            this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status}. ${error.response.data.message}`
          })
      } else {
        this.isLoading = false
        this.alertMensajes = true
        this.typeAlertaMensaje = 'error'
        this.textoAlertaMesaje = `no se a seleccionado ninguna imagen para el tour`
      }
    },

    setconf() {
      if (this.imgBannerGet !== 'n/a') {
        this.showImgGet = true
      } else {
        // console.log('limpia' + this.imgGet)
      }
    },

    close() {
      this.$emit('cerrarPop', 'payload')
    },

    addFiles() {
      // console.log('files', this.files)
      this.files.forEach((file, f) => {
        this.readers[f] = new FileReader()

        this.readers[f].onloadend = (e) => {
          const fileData = this.readers[f].result
          const imgRef = this.$refs.file[f]
          imgRef.src = fileData
          // console.log(fileData)
          // send to server here...
          // this.saveImg(fileData)
          // this.aux.push(this.files[f])
        }

        this.readers[f].readAsDataURL(this.files[f])
      })
    },

    remove(index) {
      this.files.splice(index, 1)
    },

    deleteItem(item) {
      console.log ('item select ', item);
      this.dialogoEliminacion = true
      this.idImg = item.id
      this.urlDeleteImg = item.img
      this.textoDialogoEliminacion = `¿Desea realizar la eliminación de la imagen?`
    },

    cancelaEliminacion() {
      this.dialogoEliminacion = false
      this.idImg = 0
      this.urlDeleteImg = ''
      this.textoDialogoEliminacion = ''
    },

    continuaEliminacion() {
      this.$axios
        .post('/deleteGalleyTour', {
          id: this.idImg,
          url: this.urlDeleteImg,
        })
        .then((response) => {
          this.cancelaEliminacion()
          this.getRegistros()
        })
        .catch((error) => {
          console.log(error)
          this.idImg = 0
          this.textoDialogoEliminacion =
            'La imagen que esta intentado eliminar no existe o ya fue eliminado previamente'
        })
    },

    async getRegistros() {
      try {
        await this.$axios
          .post('/getGalleyTour', {
            clave: this.claveSend,
            id: this.idRegistroSend,
          })
          .then((resp) => {
            this.desserts = resp.data.data
            this.cargandoTabla = false
          })
      } catch (e) {
        this.error = e.response.data.message
      }
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
              if (aux[a].cms_actions_id === 3) {
                this.btnSave = true
              }
              if (aux[a].cms_actions_id === 4) {
                this.btnDelete = true
              }
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
.img-fluid {
  width: 100%;
}
#wrapImgBaseDatos {
  display: flex;
  width: 300px;
  height: 300px;
  margin: 0 auto;
}
</style>
