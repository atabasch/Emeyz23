<?php
namespace Atabasch\Controllers;

use Atabasch\Models\Post;
use Cocur\Slugify\Slugify;
use Atabasch\Config;

class PostController extends \Atabasch\BaseController {

    public $postModel = null;

    public function __construct(){
        parent::__construct();
        $this->postModel = new Post();
    }


    public function index($idOrSlug=null){
        if(!$idOrSlug){
            $this->getAll();
        }else{
            $this->getOne($idOrSlug);
        }
    }

    private function getAll(){
        $offset     = $_GET["offset"] ?? 0;
        $limit      = $_GET["limit"] ?? 10;
        $orderBy    = $_GET["orderby"] ?? "id";
        $sort       = $_GET["sort"] ?? "DESC";

        $sql        = "SELECT a.id, a.title, a.slug, a.keywords, a.description, a.summary, a.content, a.views, a.cover, a.video, a.p_time, a.hide_cover,
                           (SELECT JSON_ARRAYAGG(JSON_OBJECT('id', c.id, 'title', c.title, 'slug', c.slug, 'color', c.color)) FROM blog_categories c
                               INNER JOIN conn_art_cat cac on c.id = cac.blog_category_id
                                WHERE cac.article_id=a.id AND c.status='published' AND c.hide=false) AS categories,
                            u.name AS user_name, u.fullname AS user_fullname, u.id AS user_id
                        FROM articles a
                        INNER JOIN users u ON u.id=a.author
                        WHERE a.status='published'
                        ORDER BY a.{$orderBy} {$sort}
                        LIMIT {$offset}, {$limit}";

        $datas      = $this->db()->queryAll($sql);
        $this->json($datas);
    }



    private function getOne($idOrSlug=null){
        $post = $this->postModel->getPost($idOrSlug);
        if(!$post){
            $this->notFound();
        }else{
            $this->json($post);
        }
    }


    public function favorites(){
        if($this->hasRequestMethod("POST")){
           $favorites   = $this->post("favorites", false);
           $offset      = $this->get("offset", 0);
           $limit       = $this->get("limit", 15);

           $favorites   = explode(',', $favorites);
           $inChars     = str_repeat('?,', count($favorites) - 1) . '?';

           if($favorites){
               $sql = "SELECT a.id, a.title, a.keywords, a.description, a.summary, a.content, a.views, a.cover, a.video, a.p_time,
                           (SELECT JSON_ARRAYAGG(JSON_OBJECT('id', c.id, 'title', c.title, 'slug', c.slug)) FROM blog_categories c
                               INNER JOIN conn_art_cat cac on c.id = cac.blog_category_id
                                WHERE cac.article_id=a.id AND c.status='published' AND c.hide=false) AS categories
                        FROM articles a
                        WHERE a.status='published' AND a.id IN ({$inChars})
                        ORDER BY a.id DESC 
                        LIMIT {$offset},{$limit}";
               $posts = $this->db()->queryAll($sql, $favorites);
           }
        }
        return $this->json( $posts ?? [] );
    }



    // gGörüntülenme arttır
    public function views(){
        $id     = $this->post("id", null);
        $value  = $this->post('value', null);
        if($this->hasRequestMethod("POST")){
            if($id && $value){
                $sql = "UPDATE articles SET views=views+? WHERE id=?";
                $this->db()->execute($sql, [$value, $id]);
            }
        }
        return $this->json([ 'id'=>$id, 'value'=>$value ]);
    }


    /**
     * Kullanıcılardan gelen içeriği kayıt eder.
     */
    #[Route("/post/create", methods: ["POST"])]
    public function create(){
        $result = [];
        $status = false;
        $message = 'Hatalı istek';
        if($this->hasRequestMethod("POST")){
            
            $coverUrl = $this->uploadCover($_FILES["cover"] ?? null);
            // Post edilen içerikleri alır.
            $newPost = $this->fillDataFromPost([
                'author' => $this->authData()->uid ?? null,
                'cover'  => $coverUrl
            ]);


            if(strlen($newPost['title']) < 5 || strlen($newPost['content']) < 120 || !strlen($newPost['content'])){
                $message = 'Gerekli alanlar için yetersiz giriş yapıldı. (başlık, içerik, yazar)';
            }else{
                $lastInsertId = $this->postModel->insert($newPost);
                if(!$lastInsertId){
                    $message = 'Makale oluşturulurken beklenmedik bir sorun ile karşılaşıldı. Lütfen daha sonra yeniden deneyin.';
                }else{
                    $post   = $this->db->queryOne("SELECT id, title, status FROM articles WHERE id=?", [$lastInsertId]);
                    $result['post'] =  $post;
                    $result['lastInsertId'] =  $lastInsertId;
                    $status = true;
                    $message= $post->status=='published'? 'Makale yayımlandı' : 'Makaleniz gönderildi. Yönetici onayından sonra sitede görüntülenecektir.';
                }
            }
        }
        return $this->response($result, $status, $message);
    }

    

    public function update($id, $slug){
        echo "Bu $id numaralı yazının güncellemesi => " . $slug;
    }



    /**
     * Oluşturulacak veya Güncellenecek içerik için POST dan gelen değerlerden bir post içeriği oluşturur.
     */
    private function fillDataFromPost($extraData=[]){
        $slugify    = new Slugify();
        $auth     = $this->authData();
        $newPost  = [
            'title'         => $this->post('title', ""),
            'slug'          => $slugify->slugify($this->post('title', "")),
            'description'   => $this->post('description', ""),
            'status'        => ($auth->level < 3)? 'waiting' : $this->post('status', "waiting"),
            'content'       => $this->post('content', ""),
            'video'         => $this->post('video', null),
            'hide_cover'    => ($auth->level < 3)? 'off' :      $this->post('hide_cover', "off"),
            'allow_comments'=> ($auth->level < 3)? 'on' :       $this->post('allow_comments', "on"),
            'list_order'    => ($auth->level < 3)? '1' :        $this->post('list_order', "1"),
            'categories'    =>  $this->post('categories', [])
        ];

        $pTime = $this->post('p_time', null);
        if($auth->level > 2 && $pTime){
            $newPost['p_time']  = $pTime;
        }
        return array_merge($newPost, $extraData);
    }

    /**
     * Oluşturulan makale için kapak fotoğraflarını yükler.
     */
    private function uploadCover($file){
        $coverUrl   = null;
        if($file){
            $coverName = time().'-'.rand(1000000000,1999999999);
            $uploader  = new \Atabasch\Uploader(['resize' => true, 'cover' => true, 'max_size' => (1024*1024)*3, 'path' => PATH_MEDIA_UPLOAD]);
            $cover     = $uploader->upload($file, $coverName, [
                'crop'    => Config::bool('img_lg_crop', false), 
                'width'   => Config::get('img_lg_w', 1170), 
                'height'  => Config::get('img_lg_h', 720), 
                'quality' => Config::get('img_lg_quality', 100), 
                'pre'     => 'lg_',]);

            if($cover->status){

                $coverUrl   = $cover->file->nameWithoutPre;
                $uploader->upload($file, $coverName, [
                    'crop'    => Config::bool('img_md_crop', true), 
                    'width'   => Config::get('img_md_w', 850), 
                    'height'  => Config::get('img_md_h', 510), 
                    'quality' => Config::get('img_md_quality', 90), 
                    'pre'     => 'md_',]);

                $uploader->upload($file, $coverName, [
                    'crop'    => Config::bool('img_sm_crop', true), 
                    'width'   => Config::get('img_sm_w', 600), 
                    'height'  => Config::get('img_sm_h', 450), 
                    'quality' => Config::get('img_sm_quality', 90), 
                    'pre'     => 'sm_',]);
                
            }
        }
        return $coverUrl;
    }


}
