<template>
<div>
  <SidebarCategories />
  <TrendListHorizontal />
</div>


</template>

<script>
import SidebarCategories from "@/components/child/SidebarCategories";
import TrendListHorizontal from "@/components/TrendListHorizontal";
export default {
  name: "MainSidebar",
  components: {SidebarCategories, TrendListHorizontal},
  computed:{

  }
}
</script>

<style scoped>
</style>
