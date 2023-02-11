<template>
  <v-row justify="center">
    <v-col cols="12" md="8" v-if="!getPage">
      <v-skeleton-loader v-bind="{ class:'mb-5 white' }"  min-height="650px"
                         type="image, article, divider, table-row"
      ></v-skeleton-loader>
    </v-col>

    <v-col v-else cols="12" md="8">
      <v-card color="white" class="pa-4 mb-6">
        <BoxTitle :title="getPage.title" tag="h1" single></BoxTitle>

        <v-img v-if="getPage.cover" :src="$store.state.global.img.lg+getPage.cover" class="my-3" />

        <v-card-text v-html="getPage.content" class="content font-weight-regular black--text darken-1"></v-card-text>
      </v-card>

    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "PagePostDetail",
  data:()=>({
    page: {}
  }),
  fetch(){
    let pageID = this.$route.params.slug;
    if(!pageID){
      return this.$router.replace('/');
    }else {
      return this.$axios.get('/page/'+pageID).then(response => {
        if(response.status === 200 && response.data.success){
          this.page = response.data.page
        }else{
          return this.$router.replace('/');
        }
      }).catch(err => {
        return this.$router.replace('/');
      });
    }
  },
  computed: {
    getPage(){
      return this.page;
    }
  }
}
</script>

<style scoped>
.content{
  font-size: 18px;
  line-height: 30px;
}
</style>
