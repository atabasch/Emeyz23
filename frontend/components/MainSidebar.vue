<template>

  <v-card tile class="mx-auto">
    <v-list dense  class="pa-0">

      <v-list-item-group>


        <v-list-item link :to="'/kategori/'+item.slug" :key="item.id" v-for="item in getItems" class="text-decoration-none list-item-border">
          <v-list-item-icon>
            <v-icon v-text="item.icon"></v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title class="text-body-1" v-text="item.title"></v-list-item-title>
          </v-list-item-content>
        </v-list-item>


      </v-list-item-group>

    </v-list>
  </v-card>

</template>

<script>
export default {
  name: "MainSidebar",
  computed:{
    getItems: function(){
      return this.$store.getters["navigation/getItems"]["sidebar-menu"]
    }
  }
}
</script>

<style scoped>
.list-item-border:not(:last-child){
  border-bottom: 1px dashed rgba(0,0,0,0.10);
}
</style>
