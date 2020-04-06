<?php
namespace Models;
use Core\Model;
use Helpers\Cookie;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Core\Language;

class SliderModel extends Model{

    private static $tableName = 'slider';
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }

    public static function getList($limit=10){
        $array = self::$db->select("SELECT `id`,`link`,`time`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image` FROM `".self::$tableName."` WHERE `status`=1 ORDER BY `id` DESC LIMIT $limit");
        return $array;
    }

    public static function getItem($id){
        $array = self::$db->selectOne("SELECT `id`,`link`,`time`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image` FROM `".self::$tableName."` WHERE `id`='".$id."' AND `status`=1 ORDER BY `id` DESC");
        return $array;
    }

}
