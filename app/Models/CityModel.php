<?php
namespace Models;
use Core\Model;
use Helpers\Cookie;

class CityModel extends Model{

    protected static $tableName = 'cities';
    private static $region;

    public function __construct(){
        parent::__construct();
        self::$region = Cookie::get('set_region');
        if(self::$region==0)self::$region = DEFAULT_COUNTRY;
    }

    public static function getList($limit='LIMIT 0,100'){
        $row = self::$db->select("SELECT `id`,`name` FROM `".self::$tableName."` WHERE `country`='".self::$region."' ORDER BY `name` ASC ".$limit);
        return $row;
    }
    public static function getName($id){
        $row = self::$db->selectOne("SELECT `name` FROM `".self::$tableName."` WHERE `id`='".$id."'");
        return $row['name'];
    }

}
