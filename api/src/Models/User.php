<?php 
namespace Atabasch\Models;

use Atabasch\Validator;
use Atabasch\Config;
use Atabasch\Helpers\Hash;

class User extends \Atabasch\Model{

    protected $table = 'users';
    protected $fields = ['id', 'name', 'email', 'email_verified_at', 'password', 'fullname', 'level', 'description', 'birthday', 'gender', 'datas', 'remember_token', 'created_at', 'updated_at', 'cover', 'status', 'activation_code', 'slug'];
    public $hiddenFields = ['email_verified_at', 'password','remember_token','status', 'activation_code'];
    public $excludeFields = ['id', 'created_at', 'updated_at'];
    
    public $rules = [
        'name' => [
            'required'  => [true, 'Kullanıcı adı gerekli'],
            'minLength' => [3, 'Kullanıcı adı 3 yada daha fazla karakterden oluşmalı'],
            'maxLength' => [16, 'Kullanıcı adı 16 karakterden daha fazla olamaz']
        ],
        'email' => [
            'required'  => [true, 'E-posta adresi gerekli'],
            'minLength' => [10, 'E-posta adresi 10 ile 35 karakter arasında olmalı'],
            'maxLength' => [35, 'E-posta adresi 10 ile 35 karakter arasında olmalı']
        ],
        'password' => [
            'required'  => [true, 'Parola gerekli'],
            'minLength' => [8,  'Parola 8 karakterden daha az olamaz.'],
            'maxLength' => [32, 'Parola 32 karakterden daha fazla olamaz.']
        ]
    ];

    public function all(){
        $sql = "SELECT * FROM {$this->table} ORDER BY title ASC";
        return $this->queryAll($sql);
    }

    public function myArticles($id, $offset, $limit){
        $sql = "SELECT * FROM `articles` WHERE author=? ORDER BY id DESC LIMIT $offset,$limit";
        return $this->queryAll($sql, [$id]);
    }

    public function myComments($id, $offset, $limit){
        $sql = "SELECT * FROM `articles` WHERE author=? ORDER BY id DESC LIMIT $offset,$limit";
        return $this->queryAll($sql, [$id]);
    }

    public function one($id, $returnType='object'){
        $sql = "SELECT * FROM {$this->table} WHERE id=?";
        return $this->queryOne($sql, [$id], $returnType);
    }

    // KULLANICI KAYIT OLUYOR
    public function register($datas, $admin=false){
        $datas                      = $this->getAllowedFields($datas);
        $hash                       = new Hash();
        $datas['level']             = 0;
        $datas['status']            = 'passive';
        $datas['password']          = $hash->create($datas['password']); 
        $datas['activation_code']   = rand(100001,999999);
        $datas['datas']             = json_encode([
            "website" => null, 
            "github" => null, 
            "instagram" => null, 
            "facebook" => null, 
            "youtube" => null, 
            "linkedin" => null 
         ]);

        $fieldNames                 = implode(',', array_keys($datas));
        $marks                      = str_repeat('?,', count($datas)-1).'?';
        $sql                        = "INSERT INTO {$this->table}({$fieldNames}) VALUES({$marks})";

        return $this->execute($sql, array_values($datas), true);
    }

    // YÖNETİCİ KULLANICI EKLİYOR
    public function create($datas, $admin=false){
        $datas                      = $this->getAllowedFields($datas);
        $hash                       = new Hash();
        $datas['password']          = $hash->create($datas['password']); 

        $fieldNames                 = implode(',', array_keys($datas));
        $marks                      = str_repeat('?,', count($datas)-1).'?';
        $sql                        = "INSERT INTO {$this->table}({$fieldNames}) VALUES({$marks})";

        return $this->execute($sql, array_values($datas), true);
    }

   
    // KULLANICI HESABINI GÜNCELLE
    public function update($datas, $id){
        $datas                      = $this->getAllowedFields($datas);
        
        if(strlen($datas['password'] ?? '') >= 8){
            $hash                       = new Hash();
            $datas['password']          = $hash->create($datas['password']); 
        }else{
            unset($datas['password']);
        }

        $datas['datas'] = json_encode($datas['datas']);
        
        $setFields = [];
        $setValues = [];
        foreach($datas as $key => $val){
            if(!in_array($key, $this->excludeFields)){
                $setFields[] = "`{$key}`=?";
                $setValues[] = $val;
            }
        }
        
        
        $fields = implode(',', $setFields);  
        $sql    = "UPDATE {$this->table} SET {$fields} WHERE `id`=? AND `created_at`=?";

        return !$this->execute($sql, array_merge(array_values($setValues), [$id,  $datas['created_at']]))? false : $id;
    } // update


    public function updateCover($id, $cover){
        return $this->execute("UPDATE users SET `cover`=?, `updated_at`=NOW() WHERE id=?", [$cover, $id]);
    }

    // KULLANICININ SON GİRİŞ ZAMANINI GÜNCELLE
    public function updateLastLogin($id){
        return $this->execute("UPDATE users SET logged_at=NOW() WHERE id=?", [$id]);
    }



    public function delete($id){
        $sql = "DELETE FROM {$this->table} WHERE id=?";
        return $this->execute($sql, [$id]);
    }

    
    private function getDefaultAvatarUrl($gender){
        return match($gender){
            "Erkek" => Config::get('url_no_avatar_male', null),
            "Kadın" => Config::get('url_no_avatar_female', null),
            default => Config::get('url_no_avatar_people', null),
        };
    }


    public function getCoverUrl($user=[], $getBaseUrl=false){
        $url = (Config::get('url_media', '') . '/' . Config::get('path_media_user', 'upload') . '/');
        if(!$user['cover']){
            $url = $this->getDefaultAvatarUrl($user['gender'] ?? '');
            return $url;
        }else{
            return $url . $user['cover'];
        }
    }

}

?>