<?php
namespace Modules\user\Controllers;

use Core\Language;
use Dompdf\Dompdf;
use Helpers\Csrf;
use Helpers\Pagination;
use Helpers\Session;
use Modules\user\Models\HousematesModel;
use Modules\user\Models\HouseRulesModel;
use Modules\user\Models\LeasesModel;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;
use Modules\user\Models\UserModel;

class Houserules extends MyController{

    public static $params = [
        'name' => 'houserules',
        'title' => 'House Rules',
    ];


    public static $model;
    public static $lng;
    public static $rules;
    public static $user_id;

    public function __construct(){
        self::$lng = new Language();
        self::$lng->load('partner');
        self::$model = new HouseRulesModel();
        self::$user_id = Session::get('user_session_id');
        parent::__construct();
    }


    public function index(){
        $model = self::$model;
        $data['item'] = $model::getItem();
        $data['user_id'] = self::$user_id;

        $data['lng'] = self::$lng;
        $data['params'] = self::$params;
        View::renderUser(self::$params['name'].'/'.__FUNCTION__,$data);
    }


}