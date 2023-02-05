<template>
<div>
  <v-form v-model="formValidated" ref="registerForm">
<v-row>

  <v-col cols="12" md="8" lg="9" xl="10">

    <v-row>
      <v-col cols="12" sm="6">
        <v-text-field prepend-icon="mdi-account" v-model="user.name" :rules="rules.name" label="Kullanıcı Adı" hint="Geçerli big kullanıcı adı " counter maxlength="16"  required/>
      </v-col>

      <v-col cols="12" sm="6">
        <v-text-field id="password" prepend-icon="mdi-lock" v-model="user.password" label="Parola" :type="showPassword? 'text' : 'password'" :rules="rules.password" autocomplte="current-password"
                      :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                      @click:append="showPassword = !showPassword"
                      append-outer-icon="mdi-auto-fix"
                      @click:append-outer="createPassword()"
                      maxlength="32" counter></v-text-field>
      </v-col>

      <v-col cols="12" sm="6">
        <v-text-field prepend-icon="mdi-account-question" v-model="user.fullname" label="Tam Ad" hint="Hesap sahibinin gerçek adı ve soyadı" counter maxlength="32"  required/>
      </v-col>

      <v-col cols="12" sm="6">
        <v-text-field prepend-icon="mdi-email-open" v-model="user.email"  :rules="rules.email" label="E-Posta" hint="Hesap sahibi için iletişim e-postası" counter maxlength="32" type="email"  required/>
      </v-col>

      <v-col cols="12" sm="6">
        <v-text-field prepend-icon="mdi-calendar" v-model="user.birthday" label="Doğum Tarihi" type="date" :rules="rules.birthday"></v-text-field>
      </v-col>

      <v-col cols="12" sm="6">
        <v-select v-model="user.gender" :items="genderOptions" item-value="value" item-text="text" label="Cinsiyet" :rules="rules.gender"
                  :prepend-icon=" user.gender==='Erkek'? 'mdi-gender-male' : (user.gender==='Kadın'? 'mdi-gender-female' : 'mdi-gender-male-female') "
        ></v-select>
      </v-col>
    </v-row>

    <v-textarea prepend-icon="mdi-text" v-model="user.description" label="Açıklama" hint="Hesap hakkında kısa bir açıklama" rows="3" auto-grow maxlength="1024" counter :rules="rules.description"></v-textarea>



    <h3 class="py-2 text-h5">Ekstra Bilgiler</h3>
    <v-divider class="mb-5"></v-divider>
    <v-row>
      <v-col cols="12" sm="6"><v-text-field prepend-icon="mdi-web" v-model="user.datas.website" label="Web Sitesi" counter maxlength="45"/></v-col>
      <v-col cols="12" sm="6"><v-text-field prepend-icon="mdi-github" v-model="user.datas.github" label="Github" counter maxlength="45"/></v-col>
      <v-col cols="12" sm="6"><v-text-field prepend-icon="mdi-instagram" v-model="user.datas.instagram" label="İnstagram" counter maxlength="45"/></v-col>
      <v-col cols="12" sm="6"><v-text-field prepend-icon="mdi-facebook" v-model="user.datas.facebook" label="Facebook" counter maxlength="45"/></v-col>
      <v-col cols="12" sm="6"><v-text-field prepend-icon="mdi-youtube" v-model="user.datas.youtube" label="Youtube" counter maxlength="45"/></v-col>
      <v-col cols="12" sm="6"><v-text-field prepend-icon="mdi-linkedin" v-model="user.datas.linkedin" label="Linkedin" counter maxlength="45"/></v-col>
    </v-row>

  </v-col>


  <v-col>

    <InputCoverImage v-model="user.cover" label="Profil Fotoğrafı"  />

    <v-select v-model="user.level" :items="Object.values(levels)" item-value="value" item-text="title" label="Seviye" prepend-icon="mdi-star"></v-select>
    <v-select v-model="user.status" :items="Object.values(statusItems)" item-value="value" item-text="title" label="Hesap Durumu" prepend-icon="mdi-check"></v-select>
    <v-text-field prepend-icon="mdi-numeric" v-model="user.activation_code" label="Aktivayon Kodu" counter maxlength="6" type="number" required/>

    <v-checkbox v-if="!user.id" v-model="user.accept" :rules="rules.accept" :label="`Hesabın sorumluluklarını alıyorum.`" required></v-checkbox>
    <v-checkbox v-if="!user.id" v-model="user.notify" :label="`Hesap sahibini e-posta ile bilgilendir.`" required></v-checkbox>


    <div class="align-end">
      <v-btn v-if="!update" class="mx-1 text-capitalize subtitle-2" color="blue" @click="onCreate()" :disabled="!formValidated" dark tile block><v-icon>mdi-check-bold</v-icon> Kaydı Tamamla</v-btn>
      <v-btn v-if="update" class="mx-1 text-capitalize subtitle-2" color="green" @click="onUpdate()" :disabled="!formValidated" dark tile block><v-icon>mdi-check-bold</v-icon> Güncelle</v-btn>
    </div>

  </v-col>


</v-row>
  </v-form>

</div>
</template>

<script>
import {validPassword, passwordGenerator} from "@/helpers/string";
import {getAge} from "@/helpers/date";

import InputCoverImage from "@/components/panel/child/InputCoverImage"
export default {
  name: "UserForm",
  components: {InputCoverImage},
  props: {
    update: {
      type: Boolean,
      default: false,
    },
    user: {
      type: Object,
      default: (raw) => {
        return {
          name: '',
          email:    '',
          password: '',
          fullname: '',
          description: '',
          gender: 'Belirtilmedi',
          birthday: '',
          cover: null,
          datas: {
            website:null,
            github:null,
            instagram:null,
            facebook:null,
            youtube:null,
            linkedin:null,
          },
          level: 1,
          status: 'active',
          accept: false,
          notify: false,
          activation_code: ''
        }
      }
    },
  },

  created() {
    this.initRules();
  },

  data: ()=>({
    formValidated: false,
    rules: {},
    message: null,
    genderOptions: [
      {text:'Belirtilmedi', value:'Belirtilmedi'},
      {text:'Kadın', value:'Kadın'},
      {text:'Erkek', value:'Erkek'},
    ],
    levels: {
      '-1': {title: 'Yasaklı',  value: -1,  color: 'red'},
      '0': {title: 'Onaysız',     value: 0,   color: 'grey'},
      '1': {title: 'Abone',     value: 1,   color: 'black'},
      '2': {title: 'Moderatör', value: 2,   color: 'green'},
      '3': {title: 'Yönetici',  value: 3,   color: 'blue'},
    },
    statusItems: {
      'active':   {title: 'Aktif',    value: 'active',  color: 'green'  },
      'passive':  {title: 'Pasif',    value: 'passive', color: 'black'  },
      'trash':    {title: 'Çöpte',    value: 'trash',   color: 'red'    },
    },
    genders: {
      'Erkek': { icon: 'mdi-gender-male', color: 'blue' },
      'Kadın': { icon: 'mdi-gender-female', color: 'pink' },
      'Belirtilmedi': { icon: 'mdi-gender-male-female', color: 'black' },
    },
    showPassword: false,
    error: false,
  }),



  methods: {
    initRules(){
      this.rules = {
        name: [
          v => !!v || 'Kullanıcı Adı Gerekli',
          v => (v && v.length >= 3 && v.length <= 16) || '3 ila 16 karakter arasında bir değer olmalı.'
        ],
        email: [
          v => !!v || 'E-posta gerekli',
          v => (v && v.length >=8 && v.length <= 32) || 'Lütfen gerçekçi bir e-posta hesabı girin',
          v => /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/g.test(v) || 'Lütfen gerçekçi bir e-posta hesabı girin'
        ],
        birthday: [
          v => v && getAge(v) >= 16 || 'Bu doğum tarihi yaşınızı çok küçük gösteriyor.',
          v => v && getAge(v) <= 75 || 'Bu doğum tarihi yaşınızı çok büyük gösteriyor.'
        ],
        gender: [
          v => ['Belirtilmedi', 'Kadın', 'Erkek'].indexOf(v) >= 0 || 'Cinsiyet için geçerli bir seçenek seçilmedi.'
        ],
        description: [
          v => (!v || v.length <= 1024) || 'Açıklama çok uzun'
        ],
      }

      if(!this.update){
        this.rules.password = [
          v => !!v || 'Parola gerekli',
          v => (v && v.length >= 6) || 'Parola en az 6 karakterden oluşmalı',
          v => (v && v.length <= 32) || 'Parola fazla uzun',
          v =>  validPassword(v) || 'Parola en az 8 karakterden oluşan ve içerisinde en az 1 küçük ve büyük harf, rakam ve özel karakter bulunan bir değer olmalıdır.'
        ]
        this.rules.accept = [v => !!v || 'Hesap sorumluluklarını kabul et']
      }else{
        delete this.rules.password
        delete this.rules.accept
      }
    },

    createPassword(){
      this.user.password = passwordGenerator();
    }, // createPassword

    onCreate(){
      if(this.$refs.registerForm.validate()){
      this.$axios.post('/admin/account/create', {user: { ...this.user, datas: JSON.stringify(this.user.datas) }}).then(({status, data})=>{
        if(status===200 && data.success){
          this.$toast.success(data.message || 'Kullanıcı Oluşturuldu.')
          this.$router.replace(this.$const.url.panel+'accounts/'+data.user.id+'/edit');
        }else{
          this.$toast.error(data.message || 'Bir sorun oluştu.');
        }
      }).catch(err => {
        console.log(err)
      })
      }
    }, // onCreate

    onUpdate(){
      if(this.$refs.registerForm.validate()){
        this.$axios.post('/admin/account/update/'+this.user.id, {user: { ...this.user, datas: JSON.stringify(this.user.datas) }}).then(({status, data})=>{
          if(status===200 && data.success){
            console.log("update result", data)
            this.$toast.success(data.message || 'Kullanıcı Bilgileri Güncellendi.')
            data.user.password = '';
            this.user = data.user;
          }else{
            this.$toast.error(data.message || 'Bir sorun oluştu.');
          }
        }).catch(err => {
          console.log(err)
        })
      }
    }

  }
}
</script>

<style scoped>

</style>
