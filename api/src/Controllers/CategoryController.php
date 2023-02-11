<?php

namespace Atabasch\Controllers;

class CategoryController extends \Atabasch\BaseController
{


    public function index($id=null, $param3=null){
        if(!$id){
            $this->getAll();
        }else{
            $this->getOne($id, $param3);
        }
    }

    public function minilist(){
        $sql    = "SELECT id, title, slug FROM blog_categories WHERE hide=false AND status='published' ORDER BY title ASC";
        $items = $this->db->queryAll($sql);
        $this->response(['categories' => $items]);
    }

    private function getAll(){
        $sql = "
        SELECT c.id, 
        c.title, 
        c.slug, 
        c.description, 
        c.parent,
        c.color,
        c.cover,
        (SELECT count(*) FROM conn_art_cat WHERE blog_category_id=c.id) as total 
        FROM blog_categories as c
        WHERE c.status='published' AND parent=0 AND hide=false
        ORDER BY total DESC";
        $categories = $this->db()->queryAll($sql);
        $this->json($categories);
    }


    private function getOne($idOrSlug, $param3=null){
        $sql = "
        SELECT c.id, 
        c.title, 
        c.slug, 
        c.description, 
        c.cover, 
        c.color, 
        c.parent,
        (SELECT count(*) FROM conn_art_cat WHERE blog_category_id=c.id) as total 
        FROM blog_categories as c
        WHERE c.status='published' AND (c.id=? OR c.slug=?)
        ORDER BY total DESC";
        $category = $this->db()->queryOne($sql, [$idOrSlug, $idOrSlug]);

        $offset     = $_GET["offset"] ?? 0;
        $limit      = $_GET["limit"] ?? 10;
        $orderBy    = $_GET["orderby"] ?? "id";
        $sort       = $_GET["sort"] ?? "DESC";

        if(!$category){
            $category = (object)[];
        }else{
            if($param3=='posts'){
                $sqlForPosts = "SELECT 
                p.id, p.title, p.slug, p.keywords, p.description, p.summary, p.views, p.cover, p.video, p.p_time, p.hide_cover 
                FROM articles AS p 
                INNER JOIN conn_art_cat AS c ON c.article_id=p.id 
                WHERE c.blog_category_id=? AND p.status='published'  
                ORDER BY p.{$orderBy} {$sort} 
                LIMIT {$offset}, {$limit} ";
                $posts = $this->db()->queryAll($sqlForPosts, [$category->id]);
            }
        }

        $this->json([
            'category'  => $category,
            'posts'     => $posts ?? array()
        ]);
    }


}
