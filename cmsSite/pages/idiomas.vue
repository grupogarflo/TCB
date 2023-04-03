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
        <v-toolbar-title>Idiomas</v-toolbar-title>
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
                      v-model="editedItem.abbrev"
                      :error-messages="abbrevErrors"
                      required
                      label="abreviatura"
                      @input="$v.editedItem.abbrev.$touch()"
                      @blur="$v.editedItem.abbrev.$touch()"
                    />
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
          <v-card-title class="headline">Eliminación de idioma</v-card-title>

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
  mixins: [validationMixin],

  validations: {
    editedItem: {
      abbrev: { required },
      name: { required },
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
      { text: 'Name', value: 'name' },
      { text: 'Actions', value: 'actions', sortable: false },
    ],
    desserts: [],
    // editedIndex: -1,
    editedItem: {
      name: '',
      abbrev: '',
    },
    defaultItem: {
      name: '',
      abbrev: '',
    },
    module: 13,
    btnNuevo: false,
    btnEdit: false,
    btnDelete: false,
  }),

  computed: {
    formTitle() {
      // return this.editedIndex === -1 ? 'Nuevo usuario' : 'Editar usuario'
      return this.idRegistro === 0 ? 'Nuevo idioma' : 'Editar idioma'
    },
    nameErrors() {
      const errors = []
      if (!this.$v.editedItem.name.$dirty) {
        return errors
      }
      !this.$v.editedItem.name.required && errors.push('Nombre es requerido.')
      return errors
    },
    abbrevErrors() {
      const errors = []
      if (!this.$v.editedItem.abbrev.$dirty) {
        return errors
      }
      !this.$v.editedItem.abbrev.required &&
        errors.push('Abreviatura es requerida.')
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
        await this.$axios.get('/getAllLanguage', {}).then((resp) => {
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
      this.dialog = true
    },

    deleteItem(item) {
      // const index = this.desserts.indexOf(item)
      this.dialogoEliminacion = true
      this.idRegistro = item.id
      this.textoDialogoEliminacion = `¿Desea realizar la eliminación del idioma: ${item.name}?`
    },

    close() {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        // this.editedIndex = -1
        this.idRegistro = 0
      })
      this.$v.$reset()
    },

    save() {
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.submitStatus = 'ERROR'
      }
      // agrego un valor al campo de password cuando se hace una edicion para brincar la validacion
      if (this.idRegistro !== 0) {
        this.editedItem.password = '-'
      }
      if (!this.$v.$invalid) {
        // do your submit logic here
        this.isLoading = true
        const url = this.idRegistro === 0 ? '/addLanguage' : '/updateLanguage'
        this.$axios
          .post(url, {
            name: this.editedItem.name,
            abbrev: this.editedItem.abbrev,
            id: this.idRegistro,
          })
          .then((response) => {
            // this.desserts = resp.data
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'success'
            this.textoAlertaMesaje =
              this.idRegistro === 0
                ? 'Se a creado el idioma'
                : 'Se a modificado el idioma'

            if (this.idRegistro !== 0) {
              let edicion = {}
              edicion = Object.assign({}, this.defaultItem)
              delete edicion.passwrod
              this.$nextTick(() => {
                this.editedItem = Object.assign({}, edicion)
              })
            } else {
              this.$nextTick(() => {
                this.$v.$reset()
                this.editedItem = Object.assign({}, this.defaultItem)

                // this.editedIndex = -1
              })
            }
            this.idRegistro = 0

            this.getRegistros()
            setTimeout(() => (this.dialog = false), 3000)
          })
          .catch((error) => {
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'error'
            this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status}`
          })
      }
    },

    continuaEliminacion() {
      this.$axios
        .post('/deleteLanguage', {
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
            'El idioma que esta intentado eliminar no existe o ya fue eliminado previamente'
        })
    },

    cancelaEliminacion() {
      this.dialogoEliminacion = false
      this.idRegistro = 0
      this.textoDialogoEliminacion = ''
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
