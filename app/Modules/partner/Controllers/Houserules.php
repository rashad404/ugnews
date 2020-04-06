<?php
namespace Modules\partner\Controllers;

use Core\Language;
use Helpers\Csrf;
use Helpers\Database;
use Helpers\Pagination;
use Helpers\Session;
use Modules\partner\Models\HouseRulesModel;
use Modules\partner\Models\LeasetemplatesModel;
use Modules\partner\Models\NoticetemplatesModel;
use Modules\partner\Models\TenantsModel;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;

class Houserules extends MyController{

    public static $params = [
        'name' => 'houserules',
        'searchFields' => ['id','title','text'],
        'title' => 'House Rules',
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
        self::$model = new HouseRulesModel(self::$params);
        parent::__construct();
    }


    public function index(){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()) {
            $modelArray = $model::update();
            if (empty($modelArray['errors'])) {
                Session::setFlash('success', self::$lng->get('Data has been saved successfully'));
            } else {
                Session::setFlash('error', $modelArray['errors']);
            }
        }
        $data['input_list'] = $model::getInputs();
        $data['params'] = self::$params;
        $data['item'] = $model::getItem();
        $data['lng'] = self::$lng;
        View::renderPartner(self::$params["name"].'/'.__FUNCTION__,$data);
    }



}