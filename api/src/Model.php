<?php

namespace Atabasch;

class Model extends Database{

    protected $table    = '';
    protected $fields   = [];

    protected function getAllowedFields($datas){
        $queryDatas = [];
        foreach($datas as $field => $value){
            if(in_array($field, $this->fields)){
                $queryDatas[$field] = $value;
            }
        }
        return $queryDatas;
    }



}
