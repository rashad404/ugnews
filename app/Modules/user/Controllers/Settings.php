<?php
namespace Modules\user\Controllers;

use Core\Language;
use Dompdf\Dompdf;
use Helpers\Csrf;
use Helpers\Session;
use Core\View;
use Modules\user\Models\SettingsModel;

class Settings extends MyController{

    public static $params = [
        'name' => 'settings',
        'title' => 'Settings',
    ];


    public static $model;
    public static $lng;
    public static $rules;
    public static $user_id;

    public function __construct(){
        self::$lng = new Language();
        self::$lng->load('partner');
        self::$model = new SettingsModel();
        self::$user_id = Session::get('user_session_id');
        parent::__construct();
    }


    public function privacy(){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model::update(self::$user_id);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Data has been saved successfully'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        $data['item'] = $model::getItem();
        $data['user_id'] = self::$user_id;

        $data['lng'] = self::$lng;
        $data['params'] = self::$params;
        View::renderUser(self::$params['name'].'/'.__FUNCTION__,$data);
    }


}