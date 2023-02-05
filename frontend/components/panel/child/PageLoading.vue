<template>
<v-responsive :aspect-ratio="4/3" class="align-center text-center">
  <v-progress-circular
    :size="48"
    color="primary"
    indeterminate
  ></v-progress-circular>
  <v-progress-linear indeterminate style="max-width: 400px" class="my-7 mx-auto"></v-progress-linear>
  <h4>Yükleniyor...</h4>
</v-responsive>
</template>

<script>
export default {
  name: "PageLoading",
  props: {
    value: {
      type: Number,
      default: 0
    }
  }
}
</script>

<style scoped>

</style>
