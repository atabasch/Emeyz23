<template>
  <v-sheet>

    <AdminContentHead title="Hesap Düzenle"
                      description="Emeyz için yeni bir kullanıcı hesabı oluştur."
                      :button="{ text:'Hesap Listesi', icon:'mdi-user', to:$store.state.global.url.panel+'accounts'  }" />
    <UserForm v-if="user" :user="user" :update="true"/>

  </v-sheet>
</template>

<script>
import UserForm from "@/components/panel/UserForm";
export default {
  name: "edit",
  layout: "panel",
  components: {UserForm},
  data: ()=>({
    user: null
  }),

  created() {
    let id = this.$route.params.id || null
    if(!id){
      this.$router.replace(this.$const.url.panel)
    }else{
      this.$axios.get('/admin/account/'+id).then(({status, data}) => {
        if(status===200 && data.success && data.user){
          this.user = {...data.user, password: ''}
        }else{
          this.$router.replace(this.$const.url.panel)
        }
      }).catch(err => {
        this.$toast.error('Beklenmedik bir hata oluştu');
        this.$router.replace(this.$const.url.panel)
      })
    }
  },
}
</script>

<style scoped>

</style>
