import slugify from "slugify"

export const strToSlug = function(str){

  return slugify(str, {
    replacement: '-',
    remove: /[\.\@]+/gi,
    lower: true,
    locale: 'tr',
    trim: true
  });

}


export const validPassword = function(value){
  let patterns = [
    /[a-z]{1,}/g,
    /[A-Z]{1,}/g,
    /[0-9]{1,}/g,
    /[!#$&?\.\-_,;<>=)(+*]{1,}/g,
    /.{8,}/
  ];
  let validatedPatterns = patterns.filter(pattern => pattern.test(value) );
  return validatedPatterns.length === patterns.length;
}

export const randomInt = function(min=0, max=10){
  return Math.floor( (min+Math.random()) * max );
}

let lowerChars    = "qwertyuopasdfghjklizxcvbnm";
let upperChars    = "QWERTYUIOPASDFGHJKLZXCVBNM";
let numberChars   = "0123456789";
let specialChars  = "!#$&?.-_,;<>=)(+*";
export const passwordGenerator = function (){
    let password = [];
    password.push( lowerChars[randomInt(0, lowerChars.length)] );
    password.push( lowerChars[randomInt(0, lowerChars.length)] );
    password.push( upperChars[randomInt(0, upperChars.length)] );
    password.push( upperChars[randomInt(0, upperChars.length)] );
    password.push( numberChars[randomInt(0, numberChars.length)] );
    password.push( numberChars[randomInt(0, numberChars.length)] );
    password.push( specialChars[randomInt(0, specialChars.length)] );
    password.push( specialChars[randomInt(0, specialChars.length)] );
    password.push( lowerChars[randomInt(0, lowerChars.length)] );
    password.push( lowerChars[randomInt(0, lowerChars.length)] );
    password.push( upperChars[randomInt(0, upperChars.length)] );
    password.push( upperChars[randomInt(0, upperChars.length)] );
    return password.sort((a, b) => 0.5 - Math.random()).join('');
}
