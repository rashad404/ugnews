<?php
namespace Models;
use Core\Model;
use Core\Language;

class ChannelsModel extends Model{

    private static $tableName = 'channels';
    private static $tableNameSubscribers = 'subscribers';
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }

    public static function countSubscribers($id){
        $array = self::$db->count("SELECT count(id) FROM `".self::$tableNameSubscribers."` WHERE `channel`='".$id."'");
        return $array;
    }

    public static function getList($limit=10){
        $array = self::$db->select("SELECT `id`,`time`,`view`,`name`,`thumb`,`image`,`name_url` FROM `".self::$tableName."` WHERE `status`=1 ORDER BY `subscribers` DESC LIMIT $limit");
        return $array;
    }
    public static function getPopularList($limit=10){
        $array = self::$db->select("SELECT `id`,`time`,`name`,`thumb`,`image` FROM `".self::$tableName."` WHERE `status`=1 ORDER BY `view` DESC LIMIT $limit");
        return $array;
    }
    public static function getSearchList($text,$limit=10){
        $array = self::$db->select("SELECT `id`,`time`,`view`,`name`,`thumb`,`image` FROM `".self::$tableName."` 
        WHERE `status`=1 AND 
        (`name` LIKE '%".$text."%' OR `text_` LIKE '%".$text."%')
        ORDER BY `id` DESC LIMIT $limit");
        return $array;
    }

    public static function getItem($id){
        $array = self::$db->selectOne("SELECT `id`,`time`,`name`,`thumb`,`image`,`name_url`,`subscribers` FROM `".self::$tableName."` WHERE `id`='".$id."' AND `status`=1");
        return $array;
    }

    public static function getItemByUrl($url){
        $array = self::$db->selectOne("SELECT `id`,`time`,`name`,`thumb`,`image`,`name_url`,`subscribers` FROM `".self::$tableName."` WHERE `name_url`='".$url."' AND `status`=1");
        return $array;
    }


    public static function getName($id){
        $array = self::$db->selectOne("SELECT `name` FROM `".self::$tableName."` WHERE `id`='".$id."'");
        if($array){return $array['name'];}else{return '';}
    }
    public static function getUrl($id){
        $array = self::$db->selectOne("SELECT `name_url` FROM `".self::$tableName."` WHERE `id`='".$id."'");
        if($array){return $array['name_url'];}else{return '';}
    }

    public static function navigate($id, $action){
        if($action=='next'){
            $action_symbol = '>';
        }else{
            $action_symbol = '<';
        }
        $array = self::$db->selectOne("SELECT `id`,`time`,`name`,`thumb`,`image` FROM `".self::$tableName."` WHERE `id` ".$action_symbol." '".$id."' AND `status`=1 ORDER BY `id` DESC");
        return $array;
    }

}
