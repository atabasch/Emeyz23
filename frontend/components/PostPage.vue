<template>
  <v-row>
    <v-col cols="12" sm="12" md="9">
      <BoxTitle title="Son Eklenen Makaleler" />
      <PostList />
    </v-col>

    <v-col cols="12" md="3" class="hidden-sm-and-down">
      <div class="sticky-top">
      <MainSidebar />
      </div>
    </v-col>
  </v-row>
</template>

<script>
import MainSidebar from "@/components/MainSidebar";
import PostList from "@/components/PostList";
import TrendList from "@/components/TrendList";

export default {
  name: "PostPage",
  components: {MainSidebar, PostList, TrendList},
}
</script>

<style scoped>
.sticky-top{
  position: sticky !important;
  top: 70px !important;
}
.sticky-bottom{
  position: sticky !important;
  bottom: 20px !important;
}


</style>
