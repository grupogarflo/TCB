<template>
  <div id="wrapForm">
    <v-card-text id="scrollTop">
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
              v-model="editedItem.sub_title"
              :error-messages="sub_titleErrors"
              required
              label="Sub titulo"
              @input="$v.editedItem.sub_title.$touch()"
              @blur="$v.editedItem.sub_title.$touch()"
            />
          </v-col>
          <v-col cols="12">
            <v-text-field
              v-model="editedItem.url"
              :error-messages="urlErrors"
              required
              label="Url"
              @input="$v.editedItem.url.$touch()"
              @blur="$v.editedItem.url.$touch()"
            />
          </v-col>
          <v-col cols="12">
            <v-text-field v-model="editedItem.rank" label="Rank" />
            <!--
            <v-text-field
              v-model="editedItem.rank"
              :error-messages="rankErrors"
              required
              label="Rank"
              @input="$v.editedItem.rank.$touch()"
              @blur="$v.editedItem.rank.$touch()"
            />
            -->
          </v-col>
          <v-col cols="12">
            <v-text-field
              v-model="editedItem.avaible"
              :error-messages="avaibleErrors"
              required
              label="Disponible"
              @input="$v.editedItem.avaible.$touch()"
              @blur="$v.editedItem.avaible.$touch()"
            />
          </v-col>
          <v-col cols="12">
            <v-text-field
              v-model="editedItem.duration"
              :error-messages="durationErrors"
              required
              label="Duración"
              @input="$v.editedItem.duration.$touch()"
              @blur="$v.editedItem.duration.$touch()"
            />
          </v-col>
          <!--
          <v-col cols="12">
            <v-text-field v-model="editedItem.peek_id" label="Peek id" />
          </v-col>
          -->
          <!--
          <v-col cols="12">
            <v-checkbox
              v-model="editedItem.special_price"
              label="Precio especial"
            ></v-checkbox>
          </v-col>
          -->
          <!--
          <v-col cols="12">
            <v-row>
              <v-col cols="12" md="3">
                <v-text-field
                  v-model="editedItem.price_fake_adult"
                  :error-messages="price_fake_adultErrors"
                  required
                  label="Precio fake adulto"
                  @input="$v.editedItem.price_fake_adult.$touch()"
                  @blur="$v.editedItem.price_fake_adult.$touch()"
                />
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  v-model="editedItem.price_fake_child"
                  :error-messages="price_fake_childErrors"
                  required
                  label="Precio fake niño"
                  @input="$v.editedItem.price_fake_child.$touch()"
                  @blur="$v.editedItem.price_fake_child.$touch()"
                />
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  v-model="editedItem.price_real_adult"
                  :error-messages="price_real_adultErrors"
                  required
                  label="Precio venta adulto"
                  @input="$v.editedItem.price_real_adult.$touch()"
                  @blur="$v.editedItem.price_real_adult.$touch()"
                />
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  v-model="editedItem.price_real_child"
                  :error-messages="price_real_childErrors"
                  required
                  label="Precio venta niño"
                  @input="$v.editedItem.price_real_child.$touch()"
                  @blur="$v.editedItem.price_real_child.$touch()"
                />
              </v-col>
            </v-row>
          </v-col>
          -->
          <v-col cols="12">
            <v-checkbox
              v-model="editedItem.top_home"
              label="Se muestra en el home"
            ></v-checkbox>
          </v-col>
          <v-col cols="12 mt-8 mb-8">
            <label class="font-weight-regular">Descripción breve</label>
            <v-alert
              border="top"
              v-model="errorDescriptionSmall"
              dismissible
              type="warning"
              >Este campo es necesario para continuar</v-alert
            >
            <tinymce
              class="mt-3 editor"
              :other_options="{
                height: 300,
              }"
              :id="
                'description_small' +
                Math.random()
                  .toString(36)
                  .replace(/[^a-z]+/g, '')
                  .substr(2, 10)
              "
              v-model="description_small"
              @editorChange="onEditorFocusSmall"
            ></tinymce>
            <!--
            <vue-editor
              class="mt-3 editor"
              placeholder="Write Something..."
              v-model="description_small"
              @focus="onEditorFocusSmall"
            ></vue-editor>
            -->
          </v-col>
          <v-col cols="12 mt-8 mb-8">
            <label class="font-weight-regular">Descripción</label>
            <v-alert
              border="top"
              v-model="errorDescriptionShow"
              dismissible
              type="warning"
              >Este campo es necesario para continuar</v-alert
            >
            <tinymce
              class="mt-3 editor"
              :id="
                'description' +
                Math.random()
                  .toString(36)
                  .replace(/[^a-z]+/g, '')
                  .substr(2, 10)
              "
              :other_options="{
                height: 300,
              }"
              v-model="description"
              @editorChange="onEditorFocusShow"
            ></tinymce>
            <!--
            <vue-editor
              class="mt-3 editor"
              @focus="onEditorFocusShow"
              placeholder="Write Something..."
              v-model="description"
            ></vue-editor>
            -->
          </v-col>
          <v-col cols="12 mt-8 mb-8">
            <label class="font-weight-regular">Incluye</label>
            <v-alert
              border="top"
              v-model="errorIncluye"
              dismissible
              type="warning"
              >Este campo es necesario para continuar</v-alert
            >
            <tinymce
              class="mt-3 editor"
              :id="
                'includes' +
                Math.random()
                  .toString(36)
                  .replace(/[^a-z]+/g, '')
                  .substr(2, 10)
              "
              :other_options="{
                height: 300,
              }"
              v-model="includes"
              @editorChange="onEditorFocusIncluye"
            ></tinymce>
            <!--
            <vue-editor
              class="mt-3 editor"
              @focus="onEditorFocusIncluye"
              placeholder="Write Something..."
              v-model="includes"
            ></vue-editor>
            -->
          </v-col>
          <v-col cols="12 mt-8 mb-8">
            <label class="font-weight-regular">No incluye</label>
            <v-alert
              border="top"
              v-model="errorDescriptionOculto"
              dismissible
              type="warning"
              >Este campo es necesario para continuar</v-alert
            >
            <tinymce
              class="mt-3 editor"
              :id="
                'not_include' +
                Math.random()
                  .toString(36)
                  .replace(/[^a-z]+/g, '')
                  .substr(2, 10)
              "
              :other_options="{
                height: 300,
              }"
              v-model="not_include"
              @editorChange="onEditorFocusOculto"
            ></tinymce>

            <!--
            <vue-editor
              class="mt-3 editor"
              @focus="onEditorFocusOculto"
              placeholder="Write Something..."
              v-model="not_include"
            ></vue-editor>
            -->
          </v-col>

          <v-col cols="12 mt-8 mb-8">
            <label class="font-weight-regular">Que traer</label>
            <v-alert
              border="top"
              v-model="errorTraer"
              dismissible
              type="warning"
              >Este campo es necesario para continuar</v-alert
            >
            <tinymce
              class="mt-3 editor"
              :id="
                'bring' +
                Math.random()
                  .toString(36)
                  .replace(/[^a-z]+/g, '')
                  .substr(2, 10)
              "
              :other_options="{
                height: 300,
              }"
              v-model="bring"
              @editorChange="onEditorFocusTraer"
            ></tinymce>
            <!--
            <vue-editor
              class="mt-3 editor"
              @focus="onEditorFocusTraer"
              placeholder="Write Something..."
              v-model="bring"
            ></vue-editor>
            -->
          </v-col>
          <v-col cols="12 mt-8 mb-8">
            <label class="font-weight-regular">Notas</label>
            <v-alert
              border="top"
              v-model="errorNotas"
              dismissible
              type="warning"
              >Este campo es necesario para continuar</v-alert
            >
            <tinymce
              class="mt-3 editor"
              :id="
                'note' +
                Math.random()
                  .toString(36)
                  .replace(/[^a-z]+/g, '')
                  .substr(2, 10)
              "
              :other_options="{
                height: 300,
              }"
              v-model="note"
              @editorChange="onEditorFocusNotas"
            ></tinymce>
            <!--
            <vue-editor
              class="mt-3 editor"
              @focus="onEditorFocusNotas"
              placeholder="Write Something..."
              v-model="note"
            ></vue-editor>
            -->
          </v-col>
          <v-col cols="12">
            <v-textarea
              name="input-7-1"
              label="Meta data title"
              auto-grow
              counter
              v-model="editedItem.meta_title"
              :error-messages="metaTitleErrors"
              required
              @input="$v.editedItem.meta_title.$touch()"
              @blur="$v.editedItem.meta_title.$touch()"
            ></v-textarea>
          </v-col>
          <v-col cols="12">
            <v-textarea
              name="input-7-2"
              label="Meta data description"
              auto-grow
              counter
              v-model="editedItem.meta_description"
              :error-messages="metaDescriptionErrors"
              required
              @input="$v.editedItem.meta_description.$touch()"
              @blur="$v.editedItem.meta_description.$touch()"
            ></v-textarea>
          </v-col>
          <v-col cols="12">
            <v-textarea
              name="input-7-2"
              label="Meta data keywords"
              auto-grow
              counter
              v-model="editedItem.meta_keywords"
              :error-messages="metaKeyWordErrors"
              required
              @input="$v.editedItem.meta_keywords.$touch()"
              @blur="$v.editedItem.meta_keywords.$touch()"
            ></v-textarea>
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
      <v-btn color="blue darken-1" text @click="save">Guardar</v-btn>
    </v-card-actions>
  </div>
</template>
<script>
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

export default {
  mixins: [validationMixin],

  validations: {
    editedItem: {
      name: { required },
      sub_title: { required },
      url: { required },
      // rank: { required },
      // top_home: { required },
      avaible: { required },
      duration: { required },
      // peek_id: { required },
      meta_title: { required },
      meta_description: { required },
      meta_keywords: { required },
      // price_fake_adult: { required },
      // price_fake_child: { required },
      // price_real_adult: { required },
      // price_real_child: { required },
    },
  },

  props: ['idioma', 'idRegistroSend', 'idiomaTamano', 'uid'],

  data() {
    return {
      description_small: '',
      description: '',
      not_include: '',
      includes: '',
      bring: '',
      note: '',
      isLoading: false,
      alertMensajes: false,
      typeAlertaMensaje: 'success',
      textoAlertaMesaje: '',
      show3: false,
      error: null,
      dialog: false,
      // idRegistro: 0,
      // uid: '',
      textoBoton: 'Cancelar',
      errorDescriptionSmall: false,
      errorDescriptionShow: false,
      errorDescriptionOculto: false,
      errorIncluye: false,
      errorTraer: false,
      errorNotas: false,
      contadorIdioma: 0,
      editedItem: {
        name: '',
        sub_title: '',
        url: '',
        rank: '',
        top_home: false,
        avaible: '',
        duration: '',
        // peek_id: '',
        meta_title: '',
        meta_description: '',
        meta_keywords: '',
        // price_fake_adult: '',
        // price_fake_child: '',
        // price_real_adult: '',
        // price_real_child: '',
      },
      defaultItem: {
        name: '',
        sub_title: '',
        url: '',
        rank: '',
        top_home: false,
        avaible: '',
        duration: '',
        // peek_id: '',
        meta_title: '',
        meta_description: '',
        meta_keywords: '',
        // price_fake_adult: '',
        // price_fake_child: '',
        // price_real_adult: '',
        // price_real_child: '',
        selected: [],
      },
      tinyId: 0,
    }
  },

  computed: {
    formTitle() {
      return this.idRegistroSend === 0 ? 'Nuevo tour' : 'Editar tour'
    },
    nameErrors() {
      const errors = []
      if (!this.$v.editedItem.name.$dirty) {
        return errors
      }
      !this.$v.editedItem.name.required && errors.push('Nombre es requerido.')
      return errors
    },

    sub_titleErrors() {
      const errors = []
      if (!this.$v.editedItem.sub_title.$dirty) {
        return errors
      }
      !this.$v.editedItem.sub_title.required &&
        errors.push('sub titulo es requerido.')
      return errors
    },

    urlErrors() {
      const errors = []
      if (!this.$v.editedItem.url.$dirty) {
        return errors
      }
      !this.$v.editedItem.url.required && errors.push('Url es requerido.')
      return errors
    },
    /*
    rankErrors() {
      const errors = []
      if (!this.$v.editedItem.rank.$dirty) {
        return errors
      }
      !this.$v.editedItem.rank.required && errors.push('Rank es requerido.')
      return errors
    },
    */
    avaibleErrors() {
      const errors = []
      if (!this.$v.editedItem.avaible.$dirty) {
        return errors
      }
      !this.$v.editedItem.avaible.required &&
        errors.push('Disponibilidad es requerida.')
      return errors
    },
    durationErrors() {
      const errors = []
      if (!this.$v.editedItem.duration.$dirty) {
        return errors
      }
      !this.$v.editedItem.duration.required &&
        errors.push('Duración es requerida.')
      return errors
    },
    /* peek_idErrors() {
      const errors = []
      if (!this.$v.editedItem.peek_id.$dirty) {
        return errors
      }
      !this.$v.editedItem.peek_id.required &&
        errors.push('Id de peek es requerida.')
      return errors
    }, */
    metaTitleErrors() {
      const errors = []
      if (!this.$v.editedItem.meta_title.$dirty) {
        return errors
      }
      !this.$v.editedItem.meta_title.required &&
        errors.push('meta title es requerido.')
      return errors
    },
    metaDescriptionErrors() {
      const errors = []
      if (!this.$v.editedItem.meta_description.$dirty) {
        return errors
      }
      !this.$v.editedItem.meta_description.required &&
        errors.push('meta description es requerido.')
      return errors
    },
    metaKeyWordErrors() {
      const errors = []
      if (!this.$v.editedItem.meta_keywords.$dirty) {
        return errors
      }
      !this.$v.editedItem.meta_keywords.required &&
        errors.push('meta keywords es requerido.')
      return errors
    },
    /*
    price_fake_adultErrors() {
      const errors = []
      if (!this.$v.editedItem.price_fake_adult.$dirty) {
        return errors
      }
      !this.$v.editedItem.price_fake_adult.required &&
        errors.push('precio publico adulto es requerido.')
      return errors
    },
    price_fake_childErrors() {
      const errors = []
      if (!this.$v.editedItem.price_fake_child.$dirty) {
        return errors
      }
      !this.$v.editedItem.price_fake_child.required &&
        errors.push('precio publico niño es requerido.')
      return errors
    },
    price_real_adultErrors() {
      const errors = []
      if (!this.$v.editedItem.price_real_adult.$dirty) {
        return errors
      }
      !this.$v.editedItem.price_real_adult.required &&
        errors.push('precio venta adulto es requerido.')
      return errors
    },
    price_real_childErrors() {
      const errors = []
      if (!this.$v.editedItem.price_real_child.$dirty) {
        return errors
      }
      !this.$v.editedItem.price_real_child.required &&
        errors.push('precio venta niño es requerido.')
      return errors
    },
    */
  },

  watch: {
    idioma(newVal, oldVal) {
      // this.tinyId = this.tinyId + 1
      if (this.idRegistroSend !== 0) this.getCategoriaIdioma()

      /* this.description_smallId ='description_small' + this.tinyId
       this.descriptionId = 'description' + new Date().getTime().toString(36)
      this.includesId = 'includes' + new Date().getTime().toString(36)
      this.not_includeId = 'not_include' + new Date().getTime().toString(36)
      this.bringId = 'bring' + new Date().getTime().toString(36)
      this.noteId = 'note' + new Date().getTime().toString(36) */
    },
    dialogoEliminacion(val) {
      val || this.close()
    },
  },

  mounted() {
    // this.uid = new Date().getTime().toString(36)
    // this.creaId()

    if (this.idRegistroSend !== 0) this.getCategoriaIdioma()
    // this.$emit('claveNueva', this.uid)
  },

  methods: {
    close() {
      this.$emit('cerrarPop', 'payload')
    },

    save() {
      this.errorDescriptionSmall = false
      this.errorDescriptionShow = false
      this.errorDescriptionOculto = false
      this.errorIncluye = false
      this.errorTraer = false
      this.errorNotas = false
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.submitStatus = 'ERROR'
      }
      if (
        !this.$v.$invalid &&
        // this.description_small !== '' &&
        this.description !== '' &&
        this.not_include !== '' &&
        this.includes !== '' &&
        this.bring !== '' &&
        this.note !== ''
      ) {
        // do your submit logic here
        this.isLoading = true
        const url = this.idRegistroSend === 0 ? '/addTour' : '/updateTour'

        /*
        if (this.uid === '') {
          this.creaId()
          // this.$emit('claveNueva', this.uid)
        }
        */

        this.$axios
          .post(url, {
            name: this.editedItem.name,
            sub_title: this.editedItem.sub_title,
            url: this.editedItem.url,
            rank: this.editedItem.rank,
            top_home: this.editedItem.top_home,
            description_small: this.description_small,
            description: this.description,
            not_include: this.not_include,
            avaible: this.editedItem.avaible,
            duration: this.editedItem.duration,
            includes: this.includes,
            bring: this.bring,
            note: this.note,
            meta_title: this.editedItem.meta_title,
            meta_description: this.editedItem.meta_description,
            meta_keywords: this.editedItem.meta_keywords,
            // price_fake_adult: this.editedItem.price_fake_adult,
            // price_fake_child: this.editedItem.price_fake_child,
            // price_real_adult: this.editedItem.price_real_adult,
            // price_real_child: this.editedItem.price_real_child,
            // special_price: this.editedItem.special_price,
            // peek_id: this.editedItem.peek_id,
            peek_id: 0,
            idioma: this.idioma,
            id: this.idRegistroSend,
            clave: this.uid,
          })
          .then((response) => {
            this.$emit('claveAnterior', this.uid)
            this.contadorIdioma = this.contadorIdioma + 1
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'success'
            this.textoAlertaMesaje =
              this.idRegistroSend === 0
                ? 'Se a creado el tour, no olvides dar de alta todos los idiomas'
                : 'Se a modificado el tour'

            if (this.idRegistroSend === 0) {
              // this.idRegistroSend = 0
              this.$v.$reset()
              this.description_small = ''
              this.description = ''
              this.not_include = ''
              this.includes = ''
              this.bring = ''
              this.note = ''
              this.editedItem = Object.assign({}, this.defaultItem)
            } else {
              this.textoBoton = 'Cerrar'
            }

            if (this.idioma === 2) {
              this.$emit('claveAnterior', this.uid)
              this.$emit('openTabs', false)
              this.$emit('crearNuevaClave', true)
            }
            /*
            if (this.contadorIdioma === this.idiomaTamano) {
              this.$emit('claveNueva', this.uid)
              this.$emit('openTabs', false)
              // this.creaId()
              this.uid = ''
              this.contadorIdioma = 0
            }
            */
            setTimeout(() => (this.alertMensajes = false), 2500)
            /* setTimeout(
              () => document.querySelector('#tabs').scrollIntoView(),
              3700
            ) */

            setTimeout(() => {
              console.log('Hello Timeout!')
              this.$emit('nextIdioma', 'Ingles')
              document.querySelector('#tabs').scrollIntoView()
            }, 3100)
          })
          .catch((error) => {
            this.isLoading = false
            this.alertMensajes = true
            this.typeAlertaMensaje = 'error'
            this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} . ${error.response.data.message}`
          })
      }
      // {
      // if (this.description_small === '') this.errorDescriptionSmall = true
      if (this.description === '') this.errorDescriptionShow = true
      if (this.not_include === '') this.errorDescriptionOculto = true
      if (this.includes === '') this.errorIncluye = true
      if (this.bring === '') this.errorTraer = true
      if (this.note === '') this.errorNotas = true
      // }
    },

    getFormIdioma() {
      this.getCategoriaIdioma()
    },

    getCategoriaIdioma() {
      this.$axios
        .post('/getTourInfo', {
          idioma: this.idioma,
          id: this.idRegistroSend,
        })
        .then((response) => {
          this.editedItem = Object.assign({}, response.data[0])
          this.description_small = response.data[0].description_small
          this.description = response.data[0].description
          this.not_include = response.data[0].not_include
          this.includes = response.data[0].includes
          this.bring = response.data[0].bring
          this.note = response.data[0].note
          this.$emit('claveNueva', response.data[0].clave)
        })
        .catch((error) => {
          this.$v.$reset()
          this.description_small = ''
          this.description = ''
          this.not_include = ''
          this.includes = ''
          this.bring = ''
          this.note = ''
          this.editedItem = Object.assign({}, this.defaultItem)
          this.isLoading = false
          this.alertMensajes = true
          this.typeAlertaMensaje = 'error'
          this.textoAlertaMesaje = `se ha encontrado un error: ${error.response.status} no hay informacion disponible`
        })
    },

    onEditorFocusSmall(quill) {
      this.errorDescriptionSmall = false
    },
    onEditorFocusShow(quill) {
      this.errorDescriptionShow = false
    },
    onEditorFocusOculto(quill) {
      this.errorDescriptionOculto = false
    },
    onEditorFocusIncluye(quill) {
      this.errorIncluye = false
    },
    onEditorFocusTraer(quill) {
      this.errorTraer = false
    },
    onEditorFocusNotas(quill) {
      this.errorNotas = false
    },
    editorInit() {
      console.log('hola abre' + this.tinyId)
      // this.$refs.tm.value = this.tinyId
    },
    /*
    creaId() {
      this.uid = new Date().getTime().toString(36)
    },
    */
  },
}
</script>
