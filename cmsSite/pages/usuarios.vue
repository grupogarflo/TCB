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
        <v-toolbar-title>Usuarios</v-toolbar-title>
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
                      v-model="editedItem.email"
                      :error-messages="emailErrors"
                      required
                      label="Email"
                      @input="$v.editedItem.email.$touch()"
                      @blur="$v.editedItem.email.$touch()"
                    />
                  </v-col>
                  <v-col v-show="idRegistro == 0" cols="12">
                    <v-text-field
                      v-model="editedItem.password"
                      class="input-group--focused"
                      :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'"
                      :type="show3 ? 'text' : 'password'"
                      name="input-10-2"
                      value
                      :error-messages="passErrors"
                      required
                      label="Password"
                      @click:append="show3 = !show3"
                      @input="$v.editedItem.password.$touch()"
                      @blur="$v.editedItem.password.$touch()"
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
          <v-card-title class="headline">Eliminación de usuarios</v-card-title>

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

      <v-dialog v-model="levelUser" max-width="950">
        <v-card>
          <v-card-title class="headline">Permisos de usuarios</v-card-title>
          <v-card-text>
            <LevelUser :idUser="idRegistro" :key="count" />
          </v-card-text>
          <v-card-actions>
            <v-spacer />
          </v-card-actions>
        </v-card>
      </v-dialog>
    </template>

    <template
      v-if="this.$store.state.auth.user.type_user === 1"
      v-slot:item.actions="{ item }"
    >
      <v-icon v-show="btnEdit" small class="mr-2" @click="editItem(item)"
        >mdi-pencil</v-icon
      >
      <v-icon v-show="btnDelete" small class="mr-2" @click="deleteItem(item)"
        >mdi-delete</v-icon
      >
      <v-icon v-show="btnLevel" small @click="levelItem(item)"
        >mdi-shield-account</v-icon
      >
    </template>
  </v-data-table>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, email } from 'vuelidate/lib/validators'
import LevelUser from '~/components/admin/LevelUser'
export default {
  components: {
    LevelUser,
  },
  // layout: 'admin',

  mixins: [validationMixin],

  validations: {
    editedItem: {
      email: { required, email },
      password: { required },
      name: { required },
    },
  },

  data: () => ({
    isLoading: false,
    cargandoTabla: true,
    alertMensajes: false,
    typeAlertaMensaje: 'success',
    textoAlertaMesaje: '',
    show3: false,
    error: null,
    dialog: false,
    dialogoEliminacion: false,
    levelUser: false,
    textoDialogoEliminacion: '',
    search: '',
    idRegistro: 0,
    headers: [
      //  {
      //    text: 'Dessert (100g serving)',
      //    align: 'start',
      //    sortable: false,
      //    value: 'name'
      //   },
      { text: 'Name', value: 'name' },
      { text: 'Email', value: 'email' },
      { text: 'Actions', value: 'actions', sortable: false },
    ],
    desserts: [],
    // editedIndex: -1,
    editedItem: {
      name: '',
      email: '',
      password: '',
    },
    defaultItem: {
      name: '',
      email: '',
      password: '',
    },
    count: 0,
    module: 1,
    btnNuevo: false,
    btnEdit: false,
    btnDelete: false,
    btnLevel: false,
  }),

  computed: {
    formTitle() {
      // return this.editedIndex === -1 ? 'Nuevo usuario' : 'Editar usuario'
      return this.idRegistro === 0 ? 'Nuevo usuario' : 'Editar usuario'
    },
    nameErrors() {
      const errors = []
      if (!this.$v.editedItem.name.$dirty) {
        return errors
      }
      !this.$v.editedItem.name.required && errors.push('Nombre es requerido.')
      return errors
    },
    passErrors() {
      const errors = []
      if (!this.$v.editedItem.password.$dirty) {
        return errors
      }
      !this.$v.editedItem.password.required &&
        errors.push('password es requerido.')
      return errors
    },
    emailErrors() {
      const errors = []
      if (!this.$v.editedItem.email.$dirty) {
        return errors
      }
      !this.$v.editedItem.email.email &&
        errors.push('Debe ser un e-mail valido')
      !this.$v.editedItem.email.required && errors.push('E-mail es requerido')
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

    levelUser(val) {
      val || this.close()
    },
  },

  created() {
    this.getRegistros()
    this.getMeLvelUser()
    // console.log(this.$store.state.auth.user.id)
  },

  methods: {
    async getRegistros() {
      try {
        await this.$axios.get('/getAllUsers', {}).then((resp) => {
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
      this.textoDialogoEliminacion = `¿Desea realizar la eliminación del usuario: ${item.name}?`
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
      // this.$v.$touch()
      // agrego un valor al campo de password cuando se hace una edicion para brincar la validacion
      if (this.idRegistro !== 0) {
        this.editedItem.password = '-'
      }
      if (!this.$v.$invalid) {
        // do your submit logic here
        this.isLoading = true
        const url = this.idRegistro === 0 ? '/addUsers' : '/upateUsers'
        this.$axios
          .post(url, {
            name: this.editedItem.name,
            email: this.editedItem.email,
            password: this.editedItem.password,
            id: this.idRegistro,
          })
          .then((response) => {
            // this.desserts = resp.data
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'success'
            this.textoAlertaMesaje =
              this.idRegistro === 0
                ? 'Se a creado el usuario'
                : 'Se a modificado el usuario'

            if (this.idRegistro !== 0) {
              let edicion = {}
              edicion = Object.assign({}, this.defaultItem)
              delete edicion.passwrod
              this.$nextTick(() => {
                this.editedItem = Object.assign({}, edicion)
              })
            } else {
              this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                // this.editedIndex = -1
              })
            }
            this.idRegistro = 0
            this.$v.$reset()
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
        .post('/deleteUsers', {
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
            'El usuario que esta intentado eliminar no existe o ya fue eliminado previamente'
        })
    },

    cancelaEliminacion() {
      this.dialogoEliminacion = false
      this.idRegistro = 0
      this.textoDialogoEliminacion = ''
    },

    levelItem(item) {
      this.count += 1
      this.idRegistro = item.id
      this.levelUser = true
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
              if (aux[a].cms_actions_id === 7) this.btnLevel = true
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
