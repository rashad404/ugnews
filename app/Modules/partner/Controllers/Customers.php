<?php
namespace Modules\partner\Controllers;

use Core\Language;
use Helpers\Csrf;
use Helpers\Database;
use Helpers\Date;
use Helpers\Session;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;
use Modules\partner\Models\ApartmentsModel;
use Modules\partner\Models\BedsModel;
use Modules\partner\Models\CustomersModel;
use Modules\partner\Models\RoomsModel;
use Modules\partner\Models\SmsModel;
use Modules\partner\Models\TenantsModel;

class Customers extends MyController{

    public static $params = [
        'name' => 'customers',
        'searchFields' => ['id','first_name','last_name','father_name','source','email','phone','note'],
        'title' => 'Guest Cards',
        'position' => false,
        'status' => true,
        'actions' => true,
    ];

    public static $model;
    public static $lng;
    public static $def_language;
    public static $rules;

    public function __construct(){
        self::$def_language = LanguagesModel::getDefaultLanguage('partner');
        self::$lng = new Language();
        self::$lng->load('partner');

        self::$rules = ['name' => ['required']];
        self::$model = new CustomersModel();
        new SmsModel();
        parent::__construct();
    }

    public function index(){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $data['list'] = $model::search();
        }else{
            $data['list'] = $model::getList();
        }
        $data['params'] = self::$params;
        $data['inputs'] = $model::getInputs();
        $data['lng'] = self::$lng;
        View::renderPartner(self::$params['name'].'/index',$data);
    }

    public function view($id)
    {
        $model = self::$model;
        $data['params'] = self::$params;
        $data['lng'] = self::$lng;
        $data['item'] = CustomersModel::getItem($id);

        $data['item']['apt_name'] = '';
        $data['item']['room_name'] = '';
        $data['item']['bed_name'] = '';
        $data['item']['apt_address'] = '';

        if($data['item']['apt_id']>0)$data['item']['apt_name'] = ApartmentsModel::getName($data['item']['apt_id']);
        if($data['item']['apt_id']>0)$data['item']['apt_address'] = ApartmentsModel::getAddress($data['item']['apt_id']);

        if(isset($_POST['csrf_tokensms']) && Csrf::isTokenValid('sms')){
            $modelArray = SmsModel::send($id,1);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('SMS successfully sent'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        $data['sms_list'] = SmsModel::getList($id,1);

        View::renderPartner(self::$params["name"].'/'.__FUNCTION__,$data);
    }

    public function add(){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model::add();
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Data has been saved successfully'));
                Url::redirect(MODULE_PARTNER."/".self::$params["name"]);
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        $data['input_list'] = $model::getInputs();
        $data['params'] = self::$params;
        $data['item'] = '';
        $data['lng'] = self::$lng;
        View::renderPartner(self::$params["name"].'/'.__FUNCTION__,$data);
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
        View::renderPartner(self::$params["name"].'/'.__FUNCTION__,$data);
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
        Url::previous(MODULE_PARTNER."/".self::$params['name']);
    }
    public function down($id){
        $model = self::$model;
        $model::move($id,'down');
        Url::previous(MODULE_PARTNER."/".self::$params['name']);
    }
    public function status($id){
        $model = self::$model;
        $model::statusToggle($id);
        Url::previous(MODULE_PARTNER."/".self::$params['name']);
    }
    public function delete($id){
        $model = self::$model;
        $model::delete([$id]);
        Url::previous(MODULE_PARTNER."/".self::$params['name']);
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
        return Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"].'/album');
    }


}