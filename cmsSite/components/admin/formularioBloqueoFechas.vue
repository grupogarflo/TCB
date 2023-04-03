<template>
  <div class="wrapBloqueFechas pa-5">
    <v-container>
      <v-row>
        <v-col cols="12">
          <h4>Cierre por día</h4>
        </v-col>
        <v-col cols="12">
          <v-checkbox
            v-model="diasCheck"
            hide-details
            class="shrink mr-5 mt-0"
            label="Todos"
            value="100"
            @click="checkAll"
          ></v-checkbox>
        </v-col>
        <v-col cols="12">
          <div class="d-flex">
            <v-checkbox
              v-model="diasCheck"
              hide-details
              class="shrink mr-5 mt-0"
              label="Lunes"
              value="0"
            ></v-checkbox>
            <v-checkbox
              v-model="diasCheck"
              hide-details
              class="shrink mr-5 mt-0"
              label="Martes"
              value="1"
            ></v-checkbox>
            <v-checkbox
              v-model="diasCheck"
              hide-details
              class="shrink mr-5 mt-0"
              label="Miercoles"
              value="2"
            ></v-checkbox>
            <v-checkbox
              v-model="diasCheck"
              hide-details
              class="shrink mr-5 mt-0"
              label="Jueves"
              value="3"
            ></v-checkbox>
            <v-checkbox
              v-model="diasCheck"
              hide-details
              class="shrink mr-5 mt-0"
              label="Viernes"
              value="4"
            ></v-checkbox>
            <v-checkbox
              v-model="diasCheck"
              hide-details
              class="shrink mr-5 mt-0"
              label="Sabado"
              value="5"
            ></v-checkbox>
            <v-checkbox
              v-model="diasCheck"
              hide-details
              class="shrink mr-5 mt-0"
              label="Domingo"
              value="6"
            ></v-checkbox>
          </div>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12 mt-8">
          <h4>Cierre por fecha / rango</h4>
        </v-col>
        <v-col cols="12">
          <v-date-picker
            v-model="dates"
            range
            :show-current="false"
            width="390"
          ></v-date-picker>
        </v-col>
        <!--
        <v-col cols="6">
          <v-menu
            v-model="start_date"
            :close-on-content-click="false"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="290px"
            :allowed-dates="allowedDates"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                v-model="dateStart"
                label="Fecha inicial"
                prepend-icon="mdi-calendar"
                readonly
                v-bind="attrs"
                v-on="on"
                :show-current="false"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="dateStart"
              @input="start_date = false"
            ></v-date-picker>
          </v-menu>
        </v-col>
        <v-col cols="6">
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
        -->
      </v-row>

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
        <v-col cols="12">
          <v-card-actions>
            <v-btn depressed @click="guardar" color="teal white--text">
              Guardar
            </v-btn>
            <v-btn depressed @click="limpiar" color="pink white--text">
              Limpiar
            </v-btn>
          </v-card-actions>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
export default {
  props: ['idRegistroSend', 'editBandera'],
  data: () => ({
    banderaAll: false,
    diasCheck: [],
    enabled: false,
    dateStart: '',
    dateEnd: '',
    start_date: false,
    end_date: false,
    errorStartDate: false,
    errorFecha: false,
    typeAlertaMensaje: 'success',
    alertMensajes: false,
    textoAlertaMesaje: '',
    isLoading: false,
    dates: [],
  }),

  mounted() {
    // console.log(this.editBandera + "aqui tengo algo ");
    if (this.editBandera !== 0) this.getInfoBlock()
  },
  methods: {
    allowedDates(val) {
      // return dayjs(val).day() !== 0;
      return 1
    },
    checkAll() {
      this.banderaAll = !this.banderaAll
      if (this.banderaAll) {
        this.diasCheck = ['100', '1', '2', '3', '4', '5', '6', '0']
      } else {
        this.diasCheck = []
      }
    },
    guardar(tipo) {
      if (this.dates.length === 0 && this.diasCheck.length === 0) {
        this.alertMensajes = true
        this.typeAlertaMensaje = 'info'
        this.textoAlertaMesaje =
          'Para poder continuar debe ingresa algún tipo de bloqueo'
        return false
      }

      /*
      if (this.dateStart === "" && this.dateEnd !== "") {
        this.alertMensajes = true;
        this.typeAlertaMensaje = "info";
        this.textoAlertaMesaje =
          "Para poder colocar un rango de fechas debe seleccionar una fecha de inició";
        return false;
      }


      if (this.dateEnd === "" && this.dateStart !== "") {
        this.alertMensajes = true;
        this.typeAlertaMensaje = "info";
        this.textoAlertaMesaje =
          "Para poder colocar un rango de fechas debe seleccionar una fecha final";
        return false;
      }
      */

      // do your submit logic here
      this.isLoading = true

      let url = ''
      this.editBandera === 0 ? (url = 'addBlock') : (url = 'editBlock')

      this.$axios
        .post(url, {
          // dateStart: this.dateStart,
          // dateEnd: this.dateEnd,
          dates: this.dates,
          days: this.diasCheck,
          id: this.idRegistroSend,
        })
        .then((response) => {
          this.isLoading = false
          this.alertMensajes = true
          this.typeAlertaMensaje = 'success'
          this.textoAlertaMesaje = 'Se a agregado el registro'

          setTimeout(() => (this.alertMensajes = false), 4500)
        })
        .catch((error) => {
          this.isLoading = false
          this.alertMensajes = true
          this.typeAlertaMensaje = 'error'
          this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`
        })
    },

    limpiar() {
      this.diasCheck = []
      this.dates = []
      // this.dateStart = "";
      // this.dateEnd = "";
    },

    getInfoBlock() {
      if (this.idRegistroSend !== 0) {
        this.$axios
          .post('getInfoBlock', {
            id: this.idRegistroSend,
          })
          .then((response) => {
            if (response.data.days.length > 0) {
              const diasTotal = response.data.days[0].days.split(',')
              if (diasTotal.length === 7) {
                // diasTotal.push(100);
                diasTotal.unshift('100')
              }
              this.diasCheck = diasTotal
            }
            if (response.data.dates.length > 0) {
              Object.keys(response.data.dates).forEach((key) =>
                this.dates.push(
                  response.data.dates[key].date_start,
                  response.data.dates[key].date_end
                )
              )

              /*
              this.dates = [
                response.data.dates[0].date_start,
                response.data.dates[0].date_end,
              ]
              */
            }

            // this.isLoading = false;
            // this.alertMensajes = true;
            // this.typeAlertaMensaje = "success";
            // this.textoAlertaMesaje = "Se a agregado el registro";

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
  },
}
</script>
