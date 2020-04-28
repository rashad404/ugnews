<?php
namespace Models;
use Core\Model;
class CityModel extends Model{

    protected static $tableName = 'cities';
    public function __construct(){
        parent::__construct();
    }

    public static function getList($limit=100){
        $row = self::$db->select("SELECT `id`,`name` FROM `".self::$tableName."` ORDER BY `name` ASC LIMIT ".$limit);
        return $row;
    }
    public static function getName($id){
        $row = self::$db->selectOne("SELECT `name` FROM `".self::$tableName."` WHERE `id`='".$id."'");
        return $row['name'];
    }

}
