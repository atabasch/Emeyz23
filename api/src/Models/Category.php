<?php 
namespace Atabasch\Models;

use Atabasch\Validator;

class Category extends \Atabasch\Model{

    protected $table = 'blog_categories';
    protected $fields = ['id', 'title', 'slug', 'description', 'cover', 'parent', 'status', 'hide', 'created_at', 'updated_at'];
    public $rules = [
        'title' => [
            'required'  => [true, ''],
            'minLength' => [3],
            'maxLength' => [40]
        ],
        'slug' => [
            'required'  => [true, ''],
            'minLength' => [3],
            'maxLength' => [60]
        ],
    ];

    public function all(){
        $sql = "SELECT * FROM {$this->table} ORDER BY title ASC";
        return $this->queryAll($sql);
    }

    public function one($id){
        $sql = "SELECT * FROM {$this->table} WHERE id=?";
        return $this->queryOne($sql, [$id]);
    }

    public function create($datas){
        $datas      =  $this->getAllowedFields($datas);
        $fieldNames = implode(',', array_keys($datas));
        $marks      = str_repeat('?,', count($datas)-1).'?';
        $sql        = "INSERT INTO {$this->table}({$fieldNames}) VALUES({$marks})";
        return $this->execute($sql, array_values($datas), true);
    }

    public function update($datas, $id){
        $datas      =  $this->getAllowedFields($datas);
        
        $fields = [];
        foreach($datas as $key => $val){
            $fields[] = "{$key}=?";
        }
        $fieldNames = implode(',', $fields);

        $sql        = "UPDATE {$this->table} SET {$fieldNames} WHERE id=? ";
        return $this->execute($sql, array_merge(array_values($datas), [$id]));
    }



    public function delete($id){
            $sql = "DELETE FROM {$this->table} WHERE id=?";
            return $this->execute($sql, [$id]);
    }

}

?>