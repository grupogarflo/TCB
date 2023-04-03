<template>
  <div>
    <v-container fluid>
      <v-row>
        <v-col cols="12">
          <label>Imagen principal para el tour:</label>
        </v-col>
        <v-col cols="12">
          <v-file-input
            dense
            outlined
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
            :src="imgGet"
          />
          <!--
          <img
            id="wrapImgBaseDatos"
            v-if="showImgGet"
            :src="require(`@/../assets/images/${imgGet}`)"
          />
          -->
        </v-col>
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
    </v-container>
  </div>
</template>
<script>
export default {
  props: {
    claveSend: { required: true },
    idiomaSend: { required: true },
    idRegistroSend: { required: true },
    imgGet: { required: true },
    urlAddImg: { required: true },
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
    module: 12,
    btnSave: false,
  }),
  watch: {
    idRegistroSend(newVal, oldVal) {
      this.setconf()
    },
  },
  mounted() {
    this.setconf()
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
      this.isLoading = true
      const url = '/' + this.urlAddImg
      const formData = new FormData()
      formData.append('image', this.imageSlider)
      formData.append('idioma', this.idiomaSend)
      formData.append('clave', this.claveSend)
      formData.append('id', this.idRegistroSend)
      this.$axios
        .post(url, formData)
        .then((response) => {
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
        })
        .catch((error) => {
          this.isLoading = false
          this.alertMensajes = true
          this.typeAlertaMensaje = 'error'
          this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status}. ${error.response.data.message}`
        })
    },

    setconf() {
      if (this.imgGet !== 'n/a') {
        this.showImgGet = true
      } else {
        console.log('limpia' + this.imgGet)
      }
    },

    close() {
      this.$emit('cerrarPop', 'payload')
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
#wrapImgBaseDatos {
  display: flex;
  width: 300px;
  height: 300px;
  margin: 0 auto;
}
</style>
