<?php
namespace Models;
use Core\Model;
use Helpers\Cookie;
use Helpers\Mail;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Core\Language;

class InspectionsModel extends Model{

    public $lng;
    private static $params;
    private static $rules = [
        'first_name' => ['min_length(3)', 'max_length(20)'],
        'last_name' => ['min_length(3)', 'max_length(20)'],
        'phone' => ['min_length(7)', 'max_length(16)'],
        'email' => ['email'],
    ];
    public function __construct($params){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        self::$params = $params;
    }

    private static function naming(){
        return include SMVC.'app/language/'.self::$def_language.'/naming.php';
    }

    protected static function getPost(){
        extract($_POST);
        $array = [];
        $skip_list = ['csrf_token'];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }
        return $array;
    }

    public static function makeAppointment(){

        $return = [];
        $post_data = self::getPost();

        $params = self::$params;
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;


            $mysql_data = $post_data;
            $mysql_data['app_time'] = strtotime($mysql_data['app_date']);
            $mysql_data['move_time'] = strtotime($mysql_data['move_date']);

            unset($mysql_data['app_date']);
            unset($mysql_data['move_date']);
            self::$db->insert($params['name'], $mysql_data);

            $mail_title = 'New Appointment Request '.$post_data['first_name']." ".$post_data['last_name'];
            $mail_text = "
            Name: ".$post_data['first_name']." ".$post_data['last_name']."<br/>
            Phone number: ".$post_data['phone']."<br/>
            E-mail address: ".$post_data['email']."<br/>
            Available Date: ".$post_data['app_date']."<br/>
            Move out Date: ".$post_data['move_date']."<br/>
            Do you have a pet?: ".$post_data['pets']."<br/>
            Note: ".$post_data['note']."<br/>
            ";
//            echo $mail_text;exit;
//            Mail::sendMail(SITE_EMAIL,'reshad_mirzeyev@mail.ru', $mail_title, $mail_text);

            Mail::sendMail(SITE_EMAIL,'m.rashad@lordhousing.com', $mail_title, $mail_text);
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        $return['postData'] = $post_data;
        return $return;
    }


}
