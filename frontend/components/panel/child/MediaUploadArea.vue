<template>
<v-container>
  <v-responsive :aspect-ratio="4/3" class="text-center align-center"  style="border: 1px solid #edd">


    <v-responsive v-if="!selectedFile" :aspect-ratio="4/3" class="noActionBox" @click="onClickSelectArea()">
      <v-icon x-large>mdi-upload</v-icon>
      <h1 class="text-h4">Dosya Seç</h1>
    </v-responsive>

    <v-responsive v-else :aspect-ratio="4/3" class="actionBox">
      <h1 class="text-h4">Dosya Seçildi</h1>
      <v-sheet max-width="460" class="mx-auto text-center">
        <v-progress-linear v-if="loading" color="deep-purple accent-4" indeterminate rounded height="6"></v-progress-linear>
        <v-icon v-else large>mdi-check</v-icon>
      </v-sheet>
    </v-responsive>

    <input type="file" @change="onSelectedFile" style="display: none" ref="fileInput" label="Görsel" />


  </v-responsive>
</v-container>
</template>

<script>
import Index from "@/pages/aswpanel/posts";
export default {
  name: "MediaUploadArea",
  components: {Index},
  data(){
    return {
      selectedFile:null,
      loading: true
    }
  },
  methods: {

    onClickSelectArea(){
     this.$refs.fileInput.click()
    },

    onSelectedFile(event){
      if(event.target.files.length < 1){
        this.selectedFile = false;
      }else{
        this.selectedFile = true;

        let data = new FormData();
        data.append('file', event.target.files[0]);
        this.$axios.post('/admin/media/upload', data, { headers: { 'Content-Type': 'multipart/form-data' } }).then(({data, status}) => {
          if(status===200 && data.success){
            this.$emit('onUploaded',data.file);
            this.loading = false;
          }else{
            this.selectedFile = false;
            this.loading = false;
          }
        }).catch(err => {  });

      }
    }

  }
}
</script>

<style scoped>
.noActionBox, .actionBox{
  text-align: center;
  align-items: center;
  background-color: rgba(222, 222, 222, 0.20);

}
.noActionBox:hover{
  background-color: rgba(222, 222, 222, 0.5);
  cursor: pointer;
}

</style>
