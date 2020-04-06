<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Mail;
use Helpers\Security;
use Helpers\FileUploader;
use Helpers\Session;
use Helpers\Url;
use Helpers\File;
use Helpers\Validator;
use Helpers\Slim;
use Helpers\SimpleImage;

class BalanceModel extends Model{

    private static $tableName = 'users';
    private static $tableNameLogs = 'balance_logs';

    private static $rules;
    private static $params;
    private static $partner_id;

    public function __construct($params=''){
        parent::__construct();
        self::$rules = [
            'amount' => ['amount','positive','no_zero']
        ];
        self::$params = $params;
        self::$partner_id = Session::get('user_session_id');
    }

    public static function naming(){
        return [];
    }




    public static function getTenantList(){

        $list[] = ['key'=>0, 'name'=>'Not selected', 'disabled'=>''];

        $tenant_array = TenantsModel::getList('LIMIT 100000');
        foreach ($tenant_array as $tenant){
            $list[] = ['key'=>$tenant['id'], 'name'=>$tenant['first_name'].' '.$tenant['last_name'].' - '.$tenant['phone'], 'disabled'=>''];
        }
        return $list;
    }

    public static function getNoticeTemplateList(){

        $list[] = ['key'=>0, 'name'=>'Not selected', 'disabled'=>''];

        $array = NoticetemplatesModel::getList('LIMIT 1000');
        foreach ($array as $item){
            $list[] = ['key'=>$item['id'], 'name'=>'#'.$item['id'].' '.$item['title'], 'disabled'=>''];
        }
        return $list;
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



    public static function getLogs($limit='LIMIT 0,10'){
        return self::$db->select("SELECT a.*, b.`first_name`,b.`last_name` FROM 
        ".self::$tableNameLogs." as a INNER JOIN ".self::$tableName." as b ON a.`user_id`=b.`id` WHERE a.`partner_id`='".self::$partner_id."' ORDER BY `id` DESC $limit");
    }

    public static function getUserLogs($id, $limit='LIMIT 0,10'){
        return self::$db->select("SELECT * FROM ".self::$tableNameLogs." WHERE `user_id`='".$id."' AND `partner_id`='".self::$partner_id."' ORDER BY `id` DESC $limit");
    }
    public static function getPayments($limit='LIMIT 0,5'){
        return self::$db->select("SELECT a.`id`,a.`user_id`,a.`time`,a.`amount`,a.`action`,a.`description`, b.`first_name`,b.`last_name` 
FROM ".self::$tableNameLogs." as a INNER JOIN ".self::$tableName." as b ON a.`user_id`=b.`id`WHERE a.`partner_id`='".self::$partner_id."' AND (a.`action`='receipt' OR a.`action`='card') ORDER BY a.`id` DESC $limit");
    }


    public static function countList(){
        $count = self::$db->selectOne("SELECT count(`id`) as countList FROM ".self::$tableName);
        return $count['countList'];
    }
    public static function getPaymentsByDate($date){
        $strtime_start = strtotime($date);
        $strtime_end = strtotime($date)+86400;
        $count = self::$db->selectOne("SELECT SUM(`amount`) as c FROM ".self::$tableNameLogs." WHERE `partner_id`='".self::$partner_id."' AND `time`>'".$strtime_start."' AND `time`<'".$strtime_end."'");
        return $count['c'];
    }


    public static function convert_code_to_text($text, $tenant_id){
        if(preg_match('/#TENANT_NAME#/',$text)){
            $tenant_name = TenantsModel::getName($tenant_id);
            $text = preg_replace('/#TENANT_NAME#/',$tenant_name,$text);
        }
        return $text;
    }

    public static function add(){
        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;

            $notice_template = NoticetemplatesModel::getItem($post_data['notice_id']);
            $insert_data = $post_data;
            $insert_data['notice_title'] = $notice_template['title'];
            $insert_data['notice_text'] = self::convert_code_to_text($notice_template['text'], $post_data['tenant']);

            $insert_data['time'] = time();
            $insert_id = self::$db->insert(self::$tableName,$insert_data);
            if($insert_id>0){
                self::updatePosition($insert_id);

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

    public static function addCharge($id){
        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;
            $log_data = [
              'user_id'=>$id,
              'partner_id'=>self::$partner_id,
              'action'=> 'charge',
              'amount'=> $post_data['amount'],
              'description'=> $post_data['description'],
              'time'=> time(),
            ];
            self::$db->insert(self::$tableNameLogs, $log_data);
            self::$db->raw('UPDATE '.self::$tableName.' SET `balance`=`balance`+ '.$post_data['amount'].' WHERE `id`='.$id);
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        return $return;
    }
    public static function addCredit($id){
        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;
            $log_data = [
              'user_id'=>$id,
              'partner_id'=>self::$partner_id,
              'action'=> 'credit',
              'amount'=> '-'.$post_data['amount'],
              'description'=> $post_data['description'],
              'time'=> time(),
            ];
            self::$db->insert(self::$tableNameLogs, $log_data);
            self::$db->raw('UPDATE '.self::$tableName.' SET `balance`=`balance`- '.$post_data['amount'].' WHERE `id`='.$id);
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        return $return;
    }

    public static function addReceipt($id){
        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;
            $log_data = [
              'user_id'=>$id,
              'partner_id'=>self::$partner_id,
              'action'=> 'receipt',
              'amount'=> '-'.$post_data['amount'],
              'description'=> $post_data['description'],
              'time'=> time(),
            ];
            self::$db->insert(self::$tableNameLogs, $log_data);
            self::$db->raw('UPDATE '.self::$tableName.' SET `balance`=`balance`-'.$post_data['amount'].' WHERE `id`='.$id);
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



    public static function sendReceipt($log_id){
        $return = [];
        $return['errors'] = null;

        $array = self::$db->selectOne("SELECT * FROM ".self::$tableNameLogs." WHERE `id`='".$log_id."'");
        $user_id = $array['user_id'];
        $partner_id = $array['partner_id'];
        $log_time = $array['time'];
        $date = date('m/d/Y H:i', $log_time);
        $amount = abs($array['amount']);
        $amount = number_format($amount, 2, '.', '');
        $description = $array['description'];

        $user_array = self::$db->selectOne("SELECT `first_name`,`last_name`,`email`,`balance`,`apt_id`,`room_id`,`bed_id` FROM ".self::$tableName." WHERE `id`='".$user_id."'");
        $email = $user_array['email'];
        $balance = abs($user_array['balance']);
        $balance = number_format($balance, 2, '.', '');
        $user_name = $user_array['first_name'].' '.$user_array['last_name'];
        $apt_id = $user_array['apt_id'];
        $room_id = $user_array['room_id'];
        $bed_id = $user_array['bed_id'];

        $apt_address = $room_name = $bed_name = '';
        if($apt_id>0)$apt_address = ApartmentsModel::getAddress($user_array['apt_id']);
        if($room_id>0)$room_name = RoomsModel::getName($user_array['room_id']);
        if($bed_id>0)$bed_name = BedsModel::getName($user_array['bed_id']);

        $landlord_array = self::$db->selectOne("SELECT `first_name`,`last_name` FROM ".self::$tableName." WHERE `id`='".$partner_id."'");
        $landlord_name = $landlord_array['first_name'].' '.$landlord_array['last_name'];

        $title = $landlord_name.' Payment Receipt';


        $text = '
                <div style="padding: 10px;margin-bottom:20px;font-size: 16px;background-color: #673194;color:#fff;">Thank you for your payment.</div>
                <div style="font-size: 14px;">
                    <table>
                        <tr><td style="width:300px;padding:3px;">Payment Date:</td><td>'.$date.'</td></tr>
                        <tr><td style="width:300px;padding:3px;">Received From:</td><td>'.$user_name.'</td></tr>
                        <tr><td style="width:300px;padding:3px;">Payed To:</td><td>'.$landlord_name.'</td></tr>
                        <tr><td style="width:300px;padding:3px;">Apartment:</td><td>'.$apt_address.', '.$room_name.' '.$bed_name.'</td></tr>
                        <tr><td style="width:300px;padding:3px;">Amount:</td><td><span style="color:red;font-weight:bold;">'.DEFAULT_CURRENCY_SHORT.$amount.'</span></td></tr>
                        <tr><td style="width:300px;padding:3px;">Description:</td><td>'.$description.'</td></tr>
                        <tr><td style="width:300px;padding:3px;">Reference ID:</td><td>#'.self::getReferenceID($log_time, $log_id).'</td></tr>
                        <tr><td style="width:300px;padding:3px;">Balance due as of '.date('m/d/Y').':</td><td>'.DEFAULT_CURRENCY_SHORT.$balance.'</td></tr>
                    </table>
                </div>
                ';

        Mail::sendMail(SITE_EMAIL_NO_REPLY, $email, $title, $text);
        new EmailModel();
        EmailModel::insertLog(SITE_EMAIL_NO_REPLY, $email, $title, $text, $user_id);
        return $return;
    }

    public static function getReferenceID($time, $id){
        return 'R'.$time.$id;
    }
}

?>