<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Session;
use Helpers\Validator;
use Helpers\Slim;
use Modules\partner\Traits\CommonModelTrait;

class AdsModel extends Model
{
    use CommonModelTrait;

    private static $tableName = 'ads';
    private static $rules;
    private static $params;
    private static $user_id;

    public function __construct($params = '')
    {
        parent::__construct();
        self::$rules = [
            'title' => ['required', 'min_length(3)', 'max_length(20)'],
            'text' => ['required', 'min_length(5)', 'max_length(50)'],
            'link' => ['min_length(12)', 'max_length(250)'],
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
            ['type' => 'text', 'name' => 'Ad Title', 'key' => 'title', 'sql_type' => 'varchar(20)'],
            ['type' => 'text', 'name' => 'Ad Text', 'key' => 'text', 'sql_type' => 'varchar(50)'],
            ['type' => 'text', 'name' => 'Ad Link', 'key' => 'link', 'sql_type' => 'varchar(250)'],
            ['type' => '', 'name' => '', 'key' => 'image', 'sql_type' => 'varchar(200)'],
            ['type' => '', 'name' => '', 'key' => 'thumb', 'sql_type' => 'varchar(200)'],
            ['type' => '', 'name' => '', 'key' => 'position', 'sql_type' => 'int(11)'],
            ['type' => '', 'name' => '', 'key' => 'status', 'sql_type' => 'tinyint(2)'],
            ['type' => '', 'name' => '', 'key' => 'time', 'sql_type' => 'int(11)'],
            ['type' => '', 'name' => '', 'key' => 'view', 'sql_type' => 'int(11)'],
            ['type' => '', 'name' => '', 'key' => 'click', 'sql_type' => 'int(11)'],
            ['type' => '', 'name' => '', 'key' => 'user_id', 'sql_type' => 'int(11)'],
        ];
    }

    public static function add()
    {
        $return = ['errors' => null];
        $post_data = self::getPost();

        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $insert_data = $post_data;
            $insert_data['user_id'] = self::$user_id;
            $insert_data['time'] = time();

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
            self::$db->update(self::$tableName, $update_data, ['id' => $id]);

            $images = Slim::getImages('image');
            if ($images) {
                $image = $images[0];
                if (!empty($image)) {
                    self::imageUpload($image, $id);
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