<?php
namespace Modules\partner\Controllers;

use Core\Language;
use Helpers\Console;
use Helpers\Csrf;
use Helpers\Operation;
use Helpers\Session;
use Modules\partner\Models\BedsModel;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;

class Beds extends MyController
{

    public static $params = [
        'name' => 'beds',
        'searchFields' => ['id','name'],
        'title' => 'Beds',
        'position' => true,
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
        self::$rules = ['first_name' => ['required']];
        self::$model = new BedsModel(self::$params);
        parent::__construct();
    }


    public function index($apt_id){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $data['list'] = $model::search();
        }else{
            $data['list'] = $model::getList($apt_id);
        }
        $data['params'] = self::$params;
        $data['inputs'] = $model::getInputs();
        $data['lng'] = self::$lng;
        $data['apt_id'] = $apt_id;
        View::renderPartner(self::$params['name'].'/index',$data);
    }

    public function add($apt_id){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model::add($apt_id);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Data has been saved successfully'));
                Url::redirect(MODULE_PARTNER."/".self::$params["name"].'/index/'.$apt_id);
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        $data['input_list'] = $model::getInputs($apt_id);
        $data['params'] = self::$params;
        $data['item'] = '';
        $data['lng'] = self::$lng;
        $data['apt_id'] = $apt_id;
        View::renderPartner(self::$params["name"].'/'.__FUNCTION__,$data);
    }

    public function update($bed_id){
        $model = self::$model;
        $data['params'] = self::$params;
        $data['item'] = $model::getItem($bed_id);
        $data['input_list'] = $model::getInputs($data['item']['apt_id']);
        $data['lng'] = self::$lng;
        $data['apt_id'] = $data['item']['apt_id'];

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model::update($bed_id);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Data has been saved successfully'));
                Url::redirect(MODULE_PARTNER."/".self::$params["name"].'/index/'.$data['apt_id']);
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        View::renderPartner(self::$params["name"].'/'.__FUNCTION__,$data);
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