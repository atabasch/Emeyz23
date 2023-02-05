<template>
  <v-navigation-drawer color="primary" dark value="true" :mini-variant="miniVariant" fixed app :expand-on-hover="miniVariant">


    <v-list shaped>


      <template v-for="(group, gIndex) in items">

        <v-subheader>{{ group.title }}</v-subheader>
        <v-divider class="mx-4"></v-divider>
        <v-list-item-group>
          <v-list-item v-for="(item, index) in group.items" :key="index" :to="item.to" router exact>
            <v-list-item-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

        </v-list-item-group>

      </template>


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
            { title: 'Genel Bakış', icon: 'mdi-view-dashboard', to: '/aswpanel' }
          ]
        },

        {
          title:  'Yazılar',
          items:  [
            { title: 'Tüm Yazılar', icon: 'mdi-text', to: '/aswpanel/posts' },
            { title: 'Yazı Ekle', icon: 'mdi-pencil-plus', to: '/aswpanel/posts/create' },
            { title: 'Kategoriler', icon: 'mdi-file-tree', to: '/aswpanel/posts/categories' }
          ]
        },

        {
          title:  'Anketler',
          items:  [
            { title: 'Anket Listesi', icon: 'mdi-poll', to: '/aswpanel/surveys' },
            { title: 'Yeni Anket', icon: 'mdi-plus', to: '/aswpanel/surveys/create' },
          ]
        },

        {
          title:  'Kullanıcılar',
          items:  [
            { title: 'Hesap Listesi', icon: 'mdi-account-multiple', to: '/aswpanel/accounts' },
            { title: 'Kullanıcı Oluştur', icon: 'mdi-account-plus', to: '/aswpanel/accounts/create' },
          ]
        },

        {
          title:  'Diğer',
          items:  [
            { title: 'Medya Dosyaları', icon: 'mdi-folder-multiple', to: '/aswpanel/media' },
            { title: 'Sayfalar', icon: 'mdi-file', to: '/aswpanel/pages' },
            { title: 'Navigasyon', icon: 'mdi-link-variant', to: '/aswpanel/navigations' },
          ]
        },
        {
          title:  '',
          items:  [
            { title: "Emeyz'e Git", icon: 'mdi-web', to: '/' },
          ]
        }

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
