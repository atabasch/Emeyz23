<?php 
// APİ İÇİN SABİTLER OLUŞTURACAK
namespace Atabasch;
use Atabasch\Config;

class Init{


    public static function run(){
        self::loadGlobalConfigs();
        
        $pMedia     = $GLOBALS["CONFIG"]["path_media"] ?? 'medias';
        $pUpload    = $GLOBALS["CONFIG"]["path_media_upload"] ?? 'uploads';
        $pUser      = $GLOBALS["CONFIG"]["path_media_user"] ?? 'users';
        

        define("PATH_MEDIA", ( __DIR__ . '/../../' . $pMedia ));
        define("PATH_MEDIA_UPLOAD", ( __DIR__ . '/../../' . $pMedia . '/' . $pUpload ));
        define("PATH_MEDIA_USER", ( __DIR__ . '/../../' . $pMedia . '/' . $pUser ));


    }

    private static function loadGlobalConfigs(){
        $db = new Database();
        $GLOBALS["CONFIG"] = [];
        $config_items = $db->queryAll("SELECT * FROM config");
        if($config_items){
            foreach($config_items as $config){
                $GLOBALS["CONFIG"][$config->key] = $config->val;
            }
        }
    }


}


?>