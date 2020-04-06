<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;

class AddressModel extends Model{

    public $tableName = 'addresses';
	public $lng;
    public function __construct(){
        parent::__construct();
	    $this->lng = new Language();
	    $this->lng->load('app');
    }

	private static $rules = [
		'country_code' => ['min_length(1)','max_length(4)', 'integer'],
		'phone' => ['min_length(7)','max_length(12)', 'integer'],
		'first_name' => ['min_length(3)', 'max_length(15)'],
		'last_name' => ['min_length(3)', 'max_length(15)'],
		'street' => ['min_length(5)', 'max_length(30)'],
		'apt' => ['min_length(3)', 'max_length(30)'],
		'city' => ['min_length(3)', 'max_length(30)'],
		'state' => ['min_length(3)', 'max_length(30)'],
		'zip' => ['min_length(3)', 'max_length(10)'],
		'info' => ['max_length(200)'],
	];


    private static function naming(){
	    return include SMVC.'app/language/'.LanguagesModel::defaultLanguage().'/naming.php';
    }

	protected static function getPost()
	{
		extract($_POST);
		$skip_list = ['csrf_token'];
		foreach($_POST as $key=>$value){
			if (in_array($key, $skip_list)) continue;
			$array[$key] = Security::safe($_POST[$key]);
		}
		return $array;
	}

    public function getInfo($id){
        $row = self::$db->selectOne("SELECT `id`,`country_code`,`phone`,`first_name`,`last_name`,
            `street`,`apt`,`city`,`state`,`zip`,`info` FROM ".$this->tableName." WHERE `user_id`=:id",[":id"=>$id]);
        return $row;
    }


    public function update(){
		$return = [];
		$post_data = $this->getPost();

        $validator = Validator::validate($post_data, self::$rules, self::naming());
		if ($validator->isSuccess()) {
			$return['errors'] = null;

            //merging country_code and phone in array
            $remove_keys = ['number','csrf_token','step','forget_step'];
            $mysql_data = array_diff_key($post_data,array_flip($remove_keys));

            $userId = intval(Session::get("user_session_id"));

            $check = self::$db->selectOne("SELECT `id` FROM {$this->tableName} WHERE `user_id`=:id",[":id"=>$userId]);
            if($check){
                $mysql_where = ['user_id'=>$userId];
                self::$db->update( $this->tableName, $mysql_data, $mysql_where);
            }else {
                $mysql_data['user_id'] = $userId;
                self::$db->insert( $this->tableName, $mysql_data);
            }
		}else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
		}
		$return['postData'] = $post_data;
		return $return;
	}
}
