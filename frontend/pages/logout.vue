<template>

</template>

<script>
export default {
  name: "logout",
  async created() {
    await this.$auth.logout();
    this.$router.replace('/');
  }
}
</script>

<style scoped>

</style>
