<?php
namespace Atabasch\Models;

use Atabasch\Validator;

class Post extends \Atabasch\Model {

    protected $table = "articles";
    protected $fields = [
                    'title',
                    'slug',
                    'keywords',
                    'description',
                    'summary',
                    'author',
                    'status',
                    'content',
                    'views',
                    'cover',
                    'video',
                    'hide_cover',
                    'allow_comments',
                    'list_order',
                    'p_time',
                    ];

    private $rules = [
        'title'         => [
            'required'  => [true, 'Başlık boş bırakılamaz'],
            'type'      => ['string', 'Başlık alanı metinsel bir değer olmalı'],
            'minLength' => [16, 'Başlık en az %number karakterden oluşmalı'],
            'maxLength' => [70, 'Başlık en fazla %number karakter olabilir']
        ],
        'description'   => [
            'required'  => [false, 'Açıklama boş bırakılamaz'], 
            'maxLength' => [160, 'Açıklama en fazla %number karakter olabilir']
        ],
        'author'        => [
            'required'  => [false, 'Geçersiz yazar id si'],
            'type'      => ['int', 'Yazar alanı integer bir değer almalı'],
        ],
        'status'        => [
            'required'  => [true, 'İçerik yayımlama durumu gerekli.'],
            'enum'      => [['published', 'draft', 'trash']]
        ],
        'content'       => [
            'required'  => [true, 'İçerik alanı boş bırakılamaz'],
            'minLength' => [25, 'Lütfen en az %number karakterli bir içerik yazısı girin.']
        ],
        'hide_cover'    => [
            'enum'      => [['on', 'off'], 'Geçersiz girdi']
        ],
        'allow_comments'=> [
            'enum'      => [['on', 'off'], 'Geçersiz girdi']
        ]

    ];

    public function getPosts($isAll=false){
        $where = !$isAll? "WHERE status='published'" : null;
        return $this->queryAll("SELECT * FROM articles {$where} ORDER BY id DESC");
    }
    public function getPost($idOrSlug, $isAll=false){
        $where = !$isAll? "AND status='published'" : null;
        $sql = "SELECT * FROM articles WHERE (id=? OR slug=?) {$where}";
        $post = $this->queryOne($sql, [$idOrSlug, $idOrSlug]);

        if(!$post){ return false; }

        $postItems = $this->queryAll("SELECT * FROM lists WHERE status='published' AND parent=? ORDER BY `order`", [$post->id]);

        $categoriesSql = "SELECT cat.id,cat.title,cat.slug FROM blog_categories as cat
                          INNER JOIN conn_art_cat as conn
                          ON conn.blog_category_id=cat.id 
                          WHERE conn.article_id=?";
        $categories = $this->queryAll($categoriesSql, [$post->id]);

        $post->categories = $categories;
        $post->contentItems = $postItems;

        return $post;
    }

//    public function getAllPublished(){
//        $sql = "SELECT * FROM articles WHERE status='published' ORDER BY id DESC";
//    }

    public function create($datas){

            $validator = new Validator($datas, $this->rules);
            $errors = $validator->getResult();
            if($errors){
                return ['status'=>false, 'errors'=>$errors];
            }else{

                $sql = "INSERT INTO articles(title, slug, keywords, `description`, summary, author, `status`, content, cover, video, hide_cover, allow_comments, p_time ) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $values = [
                    $datas['title'],
                    $datas['slug'],
                    $datas['keywords'],
                    $datas['description'],
                    $datas['summary'],
                    $datas['author'],
                    $datas['status'],
                    $datas['content'],
                    $datas['cover'],
                    $datas['video'],
                    $datas['hide_cover'],
                    $datas['allow_comments'],
                    $datas['p_time']
                ];
                $lastInsertId = $this->execute($sql, $values, true);
                if(!$lastInsertId){
                    return ['status'=>false, 'error'=>null];
                }else{
                    $sql = "INSERT INTO conn_art_cat(article_id, blog_category_id) VALUES(?,?)";
                    foreach($datas['category_ids'] as $cid){
                        $this->execute($sql, [$lastInsertId, $cid]);
                    }
                }
                return ['status'=>true, 'id'=>$lastInsertId];
            }
    }



    public function insert($datas){
        $alowedFields   = $this->fields;
        $insertDatas    = array_filter($datas, function($v, $k)use($alowedFields){ return in_array($k, $alowedFields); }, ARRAY_FILTER_USE_BOTH);
        $sqlMarks       = substr(str_repeat(',?', count($insertDatas)), 1);
        $sqlFields      = implode(',', array_map(function($v){ return "`{$v}`"; }, array_keys($insertDatas)));
        $sqlValues      = array_values($insertDatas);
        $sql            = "INSERT INTO articles({$sqlFields}) VALUES({$sqlMarks})";

        $create         = $this->execute($sql, $sqlValues, true);
        if($create){
            $this->connectCategory($datas['categories'], $create);
        }
        return $create;
    }

    private function connectCategory($categories, $articleId){
        $category_ids = $categories;
        if(is_string($categories)){
            $category_ids = explode(',', $categories);
        }
        $this->execute("DELETE FROM conn_art_cat WHERE article_id=?", [$articleId]);
        $sql = "INSERT INTO conn_art_cat(article_id, blog_category_id) VALUES(?,?)";
        foreach($category_ids as $categoryId){
            $this->execute($sql, [$articleId, $categoryId]);
        }
    }



    public function update($datas, $id){

        $validator = new Validator($datas, $this->rules);
        $errors = $validator->getResult();
        if($errors){
            return ['status'=>false, 'errors'=>$errors];
        }else{

            $sql = "UPDATE articles SET 
                            `title`=?,
                            `slug`=?,
                            `keywords`=?,
                            `description`=?,
                            `summary`=?,
                            `author`=?,
                            `status`=?,
                            `content`=?,
                            `cover`=?,
                            `video`=?,
                            `hide_cover`=?,
                            `allow_comments`=?,
                            `p_time`=?,
                            `updated_at`=NOW()
                    WHERE `id`=?";
            $values = [
                $datas['title'],
                $datas['slug'],
                $datas['keywords'],
                $datas['description'],
                $datas['summary'],
                $datas['author'],
                $datas['status'],
                $datas['content'],
                $datas['cover'],
                $datas['video'],
                $datas['hide_cover'],
                $datas['allow_comments'],
                $datas['p_time'],
                $id
            ];
            $update = $this->execute($sql, $values);
            if(!$update){

            }else{
                $removeCategoryConnect = $this->execute("DELETE FROM conn_art_cat WHERE article_id=?", [$id]);
                if($removeCategoryConnect){
                    $sql = "INSERT INTO conn_art_cat(article_id, blog_category_id) VALUES(?,?)";
                    foreach($datas['category_ids'] as $cid){
                        $this->execute($sql, [$id, $cid], true);
                    }   
                }
            }
            return ['status'=>true, 'post'=>$update];
        }
    }


    public function updateStatus($status, $id){
        $sql    = "UPDATE articles SET `status`=?, `updated_at`=NOW() WHERE id=?";
        $update = $this->execute($sql, [$status, $id] );
        return $update;
    }   



    public function delete($id){
        $sql = "DELETE FROM articles WHERE id=?";
        $delete = $this->execute($sql, [$id]);
        if($delete){
            $deleteCategoryConnection = $this->execute("DELETE FROM conn_art_cat WHERE article_id=?", [$id]);
            return $delete;
        }
        return false;
    }




}
