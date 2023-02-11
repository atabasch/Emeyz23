<template>
  <div>
    <template v-if="getPosts.length < 1">
      <v-alert color="info" dark tile type="info">Bu alana henüz hiç içerik eklenmemiş. İlk makaleyi sen eklemeye ne dersin?</v-alert>
    </template>
    <template v-else>
      <div class="mb-3">
        <template v-if="$store.getters['getItemListingStyle']!='grid'">
          <PostItemHorizontal v-for="(post, index) in getPosts" :key="post.id" :post="post"  />
        </template>
        <v-row v-if="$store.getters['getItemListingStyle']=='grid'" class="px-1" >
          <PostItemGrid v-for="(post, index) in getPosts" :key="post.id" :post="post"  />
        </v-row>
      </div>

      <div class="d-flex flex-row get-more">
        <div class="line flex-grow-1"></div>
        <button v-if="!$store.getters['post/getLoadMoreEnd'] && getPosts.length > 9"
                @click.stop="$store.dispatch('post/loadPosts', {loadMore: true})">Daha fazla içerik getir</button>
        <div class="line  flex-grow-1"></div>
      </div>
    </template>

  </div>
</template>

<script>
import PostItem from "@/components/child/PostImageItem";
import PostItemGrid from "@/components/child/PostItemGrid";
import PostItemHorizontal from "@/components/child/PostItemHorizontal";
export default {
  name: "PostList",
  components: {PostItem, PostItemHorizontal, PostItemGrid},
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

.get-more .line{position: relative}
.get-more .line::before{
  content: "";
  display: block;
  height: 1px;
  width: 100%;
  background-color: #E0E0E0;
  position: absolute;
  top:50%;
  margin-top: -1px;
}
.get-more button{
  border: 1px solid #E0E0E0;
  font-size: 18px;
  line-height: 40px;
  height: 40px;
  font-weight: 400;
  font-style: italic;
  padding: 0px 25px;
  border-radius: 10px;
  background-color: rgba(255, 255, 255, 1);
}
.get-more button:hover{
  background-color: rgba(225, 225, 225, 1);
}
</style>
