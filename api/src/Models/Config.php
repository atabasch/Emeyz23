<?php 
namespace Atabasch\Models;

class Config extends \Atabasch\Model{


    public function all(){
        $sql = "SELECT * FROM config";
        return $this->queryAll($sql);
    }

    public function filter($fields=null){
        if($fields){
            $sqlValues  = explode(',', $fields);
            $sqlMarks   = substr(str_repeat(',?', count($sqlValues)), 1);
            $sql        = "SELECT * FROM config WHERE `KEY` IN ($sqlMarks)";
            return      $this->queryAll($sql, $sqlValues);
        }
        return false;
    }

    public function update($key, $val){
        $sql = "UPDATE config SET `val`=? WHERE `key`=?";
        return $this->execute($sql, [$val, $key]);
    }


}
?>