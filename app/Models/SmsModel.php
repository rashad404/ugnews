<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Console;
use Helpers\Mail;
use Helpers\Security;
use Twilio\Rest\Client;

class SmsModel extends Model{

    private static $tableName = 'sms_logs';
    private static $tableNamePartnerPhones = 'sms_partner_numbers';
    private static $tableNameChats = 'sms_chats';
    private static $tableNameUsers = 'users';
    private static $tableNameCustomers = 'customers';
    public $lng;

    public static $sid = 'AC9140a16b1457bca79ee0afc6f69ffead';
    public static $token = '2d4aa13a535d9cc885ee91bf0e5c6aca';
    public static $from = '12133196667';

    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }

    public static function receive($to){
        $from = preg_replace('/\+/','', Security::safeText($_POST['From']));
        $text = Security::safeText($_POST['Body']);


        $partner_phones_info = self::$db->selectOne("SELECT `partner_id`,`notify_email_1`,`notify_email_2` FROM `".self::$tableNamePartnerPhones."` WHERE `phone`='".$to."'");
        $user_info = self::$db->selectOne("SELECT `id`,`first_name`,`last_name` FROM `".self::$tableNameUsers."` WHERE `phone`='".$from."'");

        if($user_info) {
            $user_id = intval($user_info['id']);
            $user_name = $user_info['first_name'] . '' . $user_info['last_name'];
        }else{
            $user_id = 0;
            $user_name = $from;
        }

        $partner_id = intval($partner_phones_info['partner_id']);
        $notify_email_1 = $partner_phones_info['notify_email_1'];
        $notify_email_2 = $partner_phones_info['notify_email_2'];



        $check = self::$db->selectOne("SELECT `id` FROM `".self::$tableNameChats."` 
        WHERE `user_id`='".$user_id."' AND `partner_id`='".$partner_id."'");

        if(!$check){
            $insert_data_chats = [
                'user_id'=>$user_id,
                'partner_id'=>$partner_id,
                'last_text'=>$text,
                'time'=>time(),
            ];
            self::$db->insert( self::$tableNameChats, $insert_data_chats);
        }else{
            self::$db->raw( "UPDATE `".self::$tableNameChats."` SET `last_text`='".$text."',time='".time()."' 
            WHERE `user_id`='".$user_id."' AND `partner_id`='".$partner_id."'");
        }


        $insert_data = [
            'sms_to'=>$to,
            'sms_from'=>$from,
            'text'=>$text,
            'user_id'=>$user_id,
            'partner_id'=>$partner_id,
            'time'=>time()
        ];
        self::$db->insert(self::$tableName, $insert_data);



            $title = 'New SMS from '.$user_name;
            $text = '
            <div style="padding: 10px;font-size: 16px;">New SMS from <span style="font-weight: bold">'.$user_name.'</span>,</div>
            <div style="font-size: 16px;padding-left: 10px;line-height: 28px;">
                SMS: '.$text.'<br/>
                <div style="padding: 20px 0;">
                    <a style="padding: 10px 15px;background-color: #673194;color: #fff;font-weight: bold;text-decoration: none" href="https://ureb.com/login/partner+sms+index">Reply</a>
                    </div>
            </div>
            ';
        if(!empty($notify_email_1)) {
            Mail::sendMail(SITE_EMAIL_NO_REPLY, $notify_email_1, $title, $text);
        }
        if(!empty($notify_email_2)) {
            Mail::sendMail(SITE_EMAIL_NO_REPLY, $notify_email_2, $title, $text);
        }

        return $insert_data;
    }


    public static function send($to, $text, $user_type=0, $delay=0){

        //user_type=1 guest card
        $to = Security::safeTextNew($to);


        $text = Security::safeTextNew($text);

        $sms_text = stripslashes($text);

//        echo $sms_text.'<br/>';
//        echo $text.'<br/>';
//        exit;

        if($user_type==1) {
            if($delay==0) {
                $twilio = new Client(self::$sid, self::$token);
                $message = $twilio->messages->create('+1' . $to, array("body" => $sms_text, "from" => self::$from));
            }
            $user_info = self::$db->selectOne("SELECT `id` FROM `" . self::$tableNameCustomers . "` WHERE `phone`='" . $to . "'");

        }else{
            if($delay==0) {
                $twilio = new Client(self::$sid, self::$token);
                $message = $twilio->messages->create('+' . $to, array("body" => $sms_text, "from" => self::$from));
            }
            $user_info = self::$db->selectOne("SELECT `id` FROM `" . self::$tableNameUsers . "` WHERE `phone`='" . $to . "'");
        }
//        print($message->sid);

        $partner_info = self::$db->selectOne("SELECT `partner_id` FROM `".self::$tableNamePartnerPhones."` WHERE `phone`='".self::$from."'");


        $user_id = intval($user_info['id']);
        $partner_id = intval($partner_info['partner_id']);
        $check = self::$db->selectOne("SELECT `id` FROM `".self::$tableNameChats."` 
        WHERE `user_id`='".$user_id."' AND `partner_id`='".$partner_id."' AND `user_type`='".$user_type."'");

        if(!$check){
            $insert_data_chats = [
                'user_id'=>$user_id,
                'partner_id'=>$partner_id,
                'last_text'=>$text,
                'user_type'=>$user_type,
                'time'=>time(),
            ];
            self::$db->insert( self::$tableNameChats, $insert_data_chats);
        }else{
            self::$db->raw( "UPDATE `".self::$tableNameChats."` SET `last_text`='".$text."',time='".time()."' 
            WHERE `user_id`='".$user_id."' AND `partner_id`='".$partner_id."'");
        }

        if($delay==0) {
            $sent = 1;
        }else{
            $sent = 0;
        }

        $insert_data = [
            'sms_to'=>$to,
            'sms_from'=>self::$from,
            'text'=>$text,
            'user_id'=>intval($user_info['id']),
            'partner_id'=>intval($partner_info['partner_id']),
            'user_type'=>$user_type,
            'sent'=>$sent,
            'time'=>time()
        ];

        self::$db->insert(self::$tableName, $insert_data);

        return $insert_data;
    }

}
