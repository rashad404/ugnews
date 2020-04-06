<?php
namespace Modules\user\Controllers;

use Core\Language;
use Helpers\Csrf;
use Helpers\Database;
use Helpers\Pagination;
use Helpers\Session;
use Modules\user\Models\UserModel;
use Modules\user\Models\WorkordersModel;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;

class Workorders extends MyController{

    public static $params = [
        'name' => 'workorders',
        'searchFields' => ['id','text'],
        'title' => 'Work orders',
        'position' => true,
        'status' => true,
        'actions' => true,
        'imageSizeX' => '750',
        'imageSizeY' => '500',
        'thumbSizeX' => '250',
        'thumbSizeY' => '165',
    ];


    public static $model;
    public static $lng;
    public static $def_language;
    public static $rules;
    public static $user_id;

    public function __construct(){
        self::$def_language = LanguagesModel::getDefaultLanguage('user');
        self::$lng = new Language();
        self::$lng->load('user');
        self::$rules = ['first_name' => ['required']];
        self::$model = new WorkordersModel(self::$params);
        self::$user_id = Session::get('user_session_id');
        parent::__construct();
    }

    public function index(){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $data['list'] = $model::search();
            $pagination = new Pagination();
            $data['pagination'] = $pagination;
        }else {

            $pagination = new Pagination();
            $pagination->limit = 30;
            $data['pagination'] = $pagination;
            $limitSql = $pagination->getLimitSql($model::countList());
            $data['list'] = $model::getList($limitSql);
        }
        $data['lng'] = self::$lng;
        $data['params'] = self::$params;
        View::renderUser(self::$params['name'].'/index',$data);
    }

    public function add(){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model::add();
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Data has been saved successfully'));
                Url::redirect("user/".self::$params["name"]);
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        $data['user_info'] = UserModel::getItem(self::$user_id);
        $data['input_list'] = $model::getInputs();
        $data['params'] = self::$params;
        $data['item'] = '';
        $data['lng'] = self::$lng;
        View::renderUser(self::$params["name"].'/'.__FUNCTION__,$data);
    }

    public function update($id){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model::update($id);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Data has been saved successfully'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        $data['input_list'] = $model::getInputs();
        $data['params'] = self::$params;
        $data['item'] = $model::getItem($id);
        $data['lng'] = self::$lng;
        View::renderUser(self::$params["name"].'/'.__FUNCTION__,$data);
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
        $sql = Database::get()->select("SELECT * FROM `".$table."` WHERE ".$sql_s);
        return $sql;

    }

    public function up($id){
        $model = self::$model;
        $model::move($id,'up');
        Url::previous("user/".self::$params['name']);
    }
    public function down($id){
        $model = self::$model;
        $model::move($id,'down');
        Url::previous("user/".self::$params['name']);
    }
    public function status($id){
        $model = self::$model;
        $model::statusToggle($id);
        Url::previous("user/".self::$params['name']);
    }
    public function delete($id){
        $model = self::$model;
        $model::delete([$id]);
        Url::previous("user/".self::$params['name']);
    }
    public function operation(){
        $model = self::$model;
        if(isset($_POST["row_check"])){
            if(isset($_POST["delete"])){
                $model::delete();
            }elseif(isset($_POST["active"])){
                $model::status(1);
            }elseif(isset($_POST["deactive"])){
                $model::status(0);
            }
        }else{
            Session::setFlash('error','Please choose an action');
        }
        return Url::previous("user/".$this->dataParams["cName"].'/album');
    }


}