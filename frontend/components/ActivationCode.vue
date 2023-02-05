<template>
  <div>
    <v-toolbar dark color="#2A2356">
      <v-toolbar-title>Hesap Onaylama</v-toolbar-title>
    </v-toolbar>

    <v-card-text>
      <v-alert type="error" v-if="error" v-html="error"></v-alert>
      <v-form>
        <template v-if="!user">
          <p>Sistemimizde kayıtlı olan ancak henüz hesap aktivasyonu yapılmamış e-posta adresinizi veya kullanıcı adınızı giriniz.</p>
          <v-text-field  prepend-icon="mdi-email" v-model="email" label="E-Posta Adresi" type="email" maxlength="35" counter :rules="rules.email" :disabled="loading"></v-text-field>
          <v-progress-linear v-if="loading" indeterminate color="cyan"></v-progress-linear>
        </template>

        <v-sheet v-if="user" max-width="330" class="mx-auto">
          <p><strong>{{user.email}}</strong> E-posta hesabına gelen onay kodunu girin</p>
          <v-btn tile text class="pa-1 text-capitalize body-2 blue--text" @click="resetUser()">E-posta hesabını değiştir.</v-btn>
          <v-otp-input v-model="code" length="6" type="number" autofocus :disabled="loading"></v-otp-input>
          <v-progress-linear v-if="loading" indeterminate color="cyan"></v-progress-linear>
        </v-sheet>
      </v-form>
    </v-card-text>

    <v-divider></v-divider>
    <v-card-actions>
      <v-btn class="mx-1 text-capitalize subtitle-2" color="green" @click="$emit('changeTab', 'login')" text tile>Giriş Yap</v-btn>
      <v-spacer></v-spacer>
      <v-btn class="mx-1 text-capitalize subtitle-2" v-if="!user" color="blue" @click="checkEmail()" :disabled="!issetEmail" text tile>Sorgula</v-btn>
      <v-btn class="mx-1 text-capitalize subtitle-2" v-if="user" color="blue" @click="onConfirm()" :disabled="isDisableConfirm" text tile>Onayla</v-btn>
    </v-card-actions>
  </div>
</template>

<script>
export default {
  name: "activationCode",
  layout: 'logon',
  head: {
    title: 'Emeyz Hesap Aktivasyonu'
  },
  props: {
    user: {
      type: Object,
      default: ()=>{
        return null;
      }
    },
    checkData: {
      type: Object,
      default: () => null
    }
  },
  async created() {
    if(this.checkData && !this.user){
      this.loading = true;
      await this.$axios.post('/account/check', {value: this.checkData.email || null}).then(({status, data}) => {
        if(status===200 && data.user){
          this.user = data.user;
          this.code = this.checkData.code || '';
        }else{
          this.error = data.message || null;
        }
        this.loading = false;
      }).catch(err => {
        this.loading = false;

      })
    }
  },
  data: () => ({
    loading:false,
    code:'',
    email:null,
    rules: {
      code: [
        v => !!v || 'Kod Gerekli',
        v => (v && v.length !== 6) || 'geçersiz kod uzunluğu'
      ],
      email: [
        v => !!v || 'E-posta gerekli',
        v => (v && v.length >=10 && v.length <= 32) || 'E-posta adresi geçerli uzunlukta değil'
      ]
    },
    error: false
  }),

  methods: {

    checkEmail(){
      this.loading = true;
      this.$axios.post('/account/check', { field:'email', value:this.email }).then(({status,  data}) => {
        if(status===200 && data.success){
          this.user = data.user;
          this.error = false;
        }else{
          this.error = data.message || false
        }
        this.loading = false;
      }).catch(err => {
        //TODO: geçerli bir hata mesajı ver
        this.loading = false;
      })
    },

    async onConfirm(){
      if(this.code.length > 5){
        this.loading = true;
        this.$axios.post('/account/activate', {email:this.user.email, code:this.code}).then(({status, data}) => {
          if(status===200 && data.success){
            this.error = false;
            this.$toast.success(data.message || 'Hesap aktivasyonu gerçekleşti.');
            if(data.user){
              this.$auth.setUser(data.user);
              this.$toast.success(data.message || 'Kullanıcı Girişi Başarılı');
              location.href = "/"
            }else{
              this.$emit('changeTab', 'login');
            }
          }else{
            this.error = data.message || 'Bir hata oluştu.';
          }
          this.loading = false;
        }).catch(err => {
          this.loading = false;
          // TODO: Geçerli bir hata mesajı döndür
        })
      }
    }, // onLogin


    resetUser(){
      this.user = null;
      this.code = '';
      this.checkData = null;
    }
  },

  computed: {
    issetEmail(){
      return this.email && this.email.length >= 10
    },
    isDisableConfirm(){
      return this.code.length < 6
    },
  }
}
</script>

