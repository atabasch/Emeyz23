<template>
<v-card class="mb-5 elevation-1" tile>
  <router-link :to="'/'+post.slug">
  <v-img :src="$store.state.global.img.md+post.cover" contain />
    <span id="trendNumber">{{ index+1 }}</span>
  </router-link>
  <div class="pa-3">
    <v-hover v-slot="{hover}">
      <h5 class="subtitle-1 font-weight-bold">
        <router-link
          :to="'/'+post.slug"
          class="text-decoration-none black-anim-text"
          >{{ post.title }}</router-link></h5>
    </v-hover>
    <small class="grey--text text-body-2"><strong>{{ getDate }}</strong> tarihinde yazıldı.</small>
  </div>
</v-card>
</template>

<script>
import {mysqlToDate} from "@/helpers/date"
export default {
  name: "TrendItem",
  props: {
    post: Object,
    index: {
      type: Number,
      required: false,
      default: 0
    }
  },
  computed: {

    getDate(){
      let date = mysqlToDate(this.post.p_time)
      return `${date.day} ${date.monthName} ${date.fullYear}`

    }

  }
}
</script>

<style scoped>
#trendNumber{
  position: absolute;
  top: 0;
  left: 0;
  display: block;
  width: 3rem;
  height: 3rem;
  line-height: 3rem;
  font-size: 1.7rem;
  text-align: center;
  background-color: rgba(255, 0, 60, 0.65);
  color: white;
  font-weight: 700;
  border-radius: 0px 0px 10px 0px;

}
/*small{*/
/*  color: rgba(0, 0, 0, 0.50)*/
/*}*/
</style>
