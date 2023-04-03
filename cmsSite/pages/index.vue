<template>
  <div id="wrapCont">
    <div id="logo" class="d-flex justify-space-around">
     
    </div>
    <v-card class="mx-auto elevation-10" max-width="450" outlined>
      <v-list-item three-line>
        <v-list-item-content>
          <h1>Login</h1>
          <v-spacer />
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="email"
                  :error-messages="emailErrors"
                  class="mb-5"
                  label="E-mail"
                  required
                  @input="$v.email.$touch()"
                  @blur="$v.email.$touch()"
                />
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="password"
                  :error-messages="passErrors"
                  class="input-group--focused"
                  label="Password"
                  required
                  :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show3 ? 'text' : 'password'"
                  name="input-10-2"
                  value
                  @click:append="show3 = !show3"
                  @input="$v.password.$touch()"
                  @blur="$v.password.$touch()"
                  @keyup.enter="login"
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
            </v-row>
          </v-container>

          <div class="d-flex align-center flex-column">
            <!-- loadin  @click="loader = 'loading'"  @click="login"-->
            <v-btn
              color="primary"
              :loading="loading"
              :disabled="loading"
              @click="login"
              >Aceptar</v-btn
            >
          </div>
        </v-list-item-content>
      </v-list-item>
    </v-card>
  </div>
</template>
<script>
import { validationMixin } from 'vuelidate'
import { required, email } from 'vuelidate/lib/validators'

export default {
  mixins: [validationMixin],

  validations: {
    email: { required, email },
    password: { required },
  },
  layout: 'loginView',

  data() {
    return {
      email: '',
      show3: false,
      password: '',
      loader: null,
      loading: false,
      error: null,
      isLoading: false,
      alertMensajes: false,
      typeAlertaMensaje: 'success',
      textoAlertaMesaje: '',
    }
  },

  computed: {
    passErrors() {
      const errors = []
      if (!this.$v.password.$dirty) {
        return errors
      }
      !this.$v.password.required && errors.push('Password es requerido.')
      return errors
    },
    emailErrors() {
      const errors = []
      if (!this.$v.email.$dirty) {
        return errors
      }
      !this.$v.email.email && errors.push('Debe ser un  e-mail valido')
      !this.$v.email.required && errors.push('E-mail es requerido')
      return errors
    },
  },

  watch: {
    loader() {
      const l = this.loader
      this[l] = !this[l]

      // setTimeout(() => (this[l] = false), 3000)

      this.loader = null
    },
  },

  methods: {
    async login() {
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.submitStatus = 'ERROR'
      } else {
        this.isLoading = true
        this.alertMensajes = false
        const param = {
          email: this.email,
          password: this.password,
        }
        try {
          await this.$auth.loginWith('local', { data: param })
          // console.log(this.$auth.loggedIn)
          this.$router.push('/usuarios')
          // console.log(response)
        } catch (error) {
          // console.log(error.response)
          // this.error = err.response.data.message
          this.isLoading = false
          this.alertMensajes = true
          this.typeAlertaMensaje = 'error'
          this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} . ${error.response.data}`
        }
      }

      /* try {
        await this.$axios
          .post('/login', {
            username: this.email,
            password: this.password
          })
          .then((resp) => {
             this.$auth.setToken('local', 'Bearer ' + resp.data.access_token)
            this.$auth.setRefreshToken('local', resp.data.refresh_token)
            this.$axios.setHeader(
              'Authorization',
              'Bearer ' + resp.data.access_token
            )
            this.$auth.ctx.app.$axios.setHeader(
              'Authorization',
              'Bearer ' + resp.data.access_token
            )
            this.$axios.get('/users/me').then((resp) => {
              this.$auth.setUser(resp.data)
              this.$router.push('/admin/profile')
            })
          })
      } catch (e) {
        this.error = e.response.data.message
      } */
    },
    onEnter() {
      this.login()
    },
  },
}
</script>
<style scoped>
h1 {
  color: black;
  text-align: center;
  font-size: 1.6rem;
}
#logo {
  margin: 3rem;
}
/* #logo img {
  width: 20%;
}
*/

.custom-loader {
  animation: loader 1s infinite;
  display: flex;
}
@-moz-keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@-webkit-keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@-o-keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}

@media screen and (max-width: 768px) {
  h1 {
    font-size: calc(60% + 10px);
  }
  /* #logo img {
    width: 40%;
  }
  */
}
</style>
No newline at end of file
