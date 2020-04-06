<?php
namespace Models;
use Core\Model;
use Helpers\Session;
use Core\Language;

class BedsModel extends Model{

    private static $tableName = 'apt_beds';
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }

    public static function getItem($id){
        return self::$db->selectOne("SELECT `id`,`apt_id`,`room_id`,`tenant_id`,`name_".self::$def_language."`,`status`,`price`,`apply_link`,`available_date` FROM ".self::$tableName." WHERE `id`='".$id."'");
    }

}
