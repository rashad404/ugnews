<?php
namespace Modules\partner\Models;
use Core\Model;
use Helpers\Session;
use Helpers\Database;
use Helpers\Url;

class GeneralModel extends Model{

    public function __construct(){
        parent::__construct();
    }

	public static function userRole()
	{
		return Session::get('user_session_role');
	}

	public static function accessControl($methods = array(), $tableName)
	{
		$current_method = Url::getMethod();
		if(in_array($current_method, $methods)) {
			$role = self::userRole();
			$get_role_cat = Database::get()->selectOne("Select * FROM `partner_roles` Where `table_name` = :table_name Limit 1", [':table_name' => $tableName]);
			if($role == 0 OR ($role == 1 && ($get_role_cat['super_admin'] == 0)) OR ($role == 2 && ($get_role_cat['admin'] == 0)) OR ($role == 3 && ($get_role_cat['editor'] == 0))) {
				return true; // access denied
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}