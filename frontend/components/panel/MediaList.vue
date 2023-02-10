<template>
  <v-container fluid class="pa-0 white">
    <v-tabs v-model="tab"grow>
      <v-tab  v-for="(item, index) in tabItems" :key="index" class="text-capitalize font-weight-bold subtitle-1"><v-icon>{{ item.icon }}</v-icon> {{ item.title }}</v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab">
      <v-tab-item>
        <v-row class="pa-0 ma-0">

          <v-col class="pa-0" :cols="selectedImage? 6 : 12" :sm="selectedImage? 7 : 12" :md="selectedImage? 8 : 12" :lg="selectedImage? 9 : 12">
            <v-sheet>
              <v-row>
                <v-col :cols="getGrid.xs" :sm="getGrid.sm" :md="getGrid.md" :lg="getGrid.lg" :xl="getGrid.xl" v-for="(item, index) in items" :key="index">
                  <v-card rounded="0" class="pa-1" @click="selectedImage = {...item, index}">
                    <v-img :src="$store.state.global.url.imgSm+item.src"></v-img>
                  </v-card>
                </v-col>
              </v-row>
            </v-sheet>
          </v-col>


          <v-col cols="6" sm="5" md="4" lg="3" v-if="selectedImage">

            <v-card :style="'position: sticky; top: '+(popup? 10 : 80)+'px'">
              <v-img :src="$store.state.global.url.imgLg+currentImage.src" @click="dialog=true"></v-img>
              <div class="pa-2">
                <v-chip dark class="subtitle-2 my-1" small>{{ currentImage.type }}</v-chip>
                <v-chip dark class="subtitle-2 my-1" small>.{{ currentImage.info.ext }}</v-chip>
                <v-chip dark class="subtitle-2 my-1" small>{{ currentImage.info.width }}x{{ currentImage.info.height }}</v-chip>
                <v-chip dark class="subtitle-2 my-1" small>{{ Math.floor(currentImage.info.size/1024) }}kb</v-chip>
                <v-chip dark class="subtitle-2 my-1" small>{{ currentImage.c_date }}</v-chip>
                <v-chip dark class="subtitle-2 my-1" small>{{ currentImage.c_time }}</v-chip>

                <v-divider class="my-2"></v-divider>

                <v-textarea auto-grow :rows="1" :value="currentImage.title" label="Görsel Başlığı"></v-textarea>

                <v-textarea auto-grow :rows="1" :value="currentImage.alt" label="Alternatif Başlık"></v-textarea>


                <v-select v-model="selectedItemUrlPre" label="Boyut Seçin" :items="sizeItems" :item-value="'url'" :item-text="'title'" ></v-select>
                <v-text-field :value="selectedItemUrlPre+currentImage.src"  readonly label="Görsel Bağlantısı"/>

                <v-sheet class="text-right">
                  <v-btn color="error" dark @click="deleteImage()">Sil</v-btn>
                  <v-spacer />
                  <v-btn color="error" dark @click="selectedImage=null">Kapat</v-btn>
                  <v-btn color="success">Güncelle</v-btn>
                  <v-btn color="primary" v-if="popup" @click="$emit('onClickSelect', selectedImage)">Seç</v-btn>
                </v-sheet>
              </div>


            </v-card>

          </v-col>


        </v-row>
      </v-tab-item>

      <v-tab-item>
        <MediaUploadArea @onUploaded="onUploadedFile($event)" />
      </v-tab-item>
    </v-tabs-items>

    <v-dialog max-width="1170" v-if="selectedImage!==null" v-model="dialog">
      <v-img :src="$store.state.global.url.imgLg+currentImage.src"></v-img>
    </v-dialog>
  </v-container>
</template>

<script>
import {mysqlToDate} from "@/helpers/date"
import MediaUploadArea from "@/components/panel/child/MediaUploadArea";
export default {
  name: "MediaList",
  components: {MediaUploadArea},
  props: {
    popup: {
      type: Boolean,
      default: false
    }
  },
  data(){
    return {
      dialog: false,
      items: [],
      selectedItemUrlPre: null,
      sizeItems: [
          {prepare: 'sm_', url: this.$store.state.global.url.imgSm,  title: 'Küçük'},
          {prepare: 'md_', url: this.$store.state.global.url.imgMd,  title: 'Orta'},
          {prepare: 'lg_', url: this.$store.state.global.url.imgLg,  title: 'Büyük'}
      ],
      selectedImage: null,
      currentImage: {},
      onScrollEventStatus: false,
      tabItems: [{title: 'Dosyalar', icon: 'mdi-image-multiple'}, {title: 'Karşıya Yükle', icon: 'mdi-upload-network'}],
      tab: 0
    }
  },
  created(){

    this.getItemsFromApi(0);
    this.selectedItemUrlPre = this.sizeItems[0].url;
  },

  mounted() {
    if(!this.onScrollEventStatus){
      window.addEventListener('scroll', this.onScroll);
      // window.addEventListener('resize', this.onScroll);
      this.onScrollEventStatus = true;
    }
  },

  destroyed(){
    window.removeEventListener('scroll', this.onScroll);
  },

  computed: {

    getGrid(){
      return {
        xs: !this.selectedImage? 6 : 12,
        sm: !this.selectedImage? 4 : 6,
        md: !this.selectedImage? 4 : 6,
        lg: !this.selectedImage? 3 : 4,
        xl: !this.selectedImage? 2 : 3,
      }
    }

  },

  methods: {

    getItemsFromApi(offset=0){
      this.$axios.get('/admin/media?offset='+offset+'&limit=36').then(({data, status}) => {
        if(status===200 && data.success){
            this.items = this.items.concat(data.files);
        }
      }).catch(err => {});
    },


    onScroll(e){
      const endOfPage = window.innerHeight + window.pageYOffset >= document.body.offsetHeight - 50;
      console.log(endOfPage);
    },

    onUploadedFile(file){
      this.items.unshift(file);
      this.tab = 0;
    },

    deleteImage(){
      this.$axios.post('/admin/media/delete', {id: this.selectedImage.id}).then(({status, data}) => {
        if(status===200 && data.success){
          this.items.splice(this.selectedImage.index, 1);
          this.selectedImage = null;
          this.$toast.success(data.message || 'Görsel silindi');
        }
      }).catch(err => {
        this.$toast.error('Beklenmedik bir hata oluştu');
      })
    }

  },

  watch: {
    selectedImage: function(value){
      if(value!=null){
        let ct = mysqlToDate(value.created_at);
        this.currentImage = {
          ...value,
          info: (typeof value.info === 'object'? value.info : JSON.parse(value.info)),
          c_date: `${ct.day} ${ct.monthName} ${ct.fullYear}`,
          c_time: `${ct.hour}:${ct.minute}`
        }
      }
    }
  }
}
</script>

<style scoped>

</style>
