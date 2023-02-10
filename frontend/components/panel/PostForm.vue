<template>
  <div>
    <v-form>
      <v-container>
        <v-row>

          <v-col cols="12" md="8" lg="9" xl="10  ">

            <v-row>
              <v-col cols="12" lg="6">
                <v-text-field label="Makale için bir başlık (Title)" hint="En fazla 100 karakterden oluşan tanıtıcı bir metin"
                              counter="100"
                              v-model="post.title"
                ></v-text-field>
              </v-col>

              <v-col cols="12" lg="6">
                <v-text-field label="Slug" v-model="post.slug" hint="turkce-karakter-olmayan-tire-karakteri" counter="100"></v-text-field>
              </v-col>

              <v-col cols="12">
                <v-combobox
                  v-model="post.categories"
                  :items="categories"
                  :item-text="({title}) => title"
                  :item-value="({id}) => id"
                  label="Kategoriler"
                  multiple
                  small-chips
                  hint="Makalenin hangi kategorilerde yayımlanacağını seçin"
                  chips
                  counter="5"
                  deletable-chips
                  :loading="loadingCategories"
                  prepend-inner-icon="mdi-file-tree"
                >

                </v-combobox>
              </v-col>

              <v-col cols="12">
                <v-text-field label="Anahtar Kelimeler (Keywords)" v-model="post.keywords" hint="Benzer içerikler bulunabilmesi için vigül (,) ile ayrılmış bir kaç tanımlayıcı söz" prepend-inner-icon="mdi-tag-multiple"></v-text-field>
              </v-col>

              <v-col cols="12">
                <v-textarea rows="1" auto-grow label="Açıklama (Description)" v-model="post.description" counter="160"></v-textarea>
              </v-col>

              <v-col cols="12">
                <v-textarea rows="1" auto-grow label="Özet" counter="255" v-model="post.summary"></v-textarea>
              </v-col>

              <v-col cols="12">
                <client-only>
                  <vue-editor :editorOptions="{placeholder: 'Makale içeriği bu alana yazılacak', height: 300}" v-model="post.content"></vue-editor>
                </client-only>
              </v-col>
            </v-row>

          </v-col>



          <v-col>
            <v-row>

              <v-col cols="12">
                <v-sheet outlined @click="onClickCoverBox()" class="notCoverBox">
                  <v-img v-if="getCoverImg" :aspect-ratio="4/3" :src="getCoverImg" class="elevation-3"></v-img>
                  <v-responsive  v-else :aspect-ratio="4/3" class=" text-center align-center">
                    <v-icon x-large>mdi-upload</v-icon>
                  </v-responsive>
                </v-sheet>
              </v-col>



              <v-col cols="12" class="py-0">
                <v-text-field label="Kapak Görseli" ref="coverFileInput" id="coverInput"  :value="post.cover" prepend-icon="" prepend-inner-icon="mdi-camera" @click:clear="resetCover" readonly clearable></v-text-field>
              </v-col>

              <v-col cols="12" class="py-0">
                <v-switch v-model="post.hide_cover" :label="'Detay sayfasında kapağı gizle'" value="on"></v-switch>
              </v-col>

              <v-col cols="12" class="py-0">
                <v-switch v-model="post.allow_comments" :label="'Yorumlara izin ver'" value="on"></v-switch>
              </v-col>

              <v-col cols="12">
                <v-text-field v-model="post.video" label="Video ID veya URL" hint="youtube video ID yada video adresi" prepend-inner-icon="mdi-video"></v-text-field>
              </v-col>

              <v-col cols="12">
                <v-text-field type="datetime-local" label="Yayımlanma tarihi" v-model="post.p_time"></v-text-field>
              </v-col>


              <v-col cols="12">

                <v-menu offset-y>
                  <template v-slot:activator="{on, attrs}">
                    <v-btn-toggle borderless dark class="d-flex" active-class="none" >
                      <v-btn :color="getCurrentActionItem.color" class="flex-grow-1 text-capitalize body-2 justify-start" :loading="saveLoading" @click="onSave(getCurrentActionItem.params)" ><v-icon left>{{ getCurrentActionItem.icon }}</v-icon><span>{{ getCurrentActionItem.title }}</span></v-btn>
                      <v-divider vertical></v-divider>
                      <v-btn :color="getCurrentActionItem.color" v-bind="attrs" v-on="on"><v-icon>mdi-chevron-down</v-icon></v-btn>
                    </v-btn-toggle>
                  </template>
                  <v-list class="pa-0" dense>
                    <v-list-item-group>

                      <v-list-item :key="index" v-for="(action, index) in getActionList" v-if="action.title!==getCurrentActionItem.title" :color="action.color" @click="post.status = index">
                        <v-list-item-icon><v-icon >{{ action.icon }}</v-icon></v-list-item-icon>
                        <v-list-item-content><v-list-item-title>{{ action.title }}</v-list-item-title></v-list-item-content>
                      </v-list-item>

                    </v-list-item-group>
                  </v-list>
                </v-menu>
              </v-col>



            </v-row>






          </v-col>



        </v-row>
      </v-container>
    </v-form>


    <v-dialog v-model="dialog.status" max-width="1300px" style="overflow-x: hidden;" class="pa-0">
        <MediaList v-if="dialog.media" popup @onClickSelect="onClickSelect($event)" :currentImage="selectedImage" />
        <v-img v-else :src="getCoverImg"></v-img>
    </v-dialog>
  </div>
</template>

<script>
import {strToSlug} from "@/helpers/string"
import ContentHead from "@/components/panel/ContentHead";
import MediaList from "@/components/panel/MediaList";
export default {
  name: "PostForm",
  components: {ContentHead, MediaList},
  layout: "panel",
  props: {
    update: {
      type: Boolean,
      default: false
    },
    post: {
      type: Object,
      required: false,
      default: function(){
        return {
          id: null,
          title: null,
          slug: null,
          keywords: null,
          description: null,
          summary: null,
          author: null,
          status: null,
          content: null,
          cover: null,
          video: null,
          hide_cover: null,
          allow_comments: 'on',
          p_time: null,
          categories: []
        }
      }
    }
  },
  data(){
    return {
      selectedImage: null,
      loadingCategories: true,
      categories: [],
      dialog: {
        status: false,
        media: false
      },
      saveLoading: false,
    }
  },

  created() {
    this.fetchData();
    return true
  },


  methods: {

    fetchData(){
      this.fetchCategories();
      let d = new Date();
      this.post.p_time = this.post.p_time!==null ? this.post.p_time : `${d.getFullYear()}-${d.getMonth()+1}-${d.getDate()}T${(d.getHours().toString().length<2? '0' : '')+d.getHours()}:${(d.getMinutes().toString().length<2? '0' : '')+d.getMinutes()}`;
    },

    fetchCategories(){
      this.$axios.get('/admin/category').then(({data, status}) => {
        if(status===200 && data.success){
          this.categories = data.categories;
          this.loadingCategories = false;
        }
      }).catch(err => {})
    },


    onClickSelect(img){
      this.post.cover = img.src;
      this.selectedImage = img;
      this.dialog.media=false;
      this.dialog.status=false;
    },

    onClickCoverBox(){
      this.dialog.status = true;
      this.dialog.media = true;
    },

    onSave(params){
      this.saveLoading    = true;

      this.post.hide_cover     = !this.post.hide_cover? 'off' : 'on';
      this.post.allow_comments = !this.post.allow_comments? 'off' : 'on';
      this.post.status = params.status
      this.post.category_ids = Object.values(this.post.categories).map(i => i.id);

      this.$axios.post(this.getApiUri, {post: this.post}).then(({status, data}) => {
        if(status===200 && data.success){
            if(!this.update){
              this.$router.replace(this.$store.state.global.url.panel+'posts/'+data.id+'/edit');
            }
            this.saveLoading    = false;
        }
      }).catch(err => {
        this.saveLoading    = false;
      })

    },

    resetCover(){
      this.post.cover = null;
      this.selectedImage = null;
    }

  },

  computed: {
    getActionList(){
      return {
        published:  ((this.update && this.post.status==='published')? {title: 'Güncelle', icon: 'mdi-check-outline',color: 'success', params: { status: 'published' } }
                      : {title: 'Yayımla', icon: 'mdi-check-outline',             color: 'primary', params: { status: 'published' } } ),
        draft:      {title: 'Taslak Olarak Kaydet',  icon: 'mdi-content-save-all-outline',  color: 'orange',  params: { status: 'draft' } },
        trash:      {title: 'Çöpe Gönder',           icon: 'mdi-trash-can-outline',         color: 'error',   params: { status: 'trash' } },
      }
    },

    getApiUri(){
      return !this.update? '/admin/post/create' : '/admin/post/update/'+this.post.id;
    },

    getCurrentActionItem(){
      return this.getActionList[this.post.status || 'published'];
    },

    getCoverImg(){
      if(typeof this.post.cover === 'string'){
        return this.$store.state.global.url.imgSm+this.post.cover;
      }else if(this.selectedImage !== null){
        return this.selectedImage
      }else{
        return false
      }
    },




  },

  watch: {

    'post.title': function(value){
      this.post.slug = strToSlug(value);
    },

    'post.cover': function(value){
      if(typeof value == "object" && value !== null){
        if(!value.type){
          this.resetCover();
        }else{
          if(value.type.match(/^image\/.+/gi)){
            this.selectedImage = URL.createObjectURL(value)
          }else{
            this.resetCover();
          }
        }
      }
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
