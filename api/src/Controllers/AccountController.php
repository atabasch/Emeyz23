<?php 
namespace Atabasch\Controllers;

use Atabasch\Helpers\Hash;
use Atabasch\Helpers\Validator;
use Atabasch\Models\User;
use Atabasch\Config;
use Atabasch\Mailer;
use Atabasch\Uploader;

use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;
use \Firebase\JWT\Key;

class AccountController extends \Atabasch\BaseController{

    private $user = null;

    public function __construct(){
        parent::__construct();
        $this->user = new User();
    }


    public function index(){
        
        return $this->response([]);
    }


    /**
     * Kullanıcı Giriş İşlemleri
     */
    public function login(){
        $message = 'Geçersiz İstek';
        if($this->hasRequestMethod("POST")){
            // TODO: e-posta ile de giriş yapılabilrisn.
            $username   = $this->post('username', '', true);
            $password   = $this->post('password', '', true);
            $status     = 'active';
            if(strlen($username) < 3 || strlen($password) < 6){
                $message = 'Kullanıcı bilgileri geçersiz.';
            }else{

                $sql = "SELECT * FROM users WHERE name=? AND level>0 AND status=? LIMIT 1";
                $user = $this->db()->queryOne($sql,[$username, $status]);

                if(!$user){
                    $message = 'Sisteme kayıtlı böyle bir hesap bulunamadı.';
                }else{
                    $hash = new Hash();
                    if(!$hash->verify($password, $user->password)){
                        $message = 'Hesap bilgileri hatalı veya eksik';
                    }else{
                        $datas = $this->getLoginToken($user);
                        return $this->response($datas, true, 'Kullanıcı Girişi Başarılı'); 
                    } // $hash->verify
                } // $user
            }
        }
        return $this->response([], false, $message);
    }




    /**
     * Kullanıcı Kayıt İşlemleri
     */
    public function register(){
        $message = '';
        if($this->hasRequestMethod("POST")){
            $user   = $this->post('user', []);
            $accept = $user['accept'] ?? false;
            if(!$accept){
                $message = 'Kullanım koşulları kabul edilmedi.';
            }else{
                $sqlUsername    = "SELECT * FROM users WHERE name=?";
                $sqlEmail       = "SELECT * FROM users WHERE email=?";

                if($this->db->queryOne($sqlUsername, [$user['name']])){
                    $message = 'Kullanıcı adı sistemde zaten kayıtlı.';

                }elseif($this->db->queryOne($sqlEmail, [$user['email']])){
                    $message = 'E-posta adresi ile daha önce kayıt olunmuş';

                }else{

                    $validate   = new Validator($user, $this->user->rules);
                    $errors = $validate->getResult();
                    if($errors){
                        return $this->response(['errors' => $errors], false, 'hatalar oldu');
                    }

                    $user['birthday']           = (strlen($user['birthday']) < 10)? null : $user['birthday'];
    
                    $register = $this->user->register($user);
                    if(!$register){
                        $message = 'Kayıt işlemi sırasında beklenmeyen bir hata ile karşılaşıldı. Lütfen daha sonra tekrar deneyin.';
                    }else{
                        $userData = $this->user->one($register, 'array');
                        if($userData){
                            $this->sendActivationMail($userData);
                            $resultData = array_filter($userData, function($k){ 
                                return !in_array($k, $this->user->hiddenFields);
                             }, ARRAY_FILTER_USE_KEY);
                            return $this->response(['user' => $resultData], true, 'Kullanıcı kayıt işlemi başarılı oldu.');
                        }
                    }

                }
            }
        }
        return $this->response([], false, $message);
    } // register





    /**
     * Kullanıcı Bilgileri Güncelleme
     */
    public function update(){
        $auth = $this->authData();
        if(!$this->hasRequestMethod("POST") && $auth){
            return $this->response([], false, "Geçersiz istek veya eksik bilgi");
        }

        $user           = $this->post('user', []);
        $sqlUsername    = "SELECT * FROM users WHERE name=? AND id!=?";
        $sqlEmail       = "SELECT * FROM users WHERE email=? AND id!=?";

        if($this->db->queryOne($sqlUsername, [$user['name'], $auth->uid])){
            return $this->response([], false, 'Kullanıcı adı başka bir hesap adına kayıtlı.');
        }

        if($this->db->queryOne($sqlEmail, [$user['email'], $auth->uid])){
            return $this->response([], false, 'E-posta adresi ile başka bir hesap oluşturulmuş');
        }

        unset($this->user->rules['password']);
        unset($this->user->rules['cover']);
        $validate   = new Validator($user, $this->user->rules);
        $errors = $validate->getResult();
        if($errors){
            return $this->response(['errors' => $errors], false, 'Hatalar ile karşılaşıldı');
        }

        $user['birthday']   = (strlen($user['birthday']) < 10)? null : $user['birthday'];

        $update             = $this->user->update($user, $auth->uid);

        if(!$update){
            return $this->response(['errors' => $errors], false, 'Kayıt işlemi sırasında beklenmeyen bir hata ile karşılaşıldı. Lütfen daha sonra tekrar deneyin.');
        }

        $userData = $this->user->one($auth->uid);
        if($userData){
            $resultData = $this->getUserDataForState($userData);
            return $this->response(['user' => $resultData], true, 'Kullanıcı kayıt işlemi başarılı oldu.');
        }

    } // update




    /**
     * Kullanıcı hesabını onaylamak için hesap varlık kontrolü
     */
    public function check(){
        $message = 'Geçersiz İstek';
        if($this->hasRequestMethod("POST")){
            $value = $this->post('value', null);
            if($value){
                $user = $this->db->queryOne("SELECT id, name, email, status, level FROM users WHERE (`email`=? OR `name`=?) AND `status`='passive'", [$value,$value]);
                if(!$user){
                    $message = "'{$value}' ile ilişkili onaylanacak bir hesap bulunamadı.";
                }else{
                    if($user->level < 0){
                        $message = 'Bu hesap yönetim tarafından engellenmiş ve aktivasyonu mümkün değildir.';
                    }else{
                        return $this->response(['user' => $user], true);
                    }
                }
            }
        }
        return $this->response([], false, $message);
    }

    

    /**
     * Girilen aktivasyon kodu ile geçerli olan kullanıcı hesabını onaylamak
     */
    public function activate(){
        $message = 'Geçersiz İstek';
        if($this->hasRequestMethod("POST")){
            $email  = $this->post('email', null);
            $code   = $this->post('code', null);
            if(!$email || !$code){
                $message = 'Hatalı veya Eksik değer';
            }else{
                $update = $this->db->execute("UPDATE users SET `status`='active', `level`=1, `activation_code`=NULL WHERE `email`=? AND `activation_code`=? AND `status`='passive' AND `level`=0", [$email,$code]);
                if(!$update){
                    $message = 'Hesap bilgisi veya doğrulama kodu hatalı.';
                }else{
                    $user = $this->db->queryOne("SELECT * FROM users WHERE `email`=? AND `status`='active' AND `level`>0 LIMIT  1", [$email]);
                    if(!$user){
                        return $this->response([], true, 'Tebrikler. Hesabınız Aktifleştirildi.');
                    }else{
                        $datas = $this->getLoginToken($user);
                        return $this->response($datas, true, 'Tebrikler. Hesabınız Aktifleştirildi.');
                    }
                    
                }
            }
        }
        return $this->response([], false, $message);
    }


    /**
     * Oturum Kapatma
     */
    public function logout(){
        $this->response(['deleted'=>true]);
    }

    /**
     *
     *
     */
    public function refresh(){
        $data = $this->post();
        $this->response($data);
    }

    /**
     * Giriş Yapmış Kullanıcıya ait bilgileri verir.
     */
    public function me(){
        $datas = $this->authData();
        if($datas){
            $user = $this->user->one($datas->uid); 
            if($user){
                $otherDatas = [
                    'posts'     => $this->getArticlesOfMe($datas->uid),
                    'comments'  => $this->getCommentsOfMe($datas->uid)
                ];
                $userData = $this->getUserDataForState($user);
                return $this->response(array_merge(['user' => $userData], $otherDatas));
            }
        }
        return $this->response(['user' => null], false);
    }

    // Giriş yapan kullanıcının makaleleri
    private function getArticlesOfMe($id){
        $limit = $this->get('articles', null);
        if(!$limit){ return []; }
        $parts = explode(',', $limit);
        $offset = count($parts)>1? ($parts[0] ?? 0) : 0;
        $limit  = count($parts)>1? ($parts[1] ?? 5) : 5;
        return $this->user->myArticles($id, $offset, $limit);
    }
    // Giriş yapan kullanıcının yorumları
    private function getCommentsOfMe($id){
        //TODO: Yorum tablosu oluştur ve yorumları çek
        $limit = $this->get('comments', null);
        if(!$limit){ return []; }
        $parts = explode(',', $limit);
        $offset = count($parts)>1? ($parts[0] ?? 0) : 0;
        $limit  = count($parts)>1? ($parts[1] ?? 5) : 5;
        return $this->user->myComments($id, $offset, $limit);
    }

    /**
     * Kullanıcı Hesap Parolasını Değiştir
     */
    public function updatePassword(){
        $message = 'Hatalı istek';
        if($this->hasRequestMethod("POST") && $this->authData()){
            $password = $this->post('password', '');
            $uid      = $this->authData()->uid;
            $user     = $this->user->one($uid);

            $hash = new Hash();
            if(!$hash->verify($password['current'], $user->password)){
                $message = 'Geçerli şifre hatalı girildi.';
            }elseif($password['new'] !== $password['confirm']){
                $message = 'Yeni hesap şifresi doğrulanamadı.';
            }else{
                $newPassword = $hash->create($password['new']);
                $update      = $this->db->execute("UPDATE users SET `password`=? WHERE id=?", [$newPassword, $uid]);
                if(!$update){
                    $message = 'Hesap şifresi güncellenirken bir sorun oluştu.';
                }else{
                    return $this->response([], true, "Hesap şifresi güncellendi.");
                }
            }

        }
        return $this->response([], false, $message);
    }


    /**
     * Kullanıcı Kapak Fotoğrafını Güncelle
     */
    public function updateCover(){
        $message = 'Hatalı istek';
        if($this->hasRequestMethod("POST") && $this->authData()){
            $filename       = $this->post('filename', '');
            $oldfilename    = $this->post('oldfilename', null);
            $uid            = $this->authData()->uid;

            if($this->user->updateCover($uid, ($filename ?? null) )){
                if($oldfilename && $oldfilename != $filename){
                     if(file_exists(PATH_MEDIA_USER.'/'.$oldfilename)){
                        unlink(PATH_MEDIA_USER.'/'.$oldfilename);
                     }
                }
                $url =  Config::get('url_media', '') . '/' . Config::get('path_media_user', 'upload') . '/' . $filename ?? '';
                return $this->response(['url' => $url], true, (!$filename? "Profil fotoğrafı kaldırıldı." : "Profil fotoğrafı güncellendi."));
            }else{
                $message = 'Kapak fotoğrafı güncellenemedi';
            }

        }
        return $this->response([], false, $message);
    }


    /**
     *
     */
    public function access(){
        if($this->hasRequestMethod("POST")){
            $type   = $this->post('type', '');
            $token  = $this->post('token', null);
            try{
                $key    = new Key($ENV['API_KEY'] ?? 'emeyz', 'HS512');
                $datas  = JWT::decode($token, $key);
                return $this->json($datas);
            }catch(\Exception $e){ 
                return $this->response(['error'=>$e], false,  'Geçersiz Token');
            }
        }
        return $this->response([], false, 'Hatalı istek');
    }


    /**
     *
     */
    private function getLoginToken($user){
        $payload = [
            'sub'       => 'Account',
            'iss'       => 'Emeyz',
            'iat'       => time(),
            'exp'       => time() + (60 * (int)(Config::get('login_timeout') ?? 60)),
            'uid'       => $user->id,
            'name'      => $user->name,
            'fullname'  => $user->fullname,
            'level'     => $user->level,
        ];
        $updateLastLogin = $this->user->updateLastLogin($user->id);
        $jwt = JWT::encode($payload, $ENV['API_KEY'] ?? 'emeyz', 'HS512');
        return [
            'token' => $jwt,
            'user'  => $this->getUserDataForState($user)
        ];
    }


    private function getUserDataForState($user){
        return [
            'id'            => $user->id,
            'name'          => $user->name,
            'username'      => $user->name,
            'email'         => $user->email,
            'level'         => $user->level,
            'fullname'      => $user->fullname,
            'description'   => $user->description,
            'birthday'      => $user->birthday,
            'gender'        => $user->gender,
            'status'        => $user->status,
            'isAdmin'       => $user->level > 2,
            'isAuthor'      => $user->level==2,
            'datas'         => json_decode($user->datas ?? []),
            'cover'         => $user->cover,
            'cover_url'     => $this->user->getCoverUrl(['cover'=>$user->cover, 'gender'=>$user->gender]),
            'created_at'    => $user->created_at,
            'logged_at'     => $user->logged_at,
        ];
    }




    private function sendActivationMail($user){
        $title = "Emeyz Hesap Aktivasyon Kodu";
        $content = file_get_contents(__DIR__.'/../Templates/mail.activation.html');
        $content = str_replace("{{ username }}", $user['name'], $content);
        $content = str_replace("{{ code }}", $user['activation_code'], $content);
        $urlActivation = Config::get('url', '')."/login?".$user['email']."&code=".$user['activation_code'];
        $content = str_replace("{{ url_activation }}", $urlActivation, $content);
        
        $mailer = new Mailer();
        $mailer->send($title, $content, $user['email']);
    }


}

?>