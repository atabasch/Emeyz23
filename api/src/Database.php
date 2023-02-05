<?php
namespace Atabasch;

class Database extends \Atabasch\Main{

    protected $db = null;
    protected $returnTypes  = [
        'array'     => \PDO::FETCH_ASSOC,
        'object'    => \PDO::FETCH_OBJ,
        'obj'       => \PDO::FETCH_OBJ,
    ];
    protected $config = [];

    public function __construct(){
        parent::__construct();
        try {
            $this->db = new \PDO("mysql:host={$_ENV['DBHOST']};dbname={$_ENV['DBNAME']};charset={$_ENV['DBCHAR']}", $_ENV['DBUSER'], $_ENV['DBPASS']);
        }catch (\PDOException $error){
            print_r($error->getMessage());
        }

    }


    public function connection(){
        return $this->db;
    }

    public function queryAll($sql, $datas=[], $returnType=null){
        $query = $this->db->prepare($sql);
        $query->execute($datas);
        return $query->fetchAll($this->getReturnType($returnType));
    }

    public function queryOne($sql, $datas=[], $returnType=null){
        $query = $this->db->prepare($sql);
        $query->execute($datas);
        return $query->fetch($this->getReturnType($returnType));
    }

    // public function insert($sql, $datas=[]){
    //     $query = $this->db->prepare($sql);
    //     $query->execute($datas);
    //     return $this->db->lastInsertId();
    // }

    public function execute($sql, $datas=[], $getLastID=false){
        $query = $this->db->prepare($sql);
        $query->execute($datas);
        if($getLastID){
            return $this->db->lastInsertId();
        }
        return $query->rowCount();
    }

    public function getTotalOfTable($tableName='', $where=null, $datas=[]){
        $sql    = "SELECT count(*) AS total FROM $tableName";
        $query  = $this->db->prepare($sql);
        $query->execute(array_merge([], $datas));
        return $query->fetchColumn(0);
    }

    private function getReturnType($type=null){
        return !$type? \PDO::FETCH_OBJ : ($this->returnTypes[strtolower($type)] ?? \PDO::FETCH_OBJ);
    }



}
