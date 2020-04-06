<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Console;
use Helpers\Security;
use Helpers\FileUploader;
use Helpers\Session;
use Helpers\Url;
use Helpers\File;
use Helpers\Validator;
use Helpers\Slim;
use Helpers\SimpleImage;

class CalendarModel extends Model{

    private static $tableName = 'users';
    private static $tableNameShowings = 'showings';

    private static $params;
    private static $partner_id;

    public function __construct($params=''){
        parent::__construct();
        self::$db->createTable(self::$tableName,self::getInputs());
        self::$params = $params;
        self::$partner_id = Session::get('user_session_id');
    }

    public static function naming(){
        return [];
    }


    /*
     *If type is empty, will not appear on the page
     *If sql_type is empty, will not create field on sql table
     */
    public static function getInputs(){
        $array[] = ['type'=>'text',         'name'=>'First name',       'key'=>'first_name',        'sql_type'=>'varchar(100)'];
        $array[] = ['type'=>'text',         'name'=>'Last name',        'key'=>'last_name',         'sql_type'=>'varchar(100)'];
        $array[] = ['type'=>'text',         'name'=>'Phone number',     'key'=>'phone',             'sql_type'=>'varchar(20)'];
        $array[] = ['type'=>'text',         'name'=>'E-mail address',   'key'=>'email',             'sql_type'=>'varchar(50)'];
        $array[] = ['type'=>'date',         'name'=>'Move in Date',   'key'=>'move_in',           'sql_type'=>'int(11)'];
        $array[] = ['type'=>'date',         'name'=>'Move out Date',    'key'=>'move_out',          'sql_type'=>'int(11)'];
        $array[] = ['type'=>'',         'name'=>'',    'key'=>'apt_id',          'sql_type'=>'int(11)'];
        $array[] = ['type'=>'',         'name'=>'',    'key'=>'room_id',          'sql_type'=>'int(11)'];
        $array[] = ['type'=>'',         'name'=>'',    'key'=>'bed_id',          'sql_type'=>'int(11)'];
        $array[] = ['type'=>'',         'name'=>'',    'key'=>'position',          'sql_type'=>'int(11)'];
        $array[] = ['type'=>'',         'name'=>'',    'key'=>'status',          'sql_type'=>'tinyint(1)'];
        return $array;
    }

    public static function getSqlFields(){
        $input_list = self::getInputs();
        $field_array = ['`id`'];
        foreach ($input_list as $value){
            $field_array[] = '`'.$value['key'].'`';
        }
        $fields = implode(',', $field_array);
        return $fields;
    }


    public static function getListMoveIn(){
        return self::$db->select("SELECT `id`,`first_name`,`last_name`,`phone`,`email`,`apt_id`,`room_id`,`bed_id`,`move_in` as `date`,`move_in` FROM ".self::$tableName." WHERE `partner_id`= '".self::$partner_id."' AND `move_in`>= '".date("Y-m-d")."' ORDER BY `move_in` ASC,`id` ASC ");
    }
    public static function getListMoveOut(){
        return self::$db->select("SELECT `id`,`first_name`,`last_name`,`phone`,`email`,`apt_id`,`room_id`,`bed_id`,`move_out` as `date` FROM ".self::$tableName." WHERE `partner_id`= '".self::$partner_id."' AND `move_out`>= '".date("Y-m-d")."' ORDER BY `move_out` ASC,`id` ASC ");
    }
    public static function getListShowings(){
        return self::$db->select("SELECT `id`,`type`,`guest_id`,`user_id`,`apt_id`,`room_id`,`bed_id`,`date` FROM ".self::$tableNameShowings." WHERE `partner_id`= '".self::$partner_id."' AND `date`>= '".date("Y-m-d")."' ORDER BY `date` ASC,`id` ASC ");
    }

    public static function getItem($id){
        return self::$db->selectOne("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `partner_id`= '".self::$partner_id."' AND `id`='".$id."'");
    }


}

?>