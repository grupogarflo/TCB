<template>
  <div class="formVideoTour">
    <v-card-text id="scrollTop">
      <v-container>
        <v-col cols="12">
          <v-text-field
            v-model="videoId"
            :error-messages="videoIdErrors"
            required
            label="Id video vimeo"
            @input="$v.videoId.$touch()"
            @blur="$v.videoId.$touch()"
          />
        </v-col>
        <v-row>
          <v-col cols="12">
            <!--
            <vimeo-player
              ref="player"
              :video-id="videoId"
              @ready="onReady"
              @update="update"
            ></vimeo-player>
            -->
            <iframe
              :src="auxVideoId"
              width="640"
              height="360"
              frameborder="0"
              allowfullscreen
              allow="autoplay; encrypted-media"
            ></iframe>
          </v-col>
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
    videoId: { required },
  },
  props: ['idRegistroSend', 'claveSend', 'videoGet'],
  data() {
    return {
      videoId: '',
      auxVideoId: '',
      url: 'https://player.vimeo.com/video/',
      textoBoton: 'Cancelar',
      isLoading: false,
      alertMensajes: false,
      typeAlertaMensaje: 'success',
      textoAlertaMesaje: '',
      options: {
        muted: true,
        autoplay: true,
      },
      playerReady: false,
      module: 10,
      btnSave: false,
    }
  },
  mounted() {
    if (this.idRegistroSend !== 0) this.getVideo()
    this.getMeLvelUser()
  },
  watch: {
    videoId(newVal, oldVal) {
      this.auxVideoId = this.url + this.videoId
    },
  },
  computed: {
    videoIdErrors() {
      const errors = []
      if (!this.$v.videoId.$dirty) {
        return errors
      }
      !this.$v.videoId.required && errors.push('id del  video es necesario.')
      return errors
    },
  },

  methods: {
    close() {
      this.$refs.player.pause()
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
        const url = '/updateVideoTour'

        this.$axios
          .post(url, {
            video: this.videoId,
            id: this.idRegistroSend,
            clave: this.claveSend,
          })
          .then((response) => {
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'success'
            this.textoAlertaMesaje =
              this.idRegistroSend === 0
                ? 'Se a guardado el video del tour'
                : 'Se a modificado el video del tour'

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

    getVideo() {
      // this.auxVideoId = this.url + this.videoGet

      this.$axios
        .post('/getVideoTour', {
          id: this.idRegistroSend,
        })
        .then((response) => {
          // this.editedItem = Object.assign({}, response.data[0])
          this.videoId = response.data[0].video
          this.auxVideoId = this.url + response.data[0].video
        })
        .catch((error) => {
          this.$v.$reset()

          // this.editedItem = Object.assign({}, this.defaultItem)
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
