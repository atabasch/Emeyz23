let monthNames = ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"];
let dayNames = ["Pazar", "Pazartesi", "Salı", "Çarşamba", "Perşembe", "Cuma", "Cumartesi"];


export const mysqlToDate = function(dateString=''){
  let t = dateString.split(/[- :]/);
  return {
    day:        t[2],
    dayName:    dayNames[t[1]],
    month:      t[1],
    monthName:  monthNames[t[1]-1],
    year:       t[0],
    fullYear:   t[0],
    hour:       (t[3].length < 2? "0" : "") + t[3],
    minute:     (t[4].length < 2? "0" : "") + t[4],
    second:     (t[5] < 2? "0" : "") + t[5],
  }
}

export const getAge = function(birthdate=null){
  if(!birthdate){
    return false;
  }
  let bdateMs   = new Date(birthdate).setHours(0,0,0);
  let nowMs     = new Date().setHours(0,0,0);
  let diffYears  = (nowMs-bdateMs) / ( (((1000*60) * 60) * 24) * 365 );
  return Math.floor(diffYears);
}
