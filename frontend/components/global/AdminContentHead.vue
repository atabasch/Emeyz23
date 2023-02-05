<template>
  <v-row>
    <v-col>
      <h1 class="text-h4 font-weight-bold">{{ title }}</h1>
      <small class="subtitle-1 font-italic grey--text" v-if="description">{{ description }}</small>
    </v-col>
    <v-col cols="2" align-self="center" v-if="button">
      <v-btn color="primary" right class="text-capitalize float-end" link :to="button.to"><v-icon v-if="button.icon">{{ button.icon }}</v-icon> {{ button.text }}</v-btn>
    </v-col>
    <v-col cols="12" class="my-0 py-0 mb-5">
      <v-divider></v-divider>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "ContentHead",
  props: {
    title: {
      type: String,
      required: true
    },
    description: {
      type: String,
      required: false,
      default: false
    },
    button: {
      type: Object,
      required: false,
      default: null
    }
  }
}
</script>

<style scoped>

</style>
