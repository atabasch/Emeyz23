export const state = function(){ return {
  head: {
    title: 'Emeyz.com',
    description: 'emeyz.com, ne, nedir, nasıl yapılır? öğren'
  },
  global: {
    url: {
      api: 'https://api.emeyz.com',
      panel: '/aswpanel/',
      img:   'https://media.emeyz.com/upload/',
      imgLg: 'https://media.emeyz.com/upload/lg_',
      imgMd: 'https://media.emeyz.com/upload/md_',
      imgSm: 'https://media.emeyz.com/upload/sm_',
    },
    img: {
      user: 'https://media.emeyz.com/user/',
      sm: 'https://media.emeyz.com/upload/sm_',
      md: 'https://media.emeyz.com/upload/md_',
      lg: 'https://media.emeyz.com/upload/lg_',
    }
  },
  access: {
    panel: process.env.DEV
  },
} }

export const getters = {
  getHead(state){
    return {
      titleTemplate(title){
        let moreTitle = ' - Emeyz; Hayat Rehberi';
        return (title+moreTitle).length <= 60 ? title + moreTitle : title ;
      },
      title:  state.head.title,
      meta: [
        {hid:"description", name: "description", content: state.head.description}
      ],
      link: [
        {rel: 'stylesheet', href: '/css/main.css'},
      ],
      script: [
        { src: 'https://www.googletagmanager.com/gtag/js?id=G-0ZSZZ2X8MF', async: true },
        { type: 'text/javascript', innerHTML: `
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-0ZSZZ2X8MF');`
        },
        {
          src: 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3641811062649980',
          // 'data-ad-client': 'ca-pub-################',
          async: true,
          crossorigin: 'anonymous'
        },
      ],

    }
  },

  loggedInAdmin(state){
    if(!state.auth.user){
      return false;
    }else{
      return state.auth.user.level? state.auth.user.level > 2 : false;
    }
  },

  getAccessPanel(state){
    return state.access.panel;
  }
}

export const mutations = {
  setAccessPanel(state,payload){ state.access.panel = payload },
  setHead(state, payload) {
    state.head = {...state.head, ...payload}
  },
  resetHead(state) {
    state.head = {
      title: 'Emeyz.com',
      description: 'emeyz.com, ne, nedir, nasıl yapılır? öğren'
    }
  }

}



export const actions = {
  nuxtServerInit({commit}, {$axios}){
    commit("setAccessPanel", process.env.DEV || 0);
    return $axios.get('/navigation/header-menu,sidebar-menu,page-menu,footer-menu,mobile-menu').then(response => {
      if(response.status===200){
        commit("navigation/setItems", response.data);
      }
    }).catch(err => {
    })

  }
}
