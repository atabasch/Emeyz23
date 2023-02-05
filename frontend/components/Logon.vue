<template>
  <v-card tile>
    <Login    v-if="componentName==='login'"            @changeTab="changeComponent($event)" />
    <Register v-if="componentName==='register'"         @changeTab="changeComponent($event)" @onRegistered="onRegistered($event)"/>
    <ActivationCode v-if="componentName==='activation'" @changeTab="changeComponent($event)" :user="registeredUser" :checkData="checkData"/>
    <v-divider></v-divider>
    <v-card-actions>
      <v-btn v-if="!popup" class="mx-1 text-capitalize subtitle-2" tile text to="/"><v-icon>mdi-chevron-left</v-icon> Emeyz'e dön</v-btn>
      <v-spacer></v-spacer>
      <v-btn v-if="componentName!=='activation'" class="mx-1 text-capitalize subtitle-2" color="orange" @click="changeComponent('activation')" text tile>Hesap Onayla</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import Login from "@/components/Login";
import Register from "@/components/Register";
import ActivationCode from "@/components/ActivationCode";
export default {
  name: "Logon",
  components:{Login, Register, ActivationCode},
  props: {
    componentName: {
      type: String,
      default: 'login'
    },
    checkData: {
      type: Object,
      default: () => null
    },
    popup: {
      type: Boolean,
      default: false
    }
  },
  data: () => ({
    registeredUser: null,
  }),
  methods: {
    changeComponent(name){
      this.componentName = name;
    },

    onRegistered(user){
      this.registeredUser = user;
      this.componentName = 'activation'
    }
  }
}
</script>

<style scoped>

</style>
