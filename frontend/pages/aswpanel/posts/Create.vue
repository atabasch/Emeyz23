<template>
<div>
  <ContentHead title="Yeni Makale Oluştur"
               description="Blog için yeni bir makale yaz"
               />

  <PostForm />
</div>
</template>

<script>
import ContentHead from "@/components/panel/ContentHead";
import PostForm from "@/components/panel/PostForm";
export default {
  name: "Create",
  components: {ContentHead, PostForm},
  layout: "panel",
}
</script>

<style scoped>
</style>
