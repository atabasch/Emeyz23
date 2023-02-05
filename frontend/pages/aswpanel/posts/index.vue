<template>
 <v-sheet>
  <ContentHead title="Yazı Listesi"
               description="Bloğa ait makaleler bu sayfadan görüntülenir, düzenlenir ve  silinebilirler."
               :button="{ text:'Yeni Makale Oluştur', icon:'mdi-pencil-plus', to:$store.state.global.url.panel+'posts/create'  }" />

   <v-data-table
   :headers="headers"
   :items="items"
   :items-per-page="limit"
   fixed-header
   item-class=""
   :loading="!pageLoaded"
   :loader-height="5"
   no-data-text="Gösterilebilecek veri yok"
   no-results-text="Gösterilebilecek veri yok"
   @page-count="currentPage = $event"
   locale="tr_TR"
   >
     <template v-slot:item.cover="{ item }" class="pa-0">
       <v-img class="coverImg" :src="$store.state.global.img.sm+item.cover" @click="openDialog({status:true, image:item.cover})"></v-img>
     </template>

     <template v-slot:item.title="{ item, index }">
           <router-link :to="$store.state.global.url.panel+'posts/'+item.id+'/edit'" class="subtitle-2 black--text text-decoration-none">{{ item.title }}</router-link><br>
           <router-link :to="$store.state.global.url.panel+'posts/'+item.id+'/edit'" class="mr-2 action-link text-decoration-none text-capitalize blue--text body-2"><v-icon class="body-2 blue--text">mdi-pencil</v-icon> Düzenle</router-link>
           <button v-if="item.status!=='trash'" @click.stop="changeStatus(item, 'trash')" class="mr-2 action-link text-decoration-none text-capitalize red--text body-2"><v-icon class="body-2 red--text">mdi-trash-can</v-icon> Çöp'e Gönder</button>
           <template v-if="item.status==='trash'">
             <button @click.stop="changeStatus(item, 'published')" class="mr-2 action-link text-decoration-none text-capitalize green--text body-2"><v-icon class="body-2 green--text">mdi-check</v-icon> Yayımla</button>
             <button @click.stop="destroyItem({item, index}, false)" class="mr-2 action-link text-decoration-none text-capitalize red--text body-2"><v-icon class="body-2 red--text">mdi-trash-can</v-icon> Kalıcı Olarak Sil</button>
           </template>
     </template>

     <template v-slot:item.author="{ item }">
      <router-link :to="$store.state.global.url.panel+'accounts/'+item.user_slug">{{ item.user_name }}</router-link>
     </template>

     <template v-slot:item.status="{ item }">

       <v-menu offset-y tile bottom >
         <template v-slot:activator="{ on, attrs }">
           <v-btn v-bind="attrs" v-on="on" x-small outlined block dark class="text-capitalize" :color="statusItems[item.status].color">{{ statusItems[item.status].title }}</v-btn>
         </template>
         <v-sheet class="pa-2">
           <v-btn v-for="(si, ikey) in statusItems" v-if="si.value !== item.status" :key="ikey" x-small outlined block dark class="text-capitalize my-2" :color="si.color" @click="changeStatus(item, si.value)">{{ si.title }}</v-btn>
         </v-sheet>
       </v-menu>

     </template>

     <template v-slot:item.date="{item}">
       <v-chip x-small dark color="orange lighten-1" v-html="convertToDate(item.p_time).date"></v-chip>
       <v-chip x-small dark color="green lighten-1" v-html="convertToDate(item.p_time).time"></v-chip>
     </template>
   </v-data-table>

   <v-pagination
     v-model="currentPage"
     :length="totalPage"
   ></v-pagination>

   <v-dialog v-model="dialog.status" :max-width="dialog.width" >
     <v-img v-if="dialog.image" :src="$store.state.global.img.lg+dialog.image"></v-img>
     <template v-if="dialog.post && !dialog.image">
       <v-card>
         <v-card-title>{{ dialog.title || '' }}</v-card-title>
         <v-divider></v-divider>
         <v-card-text class="py-3" v-html="dialog.description || ''"></v-card-text>
         <v-divider></v-divider>
         <v-card-actions>
           <v-btn @click="clearDialog" tile text color="primary">Vazgeç</v-btn>
           <v-btn @click="destroyItem({item:dialog.post, index:dialog.post.index}, true)" tile text color="red lighten-1" dark>Eminim Sil</v-btn>
         </v-card-actions>
       </v-card>
     </template>
   </v-dialog>


 </v-sheet>


</template>

<script>
import {mysqlToDate} from "@/helpers/date"
import ContentHead from "@/components/panel/ContentHead";
import PageLoading from "@/components/panel/child/PageLoading";
export default {
  name: "index",
  components: {ContentHead, PageLoading},
  layout: "panel",
  data(){
    return {
      total: 0,
      limit: 15,
      currentPage: 1,
      totalPage: 0,
      pageLoaded: false,
      headers: [
        {text:'', value:'cover', width:75, sortable: false, align: 'start', filterable: false, cellClass: 'px-0', divider: true},
        {text:'Başlık', value:'title', cellClass:'col-title', divider: true},
        {text:'Yazar', value:'author', width: 100, align: 'center', divider: true},
        {text:(<v-icon>mdi-eye</v-icon>), width: 60,  value:'views', align: 'center', sortable: false, filterable: false, cellClass: 'px-0', divider: true},
        {text:'Durum', value:'status', width: 50, align: 'center', sortable: false, filterable: false, cellClass: 'px-2 ', divider: true},
        {text:'Tarihler', value:'date', width: 50, divider: true},
      ],
      items: [],
      dialog: {},
      statusItems: {
        published:  {value:'published', title:'Yayımda',    color:'light-green'},
        draft:      {value:'draft',     title:'Taslak',     color:'orange'},
        trash:      {value:'trash',     title:'Çöp Kutusu', color:'red'}
      }
    }
  },
  created(){
    this.getDataFromApi({
      offset: (this.currentPage * this.limit)-this.limit,
      limit: this.limit
    });
  this.clearDialog();
  },
  mounted() {
  },
  methods: {
    openDialog({status, image}){
      this.dialog.status = status;
      this.dialog.image = image;
    },

    clearDialog(){
      this.dialog = {
        status: false,
        title: null,
        description: '',
        post: null,
        image: null,
        button: {},
        width: '1170px'
      }
    },

    getDataFromApi({offset=0, limit=10}={}){
      this.$axios.get(`/admin/post?offset=${offset}&limit=${limit}`).then(({data, status}) => {
        if(status===200){
          if(data.status){
            this.items = data.data.posts;
            this.totalPage = Math.ceil(data.data.total/this.limit);
            this.pageLoaded=true;
          }
        }
      }).catch(err => {});
    },


    changeStatus(item, status){
      let url = '/admin/post/status/'+item.id;
      this.$axios.post(url, {status}).then(({data, status}) => {
        if(status===200){
          item.status = data.data.status;
        }
      }).catch(err => {});
    },

    destroyItem({item, index}, accepted=false){
      if(!accepted){
        this.dialog.status = true;
        this.dialog.post = item;
        this.dialog.post.index = index;
        this.dialog.title = 'Emin Misiniz?'
        this.dialog.width = 350;
        this.dialog.description = `<strong>(${item.id})</strong> id nuramasına ait <strong>"${item.title}"</strong> başlıklı içerik sistemden <strong>kalıcı olarak</strong> silinecek.
        Bu işlem bir daha geri alınamaz.
        İşleme devam etmek istediğinizden emin misiniz?`;
        this.dialog.button.text = 'Eminim Sil';
        this.dialog.button.color = 'red';
      }else{

        this.$axios.delete('/admin/post/delete/'+item.id).then(({status, data}) => {
          if(status===200){
            if(data.status){
              this.items.splice(index, 1);
            }
          }
          this.clearDialog();

        }).catch(err => {
          this.clearDialog()
        })

      }
    },

    convertToDate: function(value){
      let d = mysqlToDate(value);
      return {
        date: `${d.day} ${d.monthName} ${d.fullYear}`,
        time: `${d.hour}:${d.minute}`
      };
    }
  },
  watch: {


    currentPage: function(pageNumber){
      this.getDataFromApi({
        offset: (this.currentPage*this.limit)-this.limit,
        limit: this.limit
      });
    },


    'dialog.status': function(value){
      if(!value){
        this.clearDialog();
      }
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
.coverImg{
  width: 100px;
  cursor: pointer;
}
.coverImg:hover{
  z-index: 999;
  transform: scale(1.2);
}
</style>
