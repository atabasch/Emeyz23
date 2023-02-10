<template>
  <v-navigation-drawer color="primary" dark value="true" :mini-variant="miniVariant" fixed app :expand-on-hover="miniVariant">


    <v-list shaped  dense>


      <template v-for="(group, gIndex) in items">

        <v-subheader>{{ group.title }}</v-subheader>
        <v-divider class="mx-4" ></v-divider>
        <v-list-item-group>
          <v-list-item v-for="(item, index) in group.items" :key="index" :to="$const.url.panel+item.to" router exact ripple>
            <v-list-item-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

        </v-list-item-group>

      </template>


      <v-divider class="mx-4" ></v-divider>
      <v-list-item-group>
        <v-list-item  to="/" router exact ripple>
          <v-list-item-action><v-icon>mdi-web</v-icon></v-list-item-action>
          <v-list-item-content><v-list-item-title>Emeyz'e Dön</v-list-item-title></v-list-item-content>
        </v-list-item>
      </v-list-item-group>


    </v-list>



    <template v-slot:append>
      <div class="pa-2"><v-btn @click="logout()" block><v-icon>mdi-logout</v-icon> Çıkış Yap</v-btn></div>
    </template>



  </v-navigation-drawer>
</template>

<script>
export default {
  name: "Sidebar",
  props: ["drawer", "clipped" , "miniVariant"],
  data(){
    return {
      items: [
        {
          title: 'Başlangıç',
          items:  [
            { title: 'Genel Bakış', icon: 'mdi-view-dashboard', to: '' }
          ]
        },

        {
          title:  'Yazılar',
          items:  [
            { title: 'Tüm Yazılar', icon: 'mdi-text', to: 'posts' },
            { title: 'Yazı Ekle', icon: 'mdi-pencil-plus', to: 'posts/create' },
            { title: 'Kategoriler', icon: 'mdi-file-tree', to: 'posts/categories' }
          ]
        },

        {
          title:  'Anketler',
          items:  [
            { title: 'Anket Listesi', icon: 'mdi-poll', to: 'surveys' },
            { title: 'Yeni Anket', icon: 'mdi-plus', to: 'surveys/create' },
          ]
        },

        {
          title:  'Diğer',
          items:  [
            { title: 'Medya Dosyaları', icon: 'mdi-folder-multiple', to: 'media' },
            { title: 'Sayfalar', icon: 'mdi-file', to: 'pages' },
            { title: 'Hesap Listesi', icon: 'mdi-account-multiple', to: 'accounts' },
            { title: 'Navigasyon', icon: 'mdi-link-variant', to: 'navigations' },
          ]
        },

        {
          title:  'Ayarlar',
          items:  [
            { title: 'Genel', icon: 'mdi-cogs', to: 'settings/general' },
            { title: 'E-Posta', icon: 'mdi-email-edit', to: 'settings/email' },
            { title: 'Medya', icon: 'mdi-image-multiple', to: 'settings/media' },
          ]
        },



      ]
    }
  },

  methods: {
    logout(){
      this.$auth.logout().then(res => {
        this.$router.replace('/login');
      }).catch(err => {
        console.log(err)
      })
    }
  }

}
</script>

<style scoped>

</style>
