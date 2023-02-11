<template>
  <div>
    <v-container fluid class="mt-n7">
    <HeadLines />
    </v-container>

    <v-container>
      <CarouselCategory/>
      <PostPage :loading="postLoading" class="mt-3" />
    </v-container>
  </div>
</template>

<script>
import PostPage from "@/components/PostPage";
import HeadLines from "@/components/HeadLines";
import CarouselCategory from "@/components/CarouselCategory";
export default {
  name: 'IndexPage',
  layout: 'free',
  components: {PostPage, HeadLines, CarouselCategory},
  data: function(){
    return {
      postLoading: true,
    }
  },
  async fetch() {
    await this.$store.dispatch('post/loadTrends')
    await this.$store.dispatch('post/loadHeadlines')
    await this.$store.dispatch("post/loadPosts", {loadMore:false});
    this.postLoading = false;
    await this.$store.commit("resetHead");
    return true;
  },

}
</script>
