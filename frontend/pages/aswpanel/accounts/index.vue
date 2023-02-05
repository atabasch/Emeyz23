<template>
<v-sheet>

  <ContentHead title="Kullanıcın Hesapları"
               description="Emeyz Kullanıcı ve Aboneleri."
               :button="{ text:'Yeni Hesap Oluştur', icon:'mdi-user-plus', to:$store.state.global.url.panel+'accounts/create'  }" />


  <v-data-table
    :headers="headers"
    :items="getUsers"
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
      <v-img class="coverImg" v-if="item.cover" :src="$getMediaUrl(item.cover, 'sm',  'user')"></v-img>
    </template>

    <template v-slot:item.gender="{ item }" class="pa-0">
      <v-icon :color="genders[item.gender].color">{{ genders[item.gender].icon }}</v-icon>
    </template>

    <template v-slot:item.name="{item, index}">
      <router-link :to="$const.url.panel+'accounts/'+item.id+'/edit'" class="subtitle-2 black--text text-decoration-none">{{ item.name }}</router-link><br>

      <router-link :to="$const.url.panel+'accounts/'+item.id+'/edit'" class="mr-2 action-link text-decoration-none text-capitalize blue--text body-2"><v-icon class="body-2 blue--text">mdi-pencil</v-icon> Düzenle</router-link>
      <button v-if="item.status==='trash'" @click.stop="destroyItem({item, index})" class="mr-2 action-link text-decoration-none text-capitalize red--text body-2"><v-icon class="body-2 red--text">mdi-trash-can</v-icon> Kalıcı Olarak Sil</button>
    </template>

    <template v-slot:item.email="{item}">
      <a :href="'mailto:'+item.email">{{ item.email }}</a>
    </template>

    <template v-slot:item.birthday="{item}">
        <span v-if="item.birthday" v-html="getAge(item.birthday)"></span>
    </template>

    <template v-slot:item.level="{item}">
      <v-menu offset-y tile bottom >
      <template v-slot:activator="{ on, attrs }">
        <v-btn v-bind="attrs" v-on="on" x-small outlined block dark class="text-capitalize" :color="getLevels[item.level].color">{{ getLevels[item.level].title }}</v-btn>
      </template>
      <v-sheet class="pa-2">
        <v-btn v-for="(lvl, ikey) in getLevels" v-if="lvl.value !== item.level" :key="ikey" x-small outlined block dark class="text-capitalize my-2" :color="lvl.color" @click="changeStatus(item, lvl.value, 'level')">{{ lvl.title }}</v-btn>
      </v-sheet>
      </v-menu>
    </template>

    <template v-slot:item.status="{item}">
      <v-menu offset-y tile bottom >
        <template v-slot:activator="{ on, attrs }">
          <v-btn v-bind="attrs" v-on="on" x-small outlined block dark class="text-capitalize" :color="getStatuses[item.status].color">{{ getStatuses[item.status].title }}</v-btn>
        </template>
        <v-sheet class="pa-2">
          <v-btn v-for="(status, ikey) in getStatuses" v-if="status.value !== item.status" :key="ikey" x-small outlined block dark class="text-capitalize my-2" :color="status.color" @click="changeStatus(item, status.value, 'status')">{{ status.title }}</v-btn>
        </v-sheet>
      </v-menu>
    </template>

    <template v-slot:item.date="{item}">
      <v-chip x-small dark color="orange lighten-1" v-html="convertToDate(item.created_at).date"></v-chip>
      <v-chip x-small dark color="green lighten-1" v-if="item.logged_at" v-html="convertToDate(item.logged_at).date"></v-chip>
      <v-chip x-small dark color="green lighten-2" v-if="item.logged_at" v-html="convertToDate(item.logged_at).time"></v-chip>
    </template>

  </v-data-table>

  <admin-alert v-if="dialog" :data="dialog" @onClickClose="closeDialog($event)" @onClickDelete="deleteUser($event)" />



</v-sheet>
</template>

<script>
import ContentHead from "@/components/panel/ContentHead"
import  {mysqlToDate, getAge} from "@/helpers/date"
export default {
  name: "index",
  layout: "panel",
  components: {ContentHead},
  data: ()=>({
    users:[],
    total: 0,
    limit: 15,
    currentPage: 1,
    totalPage: 0,
    pageLoaded: false,
    headers: [
      {text:'', value:'cover', width:75, sortable: false, align: 'start', filterable: false, cellClass: 'px-0', divider: true},
      {text:'C', value:'gender', width: 25, align: 'center', sortable: false, filterable: false, cellClass: 'px-2 ', divider: true},
      {text:'Kullanıcı Adı', value:'name', cellClass:'col-title', divider: true},
      {text:'Tam Ad', value:'fullname', divider: true},
      {text:'E-Posta', value:'email', align: 'left', divider: true},
      {text:'Yaş', value:'birthday', width: 50, align: 'center', sortable: false, filterable: false, cellClass: 'px-2 ', divider: true},
      {text:'Seviye', value:'level', width: 50, align: 'center', sortable: false, filterable: false, cellClass: 'px-2 ', divider: true},
      {text:'Durum', value:'status', width: 50, align: 'center', sortable: false, filterable: false, cellClass: 'px-2 ', divider: true},
      {text:'Tarihler', value:'date', width: 50, divider: true},
    ],
    genders: {
      'Erkek': { icon: 'mdi-gender-male', color: 'blue' },
      'Kadın': { icon: 'mdi-gender-female', color: 'pink' },
      'Belirtilmedi': { icon: 'mdi-gender-male-female', color: 'black' },
    },
    dialog: false,
  }),
  async fetch(){
    this.$axios.get('/admin/account').then(({status, data})=>{
      if(status===200 && data.success){
        this.users = data.users || [];
      }else{}
      this.pageLoaded=true;
    }).catch(err => {
      console.log(err)
    })
  },
  computed: {
    getUsers(){ return this.users },
    getLevels(){ return this.$const.user.levels },
    getStatuses(){ return this.$const.user.statuses },
  },
  methods: {

    changeStatus(item, value, field){
      this.$axios.post('/admin/account/status/'+item.id, {
        field:field,
        value:value,
        level: item.level,
        user: this.$auth.user}).then(({status, data})=>{
        if(status===200 && data.success){
          item[field] = value;
          this.$toast.success(data.message || 'İşlem Başarılı');
        }else{
          this.$toast.error(data.message || 'Hata Oluştu');
        }
      }).catch(err => {
        this.$toast.error(data.message || 'Hata Oluştu');
      })
    },

    convertToDate(value){
      if(!value){
        return {date: ``, time: ``};
      }else{
        let d = mysqlToDate(value);
        return {
          date: `${d.day} ${d.monthName} ${d.fullYear}`,
          time: `${d.hour}:${d.minute}`
        };
      }
    },

    getAge(date){
        return getAge(date);
    },

    destroyItem({item, index}){
      let dialog = this.dialog;
      this.dialog = {
        show:true,
        title: 'Kullanıcı Hesabı Silinecek',
        text: `<strong>${item.name}</strong> kullanıcı adına sahip hesabı silmek üzeresiniz. Hesap silindikten sonra bir daha geri alınamaz. Yine de silmek istediğinize emin misiniz?`,
        type: 'error',
        buttons: [
          { title: 'Vazgeç',  dark: true,  color: 'info',  data: null,  emit: 'onClickClose' },
          { title: 'Eminim Sil',  dark: true,  color: 'red',  data: {user:item, index:index},  emit: 'onClickDelete' }
        ]
      }
    },

    closeDialog(){
      this.dialog = false;
    },

    deleteUser({user, index}){
      let data = {
        me: {
          id: this.$auth.user.id,
          level: this.$auth.user.level
        }
      };
      this.$axios.post('/admin/account/delete/'+user.id, data).then(({status, data})=>{
        if(status===200 && data.success){
          this.$toast.success(data.message || 'Kullanıcı Silindi');
          this.users.splice(index, 1);
          this.closeDialog();
        }else{
          this.$toast.error(data.message || 'Beklenmedik bir hata oluştu');
        }
      }).catch(err => {
        console.log(err)
      })
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
</style>
