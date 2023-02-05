<template>
  <div>
    <v-footer color="info">
      <v-container class="py-5 text-center white--text">
        <span>Atabasch &copy; 2019-{{ new Date().getFullYear() }}</span>
      </v-container>
    </v-footer>
    <v-btn v-if="scrollY > 450" color="primary" elevation="5" right bottom fixed fab @click.native="scrollToTop"><v-icon large>mdi-chevron-up</v-icon></v-btn>
  </div>
</template>

<script>
import Logon from "@/components/Logon"
export default {
  name: "Footer",
  components: {Logon},
  data(){
    return {
      scrollY: 0,
      logonPopup: true
    }
  },
  mounted() {
    this.scrollY = window.scrollY;
    window.addEventListener('scroll', this.getTheScrollPosition);
  },
  methods: {
    getTheScrollPosition(){
      this.scrollY = window.scrollY;
    },
    scrollToTop(){
      window.scrollTo(0,0)
    }
  }
}
</script>

<style scoped>

</style>
