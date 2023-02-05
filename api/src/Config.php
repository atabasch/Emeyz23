<?php 
namespace Atabasch;

class Config{

    public static function get($key=null, $default=null){
        return !$key? ($GLOBALS["CONFIG"] ?? $default) : ($GLOBALS["CONFIG"][$key] ?? $default);
    }

}
?>