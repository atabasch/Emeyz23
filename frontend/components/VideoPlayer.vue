<template>
<div v-if="source && getVideoId">
  <v-responsive :aspect-ratio="16/9">
    <div v-html="getCode"></div>
  </v-responsive>

</div>
</template>

<script>
export default {
  name: "VideoPlayer",
  props: {
    source: {
      type: String,
      default: null
    }
  },
  computed: {
    getVideoId(){
      return this.$helper.getYoutubeId(this.source);
    },

    getCode(){
      return `<iframe
                src="https://www.youtube-nocookie.com/embed/${this.getVideoId}"
                title="YouTube video player"
                frameborder="0"
                style="width: 100%; height: 100%; position: absolute"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
    }
  },

}
</script>

<style scoped>
</style>
