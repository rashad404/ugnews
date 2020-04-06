<?php

namespace Modules\user\Models;

use Core\Model;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Modules\partner\Models\RoomsModel;

class BedsModel extends Model{

    private static $tableName = 'apt_beds';
    private static $tableNameRooms = 'apt_rooms';
    private static $tableNameTenants = 'tenants';

    private static $rules;
    private static $params;
    private static $partner_id;

    public function __construct($params=''){
        parent::__construct();
        self::$params = $params;
        self::$partner_id = Session::get('user_session_id');
    }

    public static function getItem($id){
        return self::$db->selectOne("SELECT `id`,`apt_id`,`room_id`,`tenant_id`,`available_date`,`name_".self::$def_language."`,`status`,`price`,`apply_link` FROM ".self::$tableName." WHERE `id`='".$id."'");
    }

    public static function getList($apt_id){
        return self::$db->select("SELECT a.`id`,a.`room_id`,a.`tenant_id`,a.`status`,a.`name_".self::$def_language."`,a.`price`, a.`available_date`, b.`name_".self::$def_language."` as `room_name` 
         FROM ".self::$tableName." as a INNER JOIN ".self::$tableNameRooms." as b ON a.`room_id`=b.`id` 
          WHERE a.`apt_id`='".$apt_id."' ORDER BY a.`position` ASC, a.`id` ASC ");
    }
    public static function getListByRoom($room_id){
        return self::$db->select("SELECT `id`,`name_".self::$def_language."` as `name` 
         FROM ".self::$tableName." WHERE `room_id`='".$room_id."' ORDER BY `position` DESC, `id` ASC ");
    }

    public static function getRoomList($apt_id){
        return self::$db->select("SELECT `id`,`status`,`name_".self::$def_language."` FROM ".self::$tableNameRooms." WHERE `apt_id`='".$apt_id."' ORDER BY `position` DESC,`id` ASC ");
    }



    public static function getName($id){
        $query = self::$db->selectOne("SELECT `name_".self::$def_language."` as `name` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $query['name'];
    }
}

?>