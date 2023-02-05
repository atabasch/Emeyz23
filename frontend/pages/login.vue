<template>
  <div id="wrapper">
    <v-img id="bgWallpaper" src="/wallpaper.jpg" eager></v-img>
    <div id="box">
      <Logon :component-name="componentName" :check-data="checkData" />
    </div>
  </div>
</template>

<script>
import Logon from "@/components/Logon"
export default {
  name: "login",
  layout: 'logon',
  head: {
    title: 'Yönetici Giriş Alanı'
  },
  components:{Logon},
  data: ()=>({
    componentName: 'login',
    checkData: null
  }),
  async fetch(){
    if(this.$route.query.email){
      this.componentName = 'activation'
      this.checkData = {
        email: this.$route.query.email,
        code: this.$route.query.code || null
      }
    }
  },
}
</script>


<style scoped>
#wrapper{

}

#box{
  width: 100%;
  max-width: 450px;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translateX(-50%) translateY(-50%);
}

#bgWallpaper{
  max-width: 110%;
  position: fixed;
  top:-20px;
  left: -20px;
  bottom:-20px;
  right: -20px;
  filter: blur(7px);
  -webkit-filter: blur(7px);

}
</style>
