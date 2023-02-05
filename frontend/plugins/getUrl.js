import Vue from "vue";
let url = 'https://media.emeyz.com/'
Vue.prototype.$getMediaUrl = (filename=null, size='md', path='upload') => {
  return !filename? null : `${url}${path}/${size}_${filename}`;
}
