<template>
  <v-card v-if="user">
    <v-row class="px-4">

      <v-col cols="12">
        <v-card-title>Hesap Bilgilerini Güncelle</v-card-title>
        <v-divider />
      </v-col>

      <v-col cols="6">
        <v-text-field v-model="user.name" label="Kullanıcı Adı" required />
      </v-col>

      <v-col cols="6">
        <v-text-field v-model="user.email" type="email" label="E-Posta Adresi" required />
      </v-col>

      <v-col cols="12">
        <v-text-field v-model="user.fullname" label="Ad ve Soyad" required />
      </v-col>


      <v-col cols="6">
        <v-text-field v-model="user.birthday" type="date" label="Doğum Tarihi" required />
      </v-col>

      <v-col cols="6">
        <v-select
          v-model="user.gender"
          :items="Object.values($const.user.genders)"
          label="Cinsiyet"
          item-text="title"
          item-value="value"
          required
        ></v-select>
      </v-col>

      <v-col cols="12">
        <v-textarea v-model="user.description" rows="1" auto-grow label="Hakkımda" required />
      </v-col>


      <v-col cols="12">
        <v-card-title>Sosyal Hesaplar</v-card-title>
        <v-divider />
      </v-col>


      <v-col cols="6">
        <v-text-field v-model="user.datas.website" label="website" required />
      </v-col>

      <v-col cols="6">
        <v-text-field v-model="user.datas.instagram" label="instagram" required />
      </v-col>

      <v-col cols="6">
        <v-text-field v-model="user.datas.github" label="github" required />
      </v-col>

      <v-col cols="6">
        <v-text-field v-model="user.datas.linkedin" label="linkedin" required />
      </v-col>

      <v-col cols="6">
        <v-text-field v-model="user.datas.youtube" label="youtube" required />
      </v-col>

      <v-col cols="6">
        <v-text-field v-model="user.datas.facebook" label="facebook" required />
      </v-col>

      <v-col cols="12" class="text-right">
        <v-divider class="mb-3" />
        <v-btn color="green" tile dark @click="update">Güncelle</v-btn>
      </v-col>




    </v-row>
  </v-card>
</template>

<script>
export default {
  name: "pageAccountDetails",
  layout: 'account',
  data: ()=>({

  }),
  computed: {
    user: function(){ return this.$auth.user },
  },
  methods: {

    update(){
      this.$axios.post('/account/update', {user: this.user}).then(({status, data})=>{
        if(status===200 && data.success && data.user){
          this.user = {...data.user}
          this.$toast.success('Hesap Bilgileri Güncellendi.');
          this.$auth.fetchUser();
        }else{
          this.$toast.error(data.message || 'Hesap bilgileri güncellenemedi.');
        }
      }).catch(err => {
        this.$toast.error('Hesap bilgileri güncellenemedi.');
      });
    },


  }
}
</script>

<style scoped>

</style>
