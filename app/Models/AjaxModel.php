<?php

namespace Models;

use Core\Model;
use Core\Language;
use Helpers\Cookie;
use Helpers\Format;
use Helpers\Session;
use Helpers\Url;

class AjaxModel extends Model
{

    private static $tableNameSubscribers = 'subscribers';
    private static $tableNameLikes = 'likes';
    private static $tableNameChannels = 'channels';
    private static $tableNameNews = 'news';
    public static $lng;
    public function __construct()
    {
        parent::__construct();
        self::$lng = new Language();
        self::$lng->load('app');
    }


    public static function like($id)
    {
        $user_id = intval(Session::get("user_session_id"));
        if ($user_id < 1) exit;

        $check = self::$db->selectOne("SELECT `id` FROM `" . self::$tableNameLikes . "` WHERE `news_id`= '" . $id . "' AND `user_id`= '" . $user_id . "'");
        if ($check) {
            $data = ['liked' => 1, 'disliked' => 0, 'time' => time()];
            $where = ['news_id' => $id, 'user_id' => $user_id];
            self::$db->update(self::$tableNameLikes, $data, $where);

            self::$db->raw("UPDATE `" . self::$tableNameNews . "` SET `likes`=`likes`+1 WHERE `id`= '" . $id . "'");
        } else {
            $data = ['liked' => 1, 'news_id' => $id, 'user_id' => $user_id, 'time' => time()];
            self::$db->insert(self::$tableNameLikes, $data);
        }

        return self::$lng->get('Liked');
    }

    public static function removeLike($id)
    {
        $user_id = intval(Session::get("user_session_id"));
        if ($user_id < 1) exit;

        $check = self::$db->selectOne("SELECT `id` FROM `" . self::$tableNameLikes . "` WHERE `news_id`= '" . $id . "' AND `user_id`= '" . $user_id . "'");
        if ($check) {
            $data = ['liked' => 0, 'time' => time()];
            $where = ['news_id' => $id, 'user_id' => $user_id];
            self::$db->update(self::$tableNameLikes, $data, $where);
            self::$db->raw("UPDATE `" . self::$tableNameNews . "` SET `likes`=`likes`-1 WHERE `id`= '" . $id . "'");
        }

        return self::$lng->get('Like');
    }


    public static function dislike($id)
    {
        $user_id = intval(Session::get("user_session_id"));
        if ($user_id < 1) exit;

        $check = self::$db->selectOne("SELECT `id` FROM `" . self::$tableNameLikes . "` WHERE `news_id`= '" . $id . "' AND `user_id`= '" . $user_id . "'");
        if ($check) {
            $data = ['liked' => 0, 'disliked' => 1, 'time' => time()];
            $where = ['news_id' => $id, 'user_id' => $user_id];
            self::$db->update(self::$tableNameLikes, $data, $where);
            self::$db->raw("UPDATE `" . self::$tableNameNews . "` SET `dislikes`=`dislikes`+1 WHERE `id`= '" . $id . "'");
        } else {
            $data = ['disliked' => 1, 'news_id' => $id, 'user_id' => $user_id, 'time' => time()];
            self::$db->insert(self::$tableNameLikes, $data);
        }

        return self::$lng->get('Disliked');
    }

    public static function removeDislike($id)
    {
        $user_id = intval(Session::get("user_session_id"));
        if ($user_id < 1) exit;

        $check = self::$db->selectOne("SELECT `id` FROM `" . self::$tableNameLikes . "` WHERE `news_id`= '" . $id . "' AND `user_id`= '" . $user_id . "'");
        if ($check) {
            $data = ['disliked' => 0, 'time' => time()];
            $where = ['news_id' => $id, 'user_id' => $user_id];
            self::$db->update(self::$tableNameLikes, $data, $where);
            self::$db->raw("UPDATE `" . self::$tableNameNews . "` SET `dislikes`=`dislikes`-1 WHERE `id`= '" . $id . "'");
        }

        return self::$lng->get('Dislike');
    }


    public static function subscribe($id)
    {
        $user_id = intval(Session::get("user_session_id"));
        if ($user_id < 1) exit;

        $check = self::$db->selectOne("SELECT `id` FROM `" . self::$tableNameSubscribers . "` WHERE `channel`= '" . $id . "' AND `user_id`= '" . $user_id . "'");
        if (!$check) {
            $data = ['channel' => $id, 'user_id' => $user_id, 'time' => time()];
            self::$db->insert(self::$tableNameSubscribers, $data);
            self::$db->raw("UPDATE `" . self::$tableNameChannels . "` SET `subscribers`=`subscribers`+1 WHERE `id`= '" . $id . "'");
            return self::$lng->get('Subscribed');
        } else {
            return self::$lng->get('Subscribe');
        }
    }

    public static function unSubscribe($id)
    {
        $user_id = intval(Session::get("user_session_id"));
        if ($user_id < 1) exit;
        $where = ['channel' => $id, 'user_id' => $user_id];
        self::$db->delete(self::$tableNameSubscribers, $where);
        self::$db->raw("UPDATE `" . self::$tableNameChannels . "` SET `subscribers`=`subscribers`-1 WHERE `id`= '" . $id . "'");
        return self::$lng->get('Subscribe');
    }




    public static function search($text)
    {
        $data = '';


        $array_channels = self::$db->select("SELECT `id`,`name`,`thumb` FROM `" . self::$tableNameChannels . "` WHERE `name` LIKE '%" .$text. "%'ORDER BY `id` ASC LIMIT 10");
        if ($array_channels) {
            $data .= '<li class="li_title">' . self::$lng->get('News Channels') . '</li>';
            foreach ($array_channels as $item) {
                $data .= '<li class="channel_li"><a style="padding: 10px 20px;" href="/' . Format::urlTextChannel($item['name']) . '">
                <div class="row">
                        <div class="col-2">
                            <img  class="channel_img" src="' . Url::filePath() . '/' . $item['thumb'] . '" alt=""/>
                        </div>
                        <div class="col-7">
                            ' . $item['name'] . '
                        </div>
                        <div class="col-3">

                            <div class="search_count">4 ' . self::$lng->get('subscribers') . '</div>
                        </div>
                    </div></a></li>';
            }
        }

        $array_news = self::$db->select("SELECT `id`,`title`,`slug`,`thumb`,`time` FROM `" . self::$tableNameNews . "` WHERE `slug` LIKE '%" . $text . "%'ORDER BY `time` DESC LIMIT 10");
        if ($array_news) {
            $data .= '<li class="li_title" style="margin-bottom: 10px;font-size: 16px;">' . self::$lng->get('News') . '</li>';
            foreach ($array_news as $item) {
                $data .= '
                <li>
                <a href="/' . $item['slug'] .  '">
                    <div class="row">
                        <div class="col-2 col-md-2 remove_col_padding_mob">
                            <img src="' . Url::filePath() . '/' . $item['thumb'] . '" alt=""/>
                        </div>
                        <div class="col-8 col-md-8">
                            <span class="search_news_name">' . $item['title'] . '</span> 
                        </div>
                        <div class="col-2 col-md-2">
                            <div class="search_date">' . date('M d H:i', $item['time']) . '</div>
                        </div>
                    </div>
                </a>
                </li>';
            }
        }

        if (!$array_channels && !$array_news) {
            $data .= '<li class="li_title">' . self::$lng->get('No result') . '</li>';
        }
        return $data;
    }
}
