<?php 
namespace Atabasch\Controllers\Admin;

use Atabasch\Uploader;
use Atabasch\Config;

class MediaController extends \Atabasch\Controllers\AdminController{
    private $uploadPath = PATH_MEDIA_UPLOAD;

    public function index(){
        $offset = $this->get("offset", 0);
        $limit  = $this->get("limit",   20);
        $sql    = "SELECT * FROM media ORDER BY id DESC LIMIT {$offset}, {$limit}";
        $files  = $this->db()->queryAll($sql);
        $this->response(['files' => $files]);
    }


    /**
     * bİR MEDYA DOSYASINI SUNUCUYA YÜKLER.
     */
    public function upload(){
        if(!$this->hasRequestMethod("post")){
            return $this->response([]);
        }

        $selectedFile = $_FILES['file'];
        $name = time().'_'.rand(1000000,99999999);
        $uploader = new Uploader(['resize' => true, 'cover' => true, 'max_size' => (1024*1024)*3, 'path' => PATH_MEDIA_UPLOAD]);
        $upload = $uploader->upload($selectedFile, $name, [
            'cover' => false,
            'crop'    => Config::bool('img_lg_crop', false), 
            'width'   => Config::get('img_lg_w', 1170), 
            'height'  => Config::get('img_lg_h', 720), 
            'quality' => Config::get('img_lg_quality', 100), 
            'pre'     => 'lg_',]);
        

        if(!$upload->status){ 
            return $this->response([], $upload->status, $upload->message);
        }else{
          $upladedFilePath = $this->uploadPath.'/'.$upload->file->name;
          $uploader->upload($selectedFile, $name, [
            'ratio' => true,
            'crop'    => Config::bool('img_md_crop', true), 
            'width'   => Config::get('img_md_w', 850), 
            'height'  => Config::get('img_md_h', 510), 
            'quality' => Config::get('img_md_quality', 90), 
            'pre'     => 'md_',]);

        $uploader->upload($selectedFile, $name, [
            'crop'    => Config::bool('img_sm_crop', true), 
            'width'   => Config::get('img_sm_w', 600), 
            'height'  => Config::get('img_sm_h', 450), 
            'quality' => Config::get('img_sm_quality', 90), 
            'pre'     => 'sm_',]);


            $data = [
                'title' => $name,
                'alt'   => $name,
                'src'   => $upload->file->nameWithoutPre,
                'type'  => $upload->file->type,
                'info'  => [
                    'width'     => $upload->file->width,
                    'height'    => $upload->file->height,
                    'mime'      => $upload->file->mime,
                    'ext'       => $upload->file->ext,
                    'size'      => $upload->file->size
                ],
                'author'        => $this->authData()->uid ?? 1,
                'app'           => 'media'
            ];
            return $this->addToDb($data);

        }

    }


    /**
     *  # Veritabanından ve klasörden medya dosyası siler.
     *  - Route     : /admin/media/delete
     *  - Methods   : POST,
     *  - Params    : (int)id 
     */
    public function delete(){
        $message = null;
        $status  = false;

        $id = $this->post('id', false);
        if(!$this->hasRequestMethod("POST")){
            $message = 'Geçersiz istek türü';
        }elseif(!$id){
            $message = 'Yetersiz veri gönderimi.';
        }else{
            $file = $this->db->queryOne("SELECT id, src FROM media WHERE id=?", [$id]);
            if(!$file){
                $message = 'Silinmesi gerek bir medya dosyası bulunamadı.';
            }else{
                if(!$this->db->execute("DELETE FROM media WHERE id=?", [$id])){
                    $message = 'Silinmesi istenilen dosya kayıtlarda bulunamadı';
                }else{
                    $preArr = ['', 'lg_', 'md_', 'sm_'];
                    foreach($preArr as $pre){
                        $path = PATH_MEDIA_UPLOAD . '/' . $pre . $file->src;
                        if(file_exists($path)){
                            unlink($path);
                        }
                    }
                    $status  = true;
                    $message = 'Medya dosyası silindi.';
                }
            }
        }
        $this->response([], $status, $message);
    } // remove

    /**
     * Karşıya yüklenen görseli database'e ekler
     */
    private function addToDb($data){
        $sql = "INSERT INTO media(title, alt, src, `type`, info, author, app) VALUES(?,?,?,?,?,?,?)";
        $datas = [
            $data['title'],
            $data['alt'],
            $data['src'],
            $data['type'],
            json_encode($data['info']),
            $data['author'],
            $data['app']
        ];
        $insert = $this->db->execute($sql, $datas, true);
        if(!$insert){
            return $this->response([], false);
        }else{

            $file = $this->db()->queryOne("SELECT * FROM media WHERE id=? LIMIT 1", [$insert]);
            return $this->response(['file' => $file]);
        }
    }
        

        

}
?>