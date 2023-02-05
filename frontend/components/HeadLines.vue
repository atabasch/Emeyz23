<template>
<div style="height: auto">
  <v-row v-if="getHeadline.length > 5">
    <v-col cols="4" class="hidden-sm-and-down">
      <HeadlineItem :item="getHeadline[0]"/>
    </v-col>

    <v-col cols="4" class="hidden-sm-and-down px-0">
      <HeadlineItem style="height: 170px" :item="getHeadline[1]"/>
      <HeadlineItem style="height: 170px; margin-top: 10px" :item="getHeadline[2]"/>
    </v-col>

    <v-col cols="12" md="4">
      <v-carousel height="350" cycle hide-delimiter-background show-arrows-on-hover hide-delimiters delimiter-icon="mdi-minus" progress>
        <v-carousel-item :key="'hl-item-'+index" v-for="(item, index) in getHeadline.slice(3)">
          <HeadlineItem :key="'hl-item-sub-'+index" :item="item"/>
        </v-carousel-item>
      </v-carousel>
    </v-col>
  </v-row>

</div>
</template>

<script>
import HeadlineItem from "@/components/child/HeadlineItem";
export default {
  name: "HeadLines",
  components: {HeadlineItem},
  computed: {
    getHeadline(){
      return this.$store.getters['post/getHeadlines'];
    }
  }
}
</script>

<style scoped>

</style>
