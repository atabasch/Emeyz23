<template>
  <div class="pa-2 grey lighten-5" v-if="items">
      <v-btn link
             x-small
             elevation="0"
             color="grey lighten-2"
             class="subtitle-2 text-capitalize ma-1 rounded-0"
             :to="'/kategori/'+category.slug"
             :key="category.id"
             v-for="category in items ">{{ category.title }}</v-btn>
  </div>
</template>

<script>
export default {
  name: "PostItemCategories",
  props: {
    items: {
      type: Array,
      default: null
    }
  }
}
</script>

<style scoped>

</style>
