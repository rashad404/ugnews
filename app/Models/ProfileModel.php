<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Helpers\FileUploader;

class ProfileModel extends Model{

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
        $array = ['first_name'=>'','last_name'=>'','phone'=>'','birth_month'=>'','birth_day'=>'','birth_year'=>''];
		$skip_list = ['csrf_token'];
		foreach($_POST as $key=>$value){
			if (in_array($key, $skip_list)) continue;
			$array[$key] = Security::safe($_POST[$key]);
		}
		return $array;
	}
	public function update(){
		$return = [];
		$post_data = $this->getPost();
        $post_data['step'] =1;

        $validator = Validator::validate($post_data, self::$rules, self::naming());
		if ($validator->isSuccess()) {
			$return['errors'] = null;

            if (!empty($_FILES['file']['name'])) {
                $upload_model = new FileUploader();
                $upload = $upload_model->imageUpload('',"users");
                if($upload['success']==0){
                    $return['errors'] = $upload['error'];
                }
            }

            //merging country_code and phone in array
            $remove_keys = ['number','csrf_token','step','forget_step', 'birth_year', 'birth_month', 'birth_day'];
            $phone = $post_data['country_code'].$post_data['phone'];
            $birthday = $post_data['birth_year'].'-'.$post_data['birth_month'].'-'.$post_data['birth_day'];
            $mysql_data = array_diff_key($post_data,array_flip($remove_keys));
            $mysql_data['phone'] = $phone;
            $mysql_data['birthday'] = $birthday;

            $userId = intval(Session::get("user_session_id"));
            $mysql_where = ['id'=>$userId];
            self::$db->update( $this->tableName, $mysql_data, $mysql_where);

		}else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
		}
		$return['postData'] = $post_data;
		return $return;
	}
}
