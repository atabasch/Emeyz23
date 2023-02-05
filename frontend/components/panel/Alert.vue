<template>
  <v-dialog
    v-model="value"
    persistent
    max-width="400">
    <v-card>
      <v-card-title class="pa-0" v-if="data.title">
        <v-alert dense :type="getTypeKey" width="100%" class="ma-0" border="bottom" rounded="0">{{ data.title }}</v-alert>
      </v-card-title>
      <v-card-text v-if="data.text" v-html="data.text" class="pa-3 body-1" :class="getTypeData.textBg"></v-card-text>

      <template v-if="data.buttons">
        <v-card-actions class="text-right justify-end grey lighten-4">
          <v-btn v-for="(button, index) in data.buttons" :key="index" :color="button.color || 'white'" :dark="button.dark || false" @click="onClickedButton(button, index)" text class="text-capitalize">{{ button.label }}</v-btn>
        </v-card-actions>
      </template>

    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "Alert",
  props: {
    value:{
      type: Boolean,
      default: false
    },
    data: {
      type: Object,
      default: {}
    }
  },
  methods: {
    onClickedButton(button, index){
      let emitValue = ((this.data.value || button.value) || button.label ) || false
      if(button.cancel){
        return this.data.show = false;
      }else if(button.callback){
        return button.callback(emitValue);
      }else{
        let emitName  = ( button.emit || 'onClickedButton' )
        this.$emit(emitName, emitValue);
      }
    }
  },
  computed: {

    getTypeKey(){
      switch (this.data.type){
        case 'danger':
          return 'error';

        default:
          return this.data.type;
      }
    },

    getTypeData(){
      switch (this.data.type) {
        case 'info':
          return {
            color: 'blue-grey lighten-1 white--text',
            textBg: 'blue-grey lighten-3 white--text',
          }

        case 'danger':
        case 'error':
          return {
            color: 'red darken-3 white--text',
            textBg: 'red lighten-4',
          }

        case 'warning':
          return {
            color: 'orange darken-3 white--text',
            textBg: 'orange lighten-4',
          }

        case 'success':
        case 'done':
          return {
            color: 'green darken-3 white--text',
            textBg: 'green lighten-4',
          }

        case 'primary':
          return {
            color: 'blue  darken-3 white--text',
            textBg: 'blue  lighten-4',
          }

        default:
          return {
            color: 'grey lighten-2',
            textBg: 'grey lighten-2',
          }
      }
    }, //getTypeData

  }
}
</script>

<style scoped>

</style>
