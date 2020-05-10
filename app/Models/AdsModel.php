<?php
namespace Models;
use Core\Model;
use Helpers\Cookie;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Core\Language;


class AdsModel extends Model{

    private static $tableName = 'ads';
    private static $region;
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        self::$region = Cookie::get('set_region');
        if(self::$region==0)self::$region = DEFAULT_COUNTRY;
    }



    public static function getItem($count=true){

        $array = self::$db->selectOne("SELECT `id`,`title`,`text`,`link`,`thumb`,`image` FROM `".self::$tableName."` WHERE `status`=1");

        if($count) {
            self::$db->raw("UPDATE `" . self::$tableName . "` SET `view`=`view`+1 WHERE `id`='" . $array['id'] . "'");
        }



//        if($array && $count) {
//            self::$db->raw("UPDATE `" . self::$tableNameChannels . "` SET `view`=`view`+1 WHERE `id`='" . $array['channel'] . "'");
//        }
        return $array;
    }

}
