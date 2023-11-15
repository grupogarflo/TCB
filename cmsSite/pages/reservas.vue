<template>
  <div class="wrap">
    <v-data-table
      :headers="headers"
      :items="desserts"
      sort-by="calories"
      class="elevation-1"
      :search="search"
      item-key="id"
      :loading="cargandoTabla"
      loading-text="Cargando... por favor espere"
      v-show="conDatos"
    >
      <template v-slot:top>
        <v-toolbar flat color="white">
          <v-toolbar-title>Reservas</v-toolbar-title>
          <v-divider class="mx-4" inset vertical />
          <v-select
            v-model="buscaStatus"
            :items="itemsStatus"
            label="Estado reserva"
            hide-details
            class="mt-5 mx-5"
            @change="getRegistros"
          ></v-select>
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
          <v-dialog v-model="dialog" max-width="800px">
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" md="6">
                      <h4>
                        Nombre: <span class="pl-2"> {{ dataClient.name }}</span>
                      </h4>
                    </v-col>
                    <v-col cols="12" md="6">
                      <h4>
                        Sitio:
                        <span class="pl-2"> {{ dataClient.site_book }}</span>
                      </h4>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <h4>
                        Teléfono:
                        <span class="pl-2"> {{ dataClient.phone }}</span>
                      </h4>
                    </v-col>
                    <v-col cols="12" md="6">
                      <h4>
                        Idioma:
                        <span class="pl-2"> {{ dataClient.language }}</span>
                      </h4>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <h4>
                        Email:
                        <span class="pl-2"> {{ dataClient.email }}</span>
                      </h4>
                    </v-col>
                    <v-col cols="12" md="6">
                      <h4>
                        Código reservación:
                        <span class="pl-2"> {{ dataClient.code_book }}</span>
                      </h4>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="6">
                      <h4>
                        Hotel:
                        <span class="pl-2"> {{ dataClient.hotel }}</span>
                      </h4>
                    </v-col>
                    <v-col cols="12" md="6">
                      <h4>
                        Código de pago:
                        <span class="pl-2">
                          {{ dataClient.authorization }}</span
                        >
                      </h4>
                    </v-col>
                  </v-row>

                  <v-row>
                    <v-col cols="12" md="6">
                      <h4>
                        Status:
                        <span class="pl-2"> {{ dataClient.status }}</span>
                      </h4>
                    </v-col>
                    <v-col cols="12" md="6">
                      <h4>
                        Total:
                        <span class="pl-2">
                          {{ dataClient.total }} {{ dataClient.currency }}</span
                        >
                      </h4>
                    </v-col>
                  </v-row>
                  <!--data de los tours -->
                  <v-row>
                    <v-col cols="12">
                      <template>
                        <v-simple-table>
                          <template v-slot:default>
                            <thead>
                              <tr>
                                <th class="text-left">Tour</th>
                                <th class="text-left">Día</th>
                                <th class="text-left">Adultos</th>
                                <th class="text-left">Niños</th>
                                <th class="text-left">Promocode</th>
                                <th class="text-left">Descuento</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="item in dataTours" :key="item.id">
                                <td>{{ item.name }}</td>
                                <td>{{ item.date }}</td>
                                <td>{{ item.adult }}</td>
                                <td>{{ item.child }}</td>
                                <td>{{ item.promocode }}</td>
                                <td>{{ item.discount }}</td>
                              </tr>
                            </tbody>
                          </template>
                        </v-simple-table>
                      </template>
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
                <v-btn color="blue darken-1" text @click="close">Cerrar</v-btn>
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

        <v-toolbar flat color="white" class="my-8">
          <v-toolbar-title class="mr-9">Filtrado por fechas</v-toolbar-title>
          <v-menu
            v-model="start_date"
            :close-on-content-click="false"
            :nudge-right="10"
            :nudge-bottom="-15"
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
                class="mr-8"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="dateStart"
              @input="start_date = false"
            ></v-date-picker>
          </v-menu>
          <v-menu
            v-model="end_date"
            :close-on-content-click="false"
            :nudge-right="10"
            :nudge-bottom="-15"
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
                class="mr-8"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="dateEnd"
              @input="end_date = false"
            ></v-date-picker>
          </v-menu>
          <v-btn
            v-show="btnGenerate"
            depressed
            @click="getRegistros"
            color="deep-purple accent-4 white--text"
            class="mx-5"
          >
            Filtrar
          </v-btn>
          <v-btn
            v-show="btnGenerate"
            depressed
            @click="cvsReservation"
            color="teal white--text"
            class="mx-5"
          >
            Generar Reporte
          </v-btn>
          <v-btn
            class="mx-5"
            depressed
            @click="limpiar"
            color="pink white--text"
          >
            Limpiar
          </v-btn>
          <v-alert
            v-model="errorFecha"
            dismissible
            border="top"
            dark
            type="info"
          >
            {{ errorFechasMsn }}
          </v-alert>
        </v-toolbar>
      </template>

      <template v-slot:item.actions="{ item }">
        <!--
        <v-icon v-show="btnView" small class="mr-2" @click="editItem(item)"
          >mdi-feature-search</v-icon
        >
        -->
        <v-btn
          small
          @click="editItem(item)"
          color="blue lighten-3"
          elevation="2"
          v-show="btnView"
        >
          <v-icon small class="pr-1">mdi-file-search</v-icon>
          Detalles
        </v-btn>
        <v-btn
          small
          @click="editAlert(item)"
          color="blue lighten-2"
          elevation="2"
          v-show="btnEdit"
        >
          <v-icon small class="pr-1">mdi-pencil</v-icon> Editar fecha tour
        </v-btn>
        <v-btn
          small
          @click="cancelAlert(item)"
          color="deep-orange lighten-3"
          elevation="2"
          v-show="btnCancel"
        >
          <v-icon small class="pr-1">mdi-close-octagon-outline</v-icon> Cancelar
        </v-btn>
        <v-btn
          @click="refundAlert(item)"
          small
          color="deep-orange lighten-2"
          elevation="2"
          v-show="btnRembolso"
        >
          <v-icon small class="pr-1">mdi-cash-refund</v-icon> Reembolso
        </v-btn>
        <v-btn
          small
          @click="reSendEmailItem(item)"
          color="blue lighten-3"
          elevation="2"
          v-show="btnView"
          class="my-3"
        >
          <v-icon small class="pr-1">mdi-email-fast</v-icon>
          Reenviar email
        </v-btn>
      </template>
    </v-data-table>
    <v-container class="mt-5">
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
      <!--
      <v-row v-show="conDatos">
        <v-col cols="12">
          <v-card class="mx-auto pa-3" max-width="500">
            <v-card-title>Generar reporte de ventas</v-card-title>
            <v-card-text>
              <v-row>
                <v-col cols="12" md="6">
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
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12" md="6">
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
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-alert border="top" dark v-model="errorFecha" type="info">
                    Debe seleccionar una fecha inicial, si busca un rango
                    específico de fechas.
                  </v-alert>
                </v-col>
              </v-row>
            </v-card-text>
            <v-card-actions>
              <v-btn
                v-show="btnGenerate"
                depressed
                @click="cvsReservation"
                color="teal white--text"
              >
                Generar
              </v-btn>
              <v-btn depressed @click="limpiar" color="pink white--text">
                Limpiar
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
      -->
    </v-container>
    <v-dialog v-model="dialogoCancelacion" max-width="450">
      <v-card>
        <v-card-title class="headline">Cancelacion del tour</v-card-title>

        <v-card-text>{{ textoDialogoCancelacion }}</v-card-text>

        <v-card-actions>
          <v-spacer />

          <v-btn color="green darken-1" text @click="dialogoCancelacion = false"
            >Cancelar</v-btn
          >

          <v-btn color="green darken-1" text @click="continuaCancelacion"
            >Continuar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogoEdit" max-width="450">
      <v-card>
        <v-card-title class="headline">Edita fecha del tour:</v-card-title>

        <v-card-text>
          <h4 class="mb-3">Fecha actual: {{ editDateTour }}</h4>
          <template>
            <v-row justify="center">
              <v-date-picker
                v-model="editDateTour"
                class="mt-4"
              ></v-date-picker>
            </v-row>
          </template>
        </v-card-text>

        <v-card-actions>
          <v-spacer />

          <v-btn color="green darken-1" text @click="dialogoEdit = false"
            >Cancelar</v-btn
          >

          <v-btn color="green darken-1" text @click="continuaEdicion"
            >Continuar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogoReembolso" max-width="450">
      <v-card>
        <v-card-title class="headline">Reembolso del tour:</v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <h4>Total: {{ textoTotalTour }}</h4>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="cantidadQuita"
                  label="Porcentaje que desea reembolsar"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="cantidadManda"
                  label="Total reembolso"
                  :disabled="true"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-select
                  v-model="selectRazon"
                  :items="razon"
                  label="Razon"
                  hide-details
                ></v-select>
              </v-col>
              <v-col cols="12">
                <v-alert
                  v-model="mensajeA"
                  close-text="Close Alert"
                  border="right"
                  colored-border
                  elevation="8"
                  dismissible
                  class="text-center"
                  :type="typeAlerta"
                  >{{ textoAlerta }}</v-alert
                >

                <v-progress-linear
                  v-show="isLoadingA"
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

          <v-btn color="green darken-1" text @click="dialogoReembolso = false"
            >Cancelar</v-btn
          >

          <v-btn color="green darken-1" text @click="continuaRefund"
            >Continuar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogNoData" width="500">
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Información importante:
        </v-card-title>

        <v-card-text>
          <h2 class="my-6">{{ textoAlertaMesaje }}</h2>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" text @click="dialogNoData = false">
            Aceptar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  data: () => ({
    isLoading: false,
    cargandoTabla: true,
    show3: false,
    error: null,
    dialog: false,
    search: '',
    idRegistro: 0,
    idTour: 0,
    dialogoCancelacion: false,
    textoDialogoCancelacion: '',
    dialogoEdit: false,

    headers: [
      { text: 'Name', value: 'name' },
      { text: 'Email', value: 'email' },
      { text: 'Create Date', value: 'created_at' },
      { text: 'Total', value: 'total' },
      { text: 'Currency', value: 'currency' },
      { text: 'Actions', value: 'actions', sortable: false },
    ],
    desserts: [],
    dataTours: [],
    dataClient: [],
    dateStart: '',
    dateEnd: '',
    start_date: false,
    end_date: false,
    errorStartDate: false,
    errorFecha: false,
    conDatos: true,
    alertMensajes: false,
    typeAlertaMensaje: 'success',
    textoAlertaMesaje: '',
    module: 17,
    btnView: false,
    btnGenerate: false,
    editDateTour: '',
    buscaStatus: 'Completed',
    itemsStatus: ['Approved','Completed', 'Cancelled', 'Refunded'],
    btnCancel: false,
    btnRembolso: false,
    btnEdit: false,
    dialogoReembolso: false,
    cantidadQuita: '',
    cantidadManda: '0.00',
    totalTour: '',
    textoTotalTour: '',
    razon: ['Duplicate', 'Requested by customer', 'Fraudulent'],
    selectRazon: '',
    mensajeA: false,
    typeAlerta: 'success',
    textoAlerta: '',
    isLoadingA: false,
    dataNameTour: '',
    dialogNoData: false,
    errorFechasMsn:
      'Debe seleccionar una fecha inicial, si busca un rango específico de fechas.',
  }),

  computed: {
    formTitle() {
      // return this.editedIndex === -1 ? 'Nuevo usuario' : 'Editar usuario'
      return 'Detalle de reservación'
    },
  },

  watch: {
    dialog(val) {
      val || this.close()
      this.alertMensajes = false
    },
    cantidadQuita() {
      const res = (this.cantidadQuita / 100) * this.totalTour
      this.cantidadManda = res.toFixed(2)
    },
  },

  created() {
    this.getRegistros()
    this.getMeLvelUser()
  },

  methods: {
    async getRegistros() {
      this.alertMensajes = false
      this.dialogNoData = false
      try {
        this.alertMensajes = false
        await this.$axios
          .post('/getAllReservation', {
            typeBook: this.buscaStatus,
            start_date: this.dateStart,
            end_date: this.dateEnd,
          })
          .then((resp) => {
            this.desserts = resp.data
            this.cargandoTabla = false
            this.conDatos = true
          })
      } catch (error) {
        // this.error = e.response.data.message;
        // console.log("error" + e);
        // this.alertMensajes = true
        // this.conDatos = false
        this.typeAlertaMensaje = 'error'
        this.textoAlertaMesaje = `No existen reservaciones : ${error.response.status} . ${error.response.data.message}`
        this.dialogNoData = true
      }
    },

    getDetailResevation() {
      try {
        this.$axios
          .post('/getDetailReservation', { id: this.idRegistro })
          .then((resp) => {
            this.dataTours = resp.data
            this.dataNameTour = resp.data.nameTour
          })
      } catch (e) {
        this.error = e.response.data.message
        console.log('error' + e)
        // this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`
      }
    },

    async cvsReservation() {
      this.errorFecha = false

      // if (this.dateEnd !== '' && this.dateStart === '') {
      if (this.dateStart.length === 0) {
        this.errorFecha = true
        return false
      }
      try {
        await this.$axios
          .post('/cvsReservation', {
            start_date: this.dateStart,
            end_date: this.dateEnd,
            responseType: 'blob',
          })
          .then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', 'reporte.csv')
            document.body.appendChild(link)
            link.click()
          })
      } catch (e) {
        this.error = e.response.data.message
        this.errorFechasMsn = e.response.data.message
        this.errorFecha = true
        console.log('error' + e.response.data.message)
      }
    },

    editItem(item) {
      // this.editedIndex = this.desserts.indexOf(item)
      this.idRegistro = item.payment_clients_id
      this.dataClient = Object.assign({}, item)
      this.dialog = true
      this.getDetailResevation()
    },

    close() {
      this.dialog = false
      this.idRegistro = 0
    },

    limpiar() {
      this.dateStart = ''
      this.dateEnd = ''
      this.errorFecha = false
      this.getRegistros()
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
              if (aux[a].cms_actions_id === 5) this.btnView = true
              if (aux[a].cms_actions_id === 10) this.btnEdit = true
              if (aux[a].cms_actions_id === 11) this.btnCancel = true
              if (aux[a].cms_actions_id === 9) this.btnRembolso = true
              if (aux[a].cms_actions_id === 6) this.btnGenerate = true
            }
          })
      } catch (e) {
        this.error = e.response.data.message
        console.log('error' + e)
      }
    },

    cancelAlert(item) {
      this.dialogoCancelacion = true
      this.idRegistro = item.id
      this.textoDialogoCancelacion = `You want to cancel the tour: ${item.name} ?`
    },

    continuaCancelacion(item) {
      this.$axios
        .post('/cancelTour', {
          id: this.idRegistro,
          userId: this.$store.state.auth.user.id,
          userName: this.$store.state.auth.user.name,
        })
        .then((response) => {
          this.dialogoCancelacion = false
          this.getRegistros()
        })
        .catch((error) => {
          console.log(error)
          this.idRegistro = 0
          this.textoDialogoCancelacion =
            'The tour you are trying to delete does not exist or was previously deleted'
        })
    },

    editAlert(item) {
      this.dialogoEdit = true
      this.idRegistro = item.id
      this.idTour = item.tours_id
      this.editDateTour = item.date
    },

    continuaEdicion(item) {
      this.$axios
        .post('/editTourBook', {
          id: this.idRegistro,
          idTour: this.idTour,
          date: this.editDateTour,
        })
        .then((response) => {
          this.dialogoEdit = false
          this.getRegistros()
        })
        .catch((error) => {
          console.log(error)
          this.idRegistro = 0
          this.textoDialogoCancelacion = 'Some error to try edit date'
        })
    },

    refundAlert(item) {
      this.dialogoReembolso = true
      this.cantidadQuita = ''
      this.cantidadManda = '0.00'
      this.selectRazon = ''
      this.idRegistro = item.authorization
      this.idTour = item.id
      this.totalTour = item.total
      this.textoTotalTour = `$ ${item.total + ' ' + item.currency}`
    },

    continuaRefund(item) {
      let ban = true
      this.mensajeA = false
      this.typeAlerta = 'success'
      this.textoAlerta = ''
      this.isLoadingA = false

      if (this.cantidadQuita === '' && ban) {
        this.textoAlerta = 'You must enter a refund percentage'
        ban = false
      }
      if (this.cantidadManda === '' && ban) {
        this.textoAlerta =
          'You must enter a refund percentageThe refund amount must be greater than zero'
        ban = false
      }
      if (this.selectRazon === '' && ban) {
        this.textoAlerta = 'You must select a refund reason'
        ban = false
      }
      if (!ban) {
        this.mensajeA = true
        this.typeAlerta = 'alert'
      }
      if (ban) {
        this.isLoadingA = true
        this.$axios
          .post('/refundTourBook', {
            id: this.idRegistro,
            amount: this.cantidadManda,
            reason: this.selectRazon,
            idBook: this.idTour,
            userName: this.$store.state.auth.user.name,
          })
          .then((response) => {
            this.dialogoReembolso = false
            this.getRegistros()
          })
          .catch((error) => {
            this.isLoadingA = false
            console.log(error)
            // this.idRegistro = 0;
            // this.idTour = 0;
            this.mensajeA = true
            this.typeAlerta = 'alert'
            this.textoAlerta = `Some error to try refund : ${error.response.status} . ${error.response.data.message}`
          })
      }
    },

    reSendEmailItem(item) {
      this.isLoading = true
      this.alertMensajes = true
      this.typeAlertaMensaje = 'success'
      this.textoAlertaMesaje = 'Enviando por favor espere...'
      this.$axios
        .post('/resendEmail', {
          id: item.payment_clients_id,
          email: item.email,
          userName: this.$store.state.auth.user.name,
          idioma: item.language === 'ing' ? 2 : 1,
        })
        .then((response) => {
          this.isLoading = false
          this.alertMensajes = false
          // this.typeAlertaMensaje = 'success'
          // this.textoAlertaMesaje = 'Enviando por favor espere...'
        })
        .catch((error) => {
          this.isLoadingA = false
          console.log(error)
          // this.idRegistro = 0;
          // this.idTour = 0;
          this.mensajeA = true
          this.typeAlerta = 'alert'
          this.textoAlerta = `Some error to try refund : ${error.response.status} . ${error.response.data.message}`
        })
    },
  },
}
</script>
<!--
<style scope lang="css">
tr {
  height: 100px !important;
}
</style>
-->
