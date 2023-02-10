<template>
  <div class="d-flex flex-column ih-box mb-5 align-stretch">


      <v-img :src="$store.state.global.img.lg+post.cover" :aspect-ratio="16/9" :alt="post.title" gradient="to top, rgba(0,0,0, 1), rgba(0,0,0, .75), rgba(0,0,0, .0), rgba(0,0,0,0)">


        <div class="ih-body py-4 px-2 d-flex flex-column justify-end fill-height">

          <div class="ih-info d-flex">
            <span class="ih-date mr-2"><v-icon size="20" color="red">mdi-calendar-outline</v-icon> <time v-html="$helper.getDateFormat(post.p_time, 'dateLong')"></time></span>
            <span class="ih-author mr-2"><v-icon size="20" color="red">mdi-account-outline</v-icon> <data :value="post.user_fullname">{{ post.user_fullname }}</data></span>
          </div>
          <h2 class="ih-title text-h4 mt-2 font-weight-bold"><router-link :to="'/'+post.slug" class="anim-text white-anim-text">{{ post.title }}</router-link></h2>
          <p class="ih-summary">{{ post.summary || post.description }}</p>

          <div class=" d-flex">
            <PostItemCategories v-if="post.categories"  :items="getCategories || null" />
          </div>

        </div>


      </v-img>
  </div>
</template>

<script>
import PostItemCategories from "@/components/child/PostItemCategories";
export default {
  name: "PostImageItem",
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
.ih-box{
  background-color: white;
  border: 1px solid #F5F5F5;
  color: white !important;
}
.ih-box:hover .ih-thumb img{
  transform: scale(1.2);
}

.ih-info{
  color: rgba(255, 255, 255, .65);

}
.ih-info > *{
  font-size: 14px;
  line-height: 18px;
  letter-spacing: 0.3px;
}
.ih-summary{
  color: rgba(255, 255, 255, .65);
  margin-top: 8px;
  font-size: 16px;
  line-height: 24px;
  font-weight: 400;
}


</style>
