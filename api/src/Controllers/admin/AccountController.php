<?php 
namespace Atabasch\Controllers\Admin;

use Atabasch\Helpers\Hash;
use Atabasch\Helpers\Validator;
use Atabasch\Models\User;

use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;
use \Firebase\JWT\Key;

class AccountController extends \Atabasch\BaseController{

    private $user = null;

    public function __construct(){
        parent::__construct();
        $this->user = new User();
    }


    public function index($id=null){
       
        if($id){
            return $this->getSingleUser($id);
        }else{
            return $this->getAllUser();
        }
    }

    private function getAllUser(){
        $sql    = "SELECT * FROM users ORDER BY id DESC";
        $users  = $this->db->queryAll($sql);
        $total  = $this->db->getTotalOfTable('users');
        return $this->response(['users' => $users, 'total' => $total]);
    }

    private function getSingleUser($id=null){
        if(!$id){
            $this->response(['user' => []], false); 
        }
        $sql    = "SELECT * FROM users WHERE id=? ORDER BY id DESC";
        $user   = $this->db->queryOne($sql, [$id]);
        $user->datas = json_decode($user->datas);
        return $this->response(['user' => $user]);
    }


    // KULLANICI KAYITI
    public function create(){
        $message = '';
        if($this->hasRequestMethod("POST")){
            $user   = $this->post('user', []);
            $accept = $user['accept'] ?? false;
            if(!$accept){
                $message = 'Hesap sorumluluklarını kabul etmediniz.';
            }else{
                $sqlUsername    = "SELECT * FROM users WHERE `name`=?";
                $sqlEmail       = "SELECT * FROM users WHERE `email`=?";

                if($this->db->queryOne($sqlUsername, [$user['name']])){
                    $message = 'Kullanıcı adı sistemde zaten kayıtlı.';

                }elseif($this->db->queryOne($sqlEmail, [$user['email']])){
                    $message = 'E-posta adresi ile daha önce kayıt olunmuş';

                }else{

                    $validate   = new Validator($user, $this->user->rules);
                    $errors     = $validate->getResult();
                    if($errors){
                        return $this->response(['errors' => $errors], false, 'hatalar oldu');
                    }

                    $user['birthday']           = (strlen($user['birthday']) < 10)? null : $user['birthday'];
                    $register = $this->user->create($user);
                    if(!$register){
                        $message = 'Kayıt işlemi sırasında beklenmeyen bir hata ile karşılaşıldı. Lütfen daha sonra tekrar deneyin.';
                    }else{
                        $userData = $this->user->one($register, 'array');
                        if($userData){
                            return $this->response(['user' => $userData], true, 'Kullanıcı kayıt işlemi başarılı oldu.');
                        }
                    }

                }
            }
        }
        return $this->response([], false, $message);
    }




    // KULLANICI HESABINI GÜNCELLE
    public function update($id=null){
        $message = '';
        if($this->hasRequestMethod("POST") && $id){
            $user   = $this->post('user', []);
            
            $sqlUsername    = "SELECT * FROM users WHERE `name`=? AND `id`!=?";
            $sqlEmail       = "SELECT * FROM users WHERE `email`=? AND `id`!=?";

            if($this->db->queryOne($sqlUsername, [$user['name'], $user['id']])){
                $message = 'Kullanıcı adı başak bir hesaba kayıtlı';

            }elseif($this->db->queryOne($sqlEmail, [$user['email'], $user['id']])){
                $message = 'E-posta adresi ile başka bir hesap oluşturulmuş';

            }else{
                unset($this->user->rules['password']);
                $validate   = new Validator($user, $this->user->rules);
                $errors     = $validate->getResult();
                if($errors){
                    return $this->response(['errors' => $errors], false, 'hatalar oldu');
                }

                $user['birthday']           = (strlen($user['birthday']) < 10)? null : $user['birthday'];
                $update = $this->user->update($user, $id);
                if(!$update){
                    $message = 'Güncelleme işlemi sırasında beklenmeyen bir hata ile karşılaşıldı. Lütfen daha sonra tekrar deneyin.';
                }else{
                    $userData = $this->user->one($update, 'obj');
                    if($userData){
                        $userData->datas = json_decode($userData->datas);
                        return $this->response(['user' => $userData], true, 'Kullanıcı güncelleme işlemi başarılı oldu.');
                    }
                }

            }
        }
        return $this->response([], false, $message);
    }


    // KULLANICI HESABINI SİL
    public function delete($id=null){
        $message = '';
        if($this->hasRequestMethod("POST") ){
            $me = $this->post('me', []);
            if(!$me || !$id){
                $message = 'Geçerli kullanıcı bilgileri gönderilmedi';
            }else{

                $user = $this->user->one($id, 'array');
                if($me['level'] <= $user['level'] || $user['status'] != 'trash'){
                    $message = 'kullanıcıyı silmek için gerekli yetki sağlanmadı.';
                }else{
                    $delete = $this->user->delete($id);
                    if($delete){
                        return $this->response([], true, 'Kullanıcı Hesabı Silme İşlemi Başarılı');
                    }
                }
                
            }

        }
        return $this->response([], false, $message);
    }




    public function status($id=null){
        $message = 'Hatalı Giriş';
        if(!$this->hasRequestMethod("POST") || !$id){
            $message = 'Hatalı istek yada geçersiz id';
        }else{
            $field  = $this->post('field', null);
            $value  = $this->post('value', null);
            $level  = $this->post('level', null);
            $user   = $this->post('user', null);

            if(is_null($field) || is_null($value) || is_null($level) || is_null($user)){
                $message = 'Yetersiz bilgi gönderildi.';
            }else{
                if($user['level'] <= $level){
                    $message = 'Bu işlem için yeterli yetkiye sahip değilsin';
                }else{
                    $sql = "UPDATE users SET `{$field}`=? WHERE `id`=? AND `level`<?";
                    $update = $this->db->execute($sql, [$value, $id, $user['level']]);
                    if(!$update){
                        $message = 'İşlem sırasında bir sorun oluştu';
                    }
                    return $this->response([], true, 'Kullanıcı Güncellendi.');
                }
            }
            
        }
        return $this->response([], false, $message);
    }




    private function getDataFromToken($token=null){
        if($token){
            try{
                $key    = new Key($ENV['API_KEY'] ?? 'emeyz', 'HS512');
                $datas  = JWT::decode($token, $key);
                return $datas;
            }catch(\Exception $e){ 
                return false;
            }
        }
        return false;
    }


    private function getUserDataForState($user){
        return [
            'id'        => $user->id,
            'username'  => $user->name,
            'email'     => $user->email,
            'level'     => $user->level,
            'fullname'  => $user->fullname,
            'status'    => $user->status,
            'isAdmin'   => $user->level > 2,
            'isAuthor'  => $user->level==2,
        ];
    }


}

?>