<?php
namespace Models;
use Core\Model;
use Core\Language;

class BlogModel extends Model{

    private static $tableName = 'blog';
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }

    public static function getList($limit=10){
        $array = self::$db->select("SELECT `id`,`time`,`view`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image`,`price` FROM `".self::$tableName."` WHERE `status`=1 ORDER BY `id` DESC LIMIT $limit");
        return $array;
    }
    public static function getPopularList($limit=10){
        $array = self::$db->select("SELECT `id`,`time`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image`,`price` FROM `".self::$tableName."` WHERE `status`=1 ORDER BY `view` DESC LIMIT $limit");
        return $array;
    }
    public static function getSearchList($text,$limit=10){
        $array = self::$db->select("SELECT `id`,`time`,`view`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image`,`price` FROM `".self::$tableName."` 
        WHERE `status`=1 AND 
        (`title_".self::$def_language."` LIKE '%".$text."%' OR `text_".self::$def_language."` LIKE '%".$text."%')
        ORDER BY `id` DESC LIMIT $limit");
        return $array;
    }

    public static function getItem($id){
        $array = self::$db->selectOne("SELECT `id`,`time`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image`,`price`,`features`,`view` FROM `".self::$tableName."` WHERE `id`='".$id."' AND `status`=1 ORDER BY `id` DESC");
        if($array){
            $array['view'] = $array['view']+1;
            self::$db->update(self::$tableName,['view'=> $array['view']], ['id'=>$id, 'status'=>1]);
        }
        return $array;
    }

    public static function navigate($id, $action){
        if($action=='next'){
            $action_symbol = '>';
        }else{
            $action_symbol = '<';
        }
        $array = self::$db->selectOne("SELECT `id`,`time`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image`,`price`,`features` FROM `".self::$tableName."` WHERE `id` ".$action_symbol." '".$id."' AND `status`=1 ORDER BY `id` DESC");
        return $array;
    }

}
