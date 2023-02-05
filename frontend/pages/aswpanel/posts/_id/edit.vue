<template>
  <div>
    <ContentHead title="Makale Düzenle"
                 description="Blog için yeni bir makale yaz"
    />

    <PostForm :post="post" :update="true"/>
  </div>
</template>

<script>
import ContentHead from "@/components/panel/ContentHead";
import PostForm from "@/components/panel/PostForm";
export default {
  name: "Edit",
  components: {ContentHead, PostForm},
  layout: "panel",
  data(){
    return {
      post: {}
    }
  },
  async fetch() {
    let id = this.$route.params.id || false
    if(!id){
      await this.$router.replace(this.$const.url.panel+'posts')
    }else{
      let {data, status} = await this.$axios.get('/admin/post/'+id);
      if(status===200){
        if(!data.status){
          await this.$router.replace(this.$const.url.panel+'posts')
        }else{
          this.post = data.data
        }
      }else{
        await this.$router.replace(this.$const.url.panel+'posts')
      }
    }
  } // created
}
</script>

<style scoped>

</style>
