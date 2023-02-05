<template>
  <div>
    <template v-for="(post, index) in getPosts">
      <PostItem v-if="index<1" :key="post.id" :post="post"  />
      <PostItemHorizontal v-else :key="post.id" :post="post"  />
    </template>

    <v-btn
      v-if="!$store.getters['post/getLoadMoreEnd'] && getPosts.length > 9"
      block
      color="primary"
      outlined
      plain
      text
      class="text-capitalize"
      @click.stop="$store.dispatch('post/loadPosts', {loadMore: true})">Daha fazla içerik getir</v-btn>

  </div>
</template>

<script>
import PostItem from "@/components/child/PostItem";
import PostItemHorizontal from "@/components/child/PostItemHorizontal";
export default {
  name: "PostList",
  components: {PostItem, PostItemHorizontal},
  created() {
    this.$store.commit('post/setLoadMoreEnd', false);
  },
  computed: {
    getPosts(){
      return this.$store.getters["post/getPosts"];
    }
  }
}
</script>

<style scoped>

</style>
