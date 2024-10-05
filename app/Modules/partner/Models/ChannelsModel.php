<?php

namespace Modules\partner\Models;

use Core\Model;
use Core\Language;
use Helpers\Session;
use Helpers\Validator;
use Helpers\Slim;
use Helpers\Format;
use Modules\partner\Traits\CommonModelTrait;

class ChannelsModel extends Model
{
    use CommonModelTrait;

    private static $tableName = 'channels';
    private static $tableNameSubscribers = 'subscribers';
    private static $tableNameCategories = 'categories';
    private static $tableNameCountries = 'countries';
    private static $tableNameLanguages = 'languages';

    private static $lng;
    private static $rules;
    private static $params;
    private static $user_id;

    public function __construct($params = '')
    {
        parent::__construct();
        self::$lng = new Language();
        self::$lng->load('partner');
        self::$rules = [
            'name' => ['required', 'min_length(3)', 'max_length(100)'],
            'text' => ['min_length(10)', 'max_length(10000)'],
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
            ['type' => 'text', 'name' => 'Name', 'key' => 'name', 'sql_type' => 'varchar(100)'],
            ['type' => '', 'name' => '', 'key' => 'image', 'sql_type' => 'varchar(200)'],
            ['type' => '', 'name' => '', 'key' => 'thumb', 'sql_type' => 'varchar(200)'],
            ['type' => '', 'name' => '', 'key' => 'position', 'sql_type' => 'int(11)'],
            ['type' => 'select2', 'name' => 'Select category', 'key' => 'cat', 'sql_type' => 'int(5)', 'data' => self::getCategories()],
            ['type' => 'tags', 'name' => 'Tags', 'key' => 'tags', 'sql_type' => 'varchar(255)'],
            ['type' => 'select2', 'name' => 'Select Country', 'key' => 'country', 'sql_type' => 'varchar(2)', 'data' => self::getCountries()],
            ['type' => 'select2', 'name' => 'Select Language', 'key' => 'language', 'sql_type' => 'varchar(2)', 'data' => self::getLanguages()],
            ['type' => '', 'name' => '', 'key' => 'status', 'sql_type' => 'tinyint(2)'],
            ['type' => '', 'name' => '', 'key' => 'time', 'sql_type' => 'int(11)'],
            ['type' => '', 'name' => '', 'key' => 'view', 'sql_type' => 'int(11)'],
            ['type' => '', 'name' => '', 'key' => 'subscribers', 'sql_type' => 'int(11)'],
            ['type' => '', 'name' => '', 'key' => 'user_id', 'sql_type' => 'int(11)'],
            ['type' => 'textarea', 'name' => 'About', 'key' => 'text', 'sql_type' => 'text'],
            ['type' => 'text', 'name' => 'Name URL', 'key' => 'name_url', 'sql_type' => 'text'],
        ];
    }

    public static function getCategories()
    {
        $array = self::$db->select("SELECT `id`, `name` FROM " . self::$tableNameCategories . " WHERE `status`=1 ORDER BY `position`");
        return array_map(fn($item) => ['key' => $item['id'], 'name' => $item['name'], 'disabled' => ''], $array);
    }

    public static function getCountries()
    {
        new SettingsModel();
        $defaults = SettingsModel::getItem();
        $def_country = $defaults['country_id'];

        $array = self::$db->select("SELECT `id`, `name` FROM " . self::$tableNameCountries);
        return array_map(fn($item) => [
            'key' => $item['id'],
            'name' => $item['name'],
            'disabled' => '',
            'default' => ($def_country == $item['id']) ? 'true' : ''
        ], $array);
    }

    public static function getLanguages()
    {
        new SettingsModel();
        $defaults = SettingsModel::getItem();
        $def_language = $defaults['language'];

        $array = self::$db->select("SELECT `id`, `name` FROM " . self::$tableNameLanguages . " WHERE `status`=1 ORDER BY `id` DESC");
        return array_map(fn($item) => [
            'key' => $item['id'],
            'name' => $item['name'],
            'disabled' => '',
            'default' => ($def_language == $item['id']) ? 'true' : ''
        ], $array);
    }

    public static function countSubscribers($id)
    {
        $count = self::$db->selectOne("SELECT count(`id`) as c FROM " . self::$tableNameSubscribers . " WHERE `channel`='" . $id . "'");
        return $count['c'];
    }

    public static function add()
    {
        $return = ['errors' => null];
        $post_data = self::getPost();
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $insert_data = $post_data;
            $insert_data['user_id'] = self::$user_id;
            $insert_data['name_code'] = mb_strtolower(preg_replace("/[ *()\-_.,]/", "", $insert_data['name']));
            $insert_data['name_url'] = Format::urlTextChannel($insert_data['name']);

            $check = self::$db->selectOne("SELECT `id` FROM " . self::$tableName . " WHERE `name_code`='" . $insert_data['name_code'] . "'");
            if ($check) {
                $return['errors'] = self::$lng->get("This channel name already taken. Please choose another name");
            } else {
                $insert_id = self::$db->insert(self::$tableName, $insert_data);
                if ($insert_id > 0) {
                    self::updatePosition($insert_id);
                    $images = Slim::getImages('image');
                    if ($images) {
                        $image = $images[0];
                        if (!empty($image)) {
                            self::imageUpload($image, $insert_id);
                        }
                    }
                }
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
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $update_data = $post_data;
            $update_data['name_code'] = strtolower(preg_replace("/[ *()\-_.,]/", "", $update_data['name']));
            $update_data['name_url'] = Format::urlTextChannel($update_data['name']);

            $check = self::$db->selectOne("SELECT `id` FROM " . self::$tableName . " WHERE `name_code`='" . $update_data['name_code'] . "' AND `id`!=" . $id);
            if ($check) {
                $return['errors'] = self::$lng->get("This channel name already taken. Please choose another name");
            } else {
                self::$db->update(self::$tableName, $update_data, ['id' => $id]);

                $images = Slim::getImages('image');
                if ($images) {
                    $image = $images[0];
                    if (!empty($image)) {
                        self::imageUpload($image, $id);
                    }
                }
            }
        } else {
            $return['errors'] = implode('<br/>', array_map("ucfirst", $validator->getErrors()));
        }

        return $return;
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