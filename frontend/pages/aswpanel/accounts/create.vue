<template>
  <v-sheet>

    <AdminContentHead title="Yeni Hesap Oluştur"
                 description="Emeyz için yeni bir kullanıcı hesabı oluştur."
                 :button="{ text:'Hesap Listesi', icon:'mdi-user', to:$store.state.global.url.panel+'accounts'  }" />

    <UserForm />

  </v-sheet>
</template>

<script>
import UserForm from "@/components/panel/UserForm";
export default {
  name: "create",
  layout: "panel",
  components: {UserForm}
}
</script>

<style scoped>

</style>
