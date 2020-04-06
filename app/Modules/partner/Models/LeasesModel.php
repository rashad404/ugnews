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

class LeasesModel extends Model{

    private static $tableName = 'leases';
    private static $tableNamePages = 'lease_pages';
    private static $tableNameTemplates = 'lease_templates';
    private static $tableNameUsers = 'users';
    private static $tableNameEmailLogs = 'email_logs';

    private static $rules;
    private static $rulesUpdate;
    private static $params;
    private static $partner_id;

    public function __construct($params=''){
        parent::__construct();
        self::$rules = [
            'first_name' => ['min_length(2)', 'max_length(30)'],
            'last_name' => ['min_length(2)', 'max_length(30)'],
        ];

        self::$rulesUpdate = [
            'rent' => ['amount'],
            'deposit' => ['amount'],
            'app_fee' => ['amount'],
            'prorated_rent' => ['amount'],
            'bed_id' => ['integer'],
            'start_date' => ['min_length(2)', 'max_length(30)'],
            'end_date' => ['min_length(2)', 'max_length(30)'],
        ];
        self::$params = $params;
        self::$partner_id = Session::get('user_session_id');
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



    public static function send($lease_id){
        $return = [];
        $return['errors'] = null;

        $array = self::$db->selectOne("SELECT `user_id` FROM ".self::$tableName." WHERE `id`='".$lease_id."' AND `partner_id`='".self::$partner_id."'");
        if(!$array){
            $return['errors'] = 'Lease not found';
            return $return;
        }

        $user_id = $array['user_id'];

        $user_array = self::$db->selectOne("SELECT `first_name`,`last_name`,`email` FROM ".self::$tableNameUsers." WHERE `id`='".$user_id."'");
        $email = $user_array['email'];
        $user_name = $user_array['first_name'].' '.$user_array['last_name'];

        $landlord_array = self::$db->selectOne("SELECT `first_name`,`last_name` FROM ".self::$tableNameUsers." WHERE `id`='".self::$partner_id."'");
        $landlord_name = $landlord_array['first_name'].' '.$landlord_array['last_name'];

        $title = 'Your Lease with "'.$landlord_name.'" is ready for E-Sign';


        $text = '
        <div style="padding: 10px;font-size: 16px;">Dear <span style="font-weight: bold">'.$user_name.'</span>, </div>
        <div style="font-size: 16px;padding-left: 10px;line-height: 28px;">
            Your <span style="font-weight: bold">Lease</span> with "'.$landlord_name.'" is ready for E-Sign<br/>
            You can now log into your account on <a href="https://ureb.com/login/user+leases+index">Tenant portal</a> to access your lease and sign it electronically.<br/>
            Please review the Lease Agreement and sign it online.<br/>
            After signing, you can print copies for your own records.
            <div style="padding: 20px 0;">
                <a style="padding: 10px 15px;background-color: #673194;color: #fff;font-weight: bold;text-decoration: none" href="https://ureb.com/login/user+leases+index">Sign Lease Now</a>
                </div>
        </div>
        ';

        Mail::sendMail(SITE_EMAIL_NO_REPLY, $email, $title, $text);

        new EmailModel();
        EmailModel::insertLog(SITE_EMAIL_NO_REPLY, $email, $title, $text, $user_id);
        return $return;
    }


    public static function getName($id){
        $array = self::$db->selectOne("SELECT `first_name`,`last_name` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $array['first_name'].' '.$array['last_name'];
    }

    public static function getList($limit='LIMIT 0,10'){
        return self::$db->select("SELECT * FROM ".self::$tableName." WHERE `partner_id`='".self::$partner_id."' ORDER BY `id` DESC $limit");
    }

    public static function getPages($lease_id){
        return self::$db->select("SELECT * FROM ".self::$tableNamePages." WHERE `lease_id`='".$lease_id."' ORDER BY `id` ASC");
    }

    public static function countList(){
        $count = self::$db->selectOne("SELECT count(`id`) as countList FROM ".self::$tableName." WHERE `partner_id`='".self::$partner_id."'");
        return $count['countList'];
    }

    public static function getListByApt($id){
        return self::$db->select("SELECT a.`id`,a.`first_name`,a.`last_name`,b.`name_".self::$def_language."` as `bed_name` FROM ".self::$tableName." as a
        INNER JOIN ".self::$tableNameBeds." as b ON a.`bed_id`=b.`id` 
        WHERE a.`apt_id`='".$id."' ORDER BY b.`position` DESC, b.`id` ASC ");
    }

    public static function getItem($id){
        return self::$db->selectOne("SELECT * FROM ".self::$tableName." WHERE `id`='".$id."'");
    }
    public static function getPage($lease_id, $id){
        if($id==0){
            return self::$db->selectOne("SELECT `id`,`title`,`text` FROM ".self::$tableNamePages." WHERE `lease_id`='".$lease_id."' ORDER BY `id` ASC");
        }else{
            return self::$db->selectOne("SELECT `id`,`title`,`text` FROM ".self::$tableNamePages." WHERE `id`='".$id."'");
        }
    }


    public static function getInitials($lease_id){
        $lease_id = intval($lease_id);
        $array = self::$db->selectOne("SELECT `id`,`user_first_name`,`user_last_name` FROM ".self::$tableName." WHERE `partner_id`='" . self::$partner_id . "' AND `id`=".$lease_id);
        $first_name = $array['user_first_name'];
        $last_name = $array['user_last_name'];
        $initial1 = substr($first_name, 0, 1);
        $initial2 = substr($last_name, 0, 1);
        return $initial1.' '.$initial2;
    }

    public static function getSign($lease_id){
        $lease_id = intval($lease_id);
        $array = self::$db->selectOne("SELECT `id`,`user_first_name`,`user_last_name` FROM ".self::$tableName." WHERE `partner_id`='" . self::$partner_id . "' AND `id`=".$lease_id);
        $first_name = $array['user_first_name'];
        $last_name = $array['user_last_name'];
        return $first_name.' '.$last_name;
    }


    public static function getNextPage($user_id, $page_id){
        $page_id = intval($page_id);
        $array = self::$db->selectOne("SELECT `id` FROM ".self::$tableNamePages." WHERE `user_id`='" .$user_id . "' AND `partner_id`='" . self::$partner_id . "' AND `id`>".$page_id);
        return $array['id'];
    }

    public static function getPreviousPage($user_id, $page_id){
        $page_id = intval($page_id);
        $array = self::$db->selectOne("SELECT `id` FROM ".self::$tableNamePages." WHERE `user_id`='" .$user_id. "' AND `partner_id`='" . self::$partner_id . "' AND `id`<".$page_id);
        return $array['id'];
    }

    public static function getPass($id){
        $query = self::$db->selectOne("SELECT `password_hash` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $query['password_hash'];
    }

    public static function replaceVariables($text, $lease_id){
        $lease_info = self::getItem($lease_id);
        $bed_info = BedsModel::getItem($lease_info['bed_id']);

        $bed_name = BedsModel::getName($bed_info['id']);

        $apt_id = $bed_info['apt_id'];
        $apt_name = ApartmentsModel::getAddress($apt_id);

        $room_id = $bed_info['room_id'];
        $room_name = RoomsModel::getName($room_id);

        $apt_address = $apt_name.', '.$room_name.' '.$bed_name;

        $user_name = $lease_info['user_first_name'].' '.$lease_info['user_middle_name'].' '.$lease_info['user_last_name'];

        $rent_amount = $lease_info['rent'];
        $prorated_rent = $lease_info['prorated_rent'];

        $text = preg_replace('/\$tenant_name/', '<span style="font-weight: bold">'.$user_name.'</span>', $text);
        $text = preg_replace('/\$lease_start_date/', '<span style="font-weight: bold">'.$lease_info['start_date'].'</span>', $text);
        $text = preg_replace('/\$lease_end_date/', '<span style="font-weight: bold">'.$lease_info['end_date'].'</span>', $text);
        $text = preg_replace('/\$apt_address/', '<span style="font-weight: bold">'.$apt_address.'</span>', $text);
        $text = preg_replace('/\$room_name/', '<span style="font-weight: bold">'.$room_name.'</span>', $text);
        $text = preg_replace('/\$rent_amount/', '<span style="font-weight: bold">'.$rent_amount.'</span>', $text);
        $text = preg_replace('/\$prorated_rent/', '<span style="font-weight: bold">'.$prorated_rent.'</span>', $text);
        return $text;
    }


    public static function updateLease($id){

        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();
        $post_data['start_date'] = Date::toMysqlFormat($post_data['start_date']);
        $post_data['end_date'] = Date::toMysqlFormat($post_data['end_date']);
        $post_data['step'] = 1;

        $validator = Validator::validate($post_data, self::$rulesUpdate, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;

            $update_data = $post_data;
            $where = ['id'=>$id];

            self::$db->update(self::$tableName, $update_data, $where);
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }

        return $return;
    }

    public static function prepareLease($user_id){

        $check = self::$db->selectOne("SELECT `id` FROM ".self::$tableName." WHERE `user_id`='".$user_id."' AND `partner_id`='".self::$partner_id."'");
        if($check){
            $return = $check['id'];
        }else{

            $user_info = TenantsModel::getItem($user_id);
            if($user_info['partner_id']!=self::$partner_id){
                $return = 0;
            }else {
                $partner_info = TenantsModel::getItem(self::$partner_id);
                $insert_data = [
                    'user_first_name' => $user_info['first_name'],
                    'user_middle_name' => $user_info['middle_name'],
                    'user_last_name' => $user_info['last_name'],
                    'user_id' => $user_id,
                    'partner_first_name' => $partner_info['first_name'],
                    'partner_middle_name' => $partner_info['middle_name'],
                    'partner_last_name' => $partner_info['last_name'],
                    'partner_phone' => $partner_info['phone'],
                    'partner_address' => $partner_info['address'],
                    'partner_id' => self::$partner_id,
                ];
                $lease_id = self::$db->insert(self::$tableName, $insert_data);
                $return = $lease_id;

                $pages = self::$db->select("SELECT `title`,`text` FROM ".self::$tableNameTemplates." WHERE `partner_id`='".self::$partner_id."'");
                foreach ($pages as $page) {
                    $insert_data = [
                        'title'=>$page['title'],
                        'text'=>$page['text'],
                        'user_id'=>$user_id,
                        'partner_id'=>self::$partner_id,
                        'lease_id'=>$lease_id,
                        ];
                    self::$db->insert(self::$tableNamePages, $insert_data);
                }
            }
        }
        return $return;
    }


    public static function getBedOptions(){
        $list = [];
        $list[] = ['key'=>0, 'name'=>'---', 'disabled'=>''];
        new ApartmentsModel();
        $apt_array = ApartmentsModel::getList();
        foreach ($apt_array as $apt){
            $room_array = RoomsModel::getList($apt['id']);
            foreach ($room_array as $room){
                $name = $apt['name'];
                $list[] = ['key'=>'', 'name'=>$name, 'disabled'=>'disabled'];
                $bed_array = BedsModel::getListByRoom($room['id']);
                foreach ($bed_array as $bed){
                    $list[] = ['key'=>$bed['id'], 'name'=>$name.', '.$room['name'].' '.$bed['name'], 'disabled'=>''];
                }
            }
        }
        return $list;
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
            $insert_data['partner_id'] = self::$partner_id;

            $insert_id = self::$db->insert(self::$tableName,$insert_data);
            if($insert_id>0){
                self::updatePosition($insert_id);
                if($post_data['bed_id']>0){
                    self::$db->update(self::$tableNameBeds,['tenant_id'=>$insert_id], ['id'=>$post_data['bed_id']]);
                    if($post_data['available_date']>0) {
                        self::$db->update(self::$tableNameBeds, ['available_date' => $post_data['available_date']], ['id' => $post_data['bed_id']]);
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

        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }

        return $return;
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
    public static function delete($id){
        $check = self::$db->selectOne("SELECT `id` FROM ".self::$tableName." where `user_sign`=0 AND `id` ='".$id."'");
        if($check){
            self::$db->raw("DELETE FROM ".self::$tableName." where `user_sign`=0 AND `id` ='".$id."'");
            self::$db->raw("DELETE FROM ".self::$tableNamePages." where `lease_id` ='".$id."'");
        }

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