<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Security;
use Helpers\FileUploader;
use Helpers\Url;
use Helpers\File;
use Helpers\Validator;
use Helpers\Slim;
use Helpers\SimpleImage;

class AppointmentsModel extends Model{

    private static $tableName = 'appointments';
    private static $tableNameBeds = 'apt_beds';

    private static $rules;
    private static $params;

    public function __construct($params){
        parent::__construct();
        self::$rules = [
            'first_name' => ['min_length(3)', 'max_length(15)'],
            'last_name' => ['min_length(3)', 'max_length(15)'],
        ];
        self::$db->createTable(self::$tableName,self::getInputs());
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
        $array[] = ['type'=>'text',         'name'=>'First name',       'key'=>'first_name',        'sql_type'=>'varchar(100)'];
        $array[] = ['type'=>'text',         'name'=>'Last name',        'key'=>'last_name',         'sql_type'=>'varchar(100)'];
        $array[] = ['type'=>'text',         'name'=>'Phone number',     'key'=>'phone',             'sql_type'=>'varchar(20)'];
        $array[] = ['type'=>'text',         'name'=>'E-mail address',   'key'=>'email',             'sql_type'=>'varchar(50)'];
        $array[] = ['type'=>'date',         'name'=>'Available Date',   'key'=>'app_time',           'sql_type'=>'int(11)'];
        $array[] = ['type'=>'date',         'name'=>'Move out Date',    'key'=>'move_time',          'sql_type'=>'int(11)'];
        $array[] = ['type'=>'',         'name'=>'',    'key'=>'position',          'sql_type'=>'int(11)'];
        $array[] = ['type'=>'',         'name'=>'',    'key'=>'status',          'sql_type'=>'tinyint(1)'];
        $array[] = ['type'=>'select_box',   'name'=>'Do you have a pet?','key'=>'pets',            'sql_type'=>'tinyint(1)', 'data' => [['key'=>'0','name'=>'No','disabled'=>''], ['key'=>'1','name'=>'Yes','disabled'=>'']]];
        $array[] = ['type'=>'textarea',   'name'=>'Note','key'=>'note',            'sql_type'=>'text'];
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
            $array[$key] = Security::safe($_POST[$key]);
        }
        return $array;
    }

    public static function getList(){
        return self::$db->select("SELECT ".self::getSqlFields()." FROM ".self::$tableName." ORDER BY `position` DESC,`id` ASC ");
    }

    public static function getItem($id){
        return self::$db->selectOne("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `id`='".$id."'");
    }

    public static function add(){
        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;

            $insert_data = $post_data;
            $insert_id = self::$db->insert(self::$tableName,$insert_data);
            self::updatePosition($insert_id);
            if($insert_id>0){
                $images = Slim::getImages('image');
                if($images){
                    $image = $images[0];
                    if(!empty($image)){
                        self::imageUpload($image, $insert_id);
                    }
                }
            }
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        return $return;
    }

    public static function update($id){
        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;

            $update_data = $post_data;
            if($post_data['bed_id']>0){
                self::$db->update(self::$tableNameBeds,['tenant_id'=>$id], ['id'=>$post_data['bed_id']]);
            }
            self::$db->update(self::$tableName,$update_data, ['id'=>$id]);
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
        $new_dir = Url::uploadPath().$params['name'].'/'.$id;
        $new_thumb_dir = Url::uploadPath().$params['name'].'/'.$id.'/thumbs';
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

    public static function search(){
        $postData = self::getPost();
        $text = $postData['search'];
        $values = self::$params['searchFields'];

        $sql_s = ''; // Stop errors when $words is empty
        if($values<=1){
            $sql_s = "`".$values."` LIKE '%".$text."%' ";
        } else {
            foreach($values as $value){
                $sql_s .= "`".$value."` LIKE '%".$text."%' OR ";
            }
            $sql_s = substr($sql_s,0,-3);
        }
        $list = self::$db->select("SELECT ".self::getSqlFields()." FROM `".self::$tableName."` WHERE ".$sql_s);
        return $list;
    }
    public static function delete($id_array = []){
        if(empty($id_array)) {
            $id_array = $_POST["row_check"];
        }
        $ids = Security::safe(implode(",", $id_array));
        self::$db->raw("DELETE FROM ".self::$tableName." where `id` in (".$ids.")");
        foreach ($id_array as $id){
            self::deleteImage($id);
        }
    }
    public static function deleteImage($id){
        if(is_dir(Url::uploadPath().self::$params['name'].'/'.$id)) {
            File::rmDir(Url::uploadPath().self::$params['name'].'/'.$id);
        }
        return true;
    }
    public static function statusToggle($id){
        $query = self::$db->selectOne("SELECT `status` FROM ".self::$tableName." WHERE `id`='".$id."'");
        $old_status = $query['status'];
        if($old_status==0){
            $status = 1;
        }else{
            $status = 0;
        }
        self::$db->raw("UPDATE ".self::$tableName." SET `status`='".$status."' WHERE `id` ='".$id."'");
    }
    public static function status($status){
        $row_check = $_POST["row_check"];
        $ids = Security::safe(implode(",",$row_check));
        self::$db->raw("UPDATE ".self::$tableName." SET `status`='".$status."' WHERE `id` in (".$ids.")");
    }
    public static function move($id, $type){
        $query = self::$db->selectOne("SELECT `position` FROM ".self::$tableName." WHERE `id`='".$id."'");
        $old_position = $query['position'];
        if($type=='up'){
            $position = $old_position+1;
        }else{
            $position = $old_position-1;
        }
        self::$db->raw("UPDATE ".self::$tableName." SET `position`='".$position."' WHERE `id` ='".$id."'");
    }
    public static function updatePosition($id){
        $query = self::$db->selectOne("SELECT `position` FROM ".self::$tableName." ORDER BY `position` DESC");
        $max_position = $query['position'];
        $position = $max_position+1;
        self::$db->raw("UPDATE ".self::$tableName." SET `position`='".$position."' WHERE `id` ='".$id."'");
    }

}

?>