<template>
<div v-if="getHeadline">
  <Flicking :options="carouselOptions" :plugins="plugins">

    <div class="pa-0 col-12 col-sm-6 col-md-4 col-lg-3 hl-out" :key="index" v-for="(post, index) in getHeadline">
      <router-link :to="$helper.getUrl.post(post.slug)" class="text-decoration-none">
      <v-img :src="$const.url.api.media.upload.lg+post.cover" :aspect-ratio="1/1">
        <div class="hl-box d-flex flex-column fill-height justify-end align-start px-6 pb-8 cursor-pointer fill-height">
          <div class="ih-info d-flex">
            <span class="ih-date mr-2"><v-icon size="20" color="red">mdi-calendar-outline</v-icon> <time v-html="$helper.getDateFormat(post.p_time, 'dateLong')"></time></span>
          </div>
          <h3 class="ih-title mt-2 anim-text white-anim-text">{{ post.title }}</h3>
          <p class="ih-summary">{{ post.summary || post.description  }}</p>
        </div>
      </v-img>
      </router-link>
    </div>


  </Flicking>
</div>
</template>

<script>
import { Flicking } from "@egjs/vue-flicking";
import { AutoPlay, Fade } from "@egjs/flicking-plugins";
import "@egjs/vue-flicking/dist/flicking.css";

export default {
  name: "HeadLines",
  components: { Flicking},
  data(){return {
    carouselOptions: {
      align: 'prev',
      circular: true,
      defaultIndex: 0,
      horizontal: true,
      adaptive: false,
      panelsPerView: -1,
      noPanelStyleOverride: false,
      bound: true,
      circularFallback: "bound",
    },
    plugins: [
      new AutoPlay({ duration: 4000, animationDuration:2000, direction: "NEXT", stopOnHover: true })
    ],

  }},
  computed: {
    getHeadline(){
      return this.$store.getters['post/getHeadlines'];
    }
  }
}
</script>

<style scoped>

.hl-box::after{
  content: "";
  display: block;
  position: absolute;
  width: 100%; height: 100%;
  top:0; right:0; bottom:0; left:0;
  background-color: rgba(0,0,0,0.60);
  z-index: -1;
}

.hl-box:hover::after{
  background-color: rgba(0,0,0,0.55);
}


.ih-box:hover .ih-thumb img{

}
.ih-thumb img{
  transition-duration: 500ms;
  max-width: 100%;
  height: auto;
}
.ih-title{
  font-weight: 900;
  color: white;
  font-size: 1.1vw;
  line-height: 1.4vw;
}
.ih-info{
  color: rgba(255, 255, 255, 0.95);

}
.ih-info > *{
  font-size: 14px;
  line-height: 18px;
  letter-spacing: 0.3px;
  color: rgba(255, 255, 255, 0.75);
}
.ih-summary{
  color: rgba(255, 255, 255, 0.75);
  margin-top: 8px;
  font-size: 16px;
  line-height: 24px;
  font-weight: 400;
}


@media screen and (max-width: 1400px) {
  .ih-title{font-size: 20px; line-height: 28px; }
  .ih-summary{font-size: 14px; line-height: 22px; }
}
</style>
