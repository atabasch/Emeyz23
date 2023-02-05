<template>
  <v-sheet>
    <v-app-bar
      fixed
      app
      dark
      color="primary" >
<!--      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />-->
      <v-btn icon @click.stop="miniVariant = !miniVariant">
        <v-icon>mdi-{{ `chevron-${miniVariant ? 'right' : 'left'}` }}</v-icon>
      </v-btn>

      <v-toolbar-title>Emeyz Panel</v-toolbar-title>
      <v-spacer />

    </v-app-bar>
    <Sidebar :mini-variant="miniVariant" />

  </v-sheet>
</template>

<script>
import Sidebar from "@/components/panel/Sidebar";
export default {
  name: "AppBar",
  components: {Sidebar},
  data(){
    return {
      miniVariant: false
    }
  }
}
</script>

<style scoped>

</style>
