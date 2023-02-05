<template>
  <div>
    <v-sheet outlined @click="openDialog()" class="notCoverBox">
      <v-img v-if="getCoverImg" :aspect-ratio="4/3" :src="getCoverImg" class="elevation-3"></v-img>
      <v-responsive  v-else :aspect-ratio="4/3" class=" text-center align-center">
        <v-icon x-large>mdi-upload</v-icon>
      </v-responsive>
    </v-sheet>
    <v-text-field :label="label || 'Kapak Görseli'" ref="coverFileInput" id="coverInput"  :value="image" prepend-icon="" prepend-inner-icon="mdi-camera" @click:clear="resetCover" readonly clearable></v-text-field>

    <v-dialog v-model="dialog" max-width="1300px" style="overflow-x: hidden;" class="pa-0">
      <MediaList popup @onClickSelect="onClickSelect($event)" :currentImage="selectedImage" />
    </v-dialog>
  </div>
</template>

<script>
import MediaList from "@/components/panel/MediaList";
export default {
  name: "InputCoverImage",
  components: {MediaList},
  data: ()=>({
    dialog: false,
    selectedImage: null,
    image: null,
  }),
  props: ['value', 'label'],
  created() {
    this.image = this.value
    console.log("Çalıştı", "created")
  },
  destroyed() {
    console.log("Çalıştı", "Destroyed")
  },
  computed: {

    getCoverImg(){
      if(typeof this.image === 'string'){
        return this.$store.state.global.url.imgSm+this.image;
      }else{
        return false
      }
    },

  },

  methods: {

    onClickSelect(img){
      this.image = img.src;
      this.selectedImage = img;
      this.dialog = false;
      this.changedValue();
    },

    openDialog(){
      this.dialog = true;
    },

    resetCover(){
      this.image = null;
      this.selectedImage = null;
      this.changedValue();
    },

    changedValue(){
      this.$emit('input', this.image);
    }

  },

  watch: {

    value: function(value){
      this.image = value
    } //  'post.cover'

  }
}
</script>

<style scoped>
.notCoverBox{
  cursor: pointer;
}
.notCoverBox:hover{
  background-color: rgba(0,0,0,0.05);
}
</style>
