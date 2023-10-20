<template>
<div>
  <v-app-bar app id="mainTopBar" flat class="mx-auto"  :clipped-right="true">
    <v-container class="pa-0 px-3 fill-height em-container">
      <v-app-bar-nav-icon v-if="false" />
      <v-toolbar-title>
        <router-link to="/"  class="text-decoration-none font-weight-medium"><v-icon>mdi-compass-rose</v-icon> Emeyz</router-link>
      </v-toolbar-title>
      <v-spacer />

      <v-btn-toggle class="mainTopBarNavigation d-none d-md-flex" group>
        <v-btn link :to="getToFromUrl(item.url)" class="topNavItem" :key="item.id" v-for="item in getItems"><v-icon>{{item.icon}}</v-icon> {{item.title}}</v-btn>
        <v-btn link @click="logonDialogStatus = true" class="topNavItem" v-if="!$auth.user" ><v-icon>mdi-login-variant</v-icon> Giriş | Kayıt</v-btn>

        <v-menu offset-y v-if="$auth.user">
          <template v-slot:activator="{ on, attrs }">
            <v-btn link v-bind="attrs" v-on="on" class="topNavItem"><v-icon>mdi-dots-vertical</v-icon> Hesap</v-btn>
          </template>
          <v-list nav dense class="dropdownlist" >
            <v-subheader class="text-capitalize subtitle-2 px-2">İçerikler</v-subheader>
            <v-list-item link to="/account/posts"><v-list-item-icon class="mx-2"><v-icon>mdi-text</v-icon></v-list-item-icon><v-list-item-content><v-list-item-title>Makaleler</v-list-item-title></v-list-item-content></v-list-item>
            <v-list-item link to="/account/comments"><v-list-item-icon class="mx-2"><v-icon>mdi-comment-multiple</v-icon></v-list-item-icon><v-list-item-content><v-list-item-title>Yorumlar</v-list-item-title></v-list-item-content></v-list-item>

            <v-subheader class="text-capitalize subtitle-2 px-2">Hesap</v-subheader>
            <v-list-item v-if="this.$store.getters['loggedInAdmin']" link :to="'/aswpanel'">
              <v-list-item-icon class="mx-2"><v-icon>mdi-account-tie</v-icon></v-list-item-icon>
              <v-list-item-content><v-list-item-title>Yönetici Paneli</v-list-item-title></v-list-item-content>
            </v-list-item>

            <v-list-item link to="/account" >
              <v-list-item-icon class="mx-2"><v-icon>mdi-account</v-icon></v-list-item-icon>
              <v-list-item-content><v-list-item-title>Hesap Bilgileri</v-list-item-title></v-list-item-content>
            </v-list-item>

            <v-list-item link to="/account/security" >
              <v-list-item-icon class="mx-2"><v-icon>mdi-lock</v-icon></v-list-item-icon>
              <v-list-item-content><v-list-item-title>Şifre Değiştir</v-list-item-title></v-list-item-content>
            </v-list-item>

            <v-list-item @click="$auth.logout()" >
              <v-list-item-icon class="mx-2"><v-icon>mdi-logout-variant</v-icon></v-list-item-icon>
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
      <v-list-item v-for="(item, index) in $store.getters['navigation/getItems']['mobile-menu']" :key="index" link :to="item.url">
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
#mainTopBar,
.dropdownlist{
  background: #1D5D9B;
}

#mainTopBar,
#mainTopBar a,
.topNavItem,
i,
.dropdownlist,
.dropdownlist > *{
  color: white !important;
  letter-spacing: 0.3px;
  font-weight: 400;
}

.mainTopBarNavigation a:hover,
.topNavItem:hover,
.topNavItem:focus{
  background: #1D242B !important;
  border: none;
  //color: #1D242B !important;
}

#mainTopBar{
  //background: #1D242B;
  margin: 0 auto;
  padding: 0;
}

.topNavItem{
  text-transform: capitalize;
  font-weight: 500;
  letter-spacing: 0.3px;
  font-size: 1rem;
}
.topNavItem:hover{
  color: #03a9f4;
  background-color: transparent;
}
.dropdownlist{
  color: white;
}
</style>
