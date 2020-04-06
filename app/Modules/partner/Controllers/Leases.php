<?php
namespace Modules\partner\Controllers;

use Core\Language;
use Helpers\Console;
use Helpers\Csrf;
use Helpers\Database;
use Helpers\Pagination;
use Helpers\Security;
use Helpers\Session;
use Models\MessagesModel;
use Modules\partner\Models\ApartmentsModel;
use Modules\partner\Models\ApplicationsModel;
use Modules\partner\Models\BalanceModel;
use Modules\partner\Models\BedsModel;
use Modules\partner\Models\LeasesModel;
use Modules\partner\Models\RoomsModel;
use Modules\partner\Models\SmsModel;
use Modules\partner\Models\TenantsModel;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;

class Leases extends MyController{

    public static $params = [
        'name' => 'leases',
        'searchFields' => ['id','first_name','last_name','phone','email'],
        'title' => 'Leases',
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
        self::$model = new LeasesModel(self::$params);
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
        View::renderPartner(self::$params['name'].'/'.__FUNCTION__,$data);
    }

    public function view($id, $page_id=0){
        $model = self::$model;

        $data['lease_id'] = $model::prepareLease($id);
        if($data['lease_id']==0){
            Session::setFlash('error',self::$lng->get('Wrong user'));
            exit;
        }


        if(isset($_POST['csrf_token'.'send_lease']) && Csrf::isTokenValid('send_lease')){
            $modelArray = LeasesModel::send($data['lease_id']);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Lease successfully sent'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = LeasesModel::updateLease($data['lease_id']);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Successfully updated'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }

        $data['user_id'] = $id;
        $data['lease_pages'] = $model::getPages($data['lease_id']);
        $data['item'] = $model::getItem($data['lease_id']);

        $data['page'] = $model::getPage($data['lease_id'], $page_id);

        $page_id = $data['page']['id'];
        $data['page_id'] = $page_id;
        $next_page = intval(LeasesModel::getNextPage($id, $page_id));
        $previous_page = intval(LeasesModel::getPreviousPage($id, $page_id));
        $data['next_page'] = $next_page;
        $data['previous_page'] = $previous_page;

        $data['bed_list'] = $model::getBedOptions();
        $data['app_info'] = ApplicationsModel::getItemByUser($id);


        $data['lng'] = self::$lng;
        $data['params'] = self::$params;


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
        $model::delete($id);
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