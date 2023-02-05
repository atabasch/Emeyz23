<template>
  <v-app-bar app color="blue" dark class="mx-auto my-5" :clipped-right="true" max-width="1300">
    <v-btn-toggle dense tile group dark color="orange lighten-4">
      <v-btn link :to="'/account'" class="text-capitalize subtitle-2"><v-icon>mdi-account</v-icon> {{ $auth.user.username }}</v-btn>
    </v-btn-toggle>
    <v-spacer></v-spacer>

    <v-btn-toggle dense tile group dark color="orange lighten-4">
      <v-btn link :to="'/account/details'" class="text-capitalize subtitle-2"><v-icon>mdi-account-cog</v-icon> Bilgilerimi Düzenle</v-btn>
      <v-btn link :to="'/account/posts'" class="text-capitalize subtitle-2"><v-icon>mdi-text</v-icon> Makalelerim</v-btn>
      <v-btn link :to="'/account/comments'" class="text-capitalize subtitle-2"><v-icon>mdi-comment-text-multiple-outline</v-icon> Yorumlarım</v-btn>
      <v-btn link :to="'/'" class="text-capitalize subtitle-2"><v-icon>mdi-web</v-icon> Emeyz'e Dön</v-btn>
      <v-btn link :to="'/logout'" class="text-capitalize subtitle-2"><v-icon>mdi-logout</v-icon> Çıkış Yap</v-btn>
    </v-btn-toggle>
  </v-app-bar>
</template>

<script>
export default {
  name: "TopBar"
}
</script>

<style scoped>

</style>
