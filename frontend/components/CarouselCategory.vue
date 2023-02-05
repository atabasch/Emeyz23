<template>
<div>
  <div class="text-center mb-10">
    <h4 class="boxTitle"><v-icon x-large dark >mdi-compass-outline</v-icon> Keşfedilecek Kategoriler</h4>
  </div>

  <Hooper :settings="hooperSettings" :style="'height: auto;'">

    <Slide v-for="(item, index) in items" :key="index" class="px-2" :index="index">

      <RouterLink :to="'/kategori/'+item.slug" class="text-decoration-none">
        <v-img :src="$const.url.api.media.category+item.cover" :aspect-ratio="2/3" class="rounded-lg" gradient="rgba(0,0,0,0.0), rgba(0,0,0,0.2), rgba(0,0,0,0.4), rgba(0,0,0,0.8)">
            <v-chip small class="rounded body-1 px-2 itemInfo" :color="item.color || '#0091fa'" dark>{{ item.total }} Makale</v-chip>
        </v-img>
        <h4 class="itemTitle white-anim-text anim-text">{{ item.title }}</h4>
      </RouterLink>

    </Slide>

  </Hooper>

</div>
</template>

<script>
import {Hooper, Slide} from "hooper"
import 'hooper/dist/hooper.css';
export default {
  name: "CarouselCategory",
  components: {Hooper, Slide},
  props: {
    items: {
      type: Array,
      default: []
    }
  },
  data(){ return {
    hooperSettings: {
      infiniteScroll: true,
      itemsToShow: 1,
      initialSlide: 0,
      centerMode: false,
      playSpeed: 5000,
      duration: 1000,
      breakpoints: {
        400: {
          centerMode: false,
          itemsToShow: 2
        },
        600: {
          itemsToShow: 3,
          pagination: 'fraction'
        },
        1000: {
          itemsToShow: 4,
          pagination: 'fraction'
        },
        1200: {
          itemsToShow: 5,
          pagination: 'fraction'
        }
      }
    }
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
  color: white;
}
.itemTitle{
  color: white;
  font-size: 1.1rem;
  text-decoration: none;
  margin: 10px 5px 0px;
}

</style>
