<?php
namespace Atabasch\Controllers;

class AdminController extends \Atabasch\BaseController{

   

    public function __construct(){
        parent::__construct();
        if(!$this->checkAccess()){
            $this->json([
                'message' => 'Geçersiz Erişim'
            ], false);
            return die();
        }
    }

    private function checkApiKey(){
        $headerKey = $_SERVER['HTTP_X_EMEYZ_KEY'] ?? 'notApiKeyHeader';
        $envKey = $_ENV['API_KEY'] ?? 'notApiKey';
        return $headerKey===$envKey;
    }

    private function checkAccess(){
        return $this->checkApiKey();
        
    }


    protected function renderError(){
        return $this->json([
            'message' => 'Hatalı İstek'
        ], false);
    }
    
    
    
    
}

