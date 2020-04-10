<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Models\PartnerModel;

class SettingsModel extends Model
{
    private static $tableName = 'partner_settings';
    private static $partner_id;
    private static $rules;

    public function __construct(){
        parent::__construct();
        self::$partner_id = Session::get('user_session_id');

        self::$rules = [
            'country' => ['min_length(0)', 'max_length(2)'],
            'language' => ['min_length(0)', 'max_length(2)'],
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
        $check = self::$db->selectOne("SELECT * FROM " . self::$tableName . " WHERE `partner_id`='" .self::$partner_id . "'");
        if($check){
            return $check;
        }else{
            $array = [
                'country'=>'233',
                'language'=>'3',
                'partner_id'=>self::$partner_id,
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

            self::$db->update(self::$tableName, $update_data, ['partner_id'=>self::$partner_id]);
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }

        return $return;
    }
}

?>