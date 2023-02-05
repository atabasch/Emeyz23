<template>
 <div>
   <v-card class="pa-3 mb-3"  min-height="350">
     <h6 class="text-h6">Makalelerim</h6><v-divider />
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
     <v-btn v-if="posts.length > 5" block tile dark link color="blue"  class="my-3 text-capitalize" to="/account/posts">Tümünü Gör</v-btn>
   </v-card>


   <v-card class="pa-3"  min-height="300">
     <h6 class="text-h6">Yorumlarım</h6><v-divider />
     <v-simple-table>
       <template v-slot:default>
         <thead>
         <tr>
           <th>İçerik</th>
           <th>Yorum</th>
           <th>Tarih</th>
         </tr>
         </thead>
         <tbody>
         <tr v-if="comments.length<1"><td colspan="4" class="subtitle-2 grey--text">Emeyz içeriklerinde size ait hiç yorum yok.</td></tr>
         <tr v-else v-for="(comment, index) in comments" :key="index">
           <td>
             <h6 class="subtitle-2">{{ comment.title }}</h6>
           </td>
           <td>{{ comment.views }}</td>
           <td>comment</td>
         </tr>

         </tbody>
       </template>
     </v-simple-table>
     <v-btn v-if="comments.length > 5" block tile dark link color="blue"  class="my-3 text-capitalize" to="/account/comments">Tümünü Gör</v-btn>
   </v-card>
 </div>
</template>

<script>
export default {
  name: "index",
  layout: 'account',
  data(){return {
    posts: [],
    comments: []
  }},
  fetch(){
    return this.$axios.get('/account/me?articles=0,6&comments=0,6').then(({data, status}) => {
      if(status===200 && data.success && data.user){
        this.posts = data.posts
      }
    }).catch(err => {
      console.log(err)
    })
  },
  methods: {

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
