<template>
  <div class="checkBoxLevelUser">
    <v-card class="mx-auto" height="220">
      <v-list shaped>
        <v-list-item-group v-model="modelCheck" multiple>
          <template v-for="(action, a) in actionsOp">
            <v-list-item
              :key="`item-${a}`"
              :value="action.idAction"
              active-class="green lighten-1 white--text text--accent-4"
              @click="selectCheck(action.idAction)"
              class="my-2"
            >
              <template v-slot:default="{ active }">
                <v-list-item-content>
                  <v-list-item-title
                    v-text="action.nameAction"
                  ></v-list-item-title>
                </v-list-item-content>

                <v-list-item-action>
                  <v-checkbox :input-value="active" color="white"></v-checkbox>
                </v-list-item-action>
              </template>
            </v-list-item>
          </template>
        </v-list-item-group>
      </v-list>
    </v-card>
  </div>
</template>
<script>
export default {
  props: ['idModule', 'actionsOp', 'checkAction', 'idUser'],
  data() {
    return {
      modelCheck: this.checkAction,
    }
  },

  methods: {
    selectCheck(id) {
      this.alertaError = false
      this.$axios
        .post('/addRemoveLevelUser', {
          idUser: this.idUser,
          idModule: this.idModule,
          idAction: id,
        })
        .then((response) => {})
        .catch((error) => {
          // console.log(error.response.data.message)
          // this.alertMensajes = true
          // this.typeAlertaMensaje = 'error'
          // this.textoAlertaMesaje = `${error.response.data.message}`
          console.log(
            `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`
          )
          alert(
            `se ha encontrado un error en los checkbox: ${error.response.status} . ${error.response.data.message}`
          )
        })
    },
  },
}
</script>
