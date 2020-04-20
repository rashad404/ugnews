<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Database;
use Helpers\Mail;
use Helpers\Security;
use Helpers\Session;
use Helpers\Sms;
use Helpers\Validator;
use Models\LanguagesModel;

class RegistrationModel extends Model{

    public $tableName = 'users';
	public $lng;
    public function __construct(){
        parent::__construct();
	    $this->lng = new Language();
	    $this->lng->load('app');
    }

	private static $rules = [
		'country_code' => ['min_length(1)','max_length(4)', 'integer'],
		'phone' => ['min_length(7)','max_length(12)', 'integer'],
		'password' => ['min_length(4)', 'max_length(20)', 'password'],
		'email' => ['min_length(6)', 'max_length(60)', 'email'],
		'first_name' => ['min_length(3)', 'max_length(15)'],
//		'last_name' => ['min_length(3)', 'max_length(15)'],
        'gender' => ['selectbox'],
	];


    private static function naming(){
	    return include SMVC.'app/language/'.LanguagesModel::defaultLanguage().'/naming.php';
    }

	protected static function getPost()
	{
		extract($_POST);
        $array = ['first_name'=>'','last_name'=>'','phone'=>'','email'=>'','birth_month'=>'','birth_day'=>'','birth_year'=>'','return'=>''];
		$skip_list = ['csrf_token', 'csrf_token_register', 'csrf_token_login'];
		foreach($_POST as $key=>$value){
			if (in_array($key, $skip_list)) continue;
			$array[$key] = Security::safe($_POST[$key]);
		}
		return $array;
	}
	public function registration(){
		$return = [];
		$post_data = $this->getPost();

        $validator = Validator::validate($post_data, self::$rules, self::naming());
		if ($validator->isSuccess()) {
			$return['errors'] = null;

			$check_email = Database::get()->selectOne("SELECT `id` FROM {$this->tableName} WHERE `email`=:email",[":email"=>$post_data['email']]);
            if($check_email){
                $return['errors'] = $this->lng->get('The email address you have entered is already registered');
            }else {
                //merging country_code and phone in array
                $remove_keys = ['number','csrf_token','return','birth_year','birth_month','birth_day','redirect_url'];
                $phone = $post_data['country_code'].$post_data['phone'];
                $mysql_data = array_diff_key($post_data,array_flip($remove_keys));
                $mysql_data['phone'] = $phone;
                $mysql_data['birthday'] = $post_data['birth_year'].'-'.$post_data['birth_month'].'-'.$post_data['birth_day'];
                $mysql_data['password_hash'] = Security::password_hash($post_data['password']);
                $confirmation_code = Security::generateConfirmationCodeEmail(20);
                $mysql_data['code'] = $confirmation_code;
                $mysql_data['reg_time'] = time();

                $userId = Database::get()->insert( $this->tableName, $mysql_data );
                $email_text = $this->lng->get('Hi').' '.$post_data['first_name'].' '.$post_data['last_name'].', ';
                $email_text .= $this->lng->get('Thank you for registering on our site. In order to complete your registration, please click the confirmation link below').':<br/>';
                $email_text .= SITE_URL.'/confirm_email/'.$confirmation_code;
//                Mail::sendMail(SITE_EMAIL, $post_data['email'], PROJECT_NAME.' '.$this->lng->get('Registration mail'), $email_text);
                Session::set("user_session_id", intval($userId));
                Session::set("user_session_pass", Security::session_password($mysql_data['password_hash']));
			}
		}else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
		}
		$return['postData'] = $post_data;
		return $return;
	}
}
