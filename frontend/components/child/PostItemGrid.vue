<template>
<v-col cols="12" sm="6" xl="4" class="pa-2">
  <div class="white fill-height">
    <router-link :to="$helper.getUrl.post(post.slug)" class="fill-height">
    <v-img :src="$const.url.api.media.upload.sm+post.cover" :aspect-ratio="3/2">
      <div class="coverContent pa-2">
        <PostItemCategories v-if="post.categories"  :items="getCategories || null" />
      </div>
    </v-img>
    </router-link>
    <div class="pa-3">
      <div class="infobox">
        <div><v-icon dense size="16">mdi-clock-outline</v-icon> <span v-html="$helper.getDateFormat(post.p_time, 'dateMedium')"></span></div>
        <div><v-icon dense size="16">mdi-account</v-icon> <span v-html="post.user_name"></span></div>
      </div>
      <h3 class="title pa-0 ma-0"><router-link :to="$helper.getUrl.post(post.slug)">{{ post.title }}</router-link></h3>
      <p class="summary">{{ post.summary || post.description }}</p>
    </div>
  </div>
</v-col>
</template>

<script>
import PostItemCategories from "@/components/child/PostItemCategories";
export default {
  name: "PostItemGrid",
  components: {PostItemCategories},
  props: {
    post: {type: Object, default: () => ({})}
  },
  computed: {
    getCategories(){
      return [JSON.parse(this.post.categories).sort( () => 0.5 - Math.random() )[0]];
    }
  }
}
</script>

<style scoped>
.coverContent{
  position: absolute;
  left: 0; top:0;
  width: 100%; height: 100%;
  background-color: rgba(0,0,0,0.3);
}
.coverContent:hover{
  background-color: rgba(0,0,0,0.2);
}
.infobox{
  color: rgb(150, 150, 150);
  display: flex;
}
.infobox > div{
  display: flex;
  justify-content: flex-start;
  align-items: center;
  align-content: center;
  font-size: 14px;
}
.infobox > div > *{ margin-right: 2px }
h3.title a{
  display: block;
  max-height: 102px;
  overflow: hidden;
  font-size: 24px;
  font-weight: 600;
  text-decoration: none;
  color: black;
  line-height: 34px;
}
h3.title a:hover{
  color: dodgerblue;
}
p.summary{
  color: rgb(100, 100, 100);
  font-size: 16px;
  line-height: 26px;
  max-height: 78px;
  display: block;
  overflow: hidden;
}
</style>
