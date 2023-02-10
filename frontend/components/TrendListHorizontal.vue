<template>
<div>
  <BoxTitle tag="h5" title="Popüler" icon="mdi-compass-outline" />

  <div class="d-flex tl-box mb-5 align-start" v-for="(post, index) in getItems" :key="index">
      <div class="tl-thumb d-flex">
        <router-link :to="'/'+post.slug"><v-img :src="$store.state.global.img.sm+post.cover" width="100px" height="85px" class="rounded" :alt="post.title"></v-img></router-link>
      </div>
      <div class="tl-body py-1 px-2">
        <div class="tl-info d-flex">
          <span class="tl-date mr-2"><v-icon size="16" color="red">mdi-clock-outline</v-icon> <time v-html="$helper.getDateFormat(post.p_time, 'dateLong')"></time></span>
        </div>
        <h4 class="tl-title"><router-link :to="'/'+post.slug" class=" black-anim-text">{{ post.title }}</router-link></h4>
      </div>
  </div>


</div>
</template>

<script>
export default {
  name: "TrendListHorizontal",
  computed: {
    getItems(){
      return this.$store.getters["post/getTrends"];
    }
  }
}
</script>

<style scoped>
.tl-thumb{
  flex: none;
  position: relative;
  overflow: hidden;
  height: auto;
  border-radius: 0px;
}
.tl-thumb img{
  max-width: 100%;
  height: auto;
  border-radius: 0px;
}
.tl-info > *{
  font-size: 15px;
  color: #444444;
  line-height: 22px;
}
.tl-title{
  font-size: 16px;
  line-height: 26px;
  display: block;
  height: 52px;
  overflow: hidden;
}
@media screen and (max-width: 1264px) {
  .tl-thumb{ max-width: 260px; }
}
</style>
