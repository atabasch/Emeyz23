<template>
  <div>
    <v-container>
    <HeadLines />
    </v-container>


    <v-container v-if="getCategories.length > 0" fluid class="my-10 py-16" style="background: url('/images/catbg.svg') no-repeat center; background-size: cover; ">
      <v-container>
        <CarouselCategory :items="getCategories"/>
      </v-container>
    </v-container>


    <v-container>
    <PostPage />
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
      categories: []
    }
  },
  computed: {
    getCategories: function(){
      return this.categories.sort((a, b) => 0.5 - Math.random()).slice(0, 8)
    }
  },
  async fetch() {
    await this.$store.dispatch('post/loadTrends')
    await this.$store.dispatch('post/loadHeadlines')
    await this.$store.dispatch("post/loadPosts", {loadMore:false});
    await this.$store.commit("resetHead");
    return this.$axios.get("/category").then(({status, data})=>{
      if(status===200){
        this.categories = data;
      }
    }).catch(err => {
      console.log(err)
    })
  },

}
</script>
