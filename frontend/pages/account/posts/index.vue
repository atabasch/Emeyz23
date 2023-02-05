<template>
  <div class="mt-n3">
    <v-card class="pa-3 mb-3"  min-height="350">
      <div class="pb-2 text-right justify-space-between d-flex">
        <v-card-title>Makalelerim</v-card-title>
        <v-btn color="primary" class="text-capitalize subtitle-2 mt-4" small right tile link to="/account/posts/create"><v-icon>mdi-plus</v-icon> Yeni Makale Oluştur</v-btn>
      </div>
      <v-divider />
      <v-simple-table>
        <template v-slot:default>
          <thead>
          <tr>
            <th></th>
            <th>Başlık</th>
            <th>Görüntülenme</th>
            <th>Durum</th>
          </tr>
          </thead>
          <tbody>
          <tr v-if="posts.length<1"><td colspan="4" class="subtitle-2 grey--text">Emeyz içeriklerinde size ait hiç makale yok. Hemen bir tane yazın</td></tr>
          <tr v-else v-for="(post, index) in posts" :key="index">
            <td class="pa-0">
              <v-img :src="getCoverUrl(post.cover)" :aspect-ratio="4/3"  width="75" />
            </td>
            <td>
              <h6 class="subtitle-2">{{ post.title }}</h6>
            </td>
            <td>{{ post.views }}</td>
            <td>{{ $const.post.statuses[post.status].title }}</td>
          </tr>

          </tbody>
        </template>
      </v-simple-table>
      <v-btn v-if="posts.length > 5" block tile dark link color="blue"  class="my-3 text-capitalize" to="/account/posts">Daha Fazla</v-btn>
    </v-card>
  </div>
</template>

<script>
export default {
  name: "index",
  layout: 'account',
  data(){return {
    postItems: [],
    comments: []
  }},
  fetch(){
    return this.fillItems();
  },
  computed: {

    posts: function(){ return this.postItems },

  },
  methods: {

    fillItems(){
      return this.$axios.get('/account/me?articles=0,15').then(({data, status}) => {
        if(status===200 && data.success && data.user){
          this.postItems = data.posts
        }
      }).catch(err => {
        console.log(err)
      })
    },

    getCoverUrl(cover){
      if(!cover){
        return 'https://media.emeyz.com/emeyz-800.png';
      }else{
        return 'https://media.emeyz.com/upload/sm_'+cover;
      }
    }

  }
}
</script>

<style scoped>

</style>
