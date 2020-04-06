<?php
namespace Modules\user\Controllers;

use Core\Language;
use Helpers\Csrf;
use Helpers\Session;
use Models\LanguagesModel;
use Core\View;
use Modules\user\Models\ProfileModel;

class Profile extends MyController{

    public static $params = [
        'name' => 'profile',
        'searchFields' => ['id','first_name','last_name','phone','email'],
        'title' => 'Profile',
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
        self::$def_language = LanguagesModel::getDefaultLanguage('admin');
        self::$lng = new Language();
        self::$lng->load('user');
        self::$rules = ['first_name' => ['required']];
        self::$model = new ProfileModel(self::$params);
        self::$user_id = Session::get('user_session_id');
        parent::__construct();
    }


    public function update(){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model::update(self::$user_id);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Data has been saved successfully'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        $data['input_list'] = $model::getInputs();
        $data['params'] = self::$params;



        $data['item'] = $model::getItem(self::$user_id);
        $data['lng'] = self::$lng;
        View::renderUser(self::$params["name"].'/'.__FUNCTION__,$data);
    }




}