import Vue from 'vue'

let colors =  {
  midnightBlue:"#2A2356",
  royalBlue:"#586AE2",
  lavender:"#E0D9F6",
  pink:"#C252E1",
  skyBlue:"#6ECBF5",
  orange:"#FF6E27",
  green:"#65DC98",
}

export default ({ app }, inject) => {
  inject('colorPalette', Vue.observable(colors))
}
