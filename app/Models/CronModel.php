<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Curl;
use Helpers\Session;

class CronModel extends Model{

    private static $tableNameSubscribers = 'subscribers';
    private static $tableNameLikes = 'likes';
    private static $tableNameChannels = 'channels';
    private static $tableNameNews = 'news';
    public static $lng;
    public function __construct(){
        parent::__construct();
        self::$lng = new Language();
        self::$lng->load('app');
    }

    public static function coronavirus(){
        $curl = Curl::getRequest('https://www.worldometers.info/coronavirus');
        echo $curl;exit;






        $user_id = intval(Session::get("user_session_id"));
        $where = ['channel'=>$id, 'user_id'=>$user_id];
        self::$db->delete(self::$tableNameSubscribers, $where);
        self::$db->raw("UPDATE `".self::$tableNameChannels."` SET `subscribers`=`subscribers`-1 WHERE `id`= '".$id."'");
        return self::$lng->get('Subscribe');
    }


}
