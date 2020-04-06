<?php

namespace Modules\partner\Models;

use Core\Model;
use Models\LanguagesModel;
use Helpers\Security;
use Helpers\Url;
use Helpers\Validator;
use Helpers\Operation;
use Helpers\Pagination;

class FaqModel extends Model{



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

	public static $fields = [
        [
            "field_name" => "text",
            "field_type" => "TEXT"

        ],[
            "field_name" => "title",
            "field_type" => "VARCHAR (255)"

        ],[
            "field_name" => "subtitle",
            "field_type" => "VARCHAR (255)"

        ]
    ];



    public function __construct(){
        parent::__construct();

	    self::$language->load('partner');
	    self::$defaultLang = LanguagesModel::getDefaultLanguage('partner');

        $this->getDataParams();
	    $this->operation = new Operation();

	    $this->operation->tableName = $this->dataParams["cName"];
    }

	public static function rules()
	{
		return [
			'title_'.self::$defaultLang => ['required']
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
			"cName" => "faq",
			"cModelName" => "FaqModel",
			"cTitle" => self::$language->get("FAQ"),
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

	public function getPost()
	{
		$languages = LanguagesModel::getLanguages("partner");

		extract($_POST);
		$array = [];
		foreach($languages as $lang){
			$title = "title_".$lang["name"];
			$array[$title] = Security::safe($$title);

			$text = "text_".$lang["name"];
			$array[$text] = Security::safe($$text);
		}

		$array["status"] = Security::safe($_POST['status']);

		return $array;
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
			$return['rows'] = $this->searchLikeFor($this->dataParams["cName"],['id','title_az','title_en','title_ru','text_az','text_en','text_ru'],$word);
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
        $rows = self::$db->select('SELECT 
        `id`,
        `title_'.$this->dataParams['defaultLang'].'`, `status` 
         FROM '.$this->dataParams['cName'].' ORDER BY `position`');
        return $rows;
    }

    public function getItemName($id)
    {
        $row = self::$db->selectOne('SELECT 
        `title_'.$this->dataParams['defaultLang'].'`
         FROM '.$this->dataParams['cName'].' WHERE `id`=:id',[':id'=> $id]);
        return $row['title_'.$this->dataParams['defaultLang']];
    }


}