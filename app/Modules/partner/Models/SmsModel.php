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

class SmsModel extends Model{

    private static $tableName = 'sms_logs';
    private static $tableNameUsers = 'users';
    private static $tableNameChats = 'sms_chats';

    private static $rules;
    private static $params;
    private static $partner_id;

    public function __construct($params=''){
        parent::__construct();
        self::$rules = [
            'text' => ['min_length(2)', 'max_length(300)'],
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

    public static function send($id, $user_type=0){
        $return = [];
        $post_data =self::getPost();
        if(isset($post_data['guest_id'])){$guest_id = intval($post_data['guest_id']);}else{$guest_id=0;}
        if(isset($post_data['tenant_id'])){$tenant_id = intval($post_data['tenant_id']);}else{$tenant_id=0;}

        if($id>0 && $user_type==0){
            $tenant_id = $id;
        }elseif($id>0 && $user_type==1){
            $guest_id = $id;
        }

        $text = $post_data['text'];

        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;
            if($guest_id>0 && $tenant_id>0) {
                $return['errors'] = 'Please don\'t select Guest card and Tenant at the same time';
            }else {
                if ($guest_id > 0) {
                    $user_info = CustomersModel::getItem($guest_id);
                    $to = $user_info['phone'];
                    \Models\SmsModel::send($to, $text, 1);
                } else {
                    $user_info = TenantsModel::getItem($tenant_id);
                    $to = $user_info['phone'];
                    \Models\SmsModel::send($to, $text);
                }
            }
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        $return['postData'] = $post_data;
        return $return;

    }

    public static function getList($user_id, $user_type=0){
        $array  = self::$db->select("SELECT * FROM ".self::$tableName." WHERE `user_id`='".$user_id."' AND `partner_id`='".self::$partner_id."' AND `user_type`='".$user_type."' ORDER BY `id` ASC ");
        $u_array = ['seen'=>1];
        $w_array = ['user_id'=>$user_id, 'partner_id'=>self::$partner_id];
        self::$db->update(self::$tableName, $u_array, $w_array);

        return $array;
    }


    public static function getAllChats($order){
        if($order=='recent'){
            $order = 'ORDER BY `time` DESC';
        }else{
            $order = 'ORDER BY `time` ASC';
        }
        $array = self::$db->select("SELECT * FROM `".self::$tableNameChats."` WHERE `partner_id`='".self::$partner_id."' ".$order);
        return $array;
    }

    public static function countNewMessages(){
        $count = self::$db->count("SELECT COUNT(`id`) FROM `".self::$tableName."` WHERE `partner_id`='".self::$partner_id."' AND `seen`=0");
        return $count;
    }


    public static function countNewMessagesChat($id){
        $count = self::$db->count("SELECT COUNT(`id`) FROM `".self::$tableName."` WHERE `user_id`='".$id."' AND `partner_id`='".self::$partner_id."' AND `seen`=0");
        return $count;
    }



    public static function bulkSend(){
        $return = [];
        $post_data =self::getPost();
        $text = $post_data['text'];
        $bulk_type = $post_data['bulk_type'];

        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;
            if($bulk_type==0) {
                $return['errors'] = 'Please select an option';
            }else {

                $users = TenantsModel::getListByType($bulk_type);
                foreach ($users as $user) {
                    echo $user['id'].'<br/>';
                    echo $user['first_name'].'<br/>';
                    echo $user['gender'].'<br/>';
                    echo $user['phone'].'<br/>';
                    echo $user['email'].'<br/>';
                    echo $text.'<br/>';
                    echo '<br/>';
                    \Models\SmsModel::send($user['phone'], $text, 0, 0);
                }

            }
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        $return['postData'] = $post_data;
        return $return;
    }


    public static function getBulkOptions(){
        $array = [
            0 => '---',
            1 => 'To All Tenants',
            2 => 'To Female Tenants',
            3 => 'To Male Tenants',
            4 => 'To Delinquents',
            ];
        return $array;
    }
}

?>