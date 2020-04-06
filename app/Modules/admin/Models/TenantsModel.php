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

class TenantsModel extends Model{

    private static $tableName = 'tenants';
    private static $tableNameBeds = 'apt_beds';

    private static $rules;
    private static $params;

    public function __construct($params){
        parent::__construct();
        self::$rules = [
            'first_name' => ['min_length(2)', 'max_length(30)'],
            'last_name' => ['min_length(2)', 'max_length(30)'],
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
        $array[] = ['type'=>'text',         'name'=>'Middle name',      'key'=>'middle_name',       'sql_type'=>'varchar(100)'];
        $array[] = ['type'=>'text',         'name'=>'Phone number',     'key'=>'phone',             'sql_type'=>'varchar(20)'];
        $array[] = ['type'=>'text',         'name'=>'E-mail address',   'key'=>'email',             'sql_type'=>'varchar(50)'];
        $array[] = ['type'=>'date',         'name'=>'Birthday',         'key'=>'birthday',          'sql_type'=>'varchar(20)'];
        $array[] = ['type'=>'select2',      'name'=>'Gender',           'key'=>'gender',            'sql_type'=>'int(1)', 'data' => self::getGenderOptions()];
        $array[] = ['type'=>'date',         'name'=>'Move in Date',     'key'=>'move_in',           'sql_type'=>'varchar(20)'];
        $array[] = ['type'=>'date',         'name'=>'Notice Date',    'key'=>'notice_date',          'sql_type'=>'varchar(20)'];
        $array[] = ['type'=>'date',         'name'=>'Move out Date',    'key'=>'move_out',          'sql_type'=>'varchar(20)'];
        $array[] = ['type'=>'date',         'name'=>'Bed availability date',    'key'=>'available_date',          'sql_type'=>'varchar(20)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'image',             'sql_type'=>'varchar(200)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'thumb',             'sql_type'=>'varchar(200)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'status',            'sql_type'=>'tinyint(1)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'position',          'sql_type'=>'int(3)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'apt_id',            'sql_type'=>'int(5)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'room_id',           'sql_type'=>'int(5)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'balance',           'sql_type'=>'decimal(10,2)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'monthly_charges',           'sql_type'=>'decimal(10,2)'];
        $array[] = ['type'=>'select2',   'name'=>'Select a bed',     'key'=>'bed_id',            'sql_type'=>'int(5)', 'data' => self::getBedOptions()];
        $array[] = ['type'=>'select2',      'name'=>'Break Predicts',           'key'=>'break_predict',            'sql_type'=>'int(1)', 'data' => self::getBreakPredicts()];
        return $array;
    }

    public static function getGenderOptions(){
        $list = [];
        $list[] = ['key'=>0, 'name'=>'Not selected', 'disabled'=>''];
        $list[] = ['key'=>1, 'name'=>'Male', 'disabled'=>''];
        $list[] = ['key'=>2, 'name'=>'Female', 'disabled'=>''];

        return $list;
    }
    public static function getBreakPredicts(){
        $list = [];
        $list[] = ['key'=>0, 'name'=>'Not selected', 'disabled'=>''];
        $list[] = ['key'=>1, 'name'=>'Tenant wants', 'disabled'=>''];
        $list[] = ['key'=>2, 'name'=>'Payment', 'disabled'=>''];
        $list[] = ['key'=>3, 'name'=>'Cleaning', 'disabled'=>''];
        $list[] = ['key'=>4, 'name'=>'Attitude', 'disabled'=>''];
        $list[] = ['key'=>5, 'name'=>'Overnight guest', 'disabled'=>''];
        $list[] = ['key'=>6, 'name'=>'More reasons', 'disabled'=>''];

        return $list;
    }

    public static function getBedOptions(){
        $list = [];
        $list[] = ['key'=>0, 'name'=>'Not tenant', 'disabled'=>''];
        $apt_array = ApartmentsModel::getList();
        foreach ($apt_array as $apt){
            $room_array = RoomsModel::getList($apt['id']);
            foreach ($room_array as $room){
                $name = $apt['name'].' - '.$apt['address'];
                $list[] = ['key'=>'', 'name'=>$name, 'disabled'=>'disabled'];
                $bed_array = BedsModel::getListByRoom($room['id']);
                foreach ($bed_array as $bed){
                    $list[] = ['key'=>$bed['id'], 'name'=>$name.', '.$room['name'].' '.$bed['name'], 'disabled'=>''];
                }
            }
        }
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


    public static function add(){
        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;

            $insert_data = $post_data;

            $bed_array = BedsModel::getItem($post_data['bed_id']);
            $insert_data['apt_id'] = intval($bed_array['apt_id']);
            $insert_data['room_id'] = intval($bed_array['room_id']);

            $insert_id = self::$db->insert(self::$tableName,$insert_data);
            if($insert_id>0){
                self::updatePosition($insert_id);
                if($post_data['bed_id']>0){
                    self::$db->update(self::$tableNameBeds,['tenant_id'=>$insert_id], ['id'=>$post_data['bed_id']]);
                    if($post_data['available_date']>0) {
                        self::$db->update(self::$tableNameBeds, ['available_date' => $post_data['available_date']], ['id' => $post_data['bed_id']]);
                    }
                }

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
                $bed_array = BedsModel::getItem($post_data['bed_id']);
                $update_data['apt_id'] = $bed_array['apt_id'];
                $update_data['room_id'] = $bed_array['room_id'];
                self::$db->update(self::$tableNameBeds,['tenant_id'=>$id], ['id'=>$post_data['bed_id']]);
                if($post_data['available_date']>0) {
                    self::$db->update(self::$tableNameBeds, ['available_date' => $post_data['available_date']], ['id' => $post_data['bed_id']]);
                }
            }else{
                $update_data['apt_id'] = 0;
                $update_data['room_id'] = 0;
                self::$db->update(self::$tableNameBeds,['tenant_id'=>0], ['tenant_id'=>$id]);
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