<?php
namespace Models;
use Core\Model;
use Helpers\Cookie;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Core\Language;

class MediaModel extends Model{

    private static $tableName = 'media';
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }

    public static function countList(){
        $count = self::$db->selectOne("SELECT count(`id`) as countList FROM `".self::$tableName."` WHERE `status`=1");
        return $count['countList'];
    }
    public static function getList($limit='LIMIT 0,10'){
        $array = self::$db->select("SELECT `id`,`title_".self::$def_language."`,`thumb`,`image` FROM `".self::$tableName."` WHERE `status`=1 ORDER BY `id` DESC $limit");
        return $array;
    }

    public static function getItem($id){
        $array = self::$db->selectOne("SELECT `id`,`time`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image`,`price`,`features` FROM `".self::$tableName."` WHERE `id`='".$id."' AND `status`=1 ORDER BY `id` DESC");
        return $array;
    }

    public static function navigate($id, $action){
        if($action=='next'){
            $action_symbol = '>';
        }else{
            $action_symbol = '<';
        }
        $array = self::$db->selectOne("SELECT `id`,`time`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image`,`price`,`features` FROM `".self::$tableName."` WHERE `id` ".$action_symbol." '".$id."' AND `status`=1 ORDER BY `id` DESC");
        return $array;
    }

}
