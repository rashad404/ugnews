<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Session;
use Helpers\Validator;
use Modules\partner\Traits\CommonModelTrait;

class SettingsModel extends Model
{
    use CommonModelTrait;

    private static $tableName = 'partner_settings';
    private static $user_id;
    private static $rules;

    public function __construct()
    {
        parent::__construct();
        self::$user_id = Session::get('user_session_id');

        self::$rules = [
            'country' => ['min_length(0)', 'max_length(2)'],
            'language' => ['min_length(0)', 'max_length(2)'],
        ];
    }

    public static function naming()
    {
        return [];
    }

    public static function getInputs()
    {
        return [
            ['type' => 'select2', 'name' => 'channel_id', 'key' => 'channel_id', 'sql_type' => 'int(11)'],
            ['type' => 'select2', 'name' => 'Country', 'key' => 'country', 'sql_type' => 'varchar(2)'],
            ['type' => 'select2', 'name' => 'Language', 'key' => 'language', 'sql_type' => 'varchar(2)'],
            ['type' => '', 'name' => '', 'key' => 'user_id', 'sql_type' => 'int(11)'],
        ];
    }

    public static function getItem()
    {
        $check = self::$db->selectOne("SELECT * FROM " . self::$tableName . " WHERE `user_id`=:user_id", [':user_id' => self::$user_id]);
        if ($check) {
            return $check;
        } else {
            $defaultSettings = [
                'channel_id' => '233',
                'country' => '233',
                'language' => '3',
                'user_id' => self::$user_id,
            ];
            self::$db->insert(self::$tableName, $defaultSettings);
            return $defaultSettings;
        }
    }

    public static function update()
    {
        $return = ['errors' => null];

        $post_data = self::getPost();

        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            self::$db->update(self::$tableName, $post_data, ['user_id' => self::$user_id]);
        } else {
            $return['errors'] = implode('<br/>', array_map("ucfirst", $validator->getErrors()));
        }

        return $return;
    }
}