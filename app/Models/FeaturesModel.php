<?php
namespace Models;
use Core\Model;
use Helpers\Cookie;
use Helpers\Security;
use Helpers\Validator;
use Core\Language;

class FeaturesModel extends Model{

    public static function getCats($limit=10){
        $array = self::$db->select("SELECT `id`,`name` FROM `categories` WHERE `status`=1 ORDER BY `position` DESC LIMIT $limit");
        return $array;
    }

    public static function getProduct($id){
        $array = self::$db->selectOne("SELECT `id`,`title_az`,`text_az`,`thumb`,`image`,`price`,`features` FROM `".self::$tableName."` WHERE `id`='".$id."' AND `status`=1 ORDER BY `id` DESC");
        return $array;
    }



}
