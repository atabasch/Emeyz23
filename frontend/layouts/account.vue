<template>
  <v-app style="background-color: rgba(200,200,200,0.25)">
    <v-main>
      <TopBar />
      <v-container style="max-width: 1300px" class="py-7">
        <v-row v-if="$auth.user">
          <v-col cols="12" sm="5" md="3"><ProfileCard /></v-col>
          <v-col cols="12" sm="7" md="9"><Nuxt /></v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import TopBar from "@/components/account/TopBar"
import ProfileCard from "@/components/account/ProfileCard"
export default {
  name: "account",
  middleware: ['UserAccess'],
  components: {TopBar, ProfileCard}
}
</script>

<style scoped>

</style>
