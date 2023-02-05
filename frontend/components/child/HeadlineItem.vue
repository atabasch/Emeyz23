<template>
  <v-card height="100%" link :to="'/'+item.slug" class="rounded-0">
    <v-img
      :src="$store.state.global.img.md+ item.cover"
      class="white--text align-end"
      gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.7)"
      height="100%"
      :alt="item.title"
    >
      <v-card-title >{{ item.title }}</v-card-title>
    </v-img>
  </v-card>
</template>

<script>
export default {
  name: "HeadlineItem",
  props: {
    item: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped>

</style>
