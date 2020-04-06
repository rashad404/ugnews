<?php

namespace Modules\user\Models;

use Core\Model;
use Helpers\Security;
use Helpers\Validator;

class RoomsModel extends Model{

    public static $tableName = 'apt_rooms';
    private static $rules;

    public function __construct(){
        parent::__construct();
    }

    public static function getList($apt_id){
        return self::$db->select("SELECT `id`,`status`,`name_".self::$def_language."` as `name` FROM ".self::$tableName." WHERE `apt_id`='".$apt_id."' ORDER BY `position` DESC,`id` ASC ");
    }
    public static function getName($id){
        $query = self::$db->selectOne("SELECT `name_".self::$def_language."` as `name` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $query['name'];
    }


    public static function getNameByBeds($beds){
        if($beds==1)$name = 'Private';
        if($beds==2)$name = 'Double';
        if($beds==4)$name = 'Quad';
        return $name;
    }
}

?>