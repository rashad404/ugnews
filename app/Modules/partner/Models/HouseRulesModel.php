<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Security;
use Helpers\FileUploader;
use Helpers\Session;
use Helpers\Url;
use Helpers\File;
use Helpers\Validator;
use Helpers\Slim;
use Helpers\SimpleImage;

class HouseRulesModel extends Model{

    private static $tableName = 'house_rules';

    private static $rules;
    private static $params;
    public static $partner_id;

    public function __construct($params){
        parent::__construct();
        self::$rules = [
            'title' => ['min_length(5)', 'max_length(200)'],
            'text' => ['min_length(20)', 'max_length(500000)'],
        ];
        self::$db->createTable(self::$tableName,self::getInputs());
        self::$params = $params;
        self::$partner_id = Session::get('user_session_id');
    }

    public static function naming(){
        return [];
    }


    /*
     *If type is empty, will not appear on the page
     *If sql_type is empty, will not create field on sql table
     */
    public static function getInputs(){
        $array[] = ['type'=>'text',         'name'=>'Title',       'key'=>'title',        'sql_type'=>'varchar(200)'];
        $array[] = ['type'=>'textarea',     'name'=>'Text',        'key'=>'text',         'sql_type'=>'text'];
        $array[] = ['type'=>'',             'name'=>'',            'key'=>'status',       'sql_type'=>'tinyint(1)'];
        $array[] = ['type'=>'',             'name'=>'',            'key'=>'partner_id',   'sql_type'=>'int(11)'];
        return $array;
    }

    public static function getSqlFields(){
        $input_list = self::getInputs();
        $field_array = ['`id`'];
        foreach ($input_list as $value){
            $field_array[] = '`'.$value['key'].'`';
        }
        $fields = implode(',', $field_array);
        return $fields;
    }

    protected static function getPost()
    {
        extract($_POST);
        $skip_list = ['csrf_token','image'];
        $array = [];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safeText($_POST[$key]);
        }
        return $array;
    }


    public static function getItem(){
        $id = self::$partner_id;
        return self::$db->selectOne("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `id`='".$id."'");
    }


    public static function update(){
        $id = self::$partner_id;
        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;

            $update_data = $post_data;
            $check = self::$db->selectOne("SELECT `id` FROM ".self::$tableName." WHERE  `partner_id`='".self::$partner_id."'");
            if($check) {
                self::$db->update(self::$tableName, $update_data, ['partner_id' => self::$partner_id]);
            }else{
                $insert_data = $post_data;
                $insert_data['partner_id'] = self::$partner_id;
                self::$db->insert(self::$tableName, $insert_data);
            }
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }

        return $return;
    }

}

?>