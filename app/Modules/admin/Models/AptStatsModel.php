<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Console;
use Helpers\Date;
use Helpers\Security;
use Helpers\FileUploader;
use Helpers\Url;
use Helpers\File;
use Helpers\Validator;
use Helpers\Slim;
use Helpers\SimpleImage;
use \DateTime;

class AptStatsModel extends Model{

    private static $tableName = 'inventory';
    private static $tableNameBeds = 'apt_beds';
    private static $tableNameTenants = 'tenants';
    private static $tableNameApartments = 'apartments';

    private static $rules;
    private static $params;

    public function __construct($params){
        parent::__construct();
        self::$rules = [
            'name' => ['min_length(3)', 'max_length(20)'],
            'price' => ['positive', 'min_length(1)', 'max_length(8)'],
        ];
        self::$db->createTable(self::$tableName,self::getInputs());
        self::$params = $params;
    }

    public static function naming(){
        return [];
    }


    /*
     *If type is empty, will not appear on the page
     *If sql_type is empty, will not create field on sql table
     *If index is false, will not show field on index page
     */
    public static function getInputs(){
        $array[] = ['type'=>'text', 'name'=>'Name', 'index'=>true, 'key'=>'name', 'sql_type'=>'varchar(100)'];
        $array[] = ['type'=>'text', 'name'=>'Price', 'index'=>true, 'key'=>'price', 'sql_type'=>'decimal(10,2)'];
        $array[] = ['type'=>'number', 'name'=>'Quantity', 'index'=>true, 'key'=>'quantity', 'sql_type'=>'tinyint(1)'];
        $array[] = ['type'=>'text', 'name'=>'Shop name', 'index'=>true, 'key'=>'shop', 'sql_type'=>'varchar(50)'];
        $array[] = ['type'=>'datetime-local', 'name'=>'Date', 'index'=>true, 'key'=>'time', 'sql_type'=>'int(11)'];
        $array[] = ['type'=>'note', 'name'=>'Note', 'index'=>false, 'key'=>'note', 'sql_type'=>'text'];
        $array[] = ['type'=>'', 'name'=>'', 'index'=>false, 'key'=>'position', 'sql_type'=>'int(3)'];
        $array[] = ['type'=>'', 'name'=>'', 'index'=>false, 'key'=>'status', 'sql_type'=>'tinyint(1)'];
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

    protected static function getPost()
    {
        extract($_POST);
        $skip_list = ['csrf_token','image'];
        $array = [];
//        Console::varDump($_POST);
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            if(Date::validateDate($_POST[$key])){
                $array[$key] = strtotime($_POST[$key]);
            }else {
                $array[$key] = Security::safe($_POST[$key]);
            }
        }
        return $array;
    }


    public static function sumPriceAll(){
        $query =  self::$db->selectOne("SELECT sum(`price`) as `return` FROM `".self::$tableNameBeds."` WHERE `status`=1");
        return intval($query['return']);
    }
    public static function sumVacantBeds(){
        $query =  self::$db->selectOne("SELECT sum(`price`) as `return` FROM `".self::$tableNameBeds."` WHERE `tenant_id`<1 AND `status`=1");
        return intval($query['return']);
    }
    public static function sumPriceActive(){
        $query =  self::$db->selectOne("SELECT sum(`price`) as `return` FROM `".self::$tableNameBeds."` WHERE `tenant_id`>0");
        return intval($query['return']);
    }
    public static function sumCostApt(){
        $query =  self::$db->selectOne("SELECT sum(`rent`) as `return` FROM `".self::$tableNameApartments."` WHERE `status`=1");
        return intval($query['return']);
    }

    public static function countAptTenantsByApt($id){
        $query =  self::$db->selectOne("SELECT COUNT(`id`) as `return` FROM `".self::$tableNameTenants."` WHERE  `available_date`<'".date("Y-m-d")."'AND `apt_id`=".$id);
        return $query['return'];
    }

    public static function countNoticeByApt($id){
        $query =  self::$db->selectOne("SELECT COUNT(`id`) as `return` FROM `".self::$tableNameTenants."` WHERE `available_date`>'".date("Y-m-d")."' AND `apt_id`=".$id);
        return $query['return'];
    }

    public static function countAptBeds($id){
        $query =  self::$db->selectOne("SELECT COUNT(`id`) as `return` FROM `".self::$tableNameBeds."` WHERE `apt_id`=".$id);
        return $query['return'];
    }
    public static function sumPriceByAptAll($id){
        $query =  self::$db->selectOne("SELECT sum(`price`) as `return` FROM `".self::$tableNameBeds."` WHERE `apt_id`=".$id);
        return intval($query['return']);
    }
    public static function sumPriceByAptActive($id){
        $query =  self::$db->selectOne("SELECT sum(`price`) as `return` FROM `".self::$tableNameBeds."` WHERE `tenant_id`>0 AND `apt_id`=".$id);
        return intval($query['return']);
    }


    public static function countBeds(){
        $query =  self::$db->selectOne("SELECT COUNT(`id`) as `return` FROM `".self::$tableNameBeds."` WHERE `status`=1");
        return $query['return'];
    }
    public static function countBedsActive(){
        $query =  self::$db->selectOne("SELECT COUNT(`id`) as `return` FROM `".self::$tableNameBeds."` WHERE `status`=1 AND `tenant_id`>0 AND `available_date`<'".date('Y-m-d')."'");
        return $query['return'];
    }
    public static function countBedsNotice(){
        $query =  self::$db->selectOne("SELECT COUNT(`id`) as `return` FROM `".self::$tableNameBeds."` WHERE `status`=1 AND `tenant_id`>0 AND `available_date`>'".date('Y-m-d')."'");
        return $query['return'];
    }


    public static function updateRoomBedIds(){
        $bed_list = self::$db->select("SELECT `id`,`room_id`,`apt_id` FROM ".self::$tableNameBeds);
        foreach ($bed_list as $bed){
            $update_data = ['room_id'=>$bed['room_id'], 'apt_id'=>$bed['apt_id']] ;
            $where = ['bed_id'=>$bed['id']] ;
            self::$db->update(self::$tableNameTenants, $update_data, $where);
        }
    }
}

?>