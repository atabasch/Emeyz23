<template>
<h1>
  Burası admin paneli
</h1>
</template>

<script>
export default {
  name: "index",
  layout: "panel",
  created() {
    this.$axios.post('/admin').then(({data, status}) => {
      console.log(data)
    }).catch(err => {
      console.log(err)
    })
  }
}
</script>

<style scoped>

</style>
