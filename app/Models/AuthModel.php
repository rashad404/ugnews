<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Security;
use Helpers\Url;
use Helpers\Session;
use Helpers\Curl;

class AuthModel extends Model{

    public static $tableName = 'users';
	public $lng;
    public function __construct(){
        parent::__construct();
	    $this->lng = new Language();
	    $this->lng->load('app');
    }

	public static function checkLogin(){
        $userId = intval(Session::get("user_session_id"));
        if($userId<1){
            $lng = new Language();
            $lng->load('app');
            Session::setFlash('warning', $lng->get('Please login to continue'));
            Url::redirect('login');
            exit;
        }
	}

	public static function google($user){
        $return = [];

        $email =  $user->email;
        $last_name =  $user->familyName;
        $first_name =  $user->givenName;
        $picture =  $user->picture;


        $check_exists = self::$db->selectOne("SELECT `id`,`reg_type` FROM ".self::$tableName." WHERE `email`=:email",[":email"=>$email]);
        if($check_exists){
            if($check_exists['reg_type']==1){
                Session::set("user_session_id", intval($check_exists['id']));
            }else {
                $return['errors'] = self::$language->get('This email address is already registered');
            }
        }else {
            $array = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'reg_time' => time(),
                'reg_type' => 1,
            );
            $id = self::$db->insert(self::$tableName, $array);
            Session::set("user_session_id", intval($id));
            $local_url = Url::uploadPath().'users/'.$id.'.jpg';
            Curl::saveFile($picture, $local_url);
        }

        return $return;
	}

	public static function facebook($user){
        $return = [];
        $fb_id = $user['id'];
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];
        $picture =  'https://graph.facebook.com/'.$fb_id.'/picture?width=1000';


        $check_exists = self::$db->selectOne("SELECT `id`,`reg_type`,`password` FROM ".self::$tableName." WHERE `fb_id`=:fb_id",[":fb_id"=>$fb_id]);
        if($check_exists){
            if($check_exists['reg_type']==2){
                Session::set("user_session_id", intval($check_exists['id']));
                Session::set("user_session_pass", $check_exists['password']);
            }else {
                $return['errors'] = self::$language->get('This email address is already registered');
            }
        }else {
            $new_password = Security::generatePassword(8);
            $new_password_hash = Security::password_hash($new_password);
            $array = array(
                'password' => $new_password,
                'password_hash' => $new_password_hash,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'fb_id' => $fb_id,
                'reg_time' => time(),
                'reg_type' => 2,
            );
            $id = self::$db->insert(self::$tableName, $array);

            Session::set("user_session_id", intval($id));
            Session::set("user_session_pass", $new_password);

            $local_url = Url::uploadPath().'users/'.$id.'.jpg';
            Curl::saveFileFgc($picture, $local_url);
        }

        return $return;
	}
}