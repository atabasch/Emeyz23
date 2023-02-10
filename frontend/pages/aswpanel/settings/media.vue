<template>
  <v-sheet >
    <AdminContentHead title="Medya Ayarları" description=""/>

    <div style="max-width: 850px">
      <div class="py-4"><h3 class="subtitle-2 pb-2">Yol Ayarları </h3><v-divider /></div>
      <v-text-field v-model="configs.url_media.val"
                    :label="configs.url_media.label"
                    :hint="configs.url_media.key"
                    persistent-hint
                    class="mb-3"
                    dense outlined/>
      <v-text-field v-model="configs.path_media.val"
                    :label="configs.path_media.label"
                    :hint="configs.path_media.key"
                    persistent-hint
                    class="mb-3"
                    dense outlined/>
      <v-text-field v-model="configs.path_media_upload.val"
                    :label="configs.path_media_upload.label"
                    :hint="configs.path_media_upload.key"
                    persistent-hint
                    class="mb-3"
                    dense outlined/>
      <v-text-field v-model="configs.path_media_user.val"
                    :label="configs.path_media_user.label"
                    :hint="configs.path_media_user.key"
                    persistent-hint
                    class="mb-3"
                    dense outlined/>

      <v-switch v-model="configs.img_allow_original.val"
                :label="configs.img_allow_original.label"
                :hint="configs.img_allow_original.key"
                persistent-hint
                class="mb-3"
                dense outlined false-value="off" true-value="on"/>

      <div class="py-4"><h3 class="subtitle-2 pb-2">Büyük Görsel (lg) Ayarları</h3><v-divider /></div>
      <v-switch v-model="configs.img_lg_crop.val"
                :label="configs.img_lg_crop.label"
                :hint="configs.img_lg_crop.key"
                persistent-hint
                class="pa-0 ma-0 mb-3"
                dense outlined false-value="off" true-value="on" />
      <v-text-field v-model="configs.img_lg_quality.val"
                    :label="configs.img_lg_quality.label"
                    :hint="configs.img_lg_quality.key"
                    persistent-hint
                    class="mb-3"
                    dense outlined suffix="%"/>
      <v-row>
        <v-col><v-text-field v-model="configs.img_lg_w.val"
                             :label="configs.img_lg_w.label"
                             :hint="configs.img_lg_w.key"
                             persistent-hint
                             class="mb-3"
                             dense outlined suffix="px"/></v-col>
        <v-col><v-text-field v-model="configs.img_lg_h.val"
                             :label="configs.img_lg_h.label"
                             :hint="configs.img_lg_h.key"
                             persistent-hint
                             class="mb-3"
                             dense outlined suffix="px"/></v-col>
      </v-row>

      <div class="py-4"><h3 class="subtitle-2 pb-2">Orta boy Görsel (md) Ayarları</h3><v-divider /></div>
      <v-switch v-model="configs.img_md_crop.val"
                :label="configs.img_md_crop.label"
                :hint="configs.img_md_crop.key"
                persistent-hint
                class="pa-0 ma-0 mb-3"
                dense outlined false-value="off" true-value="on"/>
      <v-text-field v-model="configs.img_md_quality.val"
                    :label="configs.img_md_quality.label"
                    :hint="configs.img_md_quality.key"
                    persistent-hint
                    class="mb-3"
                    dense outlined suffix="%"/>
      <v-row>
        <v-col><v-text-field v-model="configs.img_md_w.val"
                             :label="configs.img_md_w.label"
                             :hint="configs.img_md_w.key"
                             persistent-hint
                             class="mb-3"
                             dense outlined suffix="px"/></v-col>
        <v-col><v-text-field v-model="configs.img_md_h.val"
                             :label="configs.img_md_h.label"
                             :hint="configs.img_md_h.key"
                             persistent-hint
                             class="mb-3"
                             dense outlined suffix="px"/></v-col>
      </v-row>

      <div class="py-4"><h3 class="subtitle-2 pb-2">Küçük Görsel (sm) Ayarları</h3><v-divider /></div>
      <v-switch v-model="configs.img_sm_crop.val"
                :label="configs.img_sm_crop.label"
                :hint="configs.img_sm_crop.key"
                persistent-hint
                class="pa-0 ma-0 mb-3"
                dense outlined false-value="off" true-value="on"/>
      <v-text-field v-model="configs.img_sm_quality.val"
                    :label="configs.img_sm_quality.label"
                    :hint="configs.img_sm_quality.key"
                    persistent-hint
                    class="mb-3"
                    dense outlined suffix="%"/>
      <v-row>
        <v-col><v-text-field v-model="configs.img_sm_w.val"
                             :label="configs.img_sm_w.label"
                             :hint="configs.img_sm_w.key"
                             persistent-hint
                             class="mb-3"
                             dense outlined suffix="px"/></v-col>
        <v-col><v-text-field v-model="configs.img_sm_h.val"
                             :label="configs.img_sm_h.label"
                             :hint="configs.img_sm_h.key"
                             persistent-hint
                             class="mb-3"
                             dense outlined suffix="px"/></v-col>
      </v-row>

      <div class="py-4"><h3 class="subtitle-2 pb-2">Varsayılan Görsel Adresleri </h3><v-divider /></div>
      <v-text-field v-model="configs.url_no_avatar_male.val"
                    :label="configs.url_no_avatar_male.label"
                    :hint="configs.url_no_avatar_male.key"
                    persistent-hint
                    class="mb-3"
                    dense outlined/>
      <v-text-field v-model="configs.url_no_avatar_female.val"
                    :label="configs.url_no_avatar_female.label"
                    :hint="configs.url_no_avatar_female.key"
                    persistent-hint
                    class="mb-3"
                    dense outlined/>
      <v-text-field v-model="configs.url_no_avatar_people.val"
                    :label="configs.url_no_avatar_people.label"
                    :hint="configs.url_no_avatar_people.key"
                    persistent-hint
                    class="mb-3"
                    dense outlined/>
      <v-text-field v-model="configs.url_no_img.val"
                    :label="configs.url_no_img.label"
                    :hint="configs.url_no_img.key"
                    persistent-hint
                    class="mb-3"
                    dense outlined/>

      <div class="text-right">
        <v-btn color="primary" class="text-capitalize" @click="saveSettings()"><v-icon small>mdi-check-bold</v-icon> Kaydet</v-btn>
      </div>
    </div>
  </v-sheet>
</template>

<script>
import {getSettings, updateSettings} from "@/plugins/methods/settings";

export default {
  name: "pagePanelSettingsMedia",
  layout: "panel",
  data(){return {
    configs: {
      path_media:               {val:'', label:''},
      url_media:               {val:'', label:''},
      path_media_upload:             {val:'', label:''},
      path_media_user:       {val:'', label:''},
      url_no_avatar_male:            {val:'', label:''},
      url_no_avatar_female:            {val:'', label:''},
      url_no_avatar_people:            {val:'', label:''},
      url_no_img:            {val:'', label:''},
      img_allow_original:           {val:'', label:''},
      img_lg_crop:           {val:'', label:''},
      img_lg_quality:   {val:'', label:''},
      img_lg_w:         {val:'', label:''},
      img_lg_h:          {val:'', label:''},
      img_md_crop:          {val:'', label:''},
      img_md_quality:          {val:'', label:''},
      img_md_w:          {val:'', label:''},
      img_md_h:          {val:'', label:''},
      img_sm_crop:          {val:'', label:''},
      img_sm_quality:          {val:'', label:''},
      img_sm_w:          {val:'', label:''},
      img_sm_h:          {val:'', label:''},

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
