<?php
namespace Models;
use Core\Model;
use Helpers\Cookie;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Core\Language;


class NewsModel extends Model{

    private static $tableName = 'news';
    private static $tableNameCategories = 'categories';
    private static $tableNameTags = 'tags';
    private static $region;
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        self::$region = Cookie::get('set_region');
    }

    public static function getList($limit=10){
        $array = self::$db->select("SELECT `id`,`time`,`title`,`text`,`thumb`,`image`,`partner_id`,`cat`,`view` FROM `".self::$tableName."` WHERE `status`=1 AND `country`='".self::$region."' ORDER BY `id` DESC LIMIT $limit");
        return $array;
    }

    //Cats
    public static function getListByCat($id, $limit = 'LIMIT 0,10'){
        $array = self::$db->select("SELECT `id`,`time`,`title`,`text`,`thumb`,`image`,`partner_id`,`cat`,`view` FROM `".self::$tableName."` WHERE `status`=1 AND `country`='".self::$region."' AND `cat`='".$id."' ORDER BY `id` DESC $limit");
        return $array;
    }

    public static function countListByCat($cat){
        $array = self::$db->count("SELECT count(id) FROM `".self::$tableName."` WHERE `status`=1 AND `country`='".self::$region."' AND `cat`='".$cat."'");
        return $array;
    }

    //Tags cat
    public static function getListByTagCat($id, $limit = 'LIMIT 0,10'){
        $tag = self::getTagName($id);
        $array = self::$db->select("SELECT `id`,`time`,`title`,`text`,`thumb`,`image`,`partner_id`,`cat`,`view` FROM `".self::$tableName."` WHERE `status`=1 AND `country`='".self::$region."' AND  FIND_IN_SET ('".$tag."', `tags`) ORDER BY `id` DESC $limit");
        return $array;
    }

    public static function countListByTagCat($cat){
        $tag = self::getTagName($cat);
        $array = self::$db->count("SELECT count(id) FROM `".self::$tableName."` WHERE `status`=1 AND  FIND_IN_SET ('".$tag."', `tags`)");
        return $array;
    }

    //Tags
    public static function getListByTag($tag, $limit = 'LIMIT 0,10'){
        $array = self::$db->select("SELECT `id`,`time`,`title`,`text`,`thumb`,`image`,`partner_id`,`cat`,`view` FROM `".self::$tableName."` WHERE `status`=1 AND  FIND_IN_SET ('".$tag."', `tags`) ORDER BY `id` DESC $limit");
        return $array;
    }

    public static function countListByTag($tag){
        $array = self::$db->count("SELECT count(id) FROM `".self::$tableName."` WHERE `status`=1 AND  FIND_IN_SET ('".$tag."', `tags`)");
        return $array;
    }

    public static function getItem($id){
        $update = self::$db->raw("UPDATE `".self::$tableName."` SET `view`=`view`+1 WHERE `id`='".$id."'");
        $array = self::$db->selectOne("SELECT `id`,`time`,`title`,`text`,`thumb`,`image`,`partner_id`,`cat`,`view` FROM `".self::$tableName."` WHERE `id`='".$id."' AND `status`=1");
        return $array;
    }

    public static function getCatName($id){
        $array = self::$db->selectOne("SELECT `name` FROM `".self::$tableNameCategories."` WHERE `id`='".$id."'");
        if($array){return $array['name'];}else{return '';}
    }
    public static function getTagName($id){
        $array = self::$db->selectOne("SELECT `name` FROM `".self::$tableNameTags."` WHERE `id`='".$id."'");
        if($array){return $array['name'];}else{return '';}
    }

    public static function navigate($id, $action){
        if($action=='next'){
            $action_symbol = '>';
        }else{
            $action_symbol = '<';
        }
        $array = self::$db->selectOne("SELECT `id`,`time`,`title`,`text`,`thumb`,`image`,`partner_id` FROM `".self::$tableName."` WHERE `id` ".$action_symbol." '".$id."' AND `status`=1 ORDER BY `id` DESC");
        return $array;
    }

}
