<template>
  <v-row>
    <v-col cols="12" sm="12" md="9">
      <BoxTitle title="Son Eklenen Makaleler" >
        <template v-slot:right>
          <ChangeItemListStyle />
        </template>
      </BoxTitle>

      <v-progress-linear v-if="loading" indeterminate color="cyan"></v-progress-linear>
      <PostList v-else />
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
import ChangeItemListStyle from "@/components/child/ChangeItemListStyle";

export default {
  name: "PostPage",
  components: {MainSidebar, PostList, TrendList, ChangeItemListStyle},
  props: { loading: { type: Boolean, value: false } }
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
