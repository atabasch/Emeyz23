<template>



</template>

<script>
export default {
  name: "NavigationDrawerMobile",
  props: {
    opened: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      items: [
        { title: 'Dashboard', icon: 'mdi-view-dashboard' },
        { title: 'Photos', icon: 'mdi-image' },
        { title: 'About', icon: 'mdi-help-box' },
      ],
      right: null,
    }
  },
}
</script>

<style scoped>

</style>
