export const getSettings = async function(axios, fields=null){
  let {status, data} = await axios.get('/admin/settings?fields='+fields);
  if(status===200 && data.success){
    let results = {};
    await data.configs.forEach(async config => { results[config.key] = config; });
    return results;
  }else{
    return {}
  }
}

export const updateSettings = async function(axios, configs=null){
  let {status, data} = await axios.post('/admin/settings/update', {configs: configs});
  return {
    success: data.success && status===200,
    message: data.message || null
  }
}
