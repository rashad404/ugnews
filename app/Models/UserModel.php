<?php
namespace Models;
use Core\Model;
use Helpers\Database;
use Helpers\Session;

class UserModel extends Model{

	public static $tableName = 'users';
    public function __construct(){
        parent::__construct();
    }

	public static function getInfo($id){
		$row = self::$db->selectOne("SELECT `id`,`balance`,`first_name`,`last_name`,
            `country_code`,`phone`,`email`,`birthday`,`gender`,`block`,`time`,`landlord_portal`,`tenant_portal` FROM ".self::$tableName." WHERE `id`=:id",[":id"=>$id]);
        if($row) {
            $row['prefix'] = substr($row['phone'], 0, 5);
            $row['number'] = substr($row['phone'], 5, 7);
        }
		return $row;
	}

	public function getInfoByPhone($phone){
		$row = self::$db->selectOne("SELECT `id`,`balance` FROM ".self::$tableName." WHERE `phone`=:phone",[":phone"=>$phone]);
		return $row;
	}
	public static function getName($id){
		$row = self::$db->selectOne("SELECT `first_name`,`last_name` FROM ".self::$tableName." WHERE `id`=:id",[":id"=>$id]);
		return $row['first_name'].' '.$row['last_name'];
	}
	public static function updateOnline(){
        $userId = intval(Session::get("user_session_id"));
        $update = ['time'=>time()];
        $where = ['id'=>$userId];
		self::$db->update(self::$tableName ,$update, $where);
	}

}