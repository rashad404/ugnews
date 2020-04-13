<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Cookie;
use Helpers\Session;
use Helpers\Url;

class AjaxModel extends Model{

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
        $data = '';

        $array_channels = self::$db->select("SELECT `id`,`name` FROM `".self::$tableNameChannels."` WHERE `name` LIKE '%".$text."%'ORDER BY `id` ASC");
        if($array_channels) {
            $data .= '<li class="li_title">' . self::$lng->get('News Channels') . ':</li>';
            foreach ($array_channels as $item) {
                $data .= '<li><a href="/channels/' . $item['id'] . '"><i class="fas fa-broadcast-tower"></i> ' . $item['name'] . '</a></li>';
            }
        }

        $array_news = self::$db->select("SELECT `id`,`title`,`thumb` FROM `".self::$tableNameNews."` WHERE `title` LIKE '%".$text."%'ORDER BY `time` DESC");
        if($array_news) {
            $data .= '<li class="li_title" style="padding-top:20px;">' . self::$lng->get('News') . ':</li>';
            foreach ($array_news as $item) {
                $data .= '<li><a href="/news/' . $item['id'] . '"><img src="' . Url::filePath() . '/' . $item['thumb'] . '" alt=""/> ' . $item['title'] . '</a></li>';
            }
        }

        if(!$array_channels && !$array_news){
            $data .= '<li class="li_title">' . self::$lng->get('No result').'</li>';
        }
        return $data;
    }


}
