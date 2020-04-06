<?php

namespace Modules\admin\Models;

use Core\Model;
use Models\LanguagesModel;
use Helpers\Security;
use Helpers\Url;
use Helpers\Validator;
use Helpers\Operation;
use Helpers\Pagination;

class MenusModel extends Model{



	public static $tableName = 'menus';  // rows which is the protected from deletion
	public static $safeMode = false;  // rows which is the protected from deletion
	public static $safeModeFields = ["safe_mode"]; // fields which helps us to detect protected rows from deletion

	public static $positionEnable=true;     // Order
	public static $positionOrderBy  = 'ASC'; // Order type
	public static $positionCondition = true;    // Is there any fields for order?
	public static $positionConditionField = ['parent_id']; // Fields that will be used for order

	public static $statusMode = true; // Status active/deactive
	public static $crudMode = true; // Operation icons

	//set place of menu true or false: up and down
	public static $posUp = true;
	public static $posDown = true;

	// set menu types
	public $menuType;

	// static page url
	public static $staticUrl = 'page';

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

	    self::$language->load('admin');
	    self::$defaultLang = LanguagesModel::getDefaultLanguage('admin');

        $this->getDataParams();
	    $this->operation = new Operation();

	    $this->operation->tableName = $this->dataParams["cName"];
    }

	public static function rules()
	{
		return [
			'title_'.self::$defaultLang => ['required'],
			'menu_type' => ['required']
		];
	}

	public static function naming()
	{
		return [
			'title_'.self::$defaultLang => self::$language->get("Menu name"),
			'menu_type' => self::$language->get("Menu type"),
		];
	}

	public function getDataParams()
	{
		$this->menuType = ["site" => self::$language->get("Existent page"), "static" => self::$language->get("Static page"), "url" => self::$language->get("Other site")];
		$this->dataParams = [
			"cName" => "menus",
			"cModelName" => "MenusModel",
			"cTitle" => self::$language->get("Menus"),
			"cStatusMode" => self::$statusMode,
			"cPositionEnable" => self::$positionEnable,
			"cCrudMode" => self::$crudMode,
			"posUp" => self::$posUp,
			"posDown" => self::$posDown,
			"menuType" => $this->menuType,
			"staticUrl" => self::$staticUrl,
			"defaultLang" => self::$defaultLang,
			"lang" => self::$language,
		];
		return $this->dataParams;
	}

	public function getPost()
	{
		$languages = LanguagesModel::getLanguages("admin");

		extract($_POST);
		$array = [];
		foreach($languages as $lang){
			$title = "title_".$lang["name"];
			$array[$title] = Security::safe($$title);
		}

		$array["url"] = Security::safe($_POST['url']);
		$array["parent_id"] = Security::safe($_POST['parent_id']);
		$array["status"] = Security::safe($_POST['status']);
		$array["tags"] = Security::safe($_POST['tags']);
		$array["meta_description"] = Security::safe($_POST['meta_description']);

		$title = "title_".self::$defaultLang;
		$array["slug"] = Url::str2Url($$title);

		if(isset($up) && intval($up) > 0) {
			$array["up"] = $up;
		} else {
			$array["up"] = 0;
		}
		if(isset($down) && intval($down) > 0) {
			$array["down"] = $down;
		} else {
			$array["down"] = 0;
		}

			$array["menu_type"] = $_POST['menu_type'];

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
		$return['menus'] = $this->getMenus();
		return $return;
	}

	public function search(){
    	$return = [];
		if($_POST){
			$word = $_POST['word'];
			$return['rows'] = $this->searchLikeFor($this->dataParams["cName"],['id','title_az','title_en','title_ru','text_az','text_en','text_ru','subtitle_az','subtitle_en','subtitle_ru','url','menu_type','slug'],$word);
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
		$return['menus'] = $this->getMenus();
		return $return;
	}

	public function getMenus()
    {
        $rows = self::$db->select('SELECT 
        `id`,
        `title_'.$this->dataParams['defaultLang'].'`, 
        `up`, `down`, `url`, `menu_type`, `parent_id`, `status` 
         FROM '.$this->dataParams['cName'].' ORDER BY `position` DESC, `id` ASC');
        return $rows;
    }

    public function getMenuName($id)
    {
        $row = self::$db->selectOne('SELECT 
        `title_'.$this->dataParams['defaultLang'].'`
         FROM '.$this->dataParams['cName'].' WHERE `id`=:id',[':id'=> $id]);
        return $row['title_'.$this->dataParams['defaultLang']];
    }

    public static function move($id, $type){
        $query = self::$db->selectOne("SELECT `position` FROM ".self::$tableName." WHERE `id`='".$id."'");
        $old_position = $query['position'];
        if($type=='up'){
            $position = $old_position+1;
        }else{
            $position = $old_position-1;
        }
        self::$db->raw("UPDATE ".self::$tableName." SET `position`='".$position."' WHERE `id` ='".$id."'");
    }

}