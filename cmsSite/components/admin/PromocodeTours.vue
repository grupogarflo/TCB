<template>
  <div class="wrap">
    <v-card class="mx-auto my-1" max-width="100%" :loading="cargandoTabla">
      <v-card-title v-if="cargandoTabla">{{ loadingText }}</v-card-title>
      <v-container>
        <v-row>
          <v-col v-for="(item, i) in items" :key="i" cols="12" class="py-0">
            <v-checkbox
              v-model="selected"
              :label="item.name"
              :value="item.id"
              color="primary accent-4"
              @click="clickCheck"
              :multiple="true"
            ></v-checkbox>
            <v-divider v-if="i < items.length - 1"></v-divider>
          </v-col>
        </v-row>
      </v-container>
    </v-card>
  </div>
</template>

<script>
export default {
  props: ["idRegistroSend", "limpiaTours", "cont"],
  data: () => ({
    items: [],
    // model: ['Carrots']
    // modelItems: [1],
    cargandoTabla: true,
    loadingText: "Cargando... por favor espere",
    alertMensajes: false,
    typeAlertaMensaje: "success",
    textoAlertaMesaje: "",
    selected: [],
  }),
  methods: {
    /* selectCheck(item) {

      console.log(this.$refs.rolesSelected);
      this.$emit("aplicaTour", item);
    },
    */
    clickCheck() {
      this.$emit("aplicaTour", this.selected);
    },
    getRegistros() {
      this.$axios
        .post("/getAllToursPromocode", {
          id: this.idRegistroSend,
        })
        .then((response) => {
          this.items = response.data[0].tours;
          this.selected = response.data[0].checado;
          this.cargandoTabla = false;
        })
        .catch((error) => {
          console.log(error);
          // this.textoDialogoEliminacion =
          //  'El tour que esta intentado eliminar no existe o ya fue eliminado previamente'
          alert("Existe un error al intentar recuperar la información");
        });
    },
  },
  created() {
    this.getRegistros();
  },
  watch: {
    cont(newVal, oldVal) {
      this.modelItems = [];
    },
  },
};
</script>

<!--
  <v-card class="mx-auto my-1" max-width="100%" :loading="cargandoTabla">
    <v-card-title v-if="cargandoTabla">{{ loadingText }}</v-card-title>
    <v-list v-show="!cargandoTabla">
      <v-list-item-group v-model="modelItems" multiple>
        <template v-for="(item, i) in items">
          <v-list-item
            :key="`item-${i}`"
            active-class="light-blue darken-3 white--text text--accent-4"
            two-line
            @click="selectCheck(item)"
          >
            <template v-slot:default="{ active }">
              <v-list-item-content>
                <v-list-item-title v-text="item.name"></v-list-item-title>
              </v-list-item-content>

              <v-list-item-action>
                <v-checkbox
                  :input-value="active"
                  color="white accent-4"
                  ref="rolesSelected"
                ></v-checkbox>
              </v-list-item-action>
            </template>
          </v-list-item>
          <v-divider
            v-if="i < items.length - 1"
            :key="`divider-${i}`"
          ></v-divider>
        </template>
      </v-list-item-group>
    </v-list>

    <v-alert
      v-model="alertMensajes"
      close-text="Close Alert"
      border="right"
      colored-border
      elevation="8"
      dismissible
      class="text-center my-5"
      :type="typeAlertaMensaje"
      >{{ textoAlertaMesaje }}</v-alert
    >
  </v-card>
-->
