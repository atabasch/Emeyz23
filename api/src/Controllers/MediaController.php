<?php 
namespace Atabasch\Controllers;

use Verot\Upload\Upload;
use Atabasch\Uploader;

class MediaController extends \Atabasch\BaseController{
    private $uploadPath = __DIR__.'/../upload';

    private function fileUpload($file, $name=null, $options=[]){
        $handle = new Upload($file);
        if(!$handle->uploaded){
            return ['status' => false, 'message' => $handle->error];
        }

        $handle->file_new_name_body =  $name;
        $this->setOptionsToUploader($handle, $options);        
        $handle->process($options['path'] ?? $this->uploadPath);

        if ($handle->processed){
            $datas = $handle;
            $datas->file_name_without_pre = str_replace($options['pre'] ?? '', '', $datas->file_dst_name);
            return ['status' => true, 'data' => $datas];
        }else{
            return ['status' => false, 'message' => $handle->error];
        }
    }

    private function setOptionsToUploader($handle, $options=[]){
        $handle->image_ratio_crop   = $options['crop'] ?? false;
        $handle->image_resize       = $options['resize'] ?? false;
        $handle->image_x            = $options['width'] ?? 1600;
        $handle->image_y            = $options['height'] ?? 900;
        $handle->image_ratio_y      = !$options['cover']? true : false;
        $handle->file_name_body_pre = $options['pre'] ?? '';
        $handle->png_compression    = $options['compress'] ?? 3;
        $handle->jpeg_quality       = $options['quality'] ?? 85;
        $handle->file_max_size      = $options['max_size'] ?? (1024*1024)*5;
        $handle->allowed            = $options['allowed'] ?? array('image/*');
        $handle->file_overwrite     = $options['overwrite'] ?? false;
        return $handle;
    }


    public function index(){
        $this->response([]);
    }


    public function upload($type=null){
        switch($type){
            case "avatar":
                return $this->uploadAvatar();
                break;

            default:
                return $this->uploadMedia();
        }
        // if(!$this->hasRequestMethod("post")){
        //     return $this->json([]);
        // }

        // $selectedFile = $_FILES['file'];
        // $name = time().'_'.rand(99999,999999);
        // $upload = $this->fileUpload($selectedFile, $name, [
        //     'crop'      => true,
        //     'resize'    => true,
        //     'width'     => 1170,
        //     'height'    => 702,
        //     'cover'     => false,
        //     'pre'       => 'lg_',
        // ]);

        // if(!$upload['status']){ 
        //     return $this->json(['status' => false]);
        // }else{
        //   $upladedFilePath = $this->uploadPath.'/'.$upload['data']->file_dst_name;
        //   $this->fileUpload($selectedFile, $name, ['crop' => true, 'resize' => true, 'width' => 850, 'height' => 510, 'cover' => true, 'pre' => 'md_']);
        //   $this->fileUpload($selectedFile, $name, ['crop' => true, 'resize' => true, 'width' => 600, 'height' => 450, 'cover' => true, 'pre' => 'sm_']);


        //     $data = [
        //         'title' => $name,
        //         'alt'   => $name,
        //         'src'   => $upload['data']->file_name_without_pre,
        //         'type'  => $upload['data']->file_src_mime,
        //         'info'  => [
        //             'width'     => $upload['data']->image_dst_x,
        //             'height'    => $upload['data']->image_dst_y,
        //             'mime'      => $upload['data']->file_src_mime,
        //             'ext'       => $upload['data']->file_dst_name_ext,
        //             'size'      => $upload['data']->file_src_size
        //         ],
        //         'author'        => $_SESSION['account']->id ?? 1,
        //         'app'           => 'media'
        //     ];
        //     return $this->addToDb($data);

        // }

    }

    private function uploadMedia(){
        return $this->response([]);
    }


    private function uploadAvatar(){
        $message = 'Hatalı İstek.';

        if($this->hasRequestMethod("post")){
            $uploader = new Uploader();
            $selectedFile   = $_FILES['file'] ?? null;
            $message        = 'Eksik bilgi gönderildi';

            if($selectedFile){
                $name           = $this->post('username');
                $upload = $uploader->upload($selectedFile, $name, [
                    'crop'      => true,
                    'resize'    => true,
                    'width'     => 600,
                    'height'    => 450,
                    'cover'     => true,
                    'max_size'  => (1024*1024)*2,
                    'overwrite' => true,
                    'path'      => PATH_MEDIA_USER
    
                ]);
    
                if(!$upload->status){ 
                    $message = $upload->message;
                }else{
                    $data = [
                        'title' => $name,
                        'src'   => $upload->file->nameWithoutPre,
                        'type'  => $upload->file->mime,
                        'info'  => [
                            'width'     => $upload->file->width,
                            'height'    => $upload->file->height,
                            'mime'      => $upload->file->mime,
                            'ext'       => $upload->file->ext,
                            'size'      => $upload->file->size
                        ]
                    ];
                    return $this->response(['file' => $data]);
        
                }

            } // selectedFile
        }

        return $this->response([], false, $message);

        
    } // uploadAvatar





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
            return $this->json(['status' => false, 'message' => ""]);
        }else{

            $file = $this->db()->queryOne("SELECT * FROM media WHERE id=? LIMIT 1", [$insert]);
            return $this->json(['status' => true, 'file' => $file]);
        }
    }
        

        

}
?>