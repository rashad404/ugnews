<?php
namespace Models;
use Core\Model;
use Core\Language;

class RatingModel extends Model{

    private static $tableNameChannels = 'channels';
    private static $tableNameNews = 'news';
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }



    public static function topChannels($limit='0,10'){
        $array = self::$db->select("SELECT `id`,`time`,`view`,`name`,`thumb`,`image`,`name_url`,`subscribers` FROM `".self::$tableNameChannels."` WHERE `status`=1 ORDER BY `subscribers` DESC $limit");
        return $array;
    }
    public static function countChannels(){
        $array = self::$db->count("SELECT count(`id`) FROM `".self::$tableNameChannels."` WHERE `status`=1 ");
        return $array;
    }

    public static function topNews($limit='0,10'){
        $array = self::$db->select("SELECT `id`,`title`,`view` FROM `".self::$tableNameNews."` WHERE `status`=1 ORDER BY `view` DESC $limit");
        return $array;
    }
    public static function countNews(){
        $array = self::$db->count("SELECT count(`id`) FROM `".self::$tableNameNews."` WHERE `status`=1 ");
        return $array;
    }


}
