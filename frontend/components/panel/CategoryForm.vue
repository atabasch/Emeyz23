<template>
  <v-sheet>
    <InputCoverImage v-model="category.cover" label="Kategori Görseli" />

    <v-text-field label="Kategori adı" counter="35" v-model="category.title" @input="updateSlug" ></v-text-field>

    <v-text-field label="Slug" v-model="category.slug" hint="url-icin-bir-metin" counter="35" ></v-text-field>

    <v-textarea   rows="1" auto-grow label="Açıklama (Description)" v-model="category.description" counter="160"></v-textarea>

    <v-combobox
      v-model="category.parent"
      :items="getParentCategories"
      :item-text="({title}) => title"
      :item-value="({id}) => id"
      label="Üst Kategori"
      hint="Eğer bu bir alt kategori ise lütfen ebeveyn seçin."
      clearable
      chips
      :loading="loadingCategories"
      prepend-inner-icon="mdi-file-tree"></v-combobox>

    <v-switch
      v-model="category.hide"
      :label="`Kategori listelerinde gizle`"
      inset
    ></v-switch>

    <v-select
      v-model="category.status"
      :items="actionItems"
      item-text="text"
      item-value="value"
      label="Yayımlama Durumu"></v-select>



    <div class="text-right">
      <v-btn v-if="!update" color="primary" @click="createCategory">Oluştur</v-btn>
      <template v-else>
        <v-btn  color="red" dark @click="clearForm">Vazgeç</v-btn>
        <v-btn  color="success" @click="updateCategory">Güncelle</v-btn>
      </template>
    </div>

  </v-sheet>
</template>

<script>
import {strToSlug} from "@/helpers/string"
import InputCoverImage from "@/components/panel/child/InputCoverImage";
export default {
  name: "CategoryForm",
  components: {InputCoverImage},
  props: {
    update: { type:Boolean, default:false },
    category: {
      type: Object,
      default: function(){
        return {
          title: null,
          slug: null,
          description: null,
          cover: null,
          parent: 0,
          status: 'published',
          hide: false
        }
      }
    }
  },

  data: ()=>({
    loadingCategories: false,
    emptyCategory: {id:0, title: 'Üst Kategori Yok', parent: 0},
    categories: [],
    actionItems: [
      {value: 'published',  text: 'Yayımda'},
      {value: 'draft',      text: 'Taslak'},
      {value: 'trash',      text: 'Çöp'},
    ],
    rules: {
      required: value => !!value || 'Zorunlu Alan',
      counter: value => value.length <= 35 || '35 karakterden daha fazla olamaz.',
    }
  }),

  created(){
      this.loadingCategories=true;
      this.$axios.get('/admin/category').then(({status, data})=>{
        if(status===200){
          this.categories = data.categories
        }
        this.loadingCategories=false;
      }).catch(err => {
        console.log(err) })

  },

  mounted() {
    if(this.category.parent===0){
      this.category.parent = this.emptyCategory
    }
  },

  computed: {
    getParentCategories(){
      return [this.emptyCategory, ...this.categories].filter(category => category.parent==0);
    }
  },

  methods: {
    createCategory(){
      let datas = {
        ...this.category,
        hide: this.category.hide? 1 : 0,
        parent: this.category.parent.id
      };
      this.$axios.post('/admin/category/create', datas).then(({status, data})=>{
        if(status===200 && data.success){
            this.$emit('created', data.category);
            this.clearForm();
        }
      }).catch(err => { console.log(err) })
    },

    updateCategory(){
      let datas = {
        ...this.category,
        hide: this.category.hide? 1 : 0,
        parent: this.category.parent.id
      };
      this.$axios.post('/admin/category/update/'+this.category.id, datas).then(({status, data})=>{
        if(status===200 && data.success){
            this.$emit('updated', {index:this.category.index, ...data.category});
        }
      }).catch(err => { console.log(err) })
    },

    clearForm(){
      this.category = {
        title: null,
        slug: null,
        description: null,
        cover: null,
        parent: 0,
        status: 'published',
        hide: false
      }
      this.$emit('clearForm', true);
    },
    updateSlug(){
      this.category.slug = strToSlug(this.category.title);
    }
  },

  watch: {


  }
}
</script>

<style scoped>

</style>
