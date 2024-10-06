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

        $check = self::$db->selectOne("SELECT `id` FROM `" . self::$tableNameSubscribers . "` WHERE `channel_id`= '" . $id . "' AND `user_id`= '" . $user_id . "'");
        if (!$check) {
            $data = ['channel_id' => $id, 'user_id' => $user_id, 'time' => time()];
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
        $where = ['channel_id' => $id, 'user_id' => $user_id];
        self::$db->delete(self::$tableNameSubscribers, $where);
        self::$db->raw("UPDATE `" . self::$tableNameChannels . "` SET `subscribers`=`subscribers`-1 WHERE `id`= '" . $id . "'");
        return self::$lng->get('Subscribe');
    }




    public static function search($text)
    {
        $data = '<div class="divide-y divide-gray-200">';

        $array_channels = self::$db->select("SELECT `id`,`name`,`image`,`subscribers` FROM `" . self::$tableNameChannels . "` WHERE `name` LIKE '%" . $text . "%' ORDER BY `subscribers` DESC LIMIT 5");
        if ($array_channels) {
            $data .= '<div class="py-4 px-2">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">' . self::$lng->get('News Channels') . '</h3>
                        <ul role="list" class="divide-y divide-gray-200">';
            foreach ($array_channels as $item) {
                $data .= '<li>
                            <a href="/' . Format::urlTextChannel($item['name']) . '" class="py-3 flex items-center hover:bg-gray-50 transition duration-150 ease-in-out">
                                <img class="h-10 w-10 rounded-full object-cover" src="https://new.ug.news/storage/' . $item['image'] . '" alt="' . $item['name'] . '"/>
                                <div class="ml-3 flex-grow">
                                    <p class="text-sm font-medium text-gray-900">' . $item['name'] . '</p>
                                    <p class="text-sm text-gray-500">' . number_format($item['subscribers']) . ' ' . self::$lng->get('subscribers') . '</p>
                                </div>
                            </a>
                        </li>';
            }
            $data .= '</ul></div>';
        }

        $array_news = self::$db->select("SELECT `id`,`title`,`slug`,`image`,`time` FROM `" . self::$tableNameNews . "` WHERE `title` LIKE '%" . $text . "%' ORDER BY `time` DESC LIMIT 5");
        if ($array_news) {
            $data .= '<div class="py-4 px-2">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">' . self::$lng->get('News') . '</h3>
                        <ul role="list" class="divide-y divide-gray-200">';
            foreach ($array_news as $item) {
                $data .= '<li>
                            <a href="/' . $item['slug'] . '" class="py-3 flex items-center hover:bg-gray-50 transition duration-150 ease-in-out">
                                <img class="h-16 w-24 object-cover rounded" src="https://new.ug.news/storage/' . $item['image'] . '" alt="' . $item['title'] . '"/>
                                <div class="ml-3 flex-grow">
                                    <p class="text-sm font-medium text-gray-900">' . Format::listTitle($item['title'], 60) . '</p>
                                    <p class="text-xs text-gray-500">' . date('M d, Y H:i', $item['time']) . '</p>
                                </div>
                            </a>
                        </li>';
            }
            $data .= '</ul></div>';
        }

        if (!$array_channels && !$array_news) {
            $data .= '<div class="py-4 text-center text-gray-500">' . self::$lng->get('No result') . '</div>';
        }

        $data .= '</div>';
        return $data;
    }


    public static function countryList()
    {
        $list = CountryModel::getList();
        $data = [];
        foreach ($list as $item) {
            $data[] = ['id' => $item['id'], 'name' => $item['name']];
        }
        return json_encode($data);
    }
}
