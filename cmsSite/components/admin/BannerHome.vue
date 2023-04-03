<template>
  <div>
    <v-container>
      <v-row>
        <v-col cols="12">
          <label
            >Seleccioné la imagen y agregué la información solicitada:</label
          >
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
        </v-col>
        <v-col cols="12">
          <v-form>
            <v-row>
              <v-col cols="4">
                <v-select
                  v-model="selectIdioma"
                  :items="items"
                  item-text="name"
                  item-value="id"
                  label="Idioma"
                  single-line
                  required
                  :error-messages="selectIdiomaErrors"
                  @change="$v.selectIdioma.$touch()"
                  @blur="$v.selectIdioma.$touch()"
                ></v-select>
              </v-col>
              <v-col cols="4">
                <v-text-field
                  v-model="alt"
                  label="Alt"
                  required
                  :error-messages="altErrors"
                  @input="$v.alt.$touch()"
                  @blur="$v.alt.$touch()"
                ></v-text-field>
              </v-col>
              <v-col cols="4">
                <v-text-field
                  v-model="urlLink"
                  label="Link del banner"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
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
            <v-btn color="blue darken-1" text @click="saveImg">Guardar</v-btn>
          </v-card-actions>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
export default {
  props: {
    idiomaSend: { required: true },
    idRegistroSend: { required: true },
    imgGet: { required: true },
    altSend: { required: true },
    urlSend: { required: true },
    count: { required: true },
  },
  mixins: [validationMixin],
  validations: {
    selectIdioma: { required },
    alt: { required },
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
    selectIdioma: {},
    items: [
      { name: 'Español', id: 1 },
      { name: 'Ingles', id: 2 },
    ],
    alt: '',
    urlLink: '',
  }),
  computed: {
    selectIdiomaErrors() {
      const errors = []
      if (!this.$v.selectIdioma.$dirty) {
        return errors
      }
      !this.$v.selectIdioma.required &&
        errors.push('Debe seleccionar un idioma')
      return errors
    },
    altErrors() {
      const errors = []
      if (!this.$v.alt.$dirty) {
        return errors
      }
      !this.$v.alt.required && errors.push('Alt de la imagen es obligatorio')
      return errors
    },
  },
  watch: {
    /* idRegistroSend(newVal, oldVal) {
      this.setconf()
    }, */
    count(newVal, oldVal) {
      // console.log('llegando ' + this.count)
      this.setconf()
    },
  },
  mounted() {
    this.setconf()
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
      this.$v.$touch()
      let bandera = true
      if (this.idRegistroSend === 0) {
        if (this.imageSlider === null) {
          bandera = false
        }
      }

      if (!this.$v.$invalid && bandera) {
        let url = '/addBannerHome'
        if (this.idRegistroSend !== 0) {
          url = '/editBannerHome'
        }
        this.isLoading = true
        // const url = '/' + this.urlAddImg
        const formData = new FormData()
        formData.append('image', this.imageSlider)
        formData.append('idioma', this.selectIdioma)
        formData.append('alt', this.alt)
        formData.append('urlLink', this.urlLink)
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
      } else {
        this.alertMensajes = true
        this.typeAlertaMensaje = 'error'
        this.textoAlertaMesaje =
          'Se debe seleccionar una imagen y agregar la información del idioma y alt para la imagen'
      }
    },

    setconf() {
      this.$v.$reset()
      this.alertMensajes = false
      if (this.idRegistroSend !== 0) {
        this.alt = this.altSend
        this.urlLink = this.urlSend
        this.selectIdioma = this.idiomaSend
        this.showImgGet = true
      } else {
        this.alt = ''
        this.urlLink = ''
        this.selectIdioma = {}
        this.showImgGet = false
        this.previewSlider = {
          src: null,
          width: null,
          height: null,
        }
        this.imageSlider = null
      }
      /*
      if (this.imgGet !== 'n/a') {
        this.showImgGet = true
      } else {
        console.log('limpia' + this.imgGet)
      }
      */
    },

    close() {
      this.$emit('cerrarPop', 'payload')
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
