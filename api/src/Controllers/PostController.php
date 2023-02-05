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
                           (SELECT JSON_ARRAYAGG(JSON_OBJECT('id', c.id, 'title', c.title, 'slug', c.slug)) FROM blog_categories c
                               INNER JOIN conn_art_cat cac on c.id = cac.blog_category_id
                                WHERE cac.article_id=a.id AND c.status='published' AND c.hide=false) AS categories
                        FROM articles a
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



    #[Route("/post/create", methods: ["POST"])]
    public function create(){
        if(!$this->hasRequestMethod("POST")){
            $message = 'Hatalı istek';
        }else{
            
            $file      = $_FILES["cover"] ?? null;
            $coverUrl   = null;
            if($file){
                $coverName = time().'-'.rand(1000000000,1999999999);
                $coverPath = __DIR__.'/../../media/upload';

                $uploader  = new \Atabasch\Uploader(['resize' => true, 'cover' => true, 'max_size' => (1024*1024)*3, 'path' => $coverPath]);
                $cover     = $uploader->upload($file, $coverName, ['crop' => false, 'width' => 1170, 'height' => 720, 'pre' => 'lg_',]);
                if($cover->status){
                    $coverUrl   = $cover->file->nameWithoutPre;
                    $uploader->upload($file, $coverName, ['crop' => true, 'width' => 850, 'height' => 510, 'pre' => 'md_',]);
                    $uploader->upload($file, $coverName, ['crop' => true, 'width' => 600, 'height' => 450, 'pre' => 'sm_',]);
                }
            }
        
            $newPost = $this->fillDataFromPost([
                'author' => $this->authData()->uid ?? null,
                'cover'  => $coverUrl
            ]);

            if(strlen($newPost['title']) < 5 || strlen($newPost['content']) < 120 || !strlen($newPost['content'])){
                $message = 'Gerekli alanlarda yetersiz bilgi (başlık, içerik, yazar)';
            }else{
                $insert = $this->postModel->insert($newPost);
                if(!$insert){
                    $message = 'Makale oluşturulurken beklenmedik bir sorun ile karşılaşıldı. Lütfen daha sonra yeniden deneyin.';
                }else{
                    $createdPost = $this->postModel->getPost($insert);
                    return $this->response(['post' => $createdPost], true, "");
                }
            }
        }
        return $this->response([], false, $message);
    }

    

    public function update($id, $slug){
        echo "Bu $id numaralı yazının güncellemesi => " . $slug;;
    }

    public function delete($id){

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


}
