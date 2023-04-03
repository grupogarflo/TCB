<template>
  <div id="wrapForm">
    <v-card-text>
      <v-container>
        <v-row>
          <v-col cols="12">
            <div class="d-flex">
              <v-radio-group
                v-model="editedItem.icon"
                row
                :error-messages="radioErrors"
                required
                @input="$v.editedItem.icon.$touch()"
                @blur="$v.editedItem.icon.$touch()"
              >
                <i class="icon-turtles" />
                <v-radio
                  label=""
                  class="shrink mr-5 mt-0"
                  value="icon-turtles"
                  @click="clickshow"
                ></v-radio>
                <i class="icon-tours" />
                <v-radio
                  label=""
                  class="shrink mr-5 mt-0"
                  value="icon-tours"
                  @click="clickshow"
                ></v-radio>
                <i class="icon-shoes" />
                <v-radio
                  label=""
                  class="shrink mr-5 mt-0"
                  value="icon-shoes"
                  @click="clickshow"
                ></v-radio>
                <i class="icon-short" />
                <v-radio
                  label=""
                  class="shrink mr-5 mt-0"
                  value="icon-short"
                  @click="clickshow"
                ></v-radio>
                <i class="icon-person" />
                <v-radio
                  label=""
                  class="shrink mr-5 mt-0"
                  value="icon-person"
                  @click="clickshow"
                ></v-radio>
                <i class="icon-nature" />
                <v-radio
                  label=""
                  class="shrink mr-5 mt-0"
                  value="icon-nature"
                  @click="clickshow"
                ></v-radio>
                <i class="icon-hat" />
                <v-radio
                  label=""
                  class="shrink mr-5 mt-0"
                  value="icon-hat"
                  @click="clickshow"
                ></v-radio>
                <i class="icon-estrella" />
                <v-radio
                  label=""
                  class="shrink mr-5 mt-0"
                  value="icon-estrella"
                  @click="clickshow"
                ></v-radio>
                <i class="icon-coexist" />
                <v-radio
                  label=""
                  class="shrink mr-5 mt-0"
                  value="icon-coexist"
                  @click="clickshow"
                ></v-radio>
                <i class="icon-camera" />
                <v-radio
                  label=""
                  class="shrink mr-5 mt-0"
                  value="icon-camera"
                  @click="clickshow"
                ></v-radio>
                <i class="icon-reloj" />
                <v-radio
                  label=""
                  class="shrink mr-5 mt-0"
                  value="icon-reloj"
                  @click="clickshow"
                ></v-radio>
              </v-radio-group>
            </div>
          </v-col>
          <v-col cols="12">
            <v-text-field
              v-model="editedItem.name_esp"
              :error-messages="nameEspErrors"
              required
              label="Nombre en español"
              @input="$v.editedItem.name_esp.$touch()"
              @blur="$v.editedItem.name_esp.$touch()"
            />
          </v-col>
          <v-col cols="12">
            <v-text-field
              v-model="editedItem.name_ing"
              :error-messages="nameIngErrors"
              required
              label="Nombre en ingles"
              @input="$v.editedItem.name_ing.$touch()"
              @blur="$v.editedItem.name_ing.$touch()"
            />
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
      name_esp: { required },
      name_ing: { required },
      icon: { required },
    },
  },

  props: ['idRegistroSend'],

  data() {
    return {
      tipoCheck: [],
      isLoading: false,
      alertMensajes: false,
      typeAlertaMensaje: 'success',
      textoAlertaMesaje: '',
      show3: false,
      error: null,
      dialog: false,
      // idRegistro: 0,
      uid: '',
      textoBoton: 'Cancelar',

      editedItem: {
        name_esp: '',
        name_ing: '',
        icon: '',
      },

      defaultItem: {
        name_esp: '',
        name_ing: '',
        icon: '',
      },
    }
  },

  computed: {
    formTitle() {
      return this.idRegistroSend === 0 ? 'Nuevo icon' : 'Editar icon'
    },
    nameEspErrors() {
      const errors = []
      if (!this.$v.editedItem.name_esp.$dirty) {
        return errors
      }
      !this.$v.editedItem.name_esp.required &&
        errors.push('Nombre es requerido.')
      return errors
    },
    nameIngErrors() {
      const errors = []
      if (!this.$v.editedItem.name_ing.$dirty) {
        return errors
      }
      !this.$v.editedItem.name_ing.required &&
        errors.push('Nombre es requerido.')
      return errors
    },
    radioErrors() {
      const errors = []
      if (!this.$v.editedItem.icon.$dirty) {
        return errors
      }
      !this.$v.editedItem.icon.required && errors.push('Icon es requerido.')
      return errors
    },
  },

  watch: {
    idioma(newVal, oldVal) {
      if (this.idRegistroSend !== 0) this.getCategoriaIdioma()
    },
    dialogoEliminacion(val) {
      val || this.close()
    },
  },

  mounted() {
    // this.uid = new Date().getTime().toString(36)
    // this.creaId()
    if (this.idRegistroSend !== 0) this.getCategoriaIdioma()
    // this.$emit('claveNueva', this.uid)
  },

  methods: {
    clickshow() {
      this.$v.editedItem.icon.$reset()
    },

    close() {
      this.$emit('cerrarPop', 'payload')
    },

    save() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.isLoading = true
        const url = this.idRegistroSend === 0 ? '/addIcon' : '/updateIcon'

        this.$axios
          .post(url, {
            nameIng: this.editedItem.name_ing,
            nameEsp: this.editedItem.name_esp,
            icon: this.editedItem.icon,
            id: this.idRegistroSend,
          })
          .then((response) => {
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'success'
            this.textoAlertaMesaje =
              this.idRegistroSend === 0
                ? 'Se a creado el icon'
                : 'Se a modificado el icon'

            if (this.idRegistroSend === 0) {
              this.$v.$reset()
              this.editedItem = Object.assign({}, this.defaultItem)
            } else {
              this.textoBoton = 'Cerrar'
            }

            setTimeout(() => (this.alertMensajes = false), 4500)
          })
          .catch((error) => {
            console.log(error)
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'error'
            this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`
          })
      } else {
        console.log(this.$v.$invalid)
        console.log(this.editedItem.radio)
      }
    },

    getFormIdioma() {
      this.getCategoriaIdioma()
    },

    getCategoriaIdioma() {
      this.$axios
        .post('/getIconInfo', {
          id: this.idRegistroSend,
        })
        .then((response) => {
          this.editedItem = Object.assign({}, response.data[0])
        })
        .catch((error) => {
          this.$v.$reset()
          this.editedItem = Object.assign({}, this.defaultItem)
          this.isLoading = false
          this.alertMensajes = true
          this.typeAlertaMensaje = 'error'
          this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} no hay informacion disponible`
        })
    },
  },
}
</script>
<style scoped>
[class^='icon-'],
[class*=' icon-'] {
  font-size: 35px;
}
</style>
