<template>
  <div class="d-flex ih-box mb-5 align-stretch flex-column flex-sm-row">
    <div class="ih-thumb d-flex">
      <router-link :to="$helper.getUrl.post(post.slug)">
        <v-img :src="$store.state.global.img.sm+post.cover"  :alt="post.title" gradient="to top, rgba(0,0,0, .50), rgba(0,0,0,0)"></v-img>
      </router-link>
    </div>
    <div class="ih-body py-4 px-6">

      <div class="ih-info d-flex">
        <span class="ih-date mr-2"><v-icon size="20" color="red">mdi-calendar-outline</v-icon> <time v-html="$helper.getDateFormat(post.p_time, 'dateLong')"></time></span>
        <span class="ih-author mr-2"><v-icon size="20" color="red">mdi-account-outline</v-icon> <data :value="post.user_fullname">{{ post.user_fullname }}</data></span>
      </div>
      <v-divider class="my-2"/>
      <h2 class="ih-title"><router-link :to="$helper.getUrl.post(post.slug)" class=" black-anim-text">{{ post.title }}</router-link></h2>
      <p class="ih-summary d-none d-sm-block">{{ post.summary || post.description }}</p>

      <v-divider class="my-2 d-block d-sm-none"/>
      <div class="align-self-end d-none d-sm-flex">
        <PostItemCategories v-if="post.categories"  :items="getCategories || null" />
      </div>

    </div>
  </div>
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
.ih-box{
  background-color: white;
  border: 1px solid #F5F5F5;
}
.ih-box:hover .ih-thumb img{
  transform: scale(1.2);
}
.ih-thumb{
  flex: none;
  position: relative;
  overflow: hidden;
  height: auto;
  border-radius: 0px;
  max-width: 320px;
}
.ih-thumb img{
  transition-duration: 500ms;
  transform: scale(1);
  max-width: 100%;
  height: auto;
  border-radius: 0px;
}
.ih-title{
  font-size: 1.6rem;
}
.ih-info{
  color: rgb(115, 115, 115);

}
.ih-info > *{
  font-size: 14px;
  line-height: 18px;
  letter-spacing: 0.3px;
}
.ih-summary{
  margin-top: 8px;
  font-size: 16px;
  line-height: 24px;
  font-weight: 400;
}


@media screen and (max-width: 1264px) {
  .ih-thumb{ max-width: 260px; }
  .ih-title{ font-size: 1.3rem; }
}

@media screen and (max-width: 600px) {
  .ih-thumb{ max-width: 100%; }
  .ih-title{ font-size: 1.1rem; }
}


</style>
