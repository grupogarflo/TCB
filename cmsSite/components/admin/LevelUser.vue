<template>
  <div class="levelUer">
    <v-progress-linear
      v-if="loading"
      indeterminate
      color="cyan"
    ></v-progress-linear>
    <v-container>
      <v-row>
        <v-col
          v-for="(item, i) in levels"
          :key="i"
          cols="12"
          md="6"
          class="my-5"
        >
          <h2 class="mb-3">{{ item.nameModule }}</h2>

          <CheckBoxLevelUser
            :idModule="item.idModule"
            :actionsOp="item.action"
            :checkAction="item.checkAction"
            :idUser="idUser"
          />
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
import CheckBoxLevelUser from '~/components/admin/CheckBoxLevelUser'
export default {
  components: {
    CheckBoxLevelUser,
  },
  props: ['idUser'],
  data() {
    return {
      levels: [],
      loading: true,
    }
  },

  created() {
    this.getLevelModule()
  },
  methods: {
    getLevelModule() {
      this.$axios
        .post('/getLevelModule', {
          // idUser: this.idRegistroSend,
          idUser: this.idUser,
        })
        .then((response) => {
          this.levels = response.data.data
          this.loading = false
        })
        .catch((error) => {
          console.log(
            `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`
          )
          alert(
            `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`
          )
        })
    },
  },
}
</script>
