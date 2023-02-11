<template>
  <div v-if="items">
      <v-btn link
             small
             elevation="0"
             dark
             :color="category.color"
             class="item text-capitalize"
             :to="$helper.getUrl.category(category.slug)"
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
.item{
  font-weight: 300;
  margin:0px 5px 3px 0px;
}
</style>
