<?php
namespace Models;
use Core\Model;
class SiteModel extends Model{

    private $defLang;
    public function __construct(){
        parent::__construct();
    }

    public function getFaqList(){
        $row = self::$db->select("SELECT `title_".$this->defLang."`, `text_".$this->defLang."` FROM `faq` WHERE `status`=1");
        return $row;
    }
    public static function getContacts(){
        $row = self::$db->selectOne("SELECT `working_days_".self::$def_language."`, `address_".self::$def_language."`,`email`,`home_tel`,`mobile_tel` FROM `contacts` WHERE `id`=1");
        return $row;
    }


}
