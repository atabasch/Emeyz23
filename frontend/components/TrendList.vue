<template>
<div>
  <TrendItem :key="post.id" :post="post" :index="index" v-for="(post, index) in getTrends" />
</div>
</template>

<script>
import TrendItem from "@/components/child/TrendItem";
export default {
  name: "TrendList",
  components: {TrendItem},
  computed: {

    getTrends(){
      return this.$store.getters["post/getTrends"];
    }

  }
}
</script>

<style scoped>

</style>
