<template>
  <v-sheet>
    <ContentHead title="Kategoriler" description="Blog yazılarının ait olacağı kategoriler"/>

    <v-row>
      <v-col cols="12" sm="12" md="8" lg="8" xl="9">
        <h3 class="pb-3">Kategori Listesi</h3><v-divider />
        <v-data-table
          :headers="headers"
          :items="items"
          :items-per-page="limit"
          fixed-header
          :loading="!pageLoaded"
          :loader-height="5"
          no-data-text="Gösterilebilecek veri yok"
          no-results-text="Gösterilebilecek veri yok"
          @page-count="currentPage = $event"
          locale="tr_TR"
          dense>

          <template v-slot:item.cover="{item}">
            <v-img height="50px" width="50px" :src="$store.state.global.url.imgSm+item.cover" />
          </template>

          <template v-slot:item.title="{ item, index }">
            <button @click.stop="setCurrentCategory(item, index)" class="mr-2 text-decoration-none text-capitalize black--text subtitle-2">{{ item.title }}</button><br/>
            <button @click.stop="setCurrentCategory(item, index)" class="mr-2 action-link text-decoration-none text-capitalize blue--text body-2"><v-icon class="body-2 blue--text">mdi-pencil</v-icon> Düzenle</button>
            <button v-if="item.status!=='trash'" @click.stop="updateCategory({...item, status:'trash'}, index)" class="mr-2 action-link text-decoration-none text-capitalize red--text body-2"><v-icon class="body-2 red--text">mdi-trash-can</v-icon> Çöp'e Gönder</button>
            <template v-if="item.status==='trash'">
              <button @click.stop="updateCategory({...item, status:'published'}, index)" class="mr-2 action-link text-decoration-none text-capitalize green--text body-2"><v-icon class="body-2 green--text">mdi-check</v-icon> Yayımla</button>
              <button @click.stop="deleteCategory(item, index)" class="mr-2 action-link text-decoration-none text-capitalize red--text body-2"><v-icon class="body-2 red--text">mdi-trash-can</v-icon> Kalıcı Olarak Sil</button>
            </template>
          </template>

          <template v-slot:item.status="{ item, index }">
            <v-menu offset-y tile bottom >
              <template v-slot:activator="{ on, attrs }">
                <v-btn v-bind="attrs" v-on="on" x-small outlined block dark class="text-capitalize" :color="statusItems[item.status].color">{{ statusItems[item.status].title }}</v-btn>
              </template>
              <v-sheet class="pa-2">
                <v-btn v-for="(si, ikey) in statusItems" v-if="si.value !== item.status" :key="ikey" x-small outlined block dark class="text-capitalize my-2" :color="si.color" @click="updateCategory({...item, status:si.value}, index)">{{ si.title }}</v-btn>
              </v-sheet>
            </v-menu>
          </template>

          <template v-slot:item.hide="{item, index}">
            <v-btn @click="updateCategory({...item, hide:1}, index)" v-if="!item.hide" x-small color="success"><v-icon x-small>mdi-eye</v-icon></v-btn>
            <v-btn @click="updateCategory({...item, hide:0}, index)" v-else x-small color="red" dark><v-icon x-small>mdi-eye-off</v-icon></v-btn>
          </template>

        </v-data-table>
      </v-col>

      <v-col cols="12" sm="12" md="4" lg="4" xl="3">
        <h3 class="pb-3">{{ !currentCategory? 'Yeni Kategori Oluştur' : 'Düzenle: ' + currentCategory.title  }}</h3><v-divider />
        <CategoryForm v-if="!currentCategory" @created="createdCategory($event)" @clearForm="clearForm"/>
        <CategoryForm v-else :category="currentCategory" :update="true" @clearForm="clearForm" @updated="updatedCategory($event)"/>
      </v-col>
    </v-row>

    <Alert v-if="dialog.show" v-model="dialog.show" :data="dialog" @onClickDelete="onClickDelete($event)" />
    <v-snackbar v-if="alert.show" v-model="alert.show" :color="alert.color" right bottom absolute tile :timeout="2000"><v-icon>{{ alert.icon }}</v-icon> <strong v-html="alert.text"></strong></v-snackbar>

  </v-sheet>
</template>

<script>
 import ContentHead from "@/components/panel/ContentHead"
 import CategoryForm from "@/components/panel/CategoryForm"
 import Alert from "@/components/panel/Alert";

export default {
  name: "index",
  layout: "panel",
  components: {ContentHead, CategoryForm, Alert},
  data: () => ({
    alert: {
      show: false,
      text: '',
      color: 'success',
      icon: 'mdi-check'
    },
    dialog: {
      show: false,
      type: 'danger',
      title: 'Dikkat!',
      text: 'Seçilen kategori sistemden tamamen silinecek. Bu işlem bir daha geri alınamaz? Kategoriyi silmek istediğinize emin misiniz?',
      buttons: [
        {label: 'Vazgeç',     color: 'danger', cancel:true},
        {label: 'Eminim Sil', color: 'primary', emit: 'onClickDelete',}
      ]
    },
    headers: [
      {text:'',       value:'cover', width:50, sortable: false, align: 'start', filterable: false, cellClass: 'px-0', divider: true},
      {text:'Başlık', value:'title', align: 'start', filterable: true, divider: true},
      {text:'Durum',  value:'status', width: 50, align: 'center', sortable: false, filterable: false, cellClass: 'px-2 ', divider: true},
      {text:'Gizli',  value:'hide', width: 50, align: 'center', sortable: false, filterable: false, cellClass: 'px-2 ', divider: true},
    ],
    items: [],
    currentCategory: null,
    limit:25,
    pageLoaded: true,
    currentPage: 1,
    statusItems: {
      published:  {value:'published', title:'Yayımda',    color:'light-green'},
      draft:      {value:'draft',     title:'Taslak',     color:'orange'},
      trash:      {value:'trash',     title:'Çöp Kutusu', color:'red'}
    }
  }),
  created(){
    this.$axios.get('/admin/category').then(({status, data}) => {
      if(status===200 && data.success){
        this.items = data.categories
      }
    }).catch(err => {  })
  },

  methods: {



    setCurrentCategory(item, index){
      this.currentCategory = {...item, index:index}
    },

    createdCategory(category){
      this.items.unshift(category);
    },

    updatedCategory(category){
      this.items.splice(category.index, 1, category);
      this.clearForm();
    },

    updateCategory(category, index){
      this.$axios.post('/admin/category/update/'+category.id, category).then(({status, data})=>{
        if(status===200 && data.success){
            this.items.splice(index, 1, data.category);
        }
      }).catch(err => { console.log(err) })
    },


    deleteCategory(category, index){
      this.dialog.text = `<strong>${category.title}</strong> kategorisini silmek üzeresin. Bu işlemin bir daha geri alınamayacağını unutma. Kategoriyi silmek istediğine emin misin?`;
      this.dialog.value = {category, index};
      this.dialog.show = true;
    },

    onClickDelete({category, index}){
      this.$axios.post('/admin/category/delete/'+category.id).then(({status, data})=>{
        if(status===200 && data.success){
            this.items.splice(index, 1);
            this.dialog.value=null;
            this.dialog.show=false;
            this.alert.text = `${category.title} kategorisi silindi.`
            this.alert.show = true;
        }
      }).catch(err => { console.log(err) })
    },

    clearForm(){
      this.currentCategory=null;
    }
  }
}
</script>

<style scoped>
.action-link{
  visibility: hidden;
}
table tbody tr:hover .action-link{
  visibility: visible;
}
</style>
