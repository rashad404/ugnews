<?php

namespace Modules\user\Models;

use Core\Model;
use Helpers\Security;
use Helpers\FileUploader;
use Helpers\Url;
use Helpers\File;
use Helpers\Validator;
use Helpers\Slim;
use Helpers\SimpleImage;

class ProfileModel extends Model{

    private static $tableName = 'tenants';

    private static $rules;
    private static $params;

    public function __construct($params){
        parent::__construct();
        self::$rules = [
//            'first_name' => ['min_length(2)', 'max_length(30)'],
//            'last_name' => ['min_length(2)', 'max_length(30)'],
        ];
        self::$params = $params;
    }

    public static function naming(){
        return [];
    }


    /*
     *If type is empty, will not appear on the page
     *If sql_type is empty, will not create field on sql table
     */
    public static function getInputs(){
        $array[] = ['type'=>'text',         'name'=>'First name',       'key'=>'first_name',        'sql_type'=>'varchar(100)', 'data'=>'', 'readonly'=>true];
        $array[] = ['type'=>'text',         'name'=>'Last name',        'key'=>'last_name',         'sql_type'=>'varchar(100)', 'data'=>'', 'readonly'=>true];
        $array[] = ['type'=>'text',         'name'=>'Middle name',      'key'=>'middle_name',       'sql_type'=>'varchar(100)', 'data'=>'', 'readonly'=>true];
        $array[] = ['type'=>'text',         'name'=>'Phone number',     'key'=>'phone',             'sql_type'=>'varchar(20)', 'data'=>'', 'readonly'=>true];
        $array[] = ['type'=>'text',         'name'=>'E-mail address',   'key'=>'email',             'sql_type'=>'varchar(50)', 'data'=>'', 'readonly'=>true];
        $array[] = ['type'=>'date',         'name'=>'Birthday',         'key'=>'birthday',          'sql_type'=>'date()', 'data'=>'', 'readonly'=>true];
        $array[] = ['type'=>'select2',      'name'=>'Gender',           'key'=>'gender',            'sql_type'=>'int(1)', 'data' => self::getGenderOptions(), 'readonly'=>true];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'image',             'sql_type'=>'varchar(200)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'thumb',             'sql_type'=>'varchar(200)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'status',            'sql_type'=>'tinyint(1)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'position',          'sql_type'=>'int(3)'];
        return $array;
    }

    public static function getGenderOptions(){
        $list = [];
        $list[] = ['key'=>0, 'name'=>'Not selected', 'disabled'=>''];
        $list[] = ['key'=>1, 'name'=>'Male', 'disabled'=>''];
        $list[] = ['key'=>2, 'name'=>'Female', 'disabled'=>''];

        return $list;
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

    protected static function getPost(){
        extract($_POST);
        $skip_list[] = 'csrf_token';
        $skip_list[] = 'image';

        $inputs = self::getInputs();
        foreach ($inputs as $input_data){
            if(key_exists('readonly', $input_data)) $skip_list[] = $input_data['key'];
        }

        $array = [];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }
        return $array;
    }


    public static function getName($id){
        $array = self::$db->selectOne("SELECT `first_name`,`last_name` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $array['first_name'].' '.$array['last_name'];
    }
    public static function getGenderName($id){
        $array = self::$db->selectOne("SELECT `gender` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $array['gender'];
    }
    public static function getBreakPredict($id){
        $array = self::$db->selectOne("SELECT `break_predict` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $array['break_predict'];
    }

    public static function getList($limit='LIMIT 0,10'){
        return self::$db->select("SELECT ".self::getSqlFields()." FROM ".self::$tableName." ORDER BY `position` DESC,`id` ASC $limit");
    }
    public static function getBreakPredictList(){
        return self::$db->select("SELECT ".self::getSqlFields().",`apt_id` FROM ".self::$tableName." WHERE `break_predict`>0 ORDER BY `position` DESC,`id` ASC");
    }

    public static function countList(){
        $count = self::$db->selectOne("SELECT count(`id`) as countList FROM ".self::$tableName);
        return $count['countList'];
    }

    public static function getListByApt($id){
        return self::$db->select("SELECT a.`id`,a.`first_name`,a.`last_name`,b.`name_".self::$def_language."` as `bed_name` FROM ".self::$tableName." as a
        INNER JOIN ".self::$tableNameBeds." as b ON a.`bed_id`=b.`id` 
        WHERE a.`apt_id`='".$id."' ORDER BY b.`position` DESC, b.`id` ASC ");
    }

    public static function getItem($id){
        return self::$db->selectOne("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `id`='".$id."'");
    }

    public static function update($id){
        $return = [];
        $return['errors'] = null;

        $post_data = self::getPost();

        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;
            $update_data = $post_data;

            self::$db->update(self::$tableName, $update_data, ['id'=>$id]);

            $images = Slim::getImages('image');
            if($images){
                $image = $images[0];
                if(!empty($image)){
                    self::imageUpload($image, $id);
                }
            }
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }

        return $return;
    }

    protected static function imageUpload($image, $id)
    {
        $params = self::$params;
        $new_dir = Url::uploadPath().self::$tableName.'/'.$id;
        $new_thumb_dir = Url::uploadPath().self::$tableName.'/'.$id.'/thumbs';
        $file_name = $id.'_0.jpg';

        File::makeDir($new_dir);
        File::makeDir($new_thumb_dir);

        $new = Slim::saveFile($image['output']['data'], $file_name, $new_dir, false);

        $destination = $new_thumb_dir."/" . $file_name;

        try {
            $img = new SimpleImage();
            $img->load($new['path'])->resize($params['imageSizeX'], $params['imageSizeY'])->save($destination);
        } catch (\Exception $e) {
        }

        $sql_img = self::$tableName.'/'.$id.'/' . $file_name;
        $sql_thumb_img = self::$tableName.'/'.$id.'/thumbs/' . $file_name;
        self::$db->update(self::$tableName, ['image' => $sql_img, 'thumb' => $sql_thumb_img], ['id' => $id]);

        //Optimize images
        FileUploader::imageResizeProportional($new_dir.'/'.$file_name, $new_dir.'/'.$file_name, 80, $params['imageSizeX'], $params['imageSizeY']);
        FileUploader::imageResize($new_thumb_dir.'/'.$file_name, $new_thumb_dir.'/'.$file_name, 80, $params['imageSizeX'], $params['imageSizeY']);
    }


}

?>