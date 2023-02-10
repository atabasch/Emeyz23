<?php 
namespace Atabasch\Controllers\Admin;

use Atabasch\Models\Config;
use Atabasch\Mailer; 

class SettingsController extends \Atabasch\Controllers\AdminController{
    
    public $Config = null;

    public function __construct(){
        parent::__construct();
        $this->Config = new Config();
    }

    public function index(){

        $fields  = $this->get('fields', null);
        if(!$fields){
            $configs = $this->Config->all();
        }else{
            $configs = $this->Config->filter($fields);
        }
        return $this->response([
            'configs' => $configs
        ]);
        
    }

    public function update(){
        $message = 'Geçersiz istek türü';
        if($this->hasRequestMethod("POST")){
            $configs = $this->post('configs', null);
            if(!$configs || !is_array($configs)){
                $message = 'Yetersiz veya Geçersiz veri.';
            }else{
                $counter = 0;
                foreach($configs as $key => $config){
                    if($this->Config->update($config['key'], $config['val'])){
                        $counter++;
                    }
                }
                return $this->response(['configs' => $configs], true, $message = "$counter adet ayar güncellendi.");
            }
        }
        $this->response([], false, $message);
    }


    public function smtp_test(){
        if(!$this->hasRequestMethod("POST")){
            $message = 'Hatalı istek tipi';
        }else{
            $to = $this->post("to", null);
            if(!$to){
                $message = 'Alıcı adresi gönderilmedi';
            }else{
                $title   = "Emeyz Test E-Postası";
                $content = 'Bu e-posta emeyz.com üzerinden test amaçlı gönderilmiştir. Eğer bu e-postayı aldıysanız mail gönderme ayarları başarılıdır.';         
                $mailer = new Mailer();
                $send = $mailer->send($title, $content, $to);
                if(!$send->status){
                    $message = $send->message ?? 'E-posta gönderme işlemi başarısız oldu';
                }else{
                    $status  = true;
                    $message = 'Test e-postası gönderildi.';
                }
            }
        }
        $this->response([], $status ?? false, $message);
    }

}
?>