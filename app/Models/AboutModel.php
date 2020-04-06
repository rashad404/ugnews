<?php
namespace Models;
use Core\Model;
use Helpers\Cookie;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Core\Language;

class AboutModel extends Model{

    private static $tableName = 'about';
    public function __construct(){
        parent::__construct();
    }

    public static function getItem(){
        $array = self::$db->selectOne("SELECT `text_".self::$def_language."` FROM `".self::$tableName."`");
        return $array;
    }
}
