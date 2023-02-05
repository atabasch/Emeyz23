
export const state = () => ({
  items: {}
})

export const getters = {
  getItems(state){
    return state.items
  }
}

export const mutations = {
  setItems(state, payload){
    payload.forEach(item => {
      state.items[item.slug] = item.data
    })

  }
}

export const actions = {

}
