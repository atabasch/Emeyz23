<template>
<div v-if="items" class="category-carousel">
  <BoxTitle tag="h5" title="Keşfedilecek Kategoriler" icon="mdi-compass-outline" />

  <Flicking :options="carouselOptions" :plugins="plugins">

    <div v-for="(item, index) in items" :key="index" class="px-2 col-6 col-sm-4 col-md-3 col-lg-2">
      <router-link :to="$helper.getUrl.category(item.slug)" class="text-decoration-none">
        <v-img :src="$const.url.api.media.category+item.cover" :aspect-ratio="3/5" class="rounded-lg" gradient="rgba(0,0,0,0.0), rgba(0,0,0,0.2), rgba(0,0,0,0.4), rgba(0,0,0,0.8)">
            <v-chip small class="rounded body-1 px-2 itemInfo" :color="item.color || '#0091fa'" dark>{{ item.total }} Makale</v-chip>
        </v-img>
        <h4 class="itemTitle black-anim-text anim-text">{{ item.title }}</h4>
      </router-link>
    </div>

  </Flicking>

</div>
</template>

<script>
import { Flicking } from "@egjs/vue-flicking";
import { AutoPlay } from "@egjs/flicking-plugins";
import "@egjs/vue-flicking/dist/flicking.css";

export default {
  name: "CarouselCategory",
  components: {Flicking},
  props: {
    items: {
      type: Array,
      default: []
    }
  },
  data(){ return {
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
      new AutoPlay({ duration: 3000, animationDuration:1000, direction: "NEXT", stopOnHover: true })
    ],
  } }
}
</script>

<style scoped>
.itemInfo{
  position: absolute;
  top: 12px;
  left:12px;
  opacity: 0.9;

}

.boxTitle{
  font-size: 190% !important;
}
.itemTitle{
  font-size: 1.1rem;
  text-decoration: none;
  margin: 10px 5px 0px;
}



</style>
