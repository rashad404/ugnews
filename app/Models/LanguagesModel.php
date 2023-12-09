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

        $language  = Database::get()->selectOne('SELECT `code` FROM '.$table.' WHERE `default` = 1');

        return $language["code"];
    }

    public static function defaultLanguage($app='app')
    {
        
	    if($app=='admin'){$table = self::$adminTableName;$admin_key='admin_';}else{$table = self::$tableName;$admin_key='';}
        if(Cookie::has('set_region')) {
            $region = Cookie::get('set_region');
            if($region==16){
                $language = 'az';
            }else{
                $language = 'en';
            }
            Cookie::set('lang', $language);

        }else{
            $region = DEFAULT_COUNTRY;
        }

        if($region==16){
            $language = 'az';
        }else{
            $language = 'en';
        }
        Cookie::set('lang', $language);

        return 'az';

//        elseif(Cookie::has($admin_key.'lang')) {
//            $language = Cookie::get($admin_key.'lang');
//        } else {
//            $get_language = Database::get()->selectOne('SELECT `code` FROM '.$table.' WHERE `default` = 1');
//            $language = $get_language['code'];
//        }
        return $language;
    }

    public static function getLanguageName($code,$app='app')
    {
	    if($app=='admin'){$table = self::$adminTableName;}else{$table = self::$tableName;}
        $get_language = Database::get()->selectOne('SELECT `name` FROM '.$table.' WHERE `name` = :name', [':name' => $code]);
        if(is_array($get_language)) {
            return $get_language['name'];
        } else {
            return 'Not found';
        }
    }

}

?>