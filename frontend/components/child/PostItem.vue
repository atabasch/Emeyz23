<template>
  <v-card class="pa-3 mb-7 elevation-1 rounded-0">
    <v-skeleton-loader v-if="!post"
      v-bind="{ class:'mb-5' }"
      type="image, article, divider, table-row"
    ></v-skeleton-loader>

    <div v-else>
      <router-link :to="'/'+post.slug">
        <v-img :src="$store.state.global.img.md+post.cover" alt="post.title">

        </v-img>
      </router-link>

      <v-hover v-slot="{hover}">
        <h1 class="text-h4 my-2" :title="post.title">
          <router-link class="text-decoration-none  font-weight-medium black-anim-text"
                       :to="'/'+post.slug">{{ post.title }}</router-link>
        </h1>
      </v-hover>

      <p class="body-2 grey--text text--darken-2">{{ post.summary || post.description }}</p>
      <v-divider v-if="post.categories" />
      <PostItemCategories v-if="post.categories" :items="getCategories || null" />
    </div>
  </v-card>
</template>

<script>
import PostItemCategories from "@/components/child/PostItemCategories";
export default {
  name: "PostItem",
  components: {PostItemCategories},
  props: {
    post: Object
  },

  computed: {
    getCategories(){
      return JSON.parse(this.post.categories);
    }
  }
}
</script>

<style scoped>

</style>
