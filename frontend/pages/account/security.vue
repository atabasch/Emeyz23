<template>
  <v-card>
    <v-row class="px-4">

      <v-col cols="12">
        <v-card-title>Hesap Şifresini Değiştir</v-card-title>
        <v-divider />
      </v-col>

      <v-col cols="12">
        <v-text-field type="password"
                      v-model="password.current"
                      label="Şu anki şifrenizi girin"
                      maxlength="32"
                      :type="show.current? 'text' : 'password'"
                      :append-icon="show.current ? 'mdi-eye-off' : 'mdi-eye'"
                      @click:append="show.current = !show.current"
                      counter
                      required />
      </v-col>

      <v-col cols="12">
        <v-text-field type="password"
                      v-model="password.new"
                      label="Yeni Şifre"
                      maxlength="32"
                      :type="show.new? 'text' : 'password'"
                      :append-icon="show.new ? 'mdi-eye-off' : 'mdi-eye'"
                      @click:append="show.new = !show.new"
                      counter
                      required />
      </v-col>

      <v-col cols="12">
        <v-text-field type="password"
                      v-model="password.confirm"
                      label="Yeni Şifreyi Onaylayın"
                      maxlength="32"
                      :type="show.confirm? 'text' : 'password'"
                      :append-icon="show.confirm ? 'mdi-eye-off' : 'mdi-eye'"
                      @click:append="show.confirm = !show.confirm"
                      counter
                      required />
      </v-col>

      <v-col cols="12" class="text-right">
        <v-divider class="mb-5" />
        <v-btn tile right  color="green" class=" text-capitalize subtitle-2" :disabled="!isActiveSaveButton" :dark="isActiveSaveButton" @click="update"><v-icon>md-check</v-icon> Parolayı Değiştir</v-btn>
      </v-col>


    </v-row>
  </v-card>
</template>

<script>
import ProfileCard from "@/components/account/ProfileCard"
export default {
  name: "pageAccountSecurity",
  layout: 'account',
  components: {
    ProfileCard
  },
  data: ()=>({
    password: {
      current: '',
      new: '',
      confirm: ''
    },
    show: {
      current: '',
      new: '',
      confirm: ''
    }
  }),

  computed: {

    isActiveSaveButton(){
      return ( (this.password.current.length>5) && (this.password.new.length>5) && (this.password.confirm.length>5) && this.password.new===this.password.confirm )
    }

  },
  methods: {

    update(){
      this.$axios.post('/account/updatePassword', {password: this.password}).then(({status, data})=>{
        if(status===200 && data.success){
          this.$toast.success('Hesap Şifresi Güncellendi');
          this.clearForm();
        }else{
          this.$toast.error(data.message || 'Hesap bilgileri güncellenemedi.');
        }
      }).catch(err => {
        this.$toast.error('Hesap bilgileri güncellenemedi.');
      });
    },

    clearForm(){
      this.password = {
          current:  '',
          new:      '',
          confirm:  ''
      }
    }


  }
}
</script>

<style scoped>

</style>
