<template>

  <v-app style="background-color: rgba(252,252,252,1)">
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
  data () {return {}}
}
</script>

