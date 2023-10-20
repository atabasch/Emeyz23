export const state = function(){return {
  post: {},
  category: {},
  posts: [],
  trends: [],
  headlines: [],
  loadMoreEnd: false
}}

export const getters = {

  getCategory(state){
    return state.category
  },

  getPost(state){
    return state.post
  },

  getPosts(state){
    return state.posts
  },

  getTrends(state){
    return state.trends
  },

  getHeadlines(state){
    return state.headlines
  },

  getLoadMoreEnd(state){
    return state.loadMoreEnd
  }

}

export const mutations = {
  setCategory(state, payload){
    state.category = payload
  },

  setPost(state, payload){
    state.post = payload
  },

  setPosts(state, {posts, loadMore=false}){
    if(loadMore === true){
      state.posts = state.posts.concat(posts)
    }else{
      state.posts = posts
    }
  },

  setTrends(state, payload){
    state.trends = payload
  },

  setHeadlines(state, payload){
    state.headlines = payload
  },

  setLoadMoreEnd(state, payload){
    state.loadMoreEnd = payload
  }


}


export const actions = {

  async loadPost(vuexContext, {slug}){
    let response = await this.$axios.get('/post/'+slug);
    if(response.status===200){
      vuexContext.commit('setPost', response.data)
    }
  },

  async loadPosts(vuexContext, {loadMore=false}){
    let offset =    await(!loadMore? 0 : vuexContext.getters.getPosts.length)
    let response =  await this.$axios.get('/post?offset='+offset+'&limit=12&orderby=id&sort=DESC');
    if(response.status===200){
      vuexContext.commit('setPosts', {posts: response.data, loadMore: loadMore})
      if(response.data.length < 10){
        vuexContext.commit('setLoadMoreEnd', true);
      }
    }
  },

  async loadPostsFromCategory(vuexContext, {slug, loadMore=false}){
    let offset =    await(!loadMore? 0 : vuexContext.getters.getPosts.length)
    let response = await this.$axios.get(`/category/${slug}/posts?offset=${offset}&limit=12&orderby=id&sort=DESC`);
    if(response.status===200){
      vuexContext.commit('setPosts', {posts: response.data.posts})
      vuexContext.commit('setCategory', response.data.category)
      if(response.data.posts.length < 10){
        vuexContext.commit('setLoadMoreEnd', true);
      }
    }
  },

  async loadTrends(vuexContext){
    let response = await this.$axios.get('/post?offset=0&limit=8&orderby=views&sort=DESC');
    if(response.status===200){
      vuexContext.commit('setTrends', response.data)
    }
  },

  async loadTrendsFromCategory(vuexContext, {slug}){
    let response = await this.$axios.get(`/category/${slug}/posts?offset=0&limit=7&orderby=views&sort=DESC`);
    if(response.status===200){
      vuexContext.commit('setTrends', response.data.posts)
    }
  },

  async loadHeadlines(vuexContext){
    let response = await this.$axios.get(`/category/manset/posts?offset=0&limit=10&orderby=id&sort=DESC`);
    if(response.status===200){
      vuexContext.commit('setHeadlines', response.data.posts)
    }
  }

}
