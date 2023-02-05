<template>
  <v-card class="mb-7 elevation-1 rounded-0">
    <v-row>
      <v-col cols="12" sm="4" md="12" lg="4" class="py-0">
        <router-link :to="'/'+post.slug">
          <v-img :src="$store.state.global.img.md+post.cover" aspect-ratio="1.15" />
        </router-link>
      </v-col>

      <v-col class="px-6 px-sm-0 px-md-6 px-lg-0 py-0">
        <v-hover v-slot="{hover}">
          <h3 class="text-h5 my-2">
            <router-link class="text-decoration-none  font-weight-medium black-anim-text"
                         :to="'/'+post.slug">{{ post.title }}</router-link>
          </h3>
        </v-hover>

        <p class="body-2 grey--text text--darken-2">{{ post.summary || post.description }}</p>
      </v-col>

      <v-col cols="12" class="py-0">
        <v-divider v-if="post.categories" />
        <PostItemCategories v-if="post.categories"  :items="getCategories || null" />
      </v-col>
    </v-row>
  </v-card>
</template>

<script>
import PostItemCategories from "@/components/child/PostItemCategories";
export default {
  name: "PostItemHorizontal",
  components: {PostItemCategories},
  props: {
    post: {
      type: Object,
      default: {}
    }
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
