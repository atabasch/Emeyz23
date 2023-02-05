<template>
<div>
  <v-toolbar dark color="#2A2356">
    <v-toolbar-title>Giriş Yap</v-toolbar-title>
  </v-toolbar>

  <v-card-text>
    <v-alert type="error" v-if="error" v-html="error"></v-alert>
    <v-form>
      <v-text-field v-model="username" prepend-icon="mdi-account" label="Kullanıcı Adı" type="text" :disabled="formDissabled"></v-text-field>
      <v-text-field v-model="password" prepend-icon="mdi-lock" label="Parola" type="password" :disabled="formDissabled"></v-text-field>
    </v-form>
  </v-card-text>

  <v-divider></v-divider>

  <v-card-actions>
    <v-btn class="mx-1 text-capitalize subtitle-2" color="blue" @click="$emit('changeTab', 'register')" text tile>Kayıt Ol</v-btn>
    <v-spacer></v-spacer>
    <v-btn class="mx-1 text-capitalize subtitle-2" color="green" @click="onLogin()" v-on:keyup.enter="onLogin()" :disabled="!btnLoginStatus" text tile>Giriş Yap</v-btn>
  </v-card-actions>
</div>
</template>

<script>
export default {
  name: "login",
  layout: 'logon',
  head: {
    title: "Emeyz'e Giriş Yap",
    meta: [
      { hid: 'robots', name: 'robots', content: 'noindex' }
    ]
  },
  data: () => ({
    username: '',
    password: '',
    error: false,
    formDissabled: false
  }),
  methods: {
    async onLogin(){
      this.formDissabled = true;
      this.$auth.loginWith('local', {
        data: {
          username: this.username,
          password: this.password
        }
      }).then(({status, data}) => {
        if(status===200 && data.success && data.user){
          this.$auth.setUser(data.user);
          this.$toast.success(data.message || 'Kullanıcı Girişi Başarılı');
          location.href = "/"
        }else{
          this.formDissabled = false;
        }
      }).catch(err => {
        this.$toast.error('Giriş denemesi başarısız oldu');
        this.formDissabled = false;
      })
    }, // onLogin
  },

  computed: {

    btnLoginStatus(){
      return this.username.length > 2 && this.password.length > 5
    }

  }
}
</script>

