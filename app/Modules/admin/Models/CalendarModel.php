<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Security;
use Helpers\FileUploader;
use Helpers\Url;
use Helpers\File;
use Helpers\Validator;
use Helpers\Slim;
use Helpers\SimpleImage;

class CalendarModel extends Model{

    private static $tableName = 'tenants';

    private static $params;

    public function __construct($params){
        parent::__construct();
        self::$db->createTable(self::$tableName,self::getInputs());
        self::$params = $params;
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

    public static function getList(){
        return self::$db->select("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `move_in`>= '".date("Y-m-d")."' ORDER BY `move_in` ASC,`id` ASC ");
    }
    public static function getListMoveOut(){
        return self::$db->select("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `move_out`>= '".date("Y-m-d")."' ORDER BY `move_out` ASC,`id` ASC ");
    }

    public static function getItem($id){
        return self::$db->selectOne("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `id`='".$id."'");
    }


}

?>