<?php 
namespace Atabasch;

use Verot\Upload\Upload;

class Uploader{

    public $uploadPath = PATH_MEDIA_UPLOAD;
    public $options = [];

    public function __construct($options=[]){
        $this->options = array_merge($this->options, $options);
    }

    public function upload($file, $name=null, $options=[]){
        $this->options = array_merge($this->options, $options);
        $handle = new Upload($file);
        if(!$handle->uploaded){
            return $this->getResult(null, false, $handle->error);
        }

        $handle->file_new_name_body =  $name;
        $this->setOptionsToUploader($handle, $this->options);        
        $handle->process($this->options['path'] ?? $this->uploadPath);

        if ($handle->processed){
            $handle->file_name_without_pre = str_replace($this->options['pre'] ?? '', '', $handle->file_dst_name);
            return $this->getResult($this->getUploadedFileData($handle), true, null);
        }else{
            return $this->getResult(null, false, $handle->error);
        }
    }

    private function setOptionsToUploader($handle, $options=[]){
        $handle->image_ratio        = $options['ratio'] ?? false;
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

    private function getUploadedFileData($uploadedFileData){
        return [
            'oldName'               => $uploadedFileData->file_src_name,
            'oldNameWithoutExt'     => $uploadedFileData->file_src_name_body,
            'oldExt'                => $uploadedFileData->file_src_name_ext,
            'oldType'               => $uploadedFileData->file_src_mime,
            'oldMime'               => $uploadedFileData->file_src_mime,

            'name'                  => $uploadedFileData->file_dst_name,
            'nameWithoutExt'        => $uploadedFileData->file_dst_name_body,
            'nameWithoutPre'        => $uploadedFileData->file_name_without_pre,
            'ext'                   => $uploadedFileData->file_dst_name_ext,
            'pathWithoutFile'       => $uploadedFileData->file_dst_path,
            'path'                  => $uploadedFileData->file_dst_pathname,
            'type'                  => $uploadedFileData->file_src_mime,
            'width'                 => $uploadedFileData->image_dst_x,
            'height'                => $uploadedFileData->image_dst_y,
            'mime'                  => $uploadedFileData->file_src_mime,
            'size'                  => $uploadedFileData->file_src_size
        ];
    }

    private function getResult($file=null, $status=false, $message=null){
        return json_decode(json_encode([
            'status'    => $status,
            'message'   => $message,
            'file'      => $file
        ]));
    }

}
?>