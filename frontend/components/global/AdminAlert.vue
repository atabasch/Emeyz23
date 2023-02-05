<template>
  <v-dialog
    v-model="data.show"
    persistent
    max-width="400">
    <v-card>
      <v-card-title class="pa-0" v-if="data.title">
        <v-alert dense :type="data.type" width="100%" class="ma-0" border="bottom" rounded="0">{{ data.title }}</v-alert>
      </v-card-title>
      <v-card-text v-if="data.text" v-html="data.text" class="pa-3 body-1"></v-card-text>

      <template v-if="data.buttons">
        <v-card-actions class="text-right justify-end grey lighten-4">
          <v-btn v-for="(button, index) in data.buttons" :key="index" :color="button.color || 'white'" :dark="button.dark || false" @click.prevent.stop="onClickButton(button)" text class="text-capitalize">{{ button.title }}</v-btn>
        </v-card-actions>
      </template>

    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "AdminAlert",
  props: {
    data: {
      type: Object,
      default: ()=>({
        show:false,
        title:  "Bu bir deneme başlığıdır",
        text: "Deneme popup uyarısı için genel bir şeyler yazıyorum. Lorem Deneme popup uyarısı için genel bir şeyler yazıyorum. Lorem Deneme popup uyarısı için genel bir şeyler yazıyorum. Lorem ",
        type: 'error',
        buttons:[
          {
            dark:true,
            color:'red',
            title:'Vazgeç',
            data: null,
            callback: (data)=>{},
            emit: null
          }
        ]
      })
    }
  },

  methods: {

    onClickButton(button){
      if(!button.emit){
        button.callback(button.data);
      }else{
        this.$emit(button.emit, button.data);
      }
    }

  }
}
</script>

<style scoped>

</style>
