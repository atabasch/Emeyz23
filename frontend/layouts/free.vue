<template>

  <v-app>
    <TopBar />
    <v-main>
      <v-container fluid>
        <button id="install-pwa-app" class="v-btn blue white--text" style="display: none" >Uygulamayı Yükle</button>
        <Nuxt />
      </v-container>
    </v-main>

    <Footer />
  </v-app>

</template>

<script>
import TopBar from "~/components/TopBar";
import Footer from "~/components/Footer";
export default {
  name: 'FreeLayout',
  scrollToTop: true,
  components: {
    TopBar,
    Footer
  },
  head(){
    return this.$store.getters["getHead"]
  },
  data () {return {}},
  mounted() {
    if(process.client){
      if(window.innerWidth <= 600){
        this.$store.commit('setItemListingStyle', 'grid');
      }else{
        this.$store.commit('setItemListingStyle', localStorage.getItem('setItemListingStyle') || 'list');
      }
    }
  }
}
</script>

