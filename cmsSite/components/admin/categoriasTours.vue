<template>
  <div>
    <v-container fluid>
      <v-row>
        <v-col cols="12">
          <v-textarea
            name="input-7-2"
            label="Código de mapa"
            auto-grow
            counter
            v-model="editedItem.mapa"
            :error-messages="mapaErrors"
            required
            @input="$v.editedItem.mapa.$touch()"
            @blur="$v.editedItem.mapa.$touch()"
          ></v-textarea>
        </v-col>
        <v-col cols="12">
          <iframe
            v-show="existeMapa"
            :srcdoc="editedItem.mapa"
            width="100%"
            height="450"
            frameborder="0"
            style="border:0;"
            allowfullscreen
            aria-hidden="false"
            tabindex="0"
          ></iframe>
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
        <v-col cols="12">
          <v-card-actions>
            <v-spacer />
            <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
            <v-btn color="blue darken-1" text @click="save">Guardar</v-btn>
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
  mixins: [validationMixin],

  validations: {
    editedItem: {
      mapa: { required }
    }
  },
  props: {
    claveSend: { required: true },
    idiomaSend: { required: true },
    idRegistroSend: { required: true },
    mapGet: { required: true },
    urlAddMap: { required: true }
  },
  data: () => ({
    isLoading: false,
    alertMensajes: false,
    typeAlertaMensaje: 'success',
    textoAlertaMesaje: '',
    existeMapa: false,
    editedItem: {
      mapa: ''
    },
    defaultItem: {
      mapa: ''
    }
  }),
  watch: {
    idRegistroSend: function(newVal, oldVal) {
      this.setconf()
    }
  },
  mounted() {
    this.setconf()
  },

  computed: {
    mapaErrors() {
      const errors = []
      if (!this.$v.editedItem.mapa.$dirty) {
        return errors
      }
      !this.$v.editedItem.mapa.required &&
        errors.push('el código del mapa es requerido.')
      return errors
    }
  },

  methods: {
    save() {
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.submitStatus = 'ERROR'
      }
      if (!this.$v.$invalid) {
        // do your submit logic here
        this.isLoading = true
        const url = this.urlAddMap

        this.$axios
          .post(url, {
            mapa: this.editedItem.mapa,
            idioma: this.idiomaSend,
            clave: this.claveSend,
            id: this.idRegistroSend
          })
          .then(response => {
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'success'
            this.textoAlertaMesaje =
              this.idRegistroSend === 0
                ? 'Se a agregado el mapa al tour'
                : 'Se a modificado el mapa'

            if (this.idRegistroSend === 0) {
              this.idRegistroSend = 0
              this.$v.$reset()
              this.existeMapa = true

              //this.editedItem = Object.assign({}, this.defaultItem)
            } else {
              //this.textoBoton = 'Cerra'
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
    },

    setconf() {
      this.editedItem.mapa = this.mapGet
      if (this.mapGet !== '') this.existeMapa = true
    },

    close() {
      this.$emit('cerrarPop', 'payload')
    }
  }
}
</script>
