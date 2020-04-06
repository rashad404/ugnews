<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Database;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Models\LanguagesModel;
use Helpers\FileUploader;

class ContactsModel extends Model{

    public $tableName = 'users';
    public $lng;
    private $userId;
    public function __construct(){
        parent::__construct();
        $this->userId = intval(Session::get("user_session_id"));
        $this->lng = new Language();
        $this->lng->load('app');
    }

    private static $rules = [
        'phone' => ['min_length(10)', 'max_length(14)', 'fullPhone'],
        'email' => ['min_length(6)', 'max_length(60)', 'email'],
        'name' => ['min_length(3)', 'max_length(30)'],
        'message' => ['min_length(10)', 'max_length(200)']
    ];


    private static function naming(){
        return include SMVC.'app/language/'.LanguagesModel::defaultLanguage().'/naming.php';
    }

    protected static function getPost()
    {
        extract($_POST);
        $array = ['name'=>'','email'=>'','phone'=>'','message'=>''];
        $skip_list = ['csrf_token'];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }
        return $array;
    }

    public function sendMessage(){

        $postArray = self::getPost();
        $subject = 'From site';
        $email = $postArray['email'];
        $name = $postArray['name'];
        $message = $postArray['message'];
        $return = [];
        $validator = Validator::validate($postArray, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $txt = '
        <html>
        <head>
        <title>'.$this->lng->get("Message From Site").': '.$subject.'</title>
        </head>
        <body style="background: #ffffff">
            <h4>' . $subject . '</h4>
            <h5>' . $name . '</h5>
            <h5>' . $message . '</h5>
            <h5>' . $email . '</h5>
        </body>
        </html>
        ';

            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= "From: $email" . "\r\n";

            $send = mail(SITE_EMAIL, $subject, $txt, $headers);
            if($send) {
                $return['errors'] = '';
            }else{
                $return['errors'] = array($this->lng->get('There has been error while sending message').'');
            }
        }else{
            $return['errors'] = implode('<br/>',$validator->getErrors());
        }

        $return['postData'] = $postArray;
        return $return;
    }
}
