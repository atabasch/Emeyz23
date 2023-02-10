<template>
  <v-sheet >
    <AdminContentHead title="Genel Ayarlar"
                 description=""
                 />

    <div style="max-width: 850px">
      <div class="py-4"><h3 class="subtitle-2 pb-2">Genel Bilgiler</h3><v-divider /></div>

      <v-text-field   :label="configs.site_url.label"
                      v-model="configs.site_url.val"
                      :hint="configs.site_url.key"
                      class="mb-2"
                      outlined dense persistent-hint/>

      <v-text-field   :label="configs.site_title.label"
                      v-model="configs.site_title.val"
                      :hint="configs.site_title.key"
                      class="mb-2"
                      outlined dense persistent-hint/>

      <v-textarea   :label="configs.site_description.label"
                    v-model="configs.site_description.val"
                    :hint="configs.site_description.key"
                    class="mb-2"
                    auto-grow rows="1" outlined dense persistent-hint/>

      <v-text-field   :label="configs.site_author.label"
                      v-model="configs.site_author.val"
                      :hint="configs.site_author.key"
                      class="mb-2"
                      outlined dense persistent-hint/>

      <v-text-field   :label="configs.site_refresh.label"
                      v-model="configs.site_refresh.val"
                      :hint="configs.site_refresh.key"
                      class="mb-2"
                      outlined dense persistent-hint/>



      <div class="py-4"><h3 class="subtitle-2 pb-2">Site Durumu</h3><v-divider /></div>
      <v-switch   :label="configs.site_offline.label"
                  v-model="configs.site_offline.val"
                  :hint="configs.site_offline.key"
                  class="ma-0 pa-0 mb-2"
                  persistent-hint false-value="off" true-value="on"  outlined dense />
      <v-textarea :label="configs.site_offline_message.label"
                  v-model="configs.site_offline_message.val"
                  :hint="configs.site_offline_message.key"
                  class="mb-2"
                  persistent-hint auto-grow rows="1" outlined dense/>

      <div class="py-4"><h3 class="subtitle-2 pb-2">Kullanıcılar</h3><v-divider /></div>
      <v-switch     :label="configs.site_allow_register.label"
                    v-model="configs.site_allow_register.val"
                    :hint="configs.site_allow_register.key"
                    class="ma-0 pa-0 mb-2"
                    persistent-hint false-value="off" true-value="on"  outlined dense />
      <v-text-field :label="configs.login_timeout.label"
                    v-model="configs.login_timeout.val"
                    :hint="configs.login_timeout.key"
                    class="mb-2"
                    persistent-hint outlined dense/>

      <div class="py-4"><h3 class="subtitle-2 pb-2">İçerik</h3><v-divider /></div>
      <v-switch     :label="configs.pages_allow_comments.label"
                    v-model="configs.pages_allow_comments.val"
                    :hint="configs.pages_allow_comments.key"
                    class="ma-0 pa-0 mb-2"
                    persistent-hint false-value="off" true-value="on"  outlined dense/>
      <v-switch     :label="configs.articles_allow_comments.label"
                    v-model="configs.articles_allow_comments.val"
                    :hint="configs.articles_allow_comments.key"
                    class="ma-0 pa-0 mb-2"
                    persistent-hint false-value="off" true-value="on"  outlined dense/>
      <v-text-field :label="configs.articles_list_limit.label"
                    v-model="configs.articles_list_limit.val"
                    :hint="configs.articles_list_limit.key"
                    class="mb-2"
                    persistent-hint outlined dense/>
      <v-text-field :label="configs.articles_summary_limit.label"
                    v-model="configs.articles_summary_limit.val"
                    :hint="configs.articles_summary_limit.key"
                    class="mb-2"
                    persistent-hint outlined dense/>

      <div class="text-right">
        <v-btn color="primary" class="text-capitalize" @click="saveSettings()"><v-icon small>mdi-check-bold</v-icon> Kaydet</v-btn>
      </div>
    </div>
  </v-sheet>
</template>

<script>
import {getSettings, updateSettings} from "@/plugins/methods/settings";
export default {
  name: "pagePanelSettingsGeneral",
  layout: "panel",
  data(){return {
    configs: {
      site_url:               {val:'', label:''},
      site_title:             {val:'', label:''},
      site_description:       {val:'', label:''},
      site_author:            {val:'', label:''},
      site_refresh:           {val:'', label:''},
      site_offline:           {val:'off', label:''},
      site_offline_message:   {val:'', label:''},
      site_allow_register:    {val:'off', label:''},
      login_timeout:          {val:'', label:''},
      pages_allow_comments:   {val:'off', label:''},
      articles_allow_comments:{val:'off', label:''},
      articles_list_limit:    {val:'', label:''},
      articles_summary_limit: {val:'', label:''},

    }
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
    }
  }
}
</script>

<style scoped>

</style>
