<template>
  <div>


<v-dialog v-model="value" max-width="450" @click:outside="clickedCancel">
  <v-card>
    <v-card-title>{{ title }}</v-card-title>
    <v-divider />

    <div class="pa-2">

      <v-btn tile dark color="green" class="text-capitalize subtitle-2 mb-2" @click="clickedSelectBtn"><v-icon>mdi-upload</v-icon> Fotoğraf Seç</v-btn>

      <div>
        <v-responsive @click="clickedSelectBtn" v-if="!file" :aspect-ratio="4/3" class="align-center text-center grey lighten-3 pa-4 uploadbtn">
          <v-icon size="80">mdi-upload</v-icon>
          <h5 class="text-h6 mb-3">Tıkla ve Bilgisayardan bir görsel seç</h5>
          <small class="red lighten-4 red--text text--darken-4 pa-3">Görsel en fazla 2mb boyutunda png veya jpg formatında olmalıdır.</small>
        </v-responsive>

        <v-responsive v-if="file" :aspect-ratio="4/3" class="align-center text-center grey lighten-3">
          <v-img :src="file.url" :aspect-ratio="4/3" />
        </v-responsive>
      </div>
      <input type="file" class="d-none" @change="changedFile($event)" ref="inputFile" />


    </div>

    <v-divider />
    <v-card-actions class="justify-space-between">
      <v-btn @click="clickedCancel" dark tile color="red" class="text-capitalize subtitle-2"><v-icon>mdi-close</v-icon> Vazgeç</v-btn>
      <v-btn @click="clickedUploadFile" :dark="!!file" :disabled="!file" tile color="green" class="text-capitalize subtitle-2"><v-icon>mdi-check</v-icon> Yükle & Güncelle</v-btn>
    </v-card-actions>

  </v-card>
</v-dialog>


  </div>

</template>

<script>
export default {
  name: "PopupImageUpload",

  props: {
    value: {
      type: Boolean,
      required: true,
      default: false
    },
    title: {
      type: String,
      default: 'Bilgisayardan Fotoğraf Yükle'
    }
  },

  data: ()=>({
    file: null,
    dialog: false
  }),


  methods: {

    clickedSelectBtn(){
      this.$refs.inputFile.click();
    },

    async changedFile(event){
      if(event.target.files.length < 1){
        this.resetDialog();
      }else{
        this.file = {
          file: event.target.files[0],
          url: URL.createObjectURL( event.target.files[0])
        };
      }
    },

    clickedUploadFile(){

      let postData = new FormData();
      postData.append("file", this.file.file);
      postData.append('username', this.$auth.user.username);

      this.$axios.post('/media/upload/avatar', postData).then(({status, data}) => {
        if(status===200 && data.success){
          this.$emit('uploaded', data);
          this.resetDialog();
        }else{
         this.$toast.error(data.message || 'İşlem Başarısız');
        }
      }).catch(err => {
        this.$toast.error('İşlem Başarısız');
      });

    },

    clickedCancel(){
      this.resetDialog();
    },

    resetDialog(){
      this.file  = null;
      this.$refs.inputFile.value = null;
      this.$emit('input', false);
    },




  },

  watch: {
    value(val){
      if(!val){
        this.file = null;
      }
    }
  }
}
</script>

<style scoped>
.uploadbtn{
  cursor: pointer ;
}
</style>
