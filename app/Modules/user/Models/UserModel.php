<?php

namespace Modules\user\Models;

use Core\Model;

class UserModel extends Model{

    private static $tableName = 'users';

    public function __construct(){
        parent::__construct();
    }

    public static function naming(){
        return [];
    }

    public static function getName($id){
        $array = self::$db->selectOne("SELECT `first_name`,`last_name` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $array['first_name'].' '.$array['last_name'];
    }
    public static function getGenderName($id){
        $array = self::$db->selectOne("SELECT `gender` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $array['gender'];
    }

    public static function getItem($id){
        return self::$db->selectOne("SELECT * FROM ".self::$tableName." WHERE `id`='".$id."'");
    }



}

?>