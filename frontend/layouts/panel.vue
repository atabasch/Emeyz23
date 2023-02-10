<template>
  <v-app >
    <AppBar/>

    <v-main>
      <v-container fluid>
        <Nuxt />
      </v-container>
    </v-main>


  </v-app>
</template>

<script>
import AppBar from "@/components/panel/AppBar";
export default {
  name: "panel",
  components: {AppBar},
  middleware: ['AdminAccess'],
  head(){
    return {
      meta: [
        { hid: 'robots', name: 'robots', content: 'noindex' }
      ]
    }
  }
}
</script>

<style>
</style>
