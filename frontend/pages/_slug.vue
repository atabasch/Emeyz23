<template>
<v-row>
  <v-col cols="12" md="8" v-if="!getPost">
    <v-skeleton-loader v-bind="{ class:'mb-5 white' }"  min-height="650px"
                       type="image, article, divider, table-row"
    ></v-skeleton-loader>
  </v-col>

  <v-col v-else cols="12" md="8">
  <v-card color="white" class="pa-4 mb-6">
    <BoxTitle :title="getPost.title" tag="h1" single></BoxTitle>
    <div class="post-info">
      <v-icon size="22">mdi-calendar-edit</v-icon> <strong>{{ getDate }}</strong> tarihinde yazıldı
      <v-icon size="22">mdi-eye</v-icon> <strong>{{ getPost.views }}</strong> kez okundu.
    </div>

    <v-img :src="$store.state.global.img.lg+getPost.cover" class="my-3" />

    <v-divider />
    <v-card-text v-html="getPost.content" class="text-body-1 text-md-h6 font-weight-regular black--text darken-1"></v-card-text>

    <PostItemCategories v-if="getPost.categories"  :items="getPost.categories || null" />
  </v-card>

    <template v-if="getPost.contentItems">
      <v-card color="white" class="pa-4 mb-6" v-for="(item, index) in getPost.contentItems" :key="item.id">
        <BoxTitle :title="item.title" tag="h2" single></BoxTitle>
        <v-img :src="$store.state.global.img.lg+item.cover" v-if="item.cover" aspect-ratio="16/9" class="my-3" />
        <v-divider />
        <v-card-text v-html="item.content" class="text-h6 font-weight-regular black--text darken-1"></v-card-text>
      </v-card>
    </template>
  </v-col>


  <v-col cols="4" class="hidden-sm-and-down">
    <MainSidebar />
  </v-col>

</v-row>
</template>

<script>
import {mysqlToDate} from "@/helpers/date";
import PostItemCategories from "@/components/child/PostItemCategories";
import MainSidebar from "@/components/MainSidebar";

export default {
  name: "PagePostSlug",
  components: {PostItemCategories, MainSidebar},
  data(){ return {
    post: null,
    updateMethodForViews: null
  } },
  async fetch(){
    let response = await this.$axios.get('/post/'+this.$route.params.slug);
    if(response.status===200){
      this.post = response.data;
    }
    await this.$store.commit("setHead", {
      title: this.post.title || '',
      description: this.post.description || ''
    })
  },
  created() {
    if(process.client){
      window.scrollTo(0, 0);
      this.updateMethodForViews = setTimeout(this.updateViews, 7000);
    }
  },
  computed: {
    getPost(){
      return this.post
    },
    getDate(){
      if(this.getPost.p_time){
        let date = mysqlToDate(this.getPost.p_time)
        return `${date.day} ${date.monthName} ${date.fullYear}`
      }else{
        return '';
      }
    }
  },
  methods: {
    updateViews: function(){
      this.$axios.post('/post/views', {id:this.getPost.id, value:1}).then(({status, data}) => {})
    }
  },

  destroyed() {
    clearTimeout(this.updateMethodForViews);
    this.updateMethodForViews = false;
  }


}
</script>

<style scoped>
.post-info{
  font-size: 1rem;
  font-weight: 300;
}
</style>
