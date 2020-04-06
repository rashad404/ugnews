<?php
namespace Modules\partner\Controllers;

use Core\Language;
use Helpers\Csrf;
use Helpers\Database;
use Helpers\Pagination;
use Helpers\Security;
use Helpers\Session;
use Models\MessagesModel;
use Modules\partner\Models\ApartmentsModel;
use Modules\partner\Models\BalanceModel;
use Modules\partner\Models\BedsModel;
use Modules\partner\Models\LeasesModel;
use Modules\partner\Models\RoomsModel;
use Modules\partner\Models\SmsModel;
use Modules\partner\Models\TenantsModel;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;

class Tenants extends MyController{

    public static $params = [
        'name' => 'tenants',
        'searchFields' => ['id','first_name','last_name','phone','email'],
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
        self::$def_language = LanguagesModel::getDefaultLanguage('partner');
        self::$lng = new Language();
        self::$lng->load('partner');
        self::$rules = ['first_name' => ['required']];
        self::$model = new TenantsModel(self::$params);
        new BalanceModel();
        new SmsModel();
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
        View::renderPartner(self::$params['name'].'/index',$data);
    }


    public function setpasswords(){
        $model = self::$model;
        $model::setPasswords();
    }

    public function activateportalall(){
        $model = self::$model;
        $model::activatePortalAll();
    }
    public function showpass(){
        $model = self::$model;
        $model::showPass();
    }

    public function active(){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $data['list'] = $model::searchActive();
            $pagination = new Pagination();
            $data['pagination'] = $pagination;
        }else {

            $pagination = new Pagination();
            $pagination->limit = 30;
            $data['pagination'] = $pagination;
            $limitSql = $pagination->getLimitSql($model::countListActive());
            $data['list'] = $model::getListActive($limitSql);
        }
        $data['lng'] = self::$lng;
        $data['params'] = self::$params;
        View::renderPartner(self::$params['name'].'/active',$data);
    }

    public function view($id){

        $data['item'] = TenantsModel::getItem($id);
        $data['item']['apt_name'] = '';
        $data['item']['room_name'] = '';
        $data['item']['bed_name'] = '';
        $data['item']['apt_address'] = '';
        if($data['item']['apt_id']>0)$data['item']['apt_name'] = ApartmentsModel::getName($data['item']['apt_id']);
        if($data['item']['apt_id']>0)$data['item']['apt_address'] = ApartmentsModel::getAddress($data['item']['apt_id']);
        if($data['item']['room_id']>0)$data['item']['room_name'] = RoomsModel::getName($data['item']['room_id']);
        if($data['item']['bed_id']>0)$data['item']['bed_name'] = BedsModel::getName($data['item']['bed_id']);
        $data['lng'] = self::$lng;
        $data['params'] = self::$params;
        $data['balance_logs'] = BalanceModel::getUserLogs($id);

        if(isset($_POST['log_id'])){$log_id=intval($_POST['log_id']);}else{$log_id=0;}

        if(isset($_POST['csrf_token'.$log_id]) && Csrf::isTokenValid($log_id)){
            $modelArray = BalanceModel::sendReceipt(intval($_POST['log_id']));
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Receipt successfully sent'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }

        if(isset($_POST['csrf_tokensms']) && Csrf::isTokenValid('sms')){
            $modelArray = SmsModel::send($id);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('SMS successfully sent'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        $data['sms_list'] = SmsModel::getList($id);

        View::renderPartner(self::$params['name'].'/'.__FUNCTION__,$data);
    }

    public function view_portal($id){

        Session::set('user_session_id', $id);
        $pass = TenantsModel::getPass($id);
        Session::set("user_session_pass", Security::session_password($pass));
        Url::redirect('user');
    }

    public function add(){

        $model = self::$model;
//        echo $_POST['csrf_token'].' ||| ';
//        echo Session::get('csrf_token');

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
        $model = new TenantsModel(['user_id'=>$id]);

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model::update($id);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Data has been saved successfully'));
                Url::redirect(MODULE_PARTNER."/".self::$params["name"]);
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