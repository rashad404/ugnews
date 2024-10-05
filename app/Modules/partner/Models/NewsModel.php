<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Session;
use Helpers\Validator;
use Helpers\Slim;
use Helpers\Url;
use Modules\partner\Traits\CommonModelTrait;

class NewsModel extends Model
{
    use CommonModelTrait;

    private static $tableName = 'news';
    private static $tableNameCategories = 'categories';
    private static $tableNameCountries = 'countries';
    private static $tableNameCities = 'cities';
    private static $tableNameLanguages = 'languages';
    private static $tableNameChannels = 'channels';

    private static $rules;
    private static $params;
    private static $user_id;

    public function __construct($params = '')
    {
        parent::__construct();
        self::$rules = [
            'title' => ['required', 'min_length(5)', 'max_length(100)'],
            'text' => ['required', 'min_length(50)', 'max_length(1000000)'],
        ];
        self::$db->createTable(self::$tableName, self::getInputs());
        self::$params = $params;
        self::$user_id = Session::get('user_session_id');
    }

    public static function naming()
    {
        return [];
    }

    public static function getInputs()
    {
        return [
            ['type' => 'text', 'name' => 'Title', 'key' => 'title', 'sql_type' => 'varchar(100)'],
            ['type' => 'text', 'name' => 'Extra Title', 'key' => 'title_extra', 'sql_type' => 'varchar(100)'],
            ['type' => 'select2', 'name' => 'Select category', 'key' => 'category_id', 'sql_type' => 'int(5)', 'data' => self::getCategories()],
            ['type' => 'select2', 'name' => 'Select Channel', 'key' => 'channel_id', 'sql_type' => 'varchar(2)', 'data' => self::getChannels()],
            ['type' => 'select2', 'name' => 'City', 'key' => 'city_id', 'sql_type' => 'int(11)', 'data' => self::getCities()],
            ['type' => '', 'name' => '', 'key' => 'image', 'sql_type' => 'varchar(200)'],
            ['type' => '', 'name' => '', 'key' => 'thumb', 'sql_type' => 'varchar(200)'],
            ['type' => '', 'name' => '', 'key' => 'position', 'sql_type' => 'int(11)'],
            ['type' => 'tags', 'name' => 'Tags', 'key' => 'tags', 'sql_type' => 'varchar(255)'],
            ['type' => 'datetime', 'name' => 'Publish Date', 'key' => 'publish_time', 'sql_type' => 'int(11)'],
            ['type' => '', 'name' => '', 'key' => 'status', 'sql_type' => 'tinyint(2)'],
            ['type' => '', 'name' => '', 'key' => 'time', 'sql_type' => 'int(11)'],
            ['type' => '', 'name' => '', 'key' => 'view', 'sql_type' => 'int(11)'],
            ['type' => '', 'name' => '', 'key' => 'user_id', 'sql_type' => 'int(11)'],
            ['type' => '', 'name' => '', 'key' => 'slug', 'sql_type' => 'varchar(255)'],
            ['type' => 'textarea', 'name' => 'Text', 'key' => 'text', 'sql_type' => 'text'],
        ];
    }

    public static function getCategories()
    {
        $array = self::$db->select("SELECT `id`, `name` FROM " . self::$tableNameCategories . " WHERE `status`=1 ORDER BY `position`");
        return array_map(fn($item) => ['key' => $item['id'], 'name' => $item['name'], 'disabled' => ''], $array);
    }

    public static function getCities()
    {
        $array = self::$db->select("SELECT `id`, `name` FROM " . self::$tableNameCities);
        return array_merge(
            [['key' => 0, 'name' => '---', 'disabled' => '']],
            array_map(fn($item) => ['key' => $item['id'], 'name' => $item['name'], 'disabled' => ''], $array)
        );
    }

    public static function getChannels()
    {
        $array = self::$db->select("SELECT `id`, `name` FROM " . self::$tableNameChannels . " WHERE `status`=1 AND `user_id`='" . self::$user_id . "' ORDER BY `id` DESC");
        return array_map(fn($item) => ['key' => $item['id'], 'name' => $item['name'], 'disabled' => ''], $array);
    }

    public static function add()
    {
        $return = ['errors' => null];
        $post_data = self::getPost();

        $post_data['text'] = preg_replace(['/..\/..\/..\//','/..\/..\//'], '/', $post_data['text']);
        $post_data['publish_time'] = strtotime($post_data['publish_time']);
        $post_data['publish_time'] = max($post_data['publish_time'], time());

        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $insert_data = $post_data;
            $insert_data['user_id'] = self::$user_id;
            $insert_data['time'] = time();

            if ($post_data['channel_id'] > 0) {
                $channel_info = ChannelsModel::getItem($post_data['channel_id']);
                $insert_data['country_id'] = $channel_info['country_id'];
                $insert_data['language_id'] = $channel_info['language_id'];
            }

            $insert_data['slug'] = $channel_info['name_url'] . '/' . Url::generateSafeSlug($post_data['title']);

            $insert_id = self::$db->insert(self::$tableName, $insert_data);
            if ($insert_id > 0) {
                self::updatePosition($insert_id);
                self::handleImageUpload($insert_id);
            }
        } else {
            $return['errors'] = implode('<br/>', array_map("ucfirst", $validator->getErrors()));
        }
        return $return;
    }

    public static function update($id)
    {
        $return = ['errors' => null];
        $post_data = self::getPost();

        $post_data['text'] = preg_replace(['/..\/..\/..\//','/..\/..\//'], '/', $post_data['text']);
        $post_data['publish_time'] = strtotime($post_data['publish_time']);

        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $update_data = $post_data;
            if ($post_data['channel_id'] > 0) {
                $channel_info = ChannelsModel::getItem($post_data['channel_id']);
                $update_data['country_id'] = $channel_info['country_id'];
                $update_data['language_id'] = $channel_info['language_id'];
            }

            self::$db->update(self::$tableName, $update_data, ['id' => $id]);
            self::handleImageUpload($id);
        } else {
            $return['errors'] = implode('<br/>', array_map("ucfirst", $validator->getErrors()));
        }

        return $return;
    }

    private static function handleImageUpload($id)
    {
        $images = Slim::getImages('image');
        if ($images) {
            $image = $images[0];
            if (!empty($image)) {
                self::imageUpload($image, $id);
            }
        }
    }

    public static function search()
    {
        $postData = self::getPost();
        $text = $postData['search'];
        $values = self::$params['searchFields'];

        $sql_s = is_array($values) ? 
            implode(' OR ', array_map(fn($v) => "`$v` LIKE '%$text%'", $values)) :
            "`$values` LIKE '%$text%'";

        return self::$db->select("SELECT " . self::getSqlFields() . " FROM `" . self::$tableName . "` WHERE " . $sql_s);
    }
}