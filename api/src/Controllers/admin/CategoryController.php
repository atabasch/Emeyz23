<?php 
namespace Atabasch\Controllers\Admin;

use Atabasch\Models\Category;
use Atabasch\Helpers\Validator;

class CategoryController extends \Atabasch\Controllers\AdminController{

    public $category = null;

    public function __construct(){
        parent::__construct();
        $this->category = new Category();
    }

    public function index($id=null){
        if(!$id){   return $this->getAll();     }
        else{       return $this->getOne($id);  }
    }

    private function getAll(){
        $this->response(['categories' => $this->category->all()]);
    }

    private function getOne($id){
        $this->response(['category' => $this->category->one($id)]);
    }

    public function create(){
        if($this->hasRequestMethod('POST')){
            $postDatas  = $this->post();
            $validator  = new Validator($postDatas, $this->category->rules);
            $errors     = $validator->getResult();
            if(!$errors){
                $id = $this->category->create($postDatas);
                return $this->response(['category' => $this->category->one($id)]);
            }
        }
        return $this->response(['errors' => $post['errors'] ?? null], false);
    }

    public function update($id=null){
        if($this->hasRequestMethod('POST') && $id){
            $postDatas  = $this->post();
            $validator  = new Validator($postDatas, $this->category->rules);
            $errors     = $validator->getResult();
            if(!$errors){
                $updated = $this->category->update($postDatas, $id);
                if($updated){
                    return $this->response(['category' => $this->category->one($id)]);
                }
            }
        }
        return $this->response(['errors' => $errors ?? null], false);
    }

    public function status($id){
        if($this->hasRequestMethod('POST') && $id){
            $status  = $this->post('status', null);
            if($status){
                $updated = $this->category->update(['status'=>$status], $id);
                if($updated){
                    return $this->response(['category' => $this->category->one($id)]);
                }
            }
        }
        return $this->response(['errors' => $post['errors'] ?? null], false);
    }

    public function delete($id=null){
        if($this->hasRequestMethod('POST') && $id){
            return $this->response([]);
        }
        return $this->response(['errors' => $post['errors'] ?? null], false);
    }


}

?>