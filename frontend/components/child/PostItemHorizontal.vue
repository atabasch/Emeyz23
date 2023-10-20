<template>
  <div class="d-flex ih-box mb-5 align-start flex-column flex-sm-row">

    <div class="ih-thumb d-flex">
      <NuxtLink :to="$helper.getUrl.post(post.slug)">
        <img :src="$store.state.global.img.sm+post.cover"
             :alt="post.title"
             width="260"
             height="175"
             gradient="to top, rgba(0,0,0, .50), rgba(0,0,0,0)" />
      </NuxtLink>
    </div>

    <div class="ih-body pt-0 pb-4 px-6">
      <PostItemCategories v-if="post.categories"  :items="getCategories || null" />
      <h2 class="ih-title"><router-link :to="$helper.getUrl.post(post.slug)" class=" black-anim-text">{{ post.title }}</router-link></h2>
      <p class="ih-summary d-none d-sm-block">{{ post.summary || post.description }}</p>

      <v-divider class="my-2 d-block d-sm-none"/>
      <div class="ih-info d-flex">
        <span class="ih-date mr-2 d-flex align-end"><v-icon size="20" class="me-1" >mdi-calendar-outline</v-icon> <time v-html="$helper.getDateFormat(post.p_time, 'dateLong')"></time></span>
        <span class="ih-author mr-2 d-flex align-end"><v-icon size="20" class="me-1" >mdi-account-outline</v-icon> <data :value="post.user_fullname">{{ post.user_fullname }}</data></span>
      </div>
<!--      <div class="align-self-end d-none d-sm-flex">-->
<!--        -->
<!--      </div>-->

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
  padding: 10px;
  border-radius: .5rem;
}

.ih-thumb{
  flex: none;
  position: relative;
  overflow: hidden;
  border-radius: 0px;
  max-width: 350px;
}
.ih-thumb img{
  width: 260px;
  height: 175px;
  object-fit: cover;
}
.ih-thumb a{line-height: 1}
.ih-title{
  font-weight: 500;
  font-size: 1.3rem;
  line-height: 1.6rem;
  color: black;
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
  margin-top: 5px;
  font-size: 1rem;
  line-height: 1.4;
  font-weight: 400;
  margin-bottom: 10px;
  color: #565656;
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
