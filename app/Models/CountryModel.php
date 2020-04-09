<?php
namespace Models;
use Core\Model;
class CountryModel extends Model{

    protected static $tableName = 'countries';
    public function __construct(){
        parent::__construct();
    }

    public static function getList(){
        $row = self::$db->select("SELECT `name`,`code` FROM `".self::$tableName."`");
        return $row;
    }

}
