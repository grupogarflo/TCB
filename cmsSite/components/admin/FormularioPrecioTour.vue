<template>
  <div class="price">
    <v-card-text id="scrollTop">
      <v-container>
        <v-col cols="12">
          <v-row>
            <v-col cols="12" md="3">
              <v-text-field
                v-model="editedItem.price_fake_adult"
                :error-messages="price_fake_adultErrors"
                required
                label="Precio fake adulto"
                @input="$v.editedItem.price_fake_adult.$touch()"
                @blur="$v.editedItem.price_fake_adult.$touch()"
              />
            </v-col>
            <v-col cols="12" md="3">
              <v-text-field
                v-model="editedItem.price_fake_child"
                :error-messages="price_fake_childErrors"
                required
                label="Precio fake niño"
                @input="$v.editedItem.price_fake_child.$touch()"
                @blur="$v.editedItem.price_fake_child.$touch()"
              />
            </v-col>
            <v-col cols="12" md="3">
              <v-text-field
                v-model="editedItem.price_real_adult"
                :error-messages="price_real_adultErrors"
                required
                label="Precio venta adulto"
                @input="$v.editedItem.price_real_adult.$touch()"
                @blur="$v.editedItem.price_real_adult.$touch()"
              />
            </v-col>
            <v-col cols="12" md="3">
              <v-text-field
                v-model="editedItem.price_real_child"
                :error-messages="price_real_childErrors"
                required
                label="Precio venta niño"
                @input="$v.editedItem.price_real_child.$touch()"
                @blur="$v.editedItem.price_real_child.$touch()"
              />
            </v-col>
          </v-row>
        </v-col>
        <v-row>
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
      <v-btn v-show="btnSave" color="blue darken-1" text @click="save"
        >Guardar</v-btn
      >
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
      price_fake_adult: { required },
      price_fake_child: { required },
      price_real_adult: { required },
      price_real_child: { required },
    },
  },
  props: ['idRegistroSend', 'claveSend'],

  data() {
    return {
      textoBoton: 'Cancelar',
      isLoading: false,
      alertMensajes: false,
      typeAlertaMensaje: 'success',
      textoAlertaMesaje: '',
      editedItem: {
        price_fake_adult: '',
        price_fake_child: '',
        price_real_adult: '',
        price_real_child: '',
      },
      defaultItem: {
        price_fake_adult: '',
        price_fake_child: '',
        price_real_adult: '',
        price_real_child: '',
        selected: [],
      },
      module: 8,
      btnSave: false,
    }
  },
  computed: {
    price_fake_adultErrors() {
      const errors = []
      if (!this.$v.editedItem.price_fake_adult.$dirty) {
        return errors
      }
      !this.$v.editedItem.price_fake_adult.required &&
        errors.push('precio publico adulto es requerido.')
      return errors
    },
    price_fake_childErrors() {
      const errors = []
      if (!this.$v.editedItem.price_fake_child.$dirty) {
        return errors
      }
      !this.$v.editedItem.price_fake_child.required &&
        errors.push('precio publico niño es requerido.')
      return errors
    },
    price_real_adultErrors() {
      const errors = []
      if (!this.$v.editedItem.price_real_adult.$dirty) {
        return errors
      }
      !this.$v.editedItem.price_real_adult.required &&
        errors.push('precio venta adulto es requerido.')
      return errors
    },
    price_real_childErrors() {
      const errors = []
      if (!this.$v.editedItem.price_real_child.$dirty) {
        return errors
      }
      !this.$v.editedItem.price_real_child.required &&
        errors.push('precio venta niño es requerido.')
      return errors
    },
  },
  mounted() {
    if (this.idRegistroSend !== 0) this.getPrice()
    this.getMeLvelUser()
  },
  methods: {
    close() {
      this.$emit('cerrarPop', 'payload')
    },

    save() {
      this.$v.$touch()
      if (this.$v.$invalid) {
        // this.submitStatus = 'ERROR'
      }
      if (!this.$v.$invalid) {
        // do your submit logic here
        this.isLoading = true
        const url =
          this.idRegistroSend === 0 ? '/addPriceTour' : '/updatePriceTour'

        this.$axios
          .post(url, {
            price_fake_adult: this.editedItem.price_fake_adult,
            price_fake_child: this.editedItem.price_fake_child,
            price_real_adult: this.editedItem.price_real_adult,
            price_real_child: this.editedItem.price_real_child,
            special_price: this.editedItem.special_price,
            id: this.idRegistroSend,
            clave: this.claveSend,
          })
          .then((response) => {
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'success'
            this.textoAlertaMesaje =
              this.idRegistroSend === 0
                ? 'Se a guardado la tarifa del tour'
                : 'Se a modificado la tarifaa del tour'

            if (this.idRegistroSend === 0) {
              // this.idRegistroSend = 0
              this.$v.$reset()

              // this.editedItem = Object.assign({}, this.defaultItem)
            } else {
              this.textoBoton = 'Cerrar'
            }

            setTimeout(() => (this.alertMensajes = false), 4500)
          })
          .catch((error) => {
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'error'
            this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`
          })
      }
    },

    getPrice() {
      this.$axios
        .post('/getPriceTour', {
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
