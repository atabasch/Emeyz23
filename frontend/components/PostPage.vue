<template>
  <v-row>
    <v-col cols="12" sm="12" md="6">
      <v-alert border="left" icon="mdi-text" elevation="1">
        <strong>Gönderiler</strong>
      </v-alert>

      <PostList />
    </v-col>

    <v-col cols="12" md="3" class="hidden-sm-and-down ">
      <div class="globalnoob91">
        <v-alert border="left" icon="mdi-trending-up" elevation="1">
          <strong>Trendler</strong>
        </v-alert>
        <TrendList />
      </div>
    </v-col>

    <v-col cols="12" md="3" class="hidden-sm-and-down">
      <div class="sticky-top">
        <v-alert border="left" icon="mdi-folder-multiple" elevation="1">
          <strong>Kategoriler</strong>
        </v-alert>
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
