<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Console;
use Helpers\Date;
use Helpers\Mail;
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

class WorkordersModel extends Model{

    private static $tableName = 'work_orders';
    private static $tableNameUsers = 'users';

    private static $rules;
    private static $rulesStatus;
    private static $partner_id;

    public static $params = [
        'name' => 'work_orders',
        'searchFields' => ['id','date','time','unit','note'],
        'title' => 'Work Orders',
        'position' => false,
        'status' => true,
        'actions' => true,
    ];
    private static $lng;

    public function __construct(){
        parent::__construct();
        self::$rules = [
            'date' => ['min_length(5)', 'max_length(20)'],
        ];
        self::$rulesStatus = [
            'status' => ['exact_length(1)', 'min(1)','max(4)'],
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
    public static function getInputs(){
        $array[] = ['type'=>'textarea',         'name'=>'What needs attention?',       'key'=>'text',        'sql_type'=>'text'];
        $array[] = ['type'=>'select2',         'name'=>'Is this issue urgent?',        'key'=>'urgent',         'sql_type'=>'tinyint(1)', 'data' => self::getUrgentOptions()];
        $array[] = ['type'=>'select2',         'name'=>'Is this issue actively causing property damage or a threat to personal safety?',      'key'=>'safety',       'sql_type'=>'tinyint(1)', 'data' => self::getUrgentOptions()];
        $array[] = ['type'=>'select2',         'name'=>'To resolve the issue as quickly as possible, do we have permission to enter the residence?',        'key'=>'permission',         'sql_type'=>'tinyint(1)', 'data' => self::getPermissionOptions()];
        $array[] = ['type'=>'select2',         'name'=>'Category',        'key'=>'category',         'sql_type'=>'int(5)', 'data' => self::getCategories()];
        $array[] = ['type'=>'select2',         'name'=>'Location',        'key'=>'location',         'sql_type'=>'int(5)', 'data' => self::getLocations()];
        $array[] = ['type'=>'date',         'name'=>'Date',        'key'=>'date',         'sql_type'=>'date'];
        $array[] = ['type'=>'',         'name'=>'',        'key'=>'date_completed',         'sql_type'=>'date'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'image',             'sql_type'=>'varchar(200)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'status',            'sql_type'=>'tinyint(1)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'position',          'sql_type'=>'int(3)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'apt_id',            'sql_type'=>'int(5)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'room_id',           'sql_type'=>'int(5)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'bed_id',           'sql_type'=>'int(5)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'user_id',           'sql_type'=>'int(9)'];
        return $array;
    }




    public static function countNew(){
        $count = self::$db->count("SELECT COUNT(`id`) FROM `".self::$tableName."` WHERE `partner_id`='".self::$partner_id."' AND `status`=0");
        return $count;
    }

    public static function getUrgentOptions($id=''){
        $list = [];
        $list[] = ['key'=>0, 'name'=>'Not selected', 'disabled'=>''];
        $list[] = ['key'=>1, 'name'=>'Yes', 'disabled'=>''];
        $list[] = ['key'=>2, 'name'=>'No', 'disabled'=>''];

        if($id==''){
            return $list;
        }else{
            return $list[$id]['name'];
        }
    }
    public static function getCategories($id=''){
        $list[0] = ['key'=>0, 'name'=>'Not selected', 'disabled'=>''];
        $list[1] = ['key'=>1, 'name'=>'Amenities', 'disabled'=>''];
        $list[2] = ['key'=>2, 'name'=>'Appliance', 'disabled'=>''];
        $list[3] = ['key'=>3, 'name'=>'Carpentry', 'disabled'=>''];
        $list[4] = ['key'=>4, 'name'=>'Common Area', 'disabled'=>''];
        $list[5] = ['key'=>5, 'name'=>'Electrical', 'disabled'=>''];
        $list[6] = ['key'=>6, 'name'=>'Flooring', 'disabled'=>''];
        $list[7] = ['key'=>7, 'name'=>'Furniture', 'disabled'=>''];
        $list[8] = ['key'=>8, 'name'=>'Garage/Carport', 'disabled'=>''];
        $list[9] = ['key'=>9, 'name'=>'Grounds', 'disabled'=>''];
        $list[10] = ['key'=>10, 'name'=>'HVAC', 'disabled'=>''];
        $list[11] = ['key'=>11, 'name'=>'Life Safety', 'disabled'=>''];
        $list[12] = ['key'=>12, 'name'=>'Locks and Keys', 'disabled'=>''];
        $list[13] = ['key'=>13, 'name'=>'Painting', 'disabled'=>''];
        $list[14] = ['key'=>14, 'name'=>'Pest Control', 'disabled'=>''];
        $list[15] = ['key'=>15, 'name'=>'Plumbing', 'disabled'=>''];
        $list[16] = ['key'=>16, 'name'=>'Roofing', 'disabled'=>''];
        $list[17] = ['key'=>17, 'name'=>'Windows', 'disabled'=>''];
        $list[17] = ['key'=>17, 'name'=>'Other', 'disabled'=>''];

        if($id==''){
            return $list;
        }else{
            return $list[$id]['name'];
        }
    }


    public static function getLocations($id=''){
        $list[0] = ['key'=>0, 'name'=>'Not selected', 'disabled'=>''];
        $list[1] = ['key'=>1, 'name'=>'Bedroom', 'disabled'=>''];
        $list[2] = ['key'=>2, 'name'=>'Kitchen', 'disabled'=>''];
        $list[3] = ['key'=>3, 'name'=>'Living Room', 'disabled'=>''];
        $list[4] = ['key'=>4, 'name'=>'Entrance', 'disabled'=>''];
        $list[5] = ['key'=>5, 'name'=>'Bathroom', 'disabled'=>''];
        $list[6] = ['key'=>6, 'name'=>'Pation/Balcony', 'disabled'=>''];
        $list[7] = ['key'=>7, 'name'=>'Staircase', 'disabled'=>''];
        $list[8] = ['key'=>8, 'name'=>'Closet', 'disabled'=>''];
        $list[9] = ['key'=>9, 'name'=>'Garage', 'disabled'=>''];
        $list[10] = ['key'=>10, 'name'=>'Other', 'disabled'=>''];

        if($id==''){
            return $list;
        }else{
            return $list[$id]['name'];
        }
    }

    public static function getStatus($id=''){
        $list[0] = ['key'=>0, 'name'=>'New', 'disabled'=>''];
        $list[1] = ['key'=>1, 'name'=>'Waiting', 'disabled'=>''];
        $list[2] = ['key'=>2, 'name'=>'Scheduled', 'disabled'=>''];
        $list[3] = ['key'=>3, 'name'=>'Canceled', 'disabled'=>''];
        $list[4] = ['key'=>4, 'name'=>'Completed', 'disabled'=>''];

        if($id==''){
            return $list;
        }else{
            return $list[$id]['name'];
        }
    }
    public static function getSafetyOptions($id=''){
        $list = [];
        $list[] = ['key'=>0, 'name'=>'Not selected', 'disabled'=>''];
        $list[] = ['key'=>1, 'name'=>'Yes', 'disabled'=>''];
        $list[] = ['key'=>2, 'name'=>'No', 'disabled'=>''];

        if($id==''){
            return $list;
        }else{
            return $list[$id]['name'];
        }
    }
    public static function getPermissionOptions($id=''){
        $list = [];
        $list[] = ['key'=>0, 'name'=>'Not selected', 'disabled'=>''];
        $list[] = ['key'=>1, 'name'=>'Yes', 'disabled'=>''];
        $list[] = ['key'=>2, 'name'=>'No', 'disabled'=>''];
        $list[] = ['key'=>3, 'name'=>'Entry not necessary', 'disabled'=>''];

        if($id==''){
            return $list;
        }else{
            return $list[$id]['name'];
        }
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
        $skip_list = ['csrf_token','image','submit'];
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

    public static function getList($limit='LIMIT 0,1000'){
        return self::$db->select("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `partner_id`='".self::$partner_id."' ORDER BY `id` DESC, `position` DESC $limit");
    }

    public static function getItem($id){
        return self::$db->selectOne("SELECT * FROM ".self::$tableName." WHERE `id`='".$id."'");
    }
    public static function getItemByUser($id){
        return self::$db->selectOne("SELECT * FROM ".self::$tableName." WHERE `user_id`='".$id."'");
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
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        return $return;
    }

    public static function updateStatus($id){
        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();

        $validator = Validator::validate($post_data, self::$rulesStatus, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;

            $update_data = $post_data;
            self::$db->update(self::$tableName, $update_data, ['id'=>$id]);

            $order_status = self::getStatus($post_data['status']);

            $item_info = self::$db->selectOne("SELECT `user_id` FROM ".self::$tableName." WHERE `id`='".$id."'");
            $user_id = $item_info['user_id'];

            $user_array = self::$db->selectOne("SELECT `first_name`, `last_name`,`email` FROM ".self::$tableNameUsers." WHERE `id`='".$user_id."'");
            $user_name = $user_array['first_name'].' '.$user_array['last_name'];
            $user_mail = $user_array['email'];


            //SEND EMAIL
            $title = 'Work order #'.$id.' status change';
            $text = '
            <div style="padding: 10px;font-size: 16px;">Dear <span style="font-weight: bold">'.$user_name.'</span>, </div>
            <div style="font-size: 16px;padding-left: 10px;line-height: 28px;">
                Your <span style="font-weight: bold">Work Order #'.$id.'</span> status changed to <span style="font-weight: bold">'.$order_status.'</span><br/>
                You can always log into your account on <a href="https://ureb.com/login/user+workorders+index">Tenant portal</a> to see your work orders.<br/>
                <div style="padding: 20px 0;">
                    <a style="padding: 10px 15px;background-color: #673194;color: #fff;font-weight: bold;text-decoration: none" href="https://ureb.com/login/user+workorders+index">View Work Orders</a>
                    </div>
            </div>
            ';

            Mail::sendMail(SITE_EMAIL_NO_REPLY, $user_mail, $title, $text);

            new EmailModel();
            EmailModel::insertLog(SITE_EMAIL_NO_REPLY, $user_mail, $title, $text, $user_id);


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
            self::$db->update(self::$tableName, $update_data, ['id'=>$id]);
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

    public static function getStateCode($id){
        $array = self::$db->selectOne("SELECT `state_code` FROM `us_states` WHERE `id`='".$id."'");
        return $array['state_code'];
    }

}

?>