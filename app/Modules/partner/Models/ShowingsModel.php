<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Console;
use Helpers\Date;
use Helpers\Security;
use Helpers\FileUploader;
use Helpers\Session;
use Helpers\Url;
use Helpers\File;
use Helpers\Validator;
use Helpers\Slim;
use Helpers\SimpleImage;
use \DateTime;
use Core\Language;
use Modules\partner\Models\CustomersModel;

class ShowingsModel extends Model{

    private static $tableName = 'showings';
    private static $tableNameCustomers = 'customers';
    private static $tableNameSources = 'ad_sources';

    private static $rules;
    private static $partner_id;
    public static $params = [
        'name' => 'showings',
        'searchFields' => ['id','date','time','unit','note'],
        'title' => 'Showings',
        'position' => false,
        'status' => true,
        'actions' => true,
    ];
    private static $lng;

    public function __construct(){
        parent::__construct();
        self::$rules = [
//            'guest_id' => ['min_length(1)', 'max_length(20)'],
//            'tenant_id' => ['min_length(1)', 'max_length(20)'],
            'date' => ['min_length(5)', 'max_length(20)'],
//            'time' => ['min_length(4)', 'max_length(20)'],
        ];
        self::$lng = new Language();
        self::$lng->load('partner');
        self::$db->createTable(self::$tableName,self::getInputs());
        self::$partner_id = Session::get('user_session_id');
    }

    public static function naming(){
        return [];
    }


    /*
     *If type is empty, will not appear on the page
     *If sql_type is empty, will not create field on sql table
     *If index is false, will not show field on index page
     */
    public static function getInputs($method=''){
        if($method != 'update') {
            $array[] = ['type' => 'text', 'name' => 'First name', 'index' => false, 'key' => 'first_name', 'sql_type' => ''];
            $array[] = ['type' => 'text', 'name' => 'Last name', 'index' => false, 'key' => 'last_name', 'sql_type' => ''];
            $array[] = ['type' => 'text', 'name' => 'Phone', 'index' => false, 'key' => 'phone', 'sql_type' => ''];
            $array[] = ['type' => 'select2', 'name' => 'Gender', 'index' => false, 'key' => 'gender', 'sql_type' => '', 'data'=>self::getGenderOptions()];
        }
        $array[] = ['type'=>'select2',   'name'=>'Guest Card', 'index'=>true,     'key'=>'guest_id',            'sql_type'=>'int(5)', 'data'=>self::getGuests()];
        $array[] = ['type'=>'select2',   'name'=>'Tenant', 'index'=>false,     'key'=>'user_id',            'sql_type'=>'int(5)', 'data'=>self::getTenants()];
        $array[] = ['type'=>'datetime', 'name'=>self::$lng->get('Date'), 'index'=>true, 'key'=>'date', 'sql_type'=>'varchar(20)'];
//        $array[] = ['type'=>'date', 'name'=>self::$lng->get('Desire move in Date'), 'index'=>true, 'key'=>'movein_date', 'sql_type'=>'varchar(20)'];
        $array[] = ['type'=>'select2', 'name'=>self::$lng->get('Location'), 'index'=>true, 'key'=>'apt_id', 'sql_type'=>'int(11)','data'=>self::getApartments()];
        $array[] = ['type'=>'select2', 'name'=>self::$lng->get('Type'), 'index'=>true, 'key'=>'type', 'sql_type'=>'int(11)','data'=>self::getTypes()];
        $array[] = ['type'=>'textarea', 'name'=>self::$lng->get('Note'), 'index'=>false, 'key'=>'note', 'sql_type'=>'text'];
        $array[] = ['type'=>'', 'name'=>'', 'index'=>false, 'key'=>'position', 'sql_type'=>'int(3)'];
        $array[] = ['type'=>'', 'name'=>'', 'index'=>false, 'key'=>'status', 'sql_type'=>'tinyint(1)'];
        return $array;
    }

    public static function getGenderOptions(){
        $list = [];
        $list[] = ['key'=>0, 'name'=>'Not selected', 'disabled'=>''];
        $list[] = ['key'=>1, 'name'=>'Male', 'disabled'=>''];
        $list[] = ['key'=>2, 'name'=>'Female', 'disabled'=>''];

        return $list;
    }

    public static function getGuests(){
        new CustomersModel();
        $list = CustomersModel::getList();
        $data[] = ['key'=>'','name'=>'---','disabled'=>''];
        foreach ($list as $item){
            $data[] = ['key'=>$item['id'], 'name'=>$item['first_name'].' '.$item['last_name'].' ['.$item['phone'].']','disabled'=>''];
        }
        return $data;
    }

    public static function getTimes($time=''){

        $data[] = ['key'=>'','name'=>'---','disabled'=>''];
        $data[] = ['key'=>'8:00','name'=>'8:00 AM','disabled'=>''];
        $data[] = ['key'=>'8:30','name'=>'8:30 AM','disabled'=>''];
        $data[] = ['key'=>'9:00','name'=>'9:00 AM','disabled'=>''];
        $data[] = ['key'=>'9:30','name'=>'9:30 AM','disabled'=>''];
        $data[] = ['key'=>'10:30','name'=>'10:30 AM','disabled'=>''];
        $data[] = ['key'=>'11:00','name'=>'11:00 AM','disabled'=>''];
        $data[] = ['key'=>'11:30','name'=>'11:30 AM','disabled'=>''];
        $data[] = ['key'=>'12:00','name'=>'12:00 PM','disabled'=>''];
        $data[] = ['key'=>'12:30','name'=>'12:30 PM','disabled'=>''];
        $data[] = ['key'=>'13:00','name'=>'1:00 PM','disabled'=>''];
        $data[] = ['key'=>'13:30','name'=>'1:30 PM','disabled'=>''];
        $data[] = ['key'=>'14:00','name'=>'2:00 PM','disabled'=>''];
        $data[] = ['key'=>'14:30','name'=>'2:30 PM','disabled'=>''];
        $data[] = ['key'=>'15:00','name'=>'3:00 PM','disabled'=>''];
        $data[] = ['key'=>'15:30','name'=>'3:30 PM','disabled'=>''];
        $data[] = ['key'=>'16:00','name'=>'4:00 PM','disabled'=>''];
        $data[] = ['key'=>'16:30','name'=>'4:30 PM','disabled'=>''];
        $data[] = ['key'=>'17:00','name'=>'5:00 PM','disabled'=>''];
        $data[] = ['key'=>'17:30','name'=>'5:30 PM','disabled'=>''];
        $data[] = ['key'=>'18:00','name'=>'6:00 PM','disabled'=>''];
        $data[] = ['key'=>'18:30','name'=>'6:30 PM','disabled'=>''];
        $data[] = ['key'=>'19:00','name'=>'7:00 PM','disabled'=>''];
        $data[] = ['key'=>'19:30','name'=>'7:30 PM','disabled'=>''];
        $data[] = ['key'=>'20:00','name'=>'8:00 PM','disabled'=>''];

        return $data;
    }
    public static function getLocations(){
        new ApartmentsModel();
        $list = ApartmentsModel::getLocations();
        $data[] = ['key'=>'','name'=>'---','disabled'=>''];
        foreach ($list as $item){
            $data[] = ['key'=>$item['id'], 'name'=>$item['name'],'disabled'=>''];
        }
        return $data;
    }

    public static function getApartments(){
        new ApartmentsModel();
        $list = ApartmentsModel::getList();
        $data[] = ['key'=>'','name'=>'---','disabled'=>''];
        $data[] = ['key'=>'1','name'=>'Leasing Office','disabled'=>''];
        foreach ($list as $item){
            $data[] = ['key'=>$item['id'], 'name'=>$item['name'].' ['.$item['address'].']','disabled'=>''];
        }
        return $data;
    }

    public static function getTenants(){
        new TenantsModel();
        $list = TenantsModel::getList('LIMIT 0,10000');
        $data[] = ['key'=>'','name'=>'---','disabled'=>''];
        foreach ($list as $item){
            $data[] = ['key'=>$item['id'], 'name'=>$item['first_name'].' '.$item['last_name'].' ['.$item['phone'].']','disabled'=>''];
        }
        return $data;
    }

    public static function getTypes($type=''){
        $data[0] = ['key'=>'','name'=>'---','disabled'=>''];
        $data[1] = ['key'=>'1','name'=>'Showing','disabled'=>''];
        $data[2] = ['key'=>'2','name'=>'Appointment','disabled'=>''];
        $data[3] = ['key'=>'3','name'=>'Move-In','disabled'=>''];
        $data[4] = ['key'=>'4','name'=>'Move-Out','disabled'=>''];


        if(strlen($type)>0){
            return $data[$type]['name'];
        }else {
            return $data;
        }
    }

    public static function getAptModels(){
        new ApartmentsModel();
        $list = ApartmentsModel::getModels();
        $data[] = ['key'=>'','name'=>'---','disabled'=>''];
        foreach ($list as $item){
            $data[] = ['key'=>$item['id'], 'name'=>$item['name'],'disabled'=>''];
        }
        return $data;
    }

    public static function getAptRoomTypes(){
        new ApartmentsModel();
        $list = ApartmentsModel::getRoomTypes();
        $data[] = ['key'=>'','name'=>'---','disabled'=>''];
        foreach ($list as $item){
            $data[] = ['key'=>$item['id'], 'name'=>$item['name'],'disabled'=>''];
        }
        return $data;
    }

    public static function getSources(){
        new ApartmentsModel();
        $list = self::$db->select("SELECT `id`,`name` FROM ".self::$tableNameSources." ORDER BY `position` DESC,`id` ASC ");
        $data[] = ['key'=>'','name'=>'---','disabled'=>''];
        foreach ($list as $item){
            $data[] = ['key'=>$item['id'], 'name'=>$item['name'],'disabled'=>''];
        }
        return $data;
    }
    public static function getSourceName($id){
        $query = self::$db->selectOne("SELECT `name` FROM ".self::$tableNameSources." WHERE `id`='".$id."'");
        return $query['name'];
    }

    public static function getSqlFields(){
        $input_list = self::getInputs();
        $field_array = ['`id`'];
        $skip_list = ['first_name','last_name','phone', 'gender'];
        foreach ($input_list as $value){
            if (in_array($value['key'], $skip_list)) continue;
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
//        Console::varDump($_POST);
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            if(Date::validateDate($_POST[$key])){
                $array[$key] = strtotime($_POST[$key]);
            }else {
                $array[$key] = Security::safe($_POST[$key]);
            }
        }
        return $array;
    }

    public static function getList(){
        return self::$db->select("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `partner_id`= '".self::$partner_id."' ORDER BY `id` DESC, `position` DESC");
    }

    public static function getItem($id){
        return self::$db->selectOne("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `id`='".$id."' AND `partner_id`= '".self::$partner_id."'");
    }

    public static function add(){
        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;

            $insert_data = $post_data;
            $insert_data['date'] = strtotime($post_data['date']);

            $date = new DateTime($post_data['date']);
            $insert_data['date'] = $date->format('Y-m-d H:i:s');
            $insert_data['partner_id'] = self::$partner_id;


            if(!empty($post_data['first_name'])){
                $mysql_data = [
                    'first_name'=> $post_data['first_name'],
                    'last_name'=> $post_data['last_name'],
                    'gender'=> $post_data['gender'],
                    'phone'=> $post_data['phone'],
                    'partner_id'=> self::$partner_id,
                ];
                $insert_data['guest_id'] = self::$db->insert(self::$tableNameCustomers, $mysql_data);
            }
            $remove_keys = ['first_name', 'last_name', 'phone', 'gender'];
            $mysql_data = array_diff_key($insert_data,array_flip($remove_keys));

            $insert_id = self::$db->insert(self::$tableName, $mysql_data);
            self::updatePosition($insert_id);
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        return $return;
    }

    public static function update($id){
        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();

//        Console::varDump($post_data);
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;

            $update_data = $post_data;

            $date = new DateTime($post_data['date']);
            $update_data['date'] = $date->format('Y-m-d H:i:s');
            self::$db->update(self::$tableName, $update_data, ['id'=>$id, 'partner_id'=> self::$partner_id]);
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
        if(!empty($sql_s)){
            $sql_s .= " AND `partner_id`= '".self::$partner_id."'";
        }else{
            $sql_s .= " `partner_id`= '".self::$partner_id."'";
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