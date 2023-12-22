<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Cookie;
use Helpers\Security;

class VisitorsModel extends Model{

    private static $tableName = 'visitors';
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }

    public static function getRealIpAddr(){
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }


    public static function generateHash($length = 20)
    {
        $str = '012456789abdefghijklmnoqrstuvwxyzABCDEFGIJKLMNOPQRSTUWXYZ';
        $max = strlen($str);
        $length = @round($length);
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $str[rand(0, $max - 1)];
        }
        

        $code1 = substr($code,0,8);
        $code2 = substr($code,9,5);
        $code3 = substr($code,15,5);

        //9th symbol must be "m", 15th symbol must be: "i"
        //There can't be these symbols: "c","p","H","V","3"
        $hash = $code1.'m'.$code2.'i'.$code3;

        return $hash;
    }

    public static function uniqueID(){

        if(Cookie::has("ug_browser")===false) {
            $unique_id = self::generateHash();
            Cookie::set("ug_browser", self::generateHash());
        }else{
            $unique_id = Cookie::get("ug_browser");
        }
        return $unique_id;
    }

    public static function getUserInfo(){
        $ip = self::getRealIpAddr();
        $ua = Security::safe($_SERVER['HTTP_USER_AGENT']);
    }

    public static function updateView(){
        $id = 1;

        $unique_id = self::uniqueID();

        exit;

        self::$db->raw("UPDATE `" . self::$tableName . "` SET `view`=`view`+1 WHERE `id`='" . $id . "'");
        return true;
    }


}
