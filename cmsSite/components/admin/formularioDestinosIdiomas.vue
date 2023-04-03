<template>
  <div id="wrapForm">
    <v-card-text>
      <v-container>
        <v-row>
          <v-col cols="12">
            <v-text-field
              v-model="editedItem.name"
              :error-messages="nameErrors"
              required
              label="Nombre"
              @input="$v.editedItem.name.$touch()"
              @blur="$v.editedItem.name.$touch()"
            />
          </v-col>
          <v-col cols="12">
            <v-text-field
              v-model="editedItem.title"
              :error-messages="tituloErrors"
              required
              label="Titulo"
              @input="$v.editedItem.title.$touch()"
              @blur="$v.editedItem.title.$touch()"
            />
          </v-col>
          <v-col cols="12">
            <v-text-field
              v-model="editedItem.url"
              :error-messages="urlErrors"
              required
              label="Url"
              @input="$v.editedItem.url.$touch()"
              @blur="$v.editedItem.url.$touch()"
            />
          </v-col>
          <v-col cols="12">
            <v-checkbox v-model="editedItem.show_home" label="Se muestra en el home"></v-checkbox>
          </v-col>
          <v-col cols="12 mt-8 mb-8">
            <label class="font-weight-regular">Descripción</label>
            <v-alert
              border="top"
              v-model="errorDescription"
              dismissible
              type="warning"
            >Este campo es necesario para continuar</v-alert>
            <vue-editor
              class="mt-3 editor"
              placeholder="Write Something..."
              v-model="description"
              @focus="onEditorFocus"
            ></vue-editor>
          </v-col>
          <v-col cols="12 mt-8 mb-8">
            <label class="font-weight-regular">Descripción del footer</label>
            <v-alert
              border="top"
              v-model="errorDescriptionFooter"
              dismissible
              type="warning"
            >Este campo es necesario para continuar</v-alert>

            <vue-editor
              class="mt-3 editor"
              @focus="onEditorFocusFooter"
              placeholder="Write Something..."
              v-model="description_footer"
            ></vue-editor>
          </v-col>
          <v-col cols="12">
            <v-textarea
              name="input-7-1"
              label="Meta data title"
              auto-grow
              counter
              v-model="editedItem.meta_title"
              :error-messages="metaTitleErrors"
              required
              @input="$v.editedItem.meta_title.$touch()"
              @blur="$v.editedItem.meta_title.$touch()"
            ></v-textarea>
          </v-col>
          <v-col cols="12">
            <v-textarea
              name="input-7-2"
              label="Meta data description"
              auto-grow
              counter
              v-model="editedItem.meta_description"
              :error-messages="metaDescriptionErrors"
              required
              @input="$v.editedItem.meta_description.$touch()"
              @blur="$v.editedItem.meta_description.$touch()"
            ></v-textarea>
          </v-col>
          <v-col cols="12">
            <v-textarea
              name="input-7-2"
              label="Meta data keywords"
              auto-grow
              counter
              v-model="editedItem.meta_keywords"
              :error-messages="metaKeyWordErrors"
              required
              @input="$v.editedItem.meta_keywords.$touch()"
              @blur="$v.editedItem.meta_keywords.$touch()"
            ></v-textarea>
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
            >{{ textoAlertaMesaje }}</v-alert>

            <v-progress-linear v-show="isLoading" indeterminate height="5" striped color="cyan" />
          </v-col>
        </v-row>
      </v-container>
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-btn color="blue darken-1" text @click="close">{{ textoBoton }}</v-btn>
      <v-btn color="blue darken-1" text @click="save">Guardar</v-btn>
    </v-card-actions>
  </div>
</template>
<script>
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

export default {
  mixins: [validationMixin],

  validations: {
    editedItem: {
      name: { required },
      title: { required },
      url: { required },
      meta_title: { required },
      meta_description: { required },
      meta_keywords: { required }
    }
  },

  props: ['idioma', 'idRegistroSend', 'idiomaTamano'],

  data() {
    return {
      description: '',
      description_footer: '',
      isLoading: false,
      alertMensajes: false,
      typeAlertaMensaje: 'success',
      textoAlertaMesaje: '',
      show3: false,
      error: null,
      dialog: false,
      //idRegistro: 0,
      uid: '',
      textoBoton: 'Cancelar',
      errorDescription: false,
      errorDescriptionFooter: false,
      contadorIdioma: 0,
      editedItem: {
        name: '',
        title: '',
        url: '',
        meta_title: '',
        meta_description: '',
        meta_keyWords: '',
        show_home: false
      },
      defaultItem: {
        name: '',
        title: '',
        url: '',
        meta_title: '',
        meta_description: '',
        meta_keywords: '',
        show_home: false
      }
    }
  },

  computed: {
    formTitle() {
      // return this.editedIndex === -1 ? 'Nueva categoria' : 'Editar categoria'
      return this.idRegistroSend === 0 ? 'Nuevo destino' : 'Editar destino'
    },
    nameErrors() {
      const errors = []
      if (!this.$v.editedItem.name.$dirty) {
        return errors
      }
      !this.$v.editedItem.name.required && errors.push('Nombre es requerido.')
      return errors
    },
    tituloErrors() {
      const errors = []
      if (!this.$v.editedItem.title.$dirty) {
        return errors
      }
      !this.$v.editedItem.title.required && errors.push('Titulo es requerido.')
      return errors
    },
    urlErrors() {
      const errors = []
      if (!this.$v.editedItem.url.$dirty) {
        return errors
      }
      !this.$v.editedItem.url.required && errors.push('Url es requerido.')
      return errors
    },
    metaTitleErrors() {
      const errors = []
      if (!this.$v.editedItem.meta_title.$dirty) {
        return errors
      }
      !this.$v.editedItem.meta_title.required &&
        errors.push('meta title es requerido.')
      return errors
    },
    metaDescriptionErrors() {
      const errors = []
      if (!this.$v.editedItem.meta_description.$dirty) {
        return errors
      }
      !this.$v.editedItem.meta_description.required &&
        errors.push('meta description es requerido.')
      return errors
    },
    metaKeyWordErrors() {
      const errors = []
      if (!this.$v.editedItem.meta_keywords.$dirty) {
        return errors
      }
      !this.$v.editedItem.meta_keywords.required &&
        errors.push('meta keywords es requerido.')
      return errors
    }
  },

  watch: {
    idioma: function(newVal, oldVal) {
      if (this.idRegistroSend != 0) this.getCategoriaIdioma()
    },
    dialogoEliminacion(val) {
      val || this.close()
    }
  },

  mounted() {
    //this.uid = new Date().getTime().toString(36)
    this.creaId()
    if (this.idRegistroSend != 0) this.getCategoriaIdioma()
    this.$emit('claveNueva', this.uid)
  },

  methods: {
    close() {
      this.$emit('cerrarPop', 'payload')
    },

    save() {
      this.errorDescription = false
      this.errorDescriptionFooter = false
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.submitStatus = 'ERROR'
      }
      if (
        !this.$v.$invalid &&
        this.description != '' &&
        this.description_footer != ''
      ) {
        // do your submit logic here
        this.isLoading = true
        const url =
          this.idRegistroSend === 0 ? '/addDestination' : '/updateDestination'

        this.$axios
          .post(url, {
            name: this.editedItem.name,
            title: this.editedItem.title,
            url: this.editedItem.url,
            show_home: this.editedItem.show_home,
            description: this.description,
            description_footer: this.description_footer,
            meta_title: this.editedItem.meta_title,
            meta_description: this.editedItem.meta_description,
            meta_keywords: this.editedItem.meta_keywords,
            idioma: this.idioma,
            id: this.idRegistroSend,
            clave: this.uid
          })
          .then(response => {
            this.contadorIdioma = this.contadorIdioma + 1
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'success'
            this.textoAlertaMesaje =
              this.idRegistroSend === 0
                ? 'Se a creado el destino, no olvides dar de alta todos los idiomas'
                : 'Se a modificado el destinio'

            if (this.idRegistroSend === 0) {
              this.idRegistroSend = 0
              this.$v.$reset()
              this.description = ''
              this.description_footer = ''
              this.editedItem = Object.assign({}, this.defaultItem)
            } else {
              this.textoBoton = 'Cerrar'
            }
            if (this.contadorIdioma == this.idiomaTamano) {
              this.$emit('claveNueva', this.uid)
              this.creaId()
              this.contadorIdioma = 0
            }
            setTimeout(() => (this.alertMensajes = false), 4500)
          })
          .catch(error => {
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'error'
            this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`
          })
      }
      {
        if (this.description === '') this.errorDescription = true
        if (this.description_footer === '') this.errorDescriptionFooter = true
      }
    },

    getFormIdioma() {
      this.getCategoriaIdioma()
    },

    getCategoriaIdioma() {
      this.$axios
        .post('/getDestinationInfo', {
          idioma: this.idioma,
          id: this.idRegistroSend
        })
        .then(response => {
          this.editedItem = Object.assign({}, response.data[0])
          this.description = response.data[0].description
          this.description_footer = response.data[0].description_footer
        })
        .catch(error => {
          this.$v.$reset()
          this.description = ''
          this.description_footer = ''
          this.editedItem = Object.assign({}, this.defaultItem)
          this.isLoading = false
          this.alertMensajes = true
          this.typeAlertaMensaje = 'error'
          this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} no hay informacion disponible`
        })
    },

    onEditorFocus(quill) {
      this.errorDescription = false
    },
    onEditorFocusFooter(quill) {
      this.errorDescriptionFooter = false
    },
    creaId() {
      this.uid = new Date().getTime().toString(36)
    }
  }
}
</script>
<style scoped>
.editor {
  height: 450px;
}
#wrapImg {
  height: 450px;
}
</style>