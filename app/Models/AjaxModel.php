<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Cookie;
use Helpers\Session;

class AjaxModel extends Model{

    private static $tableNameSubscribers = 'subscribers';
    private static $tableNameLikes = 'likes';
    private static $tableNameChannels = 'channels';
    public static $lng;
    public function __construct(){
        parent::__construct();
        self::$lng = new Language();
        self::$lng->load('app');
    }




    public static function like($id){
        $user_id = intval(Session::get("user_session_id"));
        if($user_id<1)exit;

        $check = self::$db->selectOne("SELECT `id` FROM `".self::$tableNameLikes."` WHERE `news_id`= '".$id."' AND `user_id`= '".$user_id."'");
        if($check) {
            $data = ['liked'=>1, 'disliked'=>0, 'time'=>time()];
            $where = ['news_id'=>$id, 'user_id'=>$user_id];
            self::$db->update(self::$tableNameLikes, $data, $where);
        }else{
            $data = ['liked'=>1, 'news_id'=>$id, 'user_id'=>$user_id, 'time'=>time()];
            self::$db->insert(self::$tableNameLikes, $data);
        }

        return self::$lng->get('Liked');
    }

    public static function removeLike($id){
        $user_id = intval(Session::get("user_session_id"));
        if($user_id<1)exit;

        $check = self::$db->selectOne("SELECT `id` FROM `".self::$tableNameLikes."` WHERE `news_id`= '".$id."' AND `user_id`= '".$user_id."'");
        if($check) {
            $data = ['liked'=>0, 'time'=>time()];
            $where = ['news_id'=>$id, 'user_id'=>$user_id];
            self::$db->update(self::$tableNameLikes, $data, $where);
        }

        return self::$lng->get('Like');
    }


    public static function dislike($id){
        $user_id = intval(Session::get("user_session_id"));
        if($user_id<1)exit;

        $check = self::$db->selectOne("SELECT `id` FROM `".self::$tableNameLikes."` WHERE `news_id`= '".$id."' AND `user_id`= '".$user_id."'");
        if($check) {
            $data = ['liked'=>0, 'disliked'=>1, 'time'=>time()];
            $where = ['news_id'=>$id, 'user_id'=>$user_id];
            self::$db->update(self::$tableNameLikes, $data, $where);
        }else{
            $data = ['disliked'=>1, 'news_id'=>$id, 'user_id'=>$user_id, 'time'=>time()];
            self::$db->insert(self::$tableNameLikes, $data);
        }

        return self::$lng->get('Disliked');
    }

    public static function removeDislike($id){
        $user_id = intval(Session::get("user_session_id"));
        if($user_id<1)exit;

        $check = self::$db->selectOne("SELECT `id` FROM `".self::$tableNameLikes."` WHERE `news_id`= '".$id."' AND `user_id`= '".$user_id."'");
        if($check) {
            $data = ['disliked'=>0, 'time'=>time()];
            $where = ['news_id'=>$id, 'user_id'=>$user_id];
            self::$db->update(self::$tableNameLikes, $data, $where);
        }

        return self::$lng->get('Dislike');
    }


    public static function subscribe($id){
        $user_id = intval(Session::get("user_session_id"));
        if($user_id<1)exit;

        $check = self::$db->selectOne("SELECT `id` FROM `".self::$tableNameSubscribers."` WHERE `channel`= '".$id."' AND `user_id`= '".$user_id."'");
        if(!$check) {
            $data = ['channel'=>$id, 'user_id'=>$user_id, 'time'=>time()];
            self::$db->insert(self::$tableNameSubscribers, $data);
            return self::$lng->get('Subscribed');
        }else{
            return self::$lng->get('Subscribe');
        }
    }

    public static function unSubscribe($id){
        $user_id = intval(Session::get("user_session_id"));
        if($user_id<1)exit;
        $where = ['channel'=>$id, 'user_id'=>$user_id];
        self::$db->delete(self::$tableNameSubscribers, $where);
        return self::$lng->get('Subscribe');
    }




    public static function search($text){
        $array = self::$db->select("SELECT `id`,`name` FROM `".self::$tableNameChannels."` WHERE `name` LIKE '%".$text."%'ORDER BY `id` ASC");
        $data = '<li class="li_title">'.self::$lng->get('News Channels').':</li>';
        foreach ($array as $item) {
            $data .= '<li><a href="/channels/'.$item['id'].'"><i class="fas fa-broadcast-tower"></i> '.$item['name'].'</a></li>';
        }
        return $data;
    }


}
