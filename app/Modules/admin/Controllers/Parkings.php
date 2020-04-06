<?php
namespace Modules\admin\Controllers;

use Core\Language;
use Helpers\Csrf;
use Helpers\Session;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;
use Modules\admin\Models\ParkingsModel;

class Parkings extends MyController
{

    public  $params = [
        'name' => 'parkings',
        'searchFields' => ['id','name'],
        'title' => 'Parkings',
        'position' => false,
        'status' => true,
        'actions' => true,
    ];

    public static $model;
    public static $lng;
    public static $def_language;
    public static $rules;
    public static $admin_role;

    public function __construct(){
        self::$def_language = LanguagesModel::getDefaultLanguage('admin');
        self::$admin_role = Session::get('auth_session_role');
        self::$lng = new Language();
        self::$lng->load('admin');
        self::$rules = ['first_name' => ['required']];
        self::$model = new ParkingsModel($this->params);
        if(self::$admin_role!=1){
            $new_params = $this->params;
            $new_params['actions'] = false;
            $this->params = $new_params;
        }
        parent::__construct();
    }

    public function index(){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $data['list'] = $model::search();
        }else{
            $data['list'] = $model::getList();
        }
        $data['params'] = $this->params;
        $data['inputs'] = $model::getInputs();
        $data['lng'] = self::$lng;
        View::renderModule($this->params['name'].'/index',$data);
    }

    public function apartment($apt_id){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $data['list'] = $model::search();
        }else{
            $data['list'] = $model::getListByApt($apt_id);
        }
        $data['params'] = $this->params;;
        $data['inputs'] = $model::getInputs();
        $data['lng'] = self::$lng;
        $data['apt_id'] = $apt_id;
        View::renderModule($this->params['name'].'/index',$data);
    }

    public function add($apt_id){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model::add($apt_id);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Data has been saved successfully'));
                Url::redirect(MODULE_ADMIN."/".$this->params["name"].'/index/'.$apt_id);
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        $data['input_list'] = $model::getInputs($apt_id);
        $data['params'] = $this->params;;
        $data['item'] = '';
        $data['lng'] = self::$lng;
        $data['apt_id'] = $apt_id;
        View::renderModule($this->params["name"].'/'.__FUNCTION__,$data);
    }

    public function update($bed_id){
        $model = self::$model;
        $data['params'] = $this->params;;
        $data['item'] = $model::getItem($bed_id);
        $data['input_list'] = $model::getInputs($data['item']['apt_id']);
        $data['lng'] = self::$lng;
        $data['apt_id'] = $data['item']['apt_id'];

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model::update($bed_id);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Data has been saved successfully'));
                Url::redirect(MODULE_ADMIN."/".$this->params["name"].'/index/'.$data['apt_id']);
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        View::renderModule($this->params["name"].'/'.__FUNCTION__,$data);
    }

    public function up($id){
        $model = self::$model;
        $model::move($id,'up');
        Url::previous(MODULE_ADMIN."/".$this->params['name']);
    }
    public function down($id){
        $model = self::$model;
        $model::move($id,'down');
        Url::previous(MODULE_ADMIN."/".$this->params['name']);
    }
    public function status($id){
        $model = self::$model;
        $model::statusToggle($id);
        Url::previous(MODULE_ADMIN."/".$this->params['name']);
    }
    public function delete($id){
        $model = self::$model;
        $model::delete([$id]);
        Url::previous(MODULE_ADMIN."/".$this->params['name']);
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