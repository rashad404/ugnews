<?php
namespace Modules\admin\Controllers;

use Core\Language;
use Helpers\Csrf;
use Helpers\Database;
use Helpers\Pagination;
use Helpers\Session;
use Modules\admin\Models\BalanceModel;
use Modules\admin\Models\NoticesModel;
use Modules\admin\Models\NoticetemplatesModel;
use Modules\admin\Models\TenantsModel;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;

class Balance extends MyController{

    public static $params = [
        'name' => 'balance',
        'searchFields' => ['id','note'],
        'title' => 'Tenants',
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

    public function __construct(){
        self::$def_language = LanguagesModel::getDefaultLanguage('admin');
        self::$lng = new Language();
        self::$lng->load('admin');
        self::$rules = ['first_name' => ['required']];
        self::$model = new BalanceModel(self::$params);
        parent::__construct();
    }

    public function add_charge($id){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model::addCharge($id);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Data has been saved successfully'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }

        $data['item'] = TenantsModel::getItem($id);
        $data['lng'] = self::$lng;
        $data['params'] = self::$params;
        View::renderModule(self::$params['name'].'/'.__FUNCTION__,$data);
    }

    public function add(){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model::add();
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Data has been saved successfully'));
                Url::redirect(MODULE_ADMIN."/".self::$params["name"]);
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        $data['input_list'] = $model::getInputs();
        $data['params'] = self::$params;
        $data['item'] = '';
        $data['lng'] = self::$lng;
        View::renderModule(self::$params["name"].'/'.__FUNCTION__,$data);
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
        View::renderModule(self::$params["name"].'/'.__FUNCTION__,$data);
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
        Url::previous(MODULE_ADMIN."/".self::$params['name']);
    }
    public function down($id){
        $model = self::$model;
        $model::move($id,'down');
        Url::previous(MODULE_ADMIN."/".self::$params['name']);
    }
    public function status($id){
        $model = self::$model;
        $model::statusToggle($id);
        Url::previous(MODULE_ADMIN."/".self::$params['name']);
    }
    public function delete($id){
        $model = self::$model;
        $model::delete([$id]);
        Url::previous(MODULE_ADMIN."/".self::$params['name']);
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
        return Url::previous(MODULE_ADMIN."/".$this->dataParams["cName"].'/album');
    }


}