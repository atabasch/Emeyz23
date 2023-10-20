<template>
  <div v-if="items">
      <NuxtLink class="item"
             :to="$helper.getUrl.category(category.slug)"
             :key="category.id"
             v-for="category in items ">{{ category.title }}</NuxtLink>
  </div>
</template>

<script>
export default {
  name: "PostItemCategories",
  props: {
    items: {
      type: Array,
      default: null
    }
  }
}
</script>

<style scoped>
.item{
  position: relative;
  font-weight: 400;
  margin: 0px 10px 0px 0px;
  font-size: 13px;
  line-height: 12px;
  color: #757575;
  text-decoration: none;
  padding-left: 5px;
  letter-spacing: .02rem;
}
.item:not(:first-child)::before{
  content: ''; display: block;  position: absolute;
  width: 5px; height: 5px;  background: #757575; border-radius: 100%;
  left: -5px; top: 50%; transform:  translateY(-50%);
}
.item:first-child{
  padding-left: 0;
}
</style>
