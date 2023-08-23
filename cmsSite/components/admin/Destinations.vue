<template>
  <div class="mx-auto my-8" max-width="600" :loading="cargandoTabla">
    <v-container>
      <v-row>
        <v-col col="12"> <p v-if="cargandoTabla">{{loadingText}}</p> </v-col>
      </v-row>
      <v-row>
          <v-col col="6" sm="4" md="3" v-for="(item, i) in items" :key="i">

            <DestinationComp :item="item" class="mx-5" :fromDatabase="fromDatabase"></DestinationComp>


          </v-col>
      </v-row>

      <v-row>
          <v-col col="12" class="text-right">
            <v-btn  color="blue darken-1" text @click="selectCheck"
            >Guardar</v-btn>
          </v-col>
      </v-row>

      <v-alert
        v-model="alertMensajes"
        close-text="Close Alert"
        border="right"
        colored-border
        elevation="8"
        dismissible
        class="text-center my-5"
        :type="typeAlertaMensaje"
      >{{ textoAlertaMesaje }}</v-alert>
    </v-container>
  </div>
</template>
<script>
import DestinationComp from './DestinationComp.vue';

export default {
    props: ['idRegistroSend', 'claveSend'],
    data: () => ({
        items: [],
        orders: [],
        // model: ['Carrots']
        modelItems: [],
        cargandoTabla: true,
        loadingText: 'Cargando... por favor espere',
        alertMensajes: false,
        typeAlertaMensaje: 'success',
        textoAlertaMesaje: '',
        fromDatabase:[],

        selectedDestinations:[]



    }),

    computed:{

      toursDestinations(){
        // eslint-disable-next-line dot-notation
        return this.$store.getters['toursDestinations'];
      }
    },
    mounted() {
        this.getRegistros();
        // console.log('yupi me creee' + this.idRegistroSend)
        this.$nuxt.$on('addDestinationValue',(value)=>{

            this.selectedDestinations.push({
              check:value,
              order:0
            });

        });

        this.$nuxt.$on('addDestinationOrder',(payload)=>{

            const pos  = this.selectedDestinations.map(element=>element.check).indexOf(payload.value);

            if(pos ===-1){
              return false;
            }

            this.selectedDestinations[pos].order=payload.order;
        });

        this.$nuxt.$on('removeDestinationSelected',(value)=>{

          const pos  = this.selectedDestinations.map(element=>element.check).indexOf(value);

          if(pos!==-1){

            this.selectedDestinations.splice(pos,1);
          }

        })


    },
    methods: {
        showOrder(itemId) {
            const pos = this.modelItems.includes(itemId);
            return pos;
        },
        selectCheck() {
            // console.log(item)
            this.alertaError = false;
            this.$axios
                .post('/addRemoveDestinationTour', {
                idTour: this.idRegistroSend,
                destinations: this.selectedDestinations,
                claveSend: this.claveSend
            })
                .then(response => {
                this.alertMensajes = true;
                this.typeAlertaMensaje = 'success';
                this.textoAlertaMesaje = `El tour se vinculo a los destinos seleccionados`;


                /// clear tours in state
                this.$store.dispatch('clearTourDestination');


            })
                .catch(error => {
                // console.log(error.response.data.message)
                this.alertMensajes = true;
                this.typeAlertaMensaje = 'error';
                this.textoAlertaMesaje = `${error.response.data.message}`;
            });
        },
        getRegistros() {
            this.$axios
                .post('/getDestinationsCMS', {
                id: this.idRegistroSend
            })
                .then(response => {
                // this.items = response.data[0].categoria
                response.data.destinations.forEach(element => {
                    this.items.push({ id: element.id, name: element.name });
                });
                // this.modelItems = response.data.checked;

                // alert('add');
                /*
                response.data.checked.forEach(element=>{
                  this.$store.dispatch('addTourDestination',{
                      check:element.check,
                      order:element.order
                  });
                })
                */

                this.fromDatabase =response.data.checked



                this.cargandoTabla = false;
            }).catch(error => {
                //  this.textoDialogoEliminacion =
                //  'El tour que esta intentado eliminar no existe o ya fue eliminado previamente'
                alert('Existe un error al intentar recuperar la información');
                console.log(error);
            });
        }
    },
    components: { DestinationComp }
}
</script>
