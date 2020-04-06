<?php
namespace Models;
use Core\Model;
use Helpers\Database;
use Core\Language;
use Helpers\Security;
use Helpers\Validator;
use Helpers\Session;

class LoginModel extends Model{

    public $tableName = 'users';
	public $lng;
    public function __construct(){
        parent::__construct();
	    $this->lng = new Language();
	    $this->lng->load('app');
    }

	private static $rules = [
		'email' => ['required'],
		'password' => ['required']
	];


    private static function naming(){
	    return include SMVC.'app/language/'.LanguagesModel::defaultLanguage().'/naming.php';
    }

	protected static function getPost()
	{
		extract($_POST);
        $array = ['email'=>''];
		$skip_list = ['submit'];
		foreach($_POST as $key=>$value){
			if (in_array($key, $skip_list)) continue;
			$array[$key] = Security::safe($_POST[$key]);
		}
		return $array;
	}

	public function login(){
		$return = [];
		$post_data = $this->getPost();
		$validator = Validator::validate($post_data, self::$rules, self::naming());
		if ($validator->isSuccess()) {
			$return['errors'] = null;
			$pass_hash = Security::password_hash($post_data['password']);

			$check = Database::get()->selectOne("SELECT `id`,`block` FROM {$this->tableName} WHERE 
            `email`=:email AND `password_hash`=:password_hash",[":email"=>$post_data['email'],":password_hash"=>$pass_hash]);
			if($check){
			    if($check['block']==1){
                    $return['errors'] = $this->lng->get("Your account is inactive");
                }else{
                    Session::set("user_session_id",intval($check['id']));
                    Session::set("user_session_pass",Security::session_password($pass_hash));
                }
            }else{
				$return['errors'] = $this->lng->get('Email or Password is incorrect');
			}

		}else{
            $return['errors'] = implode('<br/>',$validator->getErrors());
		}
		$return['postData'] = $post_data;
		return $return;
	}
}