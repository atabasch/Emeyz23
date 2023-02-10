<?php 
namespace Atabasch;

class Config{

    public static function get($key=null, $default=null){
        return !$key? ($GLOBALS["CONFIG"] ?? $default) : ($GLOBALS["CONFIG"][$key] ?? $default);
    }

    public static function bool($key=null, $default=null){
        $val = $GLOBALS["CONFIG"][$key] ?? $default;
        return ($val==='on' || $val==='1' || $val==='true')? true : false;
    }

}
?>