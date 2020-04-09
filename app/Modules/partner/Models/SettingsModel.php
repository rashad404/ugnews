<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;

class SettingsModel extends Model
{
    private static $tableName = 'user_settings';
    private static $user_id;
    private static $partner_id;
    private static $rules;

    public function __construct(){
        parent::__construct();
        self::$user_id = Session::get('user_session_id');
        $user_info = UserModel::getItem(self::$user_id);
        self::$partner_id = $user_info['partner_id'];

        self::$rules = [
            'first_name_share' => ['min(0)', 'max(2)'],
            'photo_share' => ['min(0)', 'max(2)'],
            'age_share' => ['min(0)', 'max(2)'],
            'score_share' => ['min(0)', 'max(2)'],
        ];
    }

    public static function naming(){
        return [];
    }


    protected static function getPost()
    {
        extract($_POST);
        $skip_list = ['csrf_token','image'];
        $array = [];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }
        return $array;
    }

    public static function getItem(){
        $check = self::$db->selectOne("SELECT * FROM " . self::$tableName . " WHERE `user_id`='" .self::$user_id . "'");
        if($check){
            return $check;
        }else{
            $array = [
                'first_name_share'=>2,
                'photo_share'=>2,
                'age_share'=>2,
                'score_share'=>2,
                'user_id'=>self::$user_id,
            ];
            self::$db->insert(self::$tableName,$array);
            return $array;
        }
    }


    public static function update(){
        $return = [];
        $return['errors'] = null;

        $post_data = self::getPost();

        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;
            $update_data = $post_data;

            self::$db->update(self::$tableName, $update_data, ['user_id'=>self::$user_id]);
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }

        return $return;
    }
}

?>