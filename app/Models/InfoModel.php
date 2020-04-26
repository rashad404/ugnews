<?php
namespace Models;
use Core\Model;
use Core\Language;

class InfoModel extends Model{

    private static $tableNameCorona = 'coronavirus';
    private static $tableNameNamaz = 'namaz';
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }



    public static function coronavirusList(){
        $array = self::$db->select("SELECT * FROM `".self::$tableNameCorona."` WHERE `id`!=2 ORDER BY `total_cases` DESC");
        return $array;
    }

    public static function coronavirusSelected(){
        $array = self::$db->select("SELECT * FROM `".self::$tableNameCorona."` WHERE `id`=2 OR `id`=70");
        return $array;
    }

    public static function getMost(){
        $array = self::$db->selectOne("SELECT `total_cases` FROM `".self::$tableNameCorona."` WHERE `id`=2");
        return $array['total_cases'];
    }
    public static function getNamazTime($date=''){
        if(empty($date)){
            $date = date('Y-m-d');
        }
        $array = self::$db->selectOne("SELECT * FROM `".self::$tableNameNamaz."` WHERE `date`='".$date."'");
        return $array;
    }

}
