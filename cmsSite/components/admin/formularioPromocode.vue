<template>
  <div class="wrapBloqueFechas pa-5">
    <v-container>
      <v-row>
        <v-col cols="12">
          <h4>Tipo de descuento</h4>
        </v-col>

        <v-col cols="12">
          <div class="d-flex">
            <v-radio-group v-model="tipoCheck" row>
              <v-radio
                label="Cantidad ($)"
                class="shrink mr-5 mt-0"
                value="cantidad"
              ></v-radio>
              <v-radio
                label="Porcentaje (%)"
                class="shrink mr-5 mt-0"
                value="porcentaje"
              ></v-radio>
            </v-radio-group>
          </div>
        </v-col>
        <v-col cols="12">
          <h4>Promocode:</h4>
          <v-text-field v-model="promocode" label="Código"></v-text-field>
        </v-col>

        <v-col cols="12">
          <h4>Descuentos</h4>
        </v-col>

        <v-col cols="12">
          <div class="d-flex">
            <v-text-field
              v-model="adulto"
              label="Adulto"
              class="mr-5"
            ></v-text-field>
            <v-text-field v-model="nino" label="niño"></v-text-field>
          </div>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="6">
          <v-row>
            <v-col cols="12 mt-8">
              <h4>Rango de Booking</h4>
            </v-col>
            <v-col cols="12">
              <v-date-picker
                v-model="datesBooking"
                range
                :show-current="false"
                width="390"
              ></v-date-picker>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="12" md="6">
          <v-row>
            <v-col cols="12 mt-8">
              <h4>Rango de travel</h4>
            </v-col>
            <v-col cols="12">
              <v-date-picker
                v-model="datesTravel"
                range
                :show-current="false"
                width="390"
              ></v-date-picker>
            </v-col>
          </v-row>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12 mt-8">
          <h4>Puede aplica para los siguientes tours:</h4>
        </v-col>
        <v-col cols="12">
          <PromocodeTours
            :idRegistroSend="idRegistroSend"
            :limpiaTours="limpiaTours"
            :cont="cont"
            @aplicaTour="aplicaTour($event)"
          />
        </v-col>
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
import PromocodeTours from "./PromocodeTours.vue";
export default {
  components: { PromocodeTours },
  props: ["idRegistroSend", "editBandera"],
  data: () => ({
    tipoCheck: [],
    enabled: false,
    typeAlertaMensaje: "success",
    alertMensajes: false,
    textoAlertaMesaje: "",
    isLoading: false,
    datesBooking: [],
    datesTravel: [],
    promocode: "",
    adulto: "",
    nino: "",
    limpiaTours: "no",
    cont: 0,
    // recuperaTour: 0,
    aplicaArr: [],
  }),

  mounted() {
    // console.log(this.editBandera + "aqui tengo algo ");
    if (this.editBandera !== 0) this.getInfoBlock();
  },
  methods: {
    aplicaTour(event) {
      this.aplicaArr = [];
      this.aplicaArr = event;

      /*
      if (this.aplicaArr.length > 0) {
        const index = this.aplicaArr.indexOf(event.id);
        if (index > -1) {
          this.aplicaArr.splice(index, 1);
        } else {
          this.aplicaArr.push(event.id);
        }
      } else {
        this.aplicaArr.push(event.id);
      }
      */
    },

    guardar(tipo) {
      if (this.tipoCheck.length === 0) {
        this.alertMensajes = true;
        this.typeAlertaMensaje = "info";
        this.textoAlertaMesaje =
          "Debe seleccionar un tipo de promoción para poder continuar";
        return false;
      }
      if (this.promocode === "") {
        this.alertMensajes = true;
        this.typeAlertaMensaje = "info";
        this.textoAlertaMesaje =
          "Debe ingresar un código de promoción para poder continuar";
        return false;
      }
      if (this.adulto === "" && this.nino === "") {
        this.alertMensajes = true;
        this.typeAlertaMensaje = "info";
        this.textoAlertaMesaje =
          "Debe ingresar un descuento minimo para adultos para poder continuar";
        return false;
      }
      if (this.datesBooking.length === 0) {
        this.alertMensajes = true;
        this.typeAlertaMensaje = "info";
        this.textoAlertaMesaje =
          "Debe seleccionar un rando de fechas de booking para poder continuar";
        return false;
      }
      if (this.datesTravel.length === 0) {
        this.alertMensajes = true;
        this.typeAlertaMensaje = "info";
        this.textoAlertaMesaje =
          "Debe seleccionar un rando de fechas de travel para poder continuar";
        return false;
      }

      this.isLoading = true;

      let url = "";
      this.editBandera === 0 ? (url = "addPromoCode") : (url = "editPromoCode");

      this.$axios
        .post(url, {
          promocode: this.promocode,
          adulto: this.adulto,
          nino: this.nino,
          datesBooking: this.datesBooking,
          datesTravel: this.datesTravel,
          tipo: this.tipoCheck,
          tours: this.aplicaArr,
          id: this.idRegistroSend,
        })
        .then((response) => {
          this.isLoading = false;
          this.alertMensajes = true;
          this.typeAlertaMensaje = "success";
          this.textoAlertaMesaje = "Se a agregado el registro";

          setTimeout(() => (this.alertMensajes = false), 4500);
        })
        .catch((error) => {
          this.isLoading = false;
          this.alertMensajes = true;
          this.typeAlertaMensaje = "error";
          this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`;
        });
    },

    limpiar() {
      this.tipoCheck = [];
      this.datesBooking = [];
      this.datesTravel = [];
      this.adulto = "";
      this.nino = "";
      this.promocode = "";
      this.limpiaTours = "si";
      this.cont = this.cont + 1;
    },

    getInfoBlock() {
      if (this.idRegistroSend !== 0) {
        this.$axios
          .post("getInfoPromo", {
            id: this.idRegistroSend,
          })
          .then((response) => {
            this.promocode = response.data.promo[0].code;
            this.adulto = response.data.promo[0].discount_adult;
            this.nino = response.data.promo[0].discount_child;
            this.tipoCheck = response.data.promo[0].type_discount;
            this.datesBooking = [
              response.data.promo[0].date_start_booking,
              response.data.promo[0].date_end_booking,
            ];
            this.datesTravel = [
              response.data.promo[0].date_start_travel,
              response.data.promo[0].date_end_travel,
            ];
            /*
          if (response.data.days.length > 0) {

            const diasTotal = response.data.days[0].days.split(",");
            if (diasTotal.length === 7) {
              // diasTotal.push(100);
              diasTotal.unshift("100");
            }
            this.tipoCheck = diasTotal;
          }
          if (response.data.dates.length > 0) {
            this.dates = [
              response.data.dates[0].date_start,
              response.data.dates[0].date_end,
            ];
          }

          // this.isLoading = false;
          // this.alertMensajes = true;
          // this.typeAlertaMensaje = "success";
          // this.textoAlertaMesaje = "Se a agregado el registro";

          setTimeout(() => (this.alertMensajes = false), 4500);
          */
          })
          .catch((error) => {
            this.isLoading = false;
            this.alertMensajes = true;
            this.typeAlertaMensaje = "error";
            this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`;
          });
      }
    },
  },
};
</script>
