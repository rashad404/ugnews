<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Mail;
use Models\api\ExpressModel;
use Models\LanguagesModel;
use Helpers\Security;
use Helpers\Url;
use Helpers\Validator;
use Helpers\Operation;
use Helpers\Pagination;

class BankCardsModel extends Model{



	public static $safeMode = false;  // rows which is the protected from deletion
	public static $safeModeFields = ["safe_mode"]; // fields which helps us to detect protected rows from deletion

	public static $positionEnable=true;     // Order
	public static $positionOrderBy  = 'ASC'; // Order type
	public static $positionCondition = true;    // Is there any fields for order?
	public static $positionConditionField = ['parent_id']; // Fields that will be used for order

	public static $statusMode = true; // Status active/deactive
	public static $crudMode = true; // Operation icons

	//set place of item true or false: up and down
	public static $posUp = true;
	public static $posDown = true;


	public $operation;
	public static $defaultLang;
	public static $langLoad;


	public $dataParams = [];


    public function __construct(){
        parent::__construct();

	    self::$language->load('admin');
	    self::$defaultLang = LanguagesModel::getDefaultLanguage('admin');

        $this->getDataParams();
	    $this->operation = new Operation();

	    $this->operation->tableName = $this->dataParams["cName"];
    }

    public static function rules()
    {
        return [
            'phone' => ['exact_length(12)', 'fullPhone'],
            'email' => ['min_length(6)', 'max_length(60)', 'email'],
            'name' => ['min_length(3)', 'max_length(30)'],
            'surname' => ['min_length(3)', 'max_length(30)'],
            'father_name' => ['min_length(3)', 'max_length(30)'],
            'birth_day' => ['min_length(1)', 'max_length(2)','integer'],
            'birth_month' => ['min_length(1)', 'max_length(2)','integer'],
            'birth_year' => ['exact_length(4)','integer'],
            'passport' => ['min_length(1)', 'max_length(12)','integer'],
        ];
    }


	public static function naming()
	{
		return [
			'title_'.self::$defaultLang => self::$language->get("Title")
		];
	}

	public function getDataParams()
	{
		$this->dataParams = [
			"cName" => "bank_cards",
			"urlName" => "bankcards",
			"cModelName" => "BankCardsModel",
			"cTitle" => self::$language->get("Bank cards"),
			"cStatusMode" => self::$statusMode,
			"cPositionEnable" => self::$positionEnable,
			"cCrudMode" => self::$crudMode,
			"posUp" => self::$posUp,
			"posDown" => self::$posDown,
			"defaultLang" => self::$defaultLang,
			"lang" => self::$language,
		];
		return $this->dataParams;
	}

    protected static function getPost()
    {
        extract($_POST);
        $array = [];
        $skip_list = ['csrf_token','submit'];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }

        return $array;
    }

    public function sendMail($id){

        $data = [];
        $fields = "
        `id`,`card_name`,`name`,`surname`,`father_name`,`passport`,`passport_photo_front`,`passport_photo_back`,
        `birth_day`,`birth_month`,`birth_year`,`phone`,`work_phone`,`home_phone`,`email`,`pin`,`private_code`,`branch`,`reg_address`,`current_address`,`send`
        ";
        $array = self::$db->selectOne("SELECT ".$fields." FROM `".$this->dataParams["cName"]."` WHERE `id`='".$id."' ");
        if($array['send']!=0){
            $data['errors'] = 'Mail has been already sent for this card. Can\'t send again.';
        }else{
            $branches = ExpressModel::getBranches();
            $text = 'Müştəri: '.$array['name'].' '.$array['surname'].' '.$array['father_name'].'<br/>';
            $text .= 'Kartın üzərindəki ad: '.$array['card_name'].'<br/>';
            $text .= 'Ş.V. nömrəsi: '.$array['passport'].'<br/>';
            $text .= 'Doğum tarixi: '.$array['birth_day'].' '.$array['birth_month'].' '.$array['birth_year'].'<br/>';
            $text .= 'Mobil nömrə: '.$array['phone'].'<br/>';
            if(!empty($array['work_phone']))$text .= 'Iş telefonu: '.$array['work_phone'].'<br/>';
            if(!empty($array['home_phone']))$text .= 'Ev telefonu: '.$array['home_phone'].'<br/>';
            $text .= 'E-mail: '.$array['email'].'<br/>';
            $text .= 'FİN: '.$array['pin'].'<br/>';
            $text .= 'Gizli kod: '.$array['private_code'].'<br/>';
            $text .= 'Qeydiyyat ünvanı: '.$array['reg_address'].'<br/>';
            $text .= 'Faktiki ünvan: '.$array['current_address'].'<br/>';
            $text .= 'Bank şöbəsi: '.$branches[$array['branch']][1].'<br/>';

            $validator = Validator::validate($array, self::rules(), self::naming());
            if($validator->isSuccess()) {

                $files = [
                    'Web/uploads/passport_front/' . $array['passport_photo_front'] . '.jpg',
                    'Web/uploads/passport_back/' . $array['passport_photo_back'] . '.jpg'];
                $sendMail = Mail::sendMail_attachment(CARD_EMAIL_FROM, CARD_EMAIL_EXPRESS, 'Paymanat Card Order', $text, $files);
                if ($sendMail) {
                    $update_data = ['send' => 1];
                    $where = ['id' => $id];
                    self::$db->update($this->dataParams["cName"], $update_data, $where);
                    $data['errors'] = null;
                } else {
                    $data['errors'] = 'There has been an error while sending email.';
                }

            }else {
                $data['errors'] = implode('<br/>',$validator->getErrors());
            }
        }
        return $data;
    }


	public function update($id){
		$return = [];
		$postArray = $this->getPost();
		$model = $this->operation->findModel($id);

		$rules = self::rules();
		$validator = Validator::validate($postArray,$rules,self::naming());

		if($validator->isSuccess()){
			$update = self::$db->update($this->dataParams["cName"],$postArray,["id" => $id]);
			if($update){
				$return['errors'] = null;
			}else{
				$return['errors'] = self::$language->get("Nothing changed");
			}
		}else{
			$msg = '';
			foreach($validator->getErrors() as $error){
				$msg .= $error.'<br>';
			}
			$return['errors'] = $msg;

		}

		$return['dataParams'] = $this->dataParams;
		$return['model'] = $model;
		$return['list'] = $this->getList();
		return $return;
	}

	public function search(){
    	$return = [];
		if($_POST){
			$word = $_POST['word'];
			$return['rows'] = $this->searchLikeFor($this->dataParams["cName"],['id','name','surname','father_name','phone','email','passport','pin'],$word);
		} else {
			$return['rows'] = self::$db->select("SELECT * FROM `".$this->dataParams["cName"]."` WHERE `status`=1 ");
		}
		$return['pagination'] = new Pagination();
		return $return;
	}

	public function searchLikeFor($table, $values, $search_word){

		$sql_s = ''; // Stop errors when $words is empty
		if($values<=1){
			$sql_s = "`".$values."` LIKE '%".$search_word."%' ";
		} else {
			foreach($values as $value){
				$sql_s .= "`".$value."` LIKE '%".$search_word."%' OR ";
			}
			$sql_s = substr($sql_s,0,-3);
		}
		$sql = self::$db->select("SELECT * FROM `".$table."` WHERE ".$sql_s);
		return $sql;

	}

	public function create(){

		$return = [];
		$postArray = $this->getPost();
		$model = $postArray;

		$rules = self::rules();
		$validator = Validator::validate($postArray,$rules,self::naming());

		if($validator->isSuccess()){
			$insert = self::$db->insert($this->dataParams["cName"],$postArray);
			if($insert){
				$return['errors'] = null;
				if($this->dataParams['cPositionEnable'])
					$this->operation->getPositionForNew($insert,'up',true);
			}else{
				$return['errors'] = self::$language->get("There has been an error.");
			}
		}else{
			$msg = '';
			foreach($validator->getErrors() as $error){
				$msg .= $error.'<br>';
			}
			$return['errors'] = $msg;

		}
		$return['dataParams'] = $this->dataParams;
		$return['model'] = $model;
		$return['list'] = $this->getList();
		return $return;
	}

	public function getList()
    {
        $pagination = new Pagination();
        $countRows = self::$db->count("SELECT count(id) FROM  ".$this->dataParams['cName']);
        $limitSql = $pagination->getLimitSql($countRows);
        $return['pagination'] = $pagination;

        $return['rows'] = self::$db->select('SELECT 
        `id`,`name`,`surname`,`father_name`,`phone`,`email`,`passport`,`pin`,`private_code`,`branch`,`reg_address`,`current_address`,`time`,`status`,`send`
         FROM '.$this->dataParams['cName'].' ORDER BY `id` DESC'.$limitSql);
        return $return;
    }

    public function getItemName($id)
    {
        $row = self::$db->selectOne('SELECT 
        `title_'.$this->dataParams['defaultLang'].'`
         FROM '.$this->dataParams['cName'].' WHERE `id`=:id',[':id'=> $id]);
        return $row['title_'.$this->dataParams['defaultLang']];
    }


}