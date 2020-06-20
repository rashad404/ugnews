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
    private static $tableNameChannels = 'channels';
    private static $tableNameSubscribers = 'subscribers';
    private static $tableNameLikes = 'likes';
    private static $region;
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        self::$region = Cookie::get('set_region');
        if(self::$region==0)self::$region = DEFAULT_COUNTRY;
    }

    public static function getList($limit = 'LIMIT 0,10'){
        $array = self::$db->select("SELECT `id`,`publish_time`,`title`,`title_extra`,`text`,`tags`,`thumb`,`image`,`partner_id`,`cat`,`view`,`channel` FROM `".self::$tableName."` WHERE `publish_time`<=".time()." AND `status`=1 AND `country`='".self::$region."' ORDER BY `publish_time` DESC $limit");
        return $array;
    }
    public static function getSimilarNews($id, $limit=6){
        $array = self::getItem($id, false);
        $title = $array['title'];
        //SELECT *,
        //MATCH(`name`, `middlename`, `surname`) AGAINST ('John' IN NATURAL LANGUAGE MODE) AS score
        //FROM person
        //ORDER BY score DESC;
        $array = self::$db->select("SELECT `id`,`publish_time`,`title`,`title_extra`,`text`,`tags`,`thumb`,`image`,`partner_id`,`cat`,`view`,`channel`,
 MATCH(`title`,`title_extra`,`text`) AGAINST ('".$title."' IN NATURAL LANGUAGE MODE) AS score
 FROM `".self::$tableName."` WHERE `id`!=".$id." AND `status`=1 AND `country`='".self::$region."' ORDER BY `score` DESC LIMIT $limit");
        return $array;
    }

    //Cats
    public static function getListByCat($id, $limit = 'LIMIT 0,10'){
        $array = self::$db->select("SELECT `id`,`publish_time`,`title`,`title_extra`,`text`,`tags`,`thumb`,`image`,`partner_id`,`cat`,`view`,`channel` FROM `".self::$tableName."` WHERE `publish_time`<=".time()." AND `status`=1 AND `country`='".self::$region."' AND `cat`='".$id."' ORDER BY `publish_time` DESC $limit");
        return $array;
    }

    //Cats
    public static function getListByChannel($id, $limit = 'LIMIT 0,10'){
        $array = self::$db->select("SELECT `id`,`publish_time`,`title`,`title_extra`,`text`,`tags`,`thumb`,`image`,`partner_id`,`cat`,`view`,`channel` FROM `".self::$tableName."` WHERE `publish_time`<=".time()." AND `status`=1 AND `channel`='".$id."' ORDER BY `publish_time` DESC $limit");
        return $array;
    }

    public static function countListByCat($cat){
        $array = self::$db->count("SELECT count(id) FROM `".self::$tableName."` WHERE `status`=1 AND `country`='".self::$region."' AND `cat`='".$cat."'");
        return $array;
    }

    public static function countListByChannel($id){
        $array = self::$db->count("SELECT count(id) FROM `".self::$tableName."` WHERE `status`=1 AND `country`='".self::$region."' AND `channel`='".$id."'");
        return $array;
    }

    public static function countList(){
        $array = self::$db->count("SELECT count(id) FROM `".self::$tableName."` WHERE `status`=1 AND `country`='".self::$region."'");
        return $array;
    }

    //Tags cat
    public static function getListByTagCat($id, $limit = 'LIMIT 0,10'){
        $tag = self::getTagName($id);
        $array = self::$db->select("SELECT `id`,`time`,`title`,`title_extra`,`text`,`tags`,`thumb`,`image`,`partner_id`,`cat`,`view`,`channel` FROM `".self::$tableName."` WHERE `status`=1 AND `country`='".self::$region."' AND  FIND_IN_SET ('".$tag."', `tags`) ORDER BY `id` DESC $limit");
        return $array;
    }

    public static function countListByTagCat($cat){
        $tag = self::getTagName($cat);
        $array = self::$db->count("SELECT count(id) FROM `".self::$tableName."` WHERE `status`=1 AND  FIND_IN_SET ('".$tag."', `tags`)");
        return $array;
    }

    //City
    public static function getListByCity($id, $limit = 'LIMIT 0,10'){
        $array = self::$db->select("SELECT `id`,`time`,`title`,`title_extra`,`text`,`tags`,`thumb`,`image`,`partner_id`,`cat`,`view`,`channel` FROM `".self::$tableName."` WHERE `status`=1 AND `city`='".$id."' ORDER BY `id` DESC $limit");
        return $array;
    }

    public static function countListByCity($id){
        $array = self::$db->count("SELECT count(id) FROM `".self::$tableName."` WHERE `status`=1 AND `city`='".$id."'");
        return $array;
    }

    //Tags
    public static function getListByTag($tag, $limit = 'LIMIT 0,10'){
        $array = self::$db->select("SELECT `id`,`publish_time`,`title`,`title_extra`,`text`,`tags`,`thumb`,`image`,`partner_id`,`cat`,`view`,`channel` FROM `".self::$tableName."` WHERE `publish_time`<=".time()." AND `status`=1 AND  FIND_IN_SET ('".$tag."', `tags`) ORDER BY `publish_time` DESC $limit");
        return $array;
    }

    public static function countListByTag($tag){
        $array = self::$db->count("SELECT count(id) FROM `".self::$tableName."` WHERE `status`=1 AND  FIND_IN_SET ('".$tag."', `tags`)");
        return $array;
    }

    public static function getItem($id, $count=true){
        if($count) {
            $update = self::$db->raw("UPDATE `" . self::$tableName . "` SET `view`=`view`+1 WHERE `id`='" . $id . "'");
//            VisitorsModel::updateView();
        }
        $array = self::$db->selectOne("SELECT `id`,`publish_time`,`title`,`title_extra`,`text`,`tags`,`thumb`,`image`,`partner_id`,`cat`,`view`,`channel` FROM `".self::$tableName."` WHERE `id`='".$id."' AND `status`=1");

        if($array && $count) {
            self::$db->raw("UPDATE `" . self::$tableNameChannels . "` SET `view`=`view`+1 WHERE `id`='" . $array['channel'] . "'");
        }
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
        $array = self::$db->selectOne("SELECT `id`,`time`,`title`,`title_extra`,`text`,`tags`,`thumb`,`image`,`partner_id` FROM `".self::$tableName."` WHERE `id` ".$action_symbol." '".$id."' AND `status`=1 ORDER BY `id` DESC");
        return $array;
    }



    public static function subscribeCheck($id){
        $user_id = intval(Session::get("user_session_id"));
        $check = self::$db->selectOne("SELECT `id` FROM `".self::$tableNameSubscribers."` WHERE `channel`=".$id." AND `user_id`='".$user_id."'");
        if($check) {
            return true;
        }else{
            return false;
        }
    }

    public static function likeCheck($id){
        $user_id = intval(Session::get("user_session_id"));
        $check = self::$db->selectOne("SELECT `id` FROM `".self::$tableNameLikes."` WHERE `liked`=1 AND `news_id`=".$id." AND `user_id`='".$user_id."'");
        if($check) {
            return true;
        }else{
            return false;
        }
    }
    public static function dislikeCheck($id){
        $user_id = intval(Session::get("user_session_id"));
        $check = self::$db->selectOne("SELECT `id` FROM `".self::$tableNameLikes."` WHERE `disliked`=1 AND `news_id`=".$id." AND `user_id`='".$user_id."'");
        if($check) {
            return true;
        }else{
            return false;
        }
    }
}
