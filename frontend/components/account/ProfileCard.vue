<template>
  <div>
  <v-card class="pa-3" tile flat >
    <v-img class="rounded overflow-hidden" :src="user.cover_url" :aspect-ratio="4/3">
      <v-btn tile top right small style="opacity: 0.7;" class="text-capitalize" @click="uploadDialog = true"><v-icon size="16">mdi-image-outline</v-icon> Görseli Değiştir</v-btn>
    </v-img>

    <v-btn @click="clickedRemoveAvatar" v-if="user.cover" tile dark block color="red" small class="text-capitalize subtitle-2 mt-2"><v-icon>mdi-delete-outline</v-icon> Profil Fotoğrafı Kullanmak İstemiyorum!</v-btn>

    <v-card-title>{{ user.fullname }}</v-card-title>
    <v-divider />

    <v-list tile flat >
      <v-list-item>
        <v-list-item-icon><v-icon>mdi-account</v-icon></v-list-item-icon>
        <v-list-item-content>
          <small class="grey--text">Kullanıcı Adı</small>
          <v-list-item-title>{{ user.name }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item>
        <v-list-item-icon><v-icon>mdi-email</v-icon></v-list-item-icon>
        <v-list-item-content>
          <small class="grey--text">E-Posta Adresi</small>
          <v-list-item-title>{{ user.email }}</v-list-item-title></v-list-item-content>
      </v-list-item>

      <v-list-item >
        <v-list-item-icon><v-icon>mdi-star</v-icon></v-list-item-icon>
        <v-list-item-content>
          <small class="grey--text">Kullanıcı Seviyesi</small>
          <v-list-item-title>{{ $const.user.levels[user.level].title }}</v-list-item-title></v-list-item-content>
      </v-list-item>

      <v-list-item >
        <v-list-item-icon><v-icon>{{ $const.user.genders[user.gender].icon }}</v-icon></v-list-item-icon>
        <v-list-item-content>
          <small class="grey--text">Cinsiyet</small>
          <v-list-item-title>{{ $const.user.genders[user.gender].title }}</v-list-item-title></v-list-item-content>
      </v-list-item>

      <v-list-item >
        <v-list-item-icon><v-icon>mdi-cake-variant</v-icon></v-list-item-icon>
        <v-list-item-content>
          <small class="grey--text">Doğum Tarihi</small>
          <v-list-item-title v-html="$helper.getDateFormat(user.birthday, 'dateFull')"></v-list-item-title></v-list-item-content>
      </v-list-item>
    </v-list>

    <v-divider />


    <v-row class=" my-5">
      <v-col cols="6" v-for="(value, key) in user.datas" :key="key" class="py-1">
        <a :href="(value || null)" target="_blank" class="blue--text text-capitalize" :disabled="(value || '').length < 10" :class="{ 'text--disabled': (value || '').length < 10 }">{{ key }}</a>
      </v-col>
    </v-row>

    <v-divider />

    <v-row class="pt-3">
      <v-col class="text-left" cols="6"><v-btn to="/account/details" color="info" link tile small class="text-capitalize">Hesap Ayarları</v-btn></v-col>
      <v-col class="text-right"  cols="6"><v-btn to="/account/security" color="info" link tile small class="text-capitalize">Parola Değiştir</v-btn></v-col>
    </v-row>



  </v-card>

    <PopupImageUpload v-model="uploadDialog" @uploaded="coverUploaded($event)" />
    <admin-alert v-if="dialog" :data="dialog" @onClickClose="closeDialog($event)" @onClickDelete="removeAvatar($event)" />
  </div>
</template>

<script>
import PopupImageUpload from "@/components/PopupImageUpload";
export default {
  name: "ProfileCard",
  components: {PopupImageUpload},
  data(){return {
    uploadDialog: false,
    dialog: false
  }},

  methods: {

    coverUploaded(uploadedData){
      this.updateAvatar(uploadedData.file.src || null);
    },

    // aşağıdakiler düzenlenecek
    clickedRemoveAvatar(){
      this.dialog = {
        show:true,
        title: 'Profil Fotoğrafı Kaldırmak',
        text: `Profil fotoğrafını kaldırmak üzeresin. Emin misin?`,
        type: 'error',
        buttons: [
          { title: 'Vazgeç',  dark: true,  color: 'info',  data: null,  emit: 'onClickClose' },
          { title: 'Eminim Sil',  dark: true,  color: 'red',  data: null,  emit: 'onClickDelete' }
        ]
      }
    },

    closeDialog(){
      this.dialog = false;
    },

    removeAvatar(){
      this.updateAvatar();
      this.dialog = false;

    },

    updateAvatar(filename=null){
      this.$axios.post('/account/updateCover/', {filename: filename, oldfilename: this.$auth.user.cover}).then(({status, data}) => {
        if(status===200 && data.success){
          this.$auth.fetchUser();
          this.$toast.success(data.message || 'Profil Fotoğrafı Güncellendi');
        }else{
          this.$toast.error('Beklenmedik bir hata oluştu');
        }
      }).catch(err => {
        this.$toast.error('Beklenmedik bir hata oluştu');
      });
    }

  },

  computed: {
    user: function(){ return this.$auth.user }
  }
}
</script>

<style scoped>

</style>
