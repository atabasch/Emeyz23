<template>
<div>
  <v-app-bar app color="primary" dark class="mx-auto" :clipped-right="true">
    <v-container class="pa-0 px-3 fill-height em-container">
      <v-app-bar-nav-icon v-if="false" />
      <v-toolbar-title><router-link to="/" class="white--text text-decoration-none">Emeyz</router-link></v-toolbar-title>
      <v-spacer />

      <v-btn-toggle class="d-none d-md-flex" dark group>
        <v-btn link :to="getToFromUrl(item.url)" class="text-capitalize body-2 px-2 white--text "   :key="item.id" v-for="item in getItems"><v-icon>{{item.icon}}</v-icon> {{item.title}}</v-btn>
        <v-btn link  @click="logonDialogStatus = true" class="text-capitalize body-2 px-2 white--text" v-if="!$auth.user" ><v-icon>mdi-login-variant</v-icon> Giriş | Kayıt</v-btn>
        <v-menu offset-y v-if="$auth.user">
          <template v-slot:activator="{ on, attrs }">
            <v-btn link v-bind="attrs" v-on="on" class="text-capitalize body-2 px-2 white--text"><v-icon>mdi-dots-vertical</v-icon> Hesap</v-btn>
          </template>
          <v-list nav dense >
            <v-subheader class="text-capitalize subtitle-2 px-2">İçerikler</v-subheader>
            <v-list-item link to="/account/posts"><v-list-item-icon><v-icon>mdi-text</v-icon></v-list-item-icon><v-list-item-content><v-list-item-title>Makaleler</v-list-item-title></v-list-item-content></v-list-item>
            <v-list-item link to="/account/comments"><v-list-item-icon><v-icon>mdi-comment-multiple</v-icon></v-list-item-icon><v-list-item-content><v-list-item-title>Yorumlar</v-list-item-title></v-list-item-content></v-list-item>

            <v-subheader class="text-capitalize subtitle-2 px-2">Hesap</v-subheader>
            <v-list-item v-if="this.$store.getters['loggedInAdmin']" link :to="'/aswpanel'">
              <v-list-item-icon><v-icon>mdi-account-tie</v-icon></v-list-item-icon>
              <v-list-item-content><v-list-item-title>Yönetici Paneli</v-list-item-title></v-list-item-content>
            </v-list-item>

            <v-list-item link to="/account" >
              <v-list-item-icon><v-icon>mdi-account</v-icon></v-list-item-icon>
              <v-list-item-content><v-list-item-title>Hesap Bilgileri</v-list-item-title></v-list-item-content>
            </v-list-item>

            <v-list-item link to="/account/security" >
              <v-list-item-icon><v-icon>mdi-lock</v-icon></v-list-item-icon>
              <v-list-item-content><v-list-item-title>Şifre Değiştir</v-list-item-title></v-list-item-content>
            </v-list-item>

            <v-list-item @click="$auth.logout()" >
              <v-list-item-icon><v-icon>mdi-logout-variant</v-icon></v-list-item-icon>
              <v-list-item-content><v-list-item-title>Çıkış Yap</v-list-item-title></v-list-item-content>
            </v-list-item>
          </v-list>
        </v-menu>

      </v-btn-toggle>
      <v-app-bar-nav-icon class="d-flex d-md-none" @click.stop="openedDraw = !openedDraw"></v-app-bar-nav-icon>
    </v-container>
  </v-app-bar>


  <v-navigation-drawer app fixed right dark temporary v-model="openedDraw" v-if="true">
    <v-list-item>
      <v-list-item-content>
        <v-list-item-title class="text-h6">Emeyz</v-list-item-title>
        <v-list-item-subtitle></v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>
    <v-divider></v-divider>

    <v-list dense nav>
      <v-list-item v-for="(item, index) in $store.getters['navigation/getItems']['mobile-menu']" :key="index" link>
        <v-list-item-icon><v-icon>{{ item.icon || 'mdi-chevron-right' }}</v-icon></v-list-item-icon>
        <v-list-item-content><v-list-item-title>{{ item.title }}</v-list-item-title></v-list-item-content>
      </v-list-item>
    </v-list>
  </v-navigation-drawer>

  <v-dialog v-if="logonDialogStatus" v-model="logonDialogStatus" width="450">
    <Logon popup />
  </v-dialog>

</div>
</template>

<script>
export default {
  name: "TopBar",
  data(){ return {
    openedDraw: false,
    logonDialogStatus: false,
    accountListItems: [

    ]
  } },
  computed: {
    getItems(){
      return this.$store.getters["navigation/getItems"]["header-menu"];
    }
  },
  methods: {
    getToFromUrl: function(urlObj){
      urlObj = JSON.parse(urlObj);
      return urlObj.type==='weburl'? urlObj.to : null ;

    }
  },
}
</script>

<style scoped>
</style>
