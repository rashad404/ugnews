<?php

namespace Modules\user\Models;

use Core\Model;
use Helpers\Session;

class HouseRulesModel extends Model
{

    private static $tableName = 'house_rules';
    private static $user_id;
    private static $partner_id;

    public function __construct(){
        parent::__construct();
        self::$user_id = Session::get('user_session_id');
        $user_info = UserModel::getItem(self::$user_id);
        self::$partner_id = $user_info['partner_id'];
    }

    public static function getItem(){
        return self::$db->selectOne("SELECT * FROM " . self::$tableName . " WHERE `id`='" . self::$partner_id . "'");
    }

}

?>