<?php
namespace Models;
use Core\Model;

class FaqsModel extends Model{

    public static $list = [];
    public static $tableName = 'faqs';

    public function __construct(){
        self::$list = self::getList();
    }

    public static function getList(){
        $array = self::$db->select("SELECT `id`, `answer`, `question` FROM `".self::$tableName."`");
        return $array;
    }
}
