<template>
<div>
  <BoxTitle title="Kategoriler" />

  <router-link :to="$helper.getUrl.category(item.slug)" :key="item.id" v-for="item in getItems" class="d-flex flex-row catItem">
    <v-icon class="ico light-blue darken-2" dark v-text="item.icon"></v-icon>
    <h6 class="flex-grow-1 subtitle-2  black-anim-text">{{ item.title }}</h6>
    <span>{{ item.total }}</span>
  </router-link>
</div>
</template>

<script>
export default {
  name: "SidebarCategories",
  data(){return {
    items:[]
  }},
  computed:{
    getItems(){
      return this.$store.getters["navigation/getItems"]["sidebar-menu"]
    }
  },

}
</script>

<style scoped>
.catItem{
  padding: 6px 2px;
  text-decoration: none;
  font-size: 20px;
  line-height: 22px;
  align-items: center;
  color: black;
}
.catItem:not(:last-child){
  border-bottom: 1px solid #e0e0e0;
}
.catItem:hover{
  background-color: white;
}
.ico{
  font-size: 20px;
  padding: 8px;
  border-radius: 100%;
  margin-right: 10px;
}
</style>
