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
        <v-toolbar-title>Tipo de cambio</v-toolbar-title>
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
        <v-dialog v-model="dialog" max-width="500px">
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
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      v-model="editedItem.rate"
                      :error-messages="rateErrors"
                      required
                      label="Rate"
                      @input="$v.editedItem.rate.$touch()"
                      @blur="$v.editedItem.rate.$touch()"
                    />
                  </v-col>
                  <v-col cols="12">
                    <v-alert v-model="errorStartDate" dismissible type="warning"
                      >La fecha inicial es necesaria</v-alert
                    >
                    <v-menu
                      v-model="start_date"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      transition="scale-transition"
                      offset-y
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="dateStart"
                          label="Fecha inicial"
                          prepend-icon="mdi-calendar"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="dateStart"
                        @input="start_date = false"
                        @change="cambioStarDate"
                      ></v-date-picker>
                    </v-menu>
                  </v-col>
                  <v-col cols="12">
                    <v-menu
                      v-model="end_date"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      transition="scale-transition"
                      offset-y
                      min-width="290px"
                      required
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="dateEnd"
                          label="Fecha final"
                          prepend-icon="mdi-calendar"
                          readonly
                          v-bind="attrs"
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="dateEnd"
                        @input="end_date = false"
                      ></v-date-picker>
                    </v-menu>
                  </v-col>
                  <v-col cols="6">
                    <v-btn
                      v-if="idRegistro !== 0"
                      class="mx-2"
                      fab
                      dark
                      small
                      color="primary"
                      @click="vaciaFechaFinal"
                    >
                      <v-icon class="float-right" dark>mdi-delete-empty</v-icon>
                    </v-btn>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

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

            <v-card-actions>
              <v-spacer />
              <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
              <v-btn color="blue darken-1" text @click="save">Guardar</v-btn>
            </v-card-actions>

            <v-progress-linear
              v-show="isLoading"
              indeterminate
              height="5"
              striped
              color="cyan"
            />
          </v-card>
        </v-dialog>
      </v-toolbar>

      <v-dialog v-model="dialogoEliminacion" max-width="450">
        <v-card>
          <v-card-title class="headline"
            >Eliminación del tipo de cambio</v-card-title
          >

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
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
export default {
  // layout: 'admin',

  mixins: [validationMixin],

  validations: {
    editedItem: {
      rate: { required },
    },
  },

  data: () => ({
    isLoading: false,
    cargandoTabla: true,
    alertMensajes: false,
    typeAlertaMensaje: 'success',
    textoAlertaMesaje: '',

    error: null,
    dialog: false,
    dialogoEliminacion: false,
    textoDialogoEliminacion: '',
    search: '',
    idRegistro: 0,
    headers: [
      { text: 'Tipo cambio', value: 'rate' },
      { text: 'Fecha inicio', value: 'start_date' },
      { text: 'Fecha final', value: 'end_date' },
      { text: 'Actions', value: 'actions', sortable: false },
    ],
    desserts: [],
    // editedIndex: -1,
    editedItem: {
      rate: '',
    },
    defaultItem: {
      rate: '',
    },
    dateStart: '',
    dateEnd: '',
    start_date: false,
    end_date: false,
    errorStartDate: false,
    module: 14,
    btnNuevo: false,
    btnEdit: false,
    btnDelete: false,
  }),

  computed: {
    dateRangeText() {
      return this.dates.join(' ~ ')
    },
    formTitle() {
      // return this.editedIndex === -1 ? 'Nuevo usuario' : 'Editar usuario'
      return this.idRegistro === 0
        ? 'Nuevo tipo de cambio'
        : 'Editar tipo de cambio'
    },
    rateErrors() {
      const errors = []
      if (!this.$v.editedItem.rate.$dirty) {
        return errors
      }
      !this.$v.editedItem.rate.required &&
        errors.push('El tipo de cambio es necesario')
      return errors
    },
  },

  watch: {
    dialog(val) {
      val || this.close()
      this.alertMensajes = false
    },
    dialogoEliminacion(val) {
      val || this.close()
    },
  },

  created() {
    this.getRegistros()
    this.getMeLvelUser()
  },

  methods: {
    async getRegistros() {
      try {
        await this.$axios.get('/getAllRate', {}).then((resp) => {
          this.desserts = resp.data
          this.cargandoTabla = false
        })
      } catch (e) {
        this.error = e.response.data.message
        console.log('error' + e)
      }
    },

    editItem(item) {
      // this.editedIndex = this.desserts.indexOf(item)
      this.idRegistro = item.id
      this.editedItem = Object.assign({}, item)
      this.dateStart = item.start_date
      this.dateEnd = item.end_date
      this.dialog = true
    },

    deleteItem(item) {
      // const index = this.desserts.indexOf(item)
      this.dialogoEliminacion = true
      this.idRegistro = item.id
      this.textoDialogoEliminacion = `¿Desea eliminar el tipo de cambio: ${item.rate}?`
    },

    close() {
      this.dialog = false
      this.dateStart = ''
      this.dateEnd = ''
      this.start_date = false
      this.end_date = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        // this.editedIndex = -1
        this.idRegistro = 0
      })
      this.$v.$reset()
    },

    save() {
      // console.log('start' + this.dateStart)
      // console.log('end' + this.dateEnd)
      this.errorStartDate = false

      this.$v.$touch()
      if (this.$v.$invalid) {
        this.submitStatus = 'ERROR'
      }

      if (!this.$v.$invalid & (this.dateStart !== '')) {
        // do your submit logic here
        this.isLoading = true
        const url = this.idRegistro === 0 ? '/addRate' : '/updateRate'
        this.$axios
          .post(url, {
            rate: this.editedItem.rate,
            start_date: this.dateStart,
            end_date: this.dateEnd,
            id: this.idRegistro,
          })
          .then((response) => {
            // this.desserts = resp.data
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'success'
            this.textoAlertaMesaje =
              this.idRegistro === 0
                ? 'Se a creado el tipo de cambio'
                : 'Se a modificado el tipo de cambio'

            this.$nextTick(() => {
              this.$v.$reset()
              this.editedItem = Object.assign({}, this.defaultItem)
              this.dateStart = ''
              this.dateEnd = ''
              // this.editedIndex = -1
            })

            this.idRegistro = 0

            this.getRegistros()
            setTimeout(() => (this.dialog = false), 3000)
          })
          .catch((error) => {
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'error'
            this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status}. ${error.response.data.message}`
          })
      } else if (this.dateStart === '') {
        this.errorStartDate = true
      }
    },

    cambioStarDate(quill) {
      this.errorStartDate = false
    },

    continuaEliminacion() {
      this.$axios
        .post('/deleteRate', {
          id: this.idRegistro,
        })
        .then((response) => {
          // this.desserts = resp.data
          this.cancelaEliminacion()
          this.getRegistros()
        })
        .catch((error) => {
          this.idRegistro = 0
          console.log(error.response.status)
          this.textoDialogoEliminacion =
            'El tipo de cambio que esta intentado eliminar no existe o ya fue eliminado previamente'
        })
    },

    cancelaEliminacion() {
      this.dialogoEliminacion = false
      this.idRegistro = 0
      this.textoDialogoEliminacion = ''
    },

    vaciaFechaFinal() {
      this.dateEnd = ''
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
