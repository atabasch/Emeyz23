<template>
  <div>
    <v-toolbar dark color="#2A2356">
      <v-toolbar-title>Kayıt Ol</v-toolbar-title>
    </v-toolbar>

    <v-card-text>
      <v-alert type="error" v-if="error" v-html="getError"></v-alert>
      <v-form ref="registerForm" v-model="formValidated">
        <v-text-field prepend-icon="mdi-form-textbox" v-model="user.fullname" label="Tam Ad" type="text" maxlength="35" counter></v-text-field>

        <v-text-field prepend-icon="mdi-account" v-model="user.name" label="Kullanıcı Adı" type="text" maxlength="16" counter :rules="rules.username"></v-text-field>

        <v-text-field prepend-icon="mdi-email" v-model="user.email" label="E-Posta Adresi" type="email" maxlength="35" counter :rules="rules.email"></v-text-field>

        <v-text-field id="password" prepend-icon="mdi-lock" v-model="user.password" label="Parola" :type="showPassword? 'text' : 'password'" :rules="rules.password" autocomplte="current-password"
                      :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                      @click:append="showPassword = !showPassword" maxlength="32" counter></v-text-field>

        <v-text-field prepend-icon="mdi-calendar" v-model="user.birthday" label="Doğum Tarihi" type="date" :rules="rules.birthday"></v-text-field>

        <v-select v-model="user.gender" :items="genderOptions" item-value="value" item-text="text" label="Cinsiyet" :rules="rules.gender"
        :prepend-icon=" user.gender==='Erkek'? 'mdi-gender-male' : (user.gender==='Kadın'? 'mdi-gender-female' : 'mdi-gender-male-female') "
        ></v-select>

        <v-textarea prepend-icon="mdi-text" v-model="user.description" label="Açıklama" hint="Lütfen kendiniz hakkında bir kaç satır açıklama giriniz." rows="1" auto-grow maxlength="1024" counter :rules="rules.description"></v-textarea>

        <v-checkbox
          v-model="user.accept"
          :rules="rules.accept"
          :label="`Kullanım şartlarını kabul ediyorum.`"
          required
        ></v-checkbox>


      </v-form>
    </v-card-text>

    <v-divider></v-divider>
    <v-card-actions>
      <v-btn class="mx-1 text-capitalize subtitle-2" color="green" @click="$emit('changeTab', 'login')" text tile>Zaten Bir Hesabım Var</v-btn>
      <v-spacer></v-spacer>
      <v-btn class="mx-1 text-capitalize subtitle-2" color="blue" @click="onRegister()" :disabled="isDisabledActionButton" dark tile text>Kaydı Tamamla</v-btn>
    </v-card-actions>
  </div>
</template>

<script>
import {validPassword} from "@/helpers/string"
import {getAge} from "@/helpers/date"
export default {
  name: "register",
  layout: 'logon',
  head: {
    title: 'Emeyz Yeni Hesap Oluştur'
  },
  data: () => ({
    user: {
      name: '',
      email:    '',
      password: '',
      fullname: '',
      description: '',
      gender: 'Belirtilmedi',
      birthday: '',
      accept: false
    },

    formValidated: false,
    rules: {
      username: [
        v => !!v || 'Kullanıcı Adı Gerekli',
        v => (v && v.length >= 3 && v.length <= 16) || '3 ila 16 karakter arasında bir değer olmalı.'
      ],
      email: [
        v => !!v || 'E-posta gerekli',
        v => (v && v.length >=8 && v.length <= 32) || 'Lütfen gerçekçi bir e-posta hesabı girin',
        v => /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/g.test(v) || 'Lütfen gerçekçi bir e-posta hesabı girin'
      ],
      password: [
        v => !!v || 'Parola gerekli',
        v => (v && v.length >= 8) || 'Parola en az 8 karakterden oluşmalı',
        v => (v && v.length <= 32) || 'Parola fazla uzun',
        //v =>  validPassword(v) || 'Parola en az 8 karakterden oluşan ve içerisinde en az 1 küçük ve büyük harf, rakam ve özel karakter bulunan bir değer olmalıdır.'
      ],
      birthday: [
        v => v && getAge(v) >= 16 || 'Bu doğum tarihi yaşınızı çok küçük gösteriyor.',
        v => v && getAge(v) <= 75 || 'Bu doğum tarihi yaşınızı çok büyük gösteriyor.'
      ],
      gender: [
        v => ['Belirtilmedi', 'Kadın', 'Erkek'].indexOf(v) >= 0 || 'Cinsiyet için geçerli bir seçenek seçilmedi.'
      ],
      description: [
        v => (v.length <= 1024) || 'Açıklama çok uzun'
      ],
      accept: [
        v => v===true || 'Kullanım şartlarını kabul etmek zorundasınız!'
      ]
    },

    message: null,
    genderOptions: [
        {text:'Belirtilmedi', value:'Belirtilmedi'},
        {text:'Kadın', value:'Kadın'},
        {text:'Erkek', value:'Erkek'},
    ],

    showPassword: false,
    error: false,
  }),
  methods: {
    async onRegister(){
      if(this.$refs.registerForm.validate()){
        this.$axios.post('/account/register', {user: this.user}).then(({status, data}) => {
          if(status===200 && data.success){
            this.$emit('onRegistered', data.user);
          }else{
            this.error = data.errors || (data.message || false)
          }
        }).catch(err => {
          console.log(err)
        })
      }
    }, // onRegister
  },

  computed: {
    isDisabledActionButton(){
      return !this.formValidated;
    },
    getError(){
      if(!this.error){
        return false;
      }else{
        if(typeof this.error == 'object'){
          let msg = '';
          Object.entries(this.error).forEach(function(err, index){
            msg += err[1].message + '<br/>';
          });
          return msg;
        }else{
          return this.error;
        }
      }
    }
  }
}
</script>

