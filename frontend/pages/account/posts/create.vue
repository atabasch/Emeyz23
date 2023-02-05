<template>
<div>
  <v-card flat>
    <v-card-title>Yeni Makale Oluştur</v-card-title><v-divider />
    <div class="pa-4">
      <v-text-field v-model="post.title" label="Makale Başlığı" outlined counter maxlength="100" />

      <v-responsive :aspect-ratio="16/9" class="grey lighten-3 cursor-pointer text-center align-center justify-center mb-7" @click="clickedCoverArea">
        <v-img :src="tempImage" v-if="tempImage" :aspect-ratio="16/9" />
        <div v-if="!tempImage">
          <v-icon>mdi-image</v-icon>
          <h4 class="text-h5">Kapak Fotoğrafı Seç</h4>
        </div>
      </v-responsive>
      <input type="file" class="d-none" ref="fileInput" @change="selectedImage" />

      <v-text-field v-model="post.video" label="Video Linki" rows="1" outlined auto-grow counter maxlength="60" hint="Eğer makaleyi bir video ile desteklemek istiyorsanız bir youtube video adresi ekleyebilirsiniz."/>

      <v-textarea v-model="post.description" label="Kısa Açıklama (Description)" rows="1" outlined auto-grow counter maxlength="255"/>

      <v-combobox
        v-model="post.categories"
        :items="getCategories"
        :item-text="({title}) => title"
        :item-value="({id}) => id"
        label="Makale Kategorisi"
        multiple
        small-chips
        hint="Makalenin hangi kategorilerde yayımlanacağını seçin"
        chips
        counter="5"
        deletable-chips
        :loading="loadingCategories"
        prepend-inner-icon="mdi-file-tree"
        outlined>
      </v-combobox>

      <client-only>
        <vue-editor :editorOptions="{placeholder: 'Makale içeriği bu alana yazılacak', height: 300}" v-model="post.content"></vue-editor>
      </client-only>

      <div class="pt-5 text-right">
        <v-btn tile right color="green" class="text-capitalize subtitle-2" @click="sendPost" :disabled="!validatePost" :dark="validatePost"><v-icon small class="mr-2">mdi-send</v-icon> Gönder</v-btn>
      </div>


    </div>
  </v-card>

</div>
</template>

<script>


export default {
  name: "AccountCreatePost",
  layout: 'account',
  data: ()=>({
    categories: [],
    loadingCategories:false,
    uploadDialog: false,
    post: {
      title       : '',
      cover       : '',
      description : '',
      categories  : [],
      content     : '',
      video       : '',
    },
    tempImage: null
  }),

  fetch: function(){
    this.loadingCategories = true;
    return this.$axios.get('/category/minilist').then(({status, data})=>{
      if(status===200 && data.success && data.categories){
        this.categories = data.categories;
        this.loadingCategories = false;
      }
    }).catch(err => {})
  },

  computed: {
    getCategories: function(){
      return this.categories;
    },
    validatePost(){
      return (this.post.title.length > 5 && this.post.description.length > 60)
    }
  },

  methods: {
    clickedCoverArea(){
      this.$refs.fileInput.click();
    },

    selectedImage(event){
      if(event.target.files.length > 0){
        console.log("seçildi")
        this.post.cover = event.target.files[0];
        this.tempImage  = URL.createObjectURL(this.post.cover);
      }
    },

    sendPost(){
      if(this.validatePost){
        let postData = new FormData();
        for(let i in this.post){
          postData.append(i, this.post[i]);
        }
        postData.append('categories', Object.values(this.post.categories).map(i => i.id).join(","));

        this.$axios.post('/post/create', postData).then(({status, data})=>{
          if(status===200 && data.success){
            this.$toast.success(data.message || 'Makale gönderildi.')
            this.$router.replace('/account/posts')
          }else{
            this.$toast.error(data.message || 'Makale isteği başarısız oldu.')
          }
          console.log(data);
        }).catch(err => {

        })
      }
    }

  }
}
</script>

<style scoped>
.cursor-pointer{
  cursor: pointer;
}
.cursor-pointer:hover{
  opacity: 0.75;
}
</style>
