export default ({env}, inject) => {
  inject('const', {
    url: {
      base: env.url.base || '/',
      panel: env.url.panel || '/aswpanel/',
      api: {
        base :'',
        media: {
          category: 'https://media.emeyz.com/category/',
          user:     'https://media.emeyz.com/user/',
          upload: {
            sm: 'https://media.emeyz.com/upload/sm_',
            md: 'https://media.emeyz.com/upload/md_',
            lg: 'https://media.emeyz.com/upload/lg_',
            base: 'https://media.emeyz.com/upload/',
          }
        }
      }
    },
    user: {
      levels: {
        '-1': {title: 'Yasaklı',  value: -1,  color: 'red'},
        '0': {title: 'Onaysız',     value: 0,   color: 'grey'},
        '1': {title: 'Abone',     value: 1,   color: 'black'},
        '2': {title: 'Moderatör', value: 2,   color: 'green'},
        '3': {title: 'Yönetici',  value: 3,   color: 'blue'},
        '9': {title: 'Sahip',  value: 9,   color: 'pink'},
      },

      statuses: {
        'active':   {title: 'Aktif',      value: 'active',  color: 'green'  },
        'passive':  {title: 'Pasif',      value: 'passive', color: 'black'  },
        'trash':    {title: 'Çöpte',      value: 'trash',   color: 'red'    },
      },

      genders: {
        'Belirtilmedi':   {title: 'Belirtilmedi',     value: 'Belirtilmedi',  color: 'black',   icon:'mdi-gender-male-female'  },
        'Erkek':          {title: 'Erkek',            value: 'Erkek',         color: 'blue',    icon:'mdi-gender-male'  },
        'Kadın':          {title: 'Kadın',            value: 'Kadın',         color: 'pink',    icon:'mdi-gender-female'    },
      }
    },
    post: {
      statuses: {
        published:  {value:'published', title:'Yayımda',    color:'light-green'},
        draft:      {value:'draft',     title:'Taslak',     color:'brown'},
        trash:      {value:'trash',     title:'Çöp Kutusu', color:'red'},
        waiting:    {value:'waiting',   title:'Beklemede',  color:'orange'},
      }
    }
  })
}

