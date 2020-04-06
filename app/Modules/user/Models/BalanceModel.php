<?php

namespace Modules\user\Models;

use Core\Model;
use Helpers\Session;

class BalanceModel extends Model{

    private static $tableName = 'balance_logs';
    private static $tableNameUsers = 'users';

    private static $params;
    private static $user_id;
    private static $partner_id;

    public function __construct($params=''){
        parent::__construct();
        self::$params = $params;
        self::$user_id = Session::get('user_session_id');
        $user_info = UserModel::getItem(self::$user_id);
        self::$partner_id = $user_info['partner_id'];
    }


    public static function getLogs($limit='LIMIT 0,100'){
        $user_id = Session::get('user_session_id');
        return self::$db->select("SELECT `id`,`time`,`amount`,`action`,`description` FROM ".self::$tableName." WHERE `user_id`='".$user_id."'AND `partner_id`='".self::$partner_id."' ORDER BY `id` DESC $limit");
    }
    public static function getAllLogs($limit='LIMIT 0,5'){
        return self::$db->select("SELECT a.`id`,a.`user_id`,a.`time`,a.`amount`,a.`action`,a.`description`, b.`first_name`,b.`last_name` 
FROM ".self::$tableName." as a INNER JOIN ".self::$tableNameUsers." as b ON a.`user_id`=b.`id`WHERE a.`partner_id`='".self::$partner_id."' ORDER BY a.`id` DESC $limit");
    }

    public static function getReceipts($limit='LIMIT 0,5'){
        return self::$db->select("SELECT a.`id`,a.`user_id`,a.`time`,a.`amount`,a.`action`,a.`description`, b.`first_name`,b.`last_name` 
FROM ".self::$tableName." as a INNER JOIN ".self::$tableNameUsers." as b ON a.`user_id`=b.`id`WHERE a.`partner_id`='".self::$partner_id."' AND a.`action`='receipt' ORDER BY a.`id` DESC $limit");
    }
}

?>