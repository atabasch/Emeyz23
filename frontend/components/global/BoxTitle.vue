<template>
  <div class="content-title">
    <h1 v-if="tag==='h1'" :class="getClass"><v-icon v-if="icon" v-html="icon"></v-icon> {{ title }} <i></i></h1>
    <h2 v-else-if="tag==='h2'" :class="getClass"><v-icon v-if="icon" v-html="icon"></v-icon> {{ title }} <i></i></h2>
    <h3 v-else-if="tag==='h3'" :class="getClass"><v-icon v-if="icon" v-html="icon"></v-icon> {{ title }} <i></i></h3>
    <h4 v-else-if="tag==='h4'" :class="getClass"><v-icon v-if="icon" v-html="icon"></v-icon> {{ title }} <i></i></h4>
    <h5 v-else-if="tag==='h5'" :class="getClass"><v-icon v-if="icon" v-html="icon"></v-icon> {{ title }} <i></i></h5>
    <h6 v-else :class="getClass"><v-icon v-if="icon" v-html="icon"></v-icon> {{ title }} <i></i></h6>
    <i></i>
  </div>
</template>

<script>
export default {
  name: "BoxTitle",
  props: {
    title: {
      type: String,
      default: ''
    },
    icon:{
      type: String,
      default: null
    },
    tag: {
      type: String,
      default: 'h6'
    },
    single: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    getClass(){
      return {
        'content-title-head': 1,
        'single-title': this.single
      }
    }
  }
}
</script>

<style scoped>

.content-title{
  margin-bottom: 15px;
  overflow: hidden;
  position: relative;
}
.content-title > i{
  content: "";
  display: inline-block;
  height: 2px;
  width: 100%;
  position: absolute;
  bottom: 0;
  background-color: #E0E0E0;

}
.content-title-head{
  display: inline-block;
  font-size: 23px;
  line-height: 43px;
  font-weight: 500;
  padding-right: 20px;
  position: relative;
}
.content-title-head > i{
  display: inline-block;
  content: '';
  height: 2px;
  width: 100%;
  background-color: #03a9f4;
  position: absolute;
  bottom: 0px;
  left: 0;
}
.single-title{
  font-size: 32px;
  line-height: 52px;
}
</style>
