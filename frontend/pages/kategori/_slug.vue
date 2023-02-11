<template>
  <div>
    <v-alert border="bottom" color="info" dark icon="mdi-chevron-right" >
      <h3 class="">{{ $store.getters["post/getCategory"].title }}</h3>
      <p class="ma-0 font-italic grey--text text--lighten-3">{{ $store.getters["post/getCategory"].description  }}</p>
    </v-alert>
    <PostPage :loading="loadingPosts" />
  </div>
</template>

<script>
import PostPage from "@/components/PostPage";
export default {
  name: "PageCategorySlug",
  components: {PostPage},
  data:()=>({
    loadingPosts:true
  }),
  async fetch(){
    this.slug = this.$route.params.slug
    await this.$store.dispatch("post/loadPostsFromCategory", {slug:this.slug});
    if(!this.$store.getters["post/getCategory"].id){
      return this.$router.replace('/');
    }else{
      this.loadingPosts = false;
      await this.$store.dispatch("post/loadTrendsFromCategory", {slug:this.slug})
      this.$store.commit("setHead", {
        title: this.$store.getters["post/getCategory"].title || '',
        description: this.$store.getters["post/getCategory"].description || ''
      })
    }
    return true
  }
}
</script>

<style scoped>

</style>
