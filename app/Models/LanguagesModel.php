<?php
namespace Models;
use Helpers\Cookie;
use Core\Model;
use PDO;
use Helpers\Database;

class LanguagesModel extends Model{

    public static $tableName = 'languages';
    public static $adminTableName = 'admin_languages';
    public static $partnerTableName = 'partner_languages';

    public function __construct(){
        parent::__construct();
    }


    public static function getLanguages($app='app')
    {
    	if($app=='admin'){$table = self::$adminTableName;}else{$table = self::$tableName;}
        $languages  = Database::get()->select('SELECT * FROM '.$table.' WHERE `status` = 1 ORDER BY `default` DESC, `position` DESC');
        return $languages;
    }

    public static function getDefaultLanguage($app='app')
    {
	    if($app=='admin'){$table = self::$adminTableName;}elseif($app=='partner'){$table = self::$partnerTableName;}else{$table = self::$tableName;}

        $language  = Database::get()->selectOne('SELECT `name` FROM '.$table.' WHERE `default` = 1');

        return $language["name"];
    }

    public static function defaultLanguage($app='app')
    {
	    if($app=='admin'){$table = self::$adminTableName;$admin_key='admin_';}else{$table = self::$tableName;$admin_key='';}
        if(Cookie::has($admin_key.'lang')) {
            $language = Cookie::get($admin_key.'lang');
        } else {
            $get_language = Database::get()->selectOne('SELECT `name` FROM '.$table.' WHERE `default` = 1');
            $language = $get_language['name'];
        }
        return $language;
    }

    public static function getLanguageName($code,$app='app')
    {
	    if($app=='admin'){$table = self::$adminTableName;}else{$table = self::$tableName;}
        $get_language = Database::get()->selectOne('SELECT `fullname` FROM '.$table.' WHERE `name` = :name', [':name' => $code]);
        if(is_array($get_language)) {
            return $get_language['fullname'];
        } else {
            return 'Not found';
        }
    }

}

?>