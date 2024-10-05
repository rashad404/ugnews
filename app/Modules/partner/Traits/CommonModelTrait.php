<?php

namespace Modules\partner\Traits;

use Helpers\Security;
use Helpers\Date;
use Helpers\Slim;
use Helpers\FileUploader;
use Helpers\File;
use Helpers\SimpleImage;
use Helpers\Url;

trait CommonModelTrait
{
    protected static function getPost()
    {
        $skip_list = ['csrf_token', 'image'];
        $array = [];

        foreach ($_POST as $key => $value) {
            if (in_array($key, $skip_list)) continue;
            if (Date::validateDate($_POST[$key])) {
                $array[$key] = strtotime($_POST[$key]);
            } else {
                $array[$key] = Security::safeText($_POST[$key]);
            }
        }
        return $array;
    }

    public static function getSqlFields()
    {
        $input_list = static::getInputs();
        $field_array = ['`id`'];
        foreach ($input_list as $value) {
            $field_array[] = '`' . $value['key'] . '`';
        }
        return implode(',', $field_array);
    }

    public static function getList($limit = 'LIMIT 0,10')
    {
        return self::$db->select("SELECT " . self::getSqlFields() . " FROM " . static::$tableName . " WHERE `partner_id`='" . static::$partner_id . "' ORDER BY `id` DESC $limit");
    }

    public static function countList()
    {
        $count = self::$db->selectOne("SELECT count(`id`) as countList FROM " . static::$tableName . " WHERE `partner_id`='" . static::$partner_id . "'");
        return $count['countList'];
    }

    public static function getItem($id)
    {
        return self::$db->selectOne("SELECT " . self::getSqlFields() . " FROM " . static::$tableName . " WHERE `id`='" . $id . "'");
    }

    public static function delete($id_array = [])
    {
        if (empty($id_array)) {
            $id_array = $_POST["row_check"];
        }
        $ids = Security::safe(implode(",", $id_array));
        self::$db->raw("DELETE FROM " . static::$tableName . " where `id` in (" . $ids . ")");
        foreach ($id_array as $id) {
            static::deleteImage($id);
        }
    }

    public static function statusToggle($id)
    {
        $query = self::$db->selectOne("SELECT `status` FROM " . static::$tableName . " WHERE `id`='" . $id . "'");
        $status = $query['status'] == 0 ? 1 : 0;
        self::$db->raw("UPDATE " . static::$tableName . " SET `status`='" . $status . "' WHERE `id` ='" . $id . "'");
    }

    public static function updatePosition($id)
    {
        $query = self::$db->selectOne("SELECT `position` FROM " . static::$tableName . " ORDER BY `position` DESC");
        $position = $query['position'] + 1;
        self::$db->raw("UPDATE " . static::$tableName . " SET `position`='" . $position . "' WHERE `id` ='" . $id . "'");
    }

    protected static function imageUpload($image, $id)
    {
        $params = static::$params;

        $new_dir = Url::uploadPath() . $params['name'] . '/' . $id;
        $new_thumb_dir = Url::uploadPath() . $params['name'] . '/' . $id . '/thumbs';
        $file_name = $id . '_0.jpg';

        File::makeDir($new_dir);
        File::makeDir($new_thumb_dir);

        $new = Slim::saveFile($image['output']['data'], $file_name, $new_dir, false);

        $destination = $new_thumb_dir . "/" . $file_name;

        try {
            $img = new SimpleImage();
            $img->load($new['path'])->resize($params['imageSizeX'], $params['imageSizeY'])->save($destination);
        } catch (\Exception $e) {
            // Handle exception
        }

        $sql_img = static::$tableName . '/' . $id . '/' . $file_name;
        $sql_thumb_img = static::$tableName . '/' . $id . '/thumbs/' . $file_name;
        self::$db->update(static::$tableName, ['image' => $sql_img, 'thumb' => $sql_thumb_img], ['id' => $id]);

        FileUploader::imageResizeProportional($new_dir . '/' . $file_name, $new_dir . '/' . $file_name, 90, $params['imageSizeX'], $params['imageSizeY']);
        FileUploader::imageResizeProportional($new_dir . '/' . $file_name, $new_thumb_dir . '/' . $file_name, 90, $params['thumbSizeX'], $params['thumbSizeY']);
    }

    public static function deleteImage($id)
    {
        if (is_dir(Url::uploadPath() . static::$params['name'] . '/' . $id)) {
            File::rmDir(Url::uploadPath() . static::$params['name'] . '/' . $id);
        }
        return true;
    }
}