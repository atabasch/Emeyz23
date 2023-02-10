<template>
  <v-sheet >
    <AdminContentHead title="E-Posta Ayarları" description=""/>

    <div style="max-width: 850px">
      <div class="py-4"><h3 class="subtitle-2 pb-2">Gönderme (SMTP) </h3><v-divider /></div>

      <v-text-field :label="configs.mail_driver.label" disabled v-model="configs.mail_driver.val" outlined dense/>
      <v-text-field :label="configs.mail_host.label" v-model="configs.mail_host.val" outlined dense/>
      <v-select :items="['465', '587', '25']" :label="configs.mail_port.label" v-model="configs.mail_port.val" outlined dense/>
      <v-select :items="['tls', 'ssl']" :label="configs.mail_encryption.label" v-model="configs.mail_encryption.val" outlined dense/>

      <div class="py-4"><h3 class="subtitle-2 pb-2">Hesap Bilgileri (SMTP) </h3><v-divider /></div>
      <v-text-field :label="configs.mail_username.label" v-model="configs.mail_username.val" outlined dense/>
      <v-text-field type="password" :label="configs.mail_password.label" v-model="configs.mail_password.val" outlined dense/>

      <div class="py-4"><h3 class="subtitle-2 pb-2">Diğer </h3><v-divider /></div>
      <v-text-field :label="configs.mail_reply.label" v-model="configs.mail_reply.val" outlined dense/>
      <v-text-field :label="configs.mail_sender.label" v-model="configs.mail_sender.val" outlined dense/>
      <v-text-field :label="configs.mail_sender_name.label" v-model="configs.mail_sender_name.val" outlined dense/>


      <div class="text-right">
        <v-btn color="primary" class="text-capitalize" @click="saveSettings()"><v-icon small>mdi-check-bold</v-icon> Kaydet</v-btn>
      </div>

      <div class="py-4"><h3 class="subtitle-2 pb-2">Ayarları Test Et </h3><v-divider /></div>
      <small>Bir alıcı e-postası yazarak yukarıdaki ayarların çalışma durumunu test edebilirsin.</small>
      <v-text-field label="Alıcı E-Posta adresi" v-model="testMail" outlined dense/>
      <div class="text-right">
        <v-btn color="primary" class="text-capitalize" @click="doTestMail()"><v-icon small>mdi-check-bold</v-icon> Test et</v-btn>
      </div>


    </div>
  </v-sheet>
</template>

<script>
import {getSettings, updateSettings} from "@/plugins/methods/settings";

export default {
  name: "pagePanelSettingsEmail",
  layout: "panel",
  data(){return {
    configs: {
      mail_reply:               {val:'', label:''},
      mail_sender:             {val:'', label:''},
      mail_sender_name:       {val:'', label:''},
      mail_driver:            {val:'', label:''},
      mail_host:           {val:'', label:''},
      mail_port:           {val:'', label:''},
      mail_username:   {val:'', label:''},
      mail_password:    {val:'', label:''},
      mail_encryption:          {val:'', label:''},

    },
    testMail: ''
  }},
  async fetch(){
    let fields = Object.keys(this.configs).join(',');
    this.configs = await getSettings(this.$axios, fields);
  },
  methods: {
    async saveSettings(){
      let {success, message} = await updateSettings(this.$axios, this.configs);
      if(success){
        this.$toast.success(message || 'Ayarlar Güncellendi')
      }else{
        this.$toast.error(message || 'Güncelleme işlemi sırasında beklenmedik bir hata oluştu')
      }
    },

    doTestMail(){
      if(this.testMail.length > 5){
        this.$axios.post('/admin/settings/smtp_test', {to: this.testMail}).then(({status, data}) => {
          if(status===200 && data.success){
            this.$toast.success(data.message || 'E-posta Gönderildi')
          }else{
            this.$toast.error(data.message || 'E-posta gönderme hatası')
          }
        }).catch(err => {
          this.$toast.error("Beklenmedik bir hata oluştu")
        })
      }
    }
  }
}
</script>

<style scoped>

</style>
