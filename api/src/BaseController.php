<?php

namespace Atabasch;

use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;
use \Firebase\JWT\Key;



class BaseController extends \Atabasch\Main{

    private $postDatas = [];
    protected $db = null;
    protected $config = [];

    public function __construct(){
        parent::__construct();
        $this->db  = new Database();
        $this->loadConfigs();
        $this->postDatas = count($_POST) > 0? $_POST : json_decode(file_get_contents('php://input'), true) ;
    }

    private function loadConfigs(){
        $configs = [];
        $config_items = $this->db->queryAll("SELECT * FROM config");
        if($config_items){
            foreach($config_items as $config){
                $configs[$config->key] = $config->val;
            }
        }
        $GLOBALS["CONFIG"] = $configs;
    }

    protected function json($data = []){
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode( $data );
    }

    protected function response($data=[], $success=true, $message=''){
        $datas = [
            'success' => $success,
            'message' => $message,  
        ];
        return $this->json(array_merge($datas, $data));
    }

    protected function notFound($message=null){
        return $this->json([
            'status'    => false,
            'message'   => !$message? 'İçerik bulunamadı' : $message
        ]);
    }



    protected function db(){
        return $this->db;
    }

    /**
     * GET ile gelen parametreleri alır.
     */
    protected function get($key=null, $default=null){
        if(!$key){
            return $_GET;
        }else{
            return $_GET[$key] ?? $default;
        }
    }

    // POST isteği ile gönderilmiş olan verileri alır
    protected function post($key=null, $default=null, $clear=false){
        if(!$key){
            return $this->postDatas;
        }
        $result =  isset($this->postDatas[$key])? $this->postDatas[$key] : $default;

        if($clear){
            $result = htmlspecialchars(trim($result));
        }

        return $result;
    }

    // REQUEST METHOD sorgulamak
    protected function hasRequestMethod($methods=["get"]){
        if(!is_array($methods)){
            $methods = explode(',', $methods);
        }
        $methods = array_map(function($i){
                                return strtoupper(trim($i));
                            }, $methods);
        return in_array(strtoupper($_SERVER['REQUEST_METHOD']), $methods);
    }

    // Headerdan Gelen Oturum Jetonunu alır
    protected function authToken(){
        return trim( substr($_SERVER['HTTP_AUTHORIZATION'], 6) );
    }

    // Açılmış oturum bilgilerini verir.
    protected function authData(){
        if($this->authToken()){
            try{
                $key    = new Key($ENV['API_KEY'] ?? 'emeyz', 'HS512');
                $datas  = JWT::decode($this->authToken(), $key);
                return $datas;
            }catch(\Exception $e){ 
                return false;
            }
        }
        return false;
    }
    

    protected function getRequestMethod(){
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }



}
