<template>
  <div>
    <ContentHead title="Medya Dosyaları" description="Sunucuda bulunan medya dosyaları" />
    <MediaList />
  </div>
</template>

<script>
import MediaList from "@/components/panel/MediaList";
import ContentHead from "@/components/panel/ContentHead";
export default {
  name: "index",
  components: {ContentHead, MediaList},
  layout: "panel"
}
</script>

<style scoped>

</style>
