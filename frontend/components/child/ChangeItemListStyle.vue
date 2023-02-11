<template>
  <div>
    <v-btn-toggle dense borderless :value="$store.getters['getItemListingStyle']">
      <v-btn @click="changeStyle('list')" value="list"><v-icon>mdi-view-list</v-icon></v-btn>
      <v-btn @click="changeStyle('grid')" value="grid"><v-icon>mdi-view-grid</v-icon></v-btn>
    </v-btn-toggle>
  </div>
</template>

<script>
export default {
  name: "ChangeItemListStyle",
  methods: {

    changeStyle(style){
      if(process.client){
        this.$store.commit('setItemListingStyle', style)
      }
    }

  }
}
</script>

<style scoped>

</style>
