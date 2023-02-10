<template>

</template>

<script>
export default {
  name: "index",
  layout: "panel",
  created() {
    return this.rotate();
  },
  fetch(){
    return this.rotate();
  },
  methods: {
    rotate(){
      this.$router.replace(this.$const.url.panel+'settings/general')
    }
  }
}
</script>

<style scoped>

</style>
