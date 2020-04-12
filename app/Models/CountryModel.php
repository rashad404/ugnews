<?php
namespace Models;
use Core\Model;
class CountryModel extends Model{

    protected static $tableName = 'countries';
    public function __construct(){
        parent::__construct();
    }

    public static function getList(){
        $row = self::$db->select("SELECT `id`,`name`,`code` FROM `".self::$tableName."` WHERE `status`=1 ORDER BY `name`");
        return $row;
    }
    public static function getCode($id){
        $row = self::$db->selectOne("SELECT `code` FROM `".self::$tableName."` WHERE `id`='".$id."'");
        return $row['code'];
    }
    public static function getName($id){
        $row = self::$db->selectOne("SELECT `name` FROM `".self::$tableName."` WHERE `id`='".$id."'");
        return $row['name'];
    }

}
