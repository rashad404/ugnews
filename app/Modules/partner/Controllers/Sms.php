<?php
namespace Modules\partner\Controllers;

use Core\Language;
use Helpers\Cookie;
use Helpers\Csrf;
use Helpers\Session;
use Models\LanguagesModel;
use Core\View;
use Modules\partner\Models\CustomersModel;
use Modules\partner\Models\SmsModel;
use Modules\partner\Models\TenantsModel;

class Sms extends MyController{

    public static $params = [
        'name' => 'sms',
        'title' => 'Sms Messages',
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
        self::$model = new SmsModel();

        new CustomersModel();
        new TenantsModel();
        parent::__construct();
    }

    public function index(){
        if(isset($_POST['list_order'])){
            Cookie::set('list_order', $_POST['list_order']);
            $data['list_order'] = $_POST['list_order'];
        }elseif(Cookie::has('list_order')){
            $data['list_order'] = Cookie::get('list_order');
        }else{
            $data['list_order'] = 'recent';
        }
//        echo $data['list_order'];exit;
        $model = self::$model;
        $data['list'] = $model::getAllChats($data['list_order']);
        $data['params'] = self::$params;
        $data['lng'] = self::$lng;
        View::renderPartner(self::$params['name'].'/'.__FUNCTION__,$data);
    }

    public function send(){

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = SmsModel::send(0);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('SMS successfully sent'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }

        $data['guest_list'] = CustomersModel::getList('LIMIT 0,10000');
        $data['tenant_list'] = TenantsModel::getList('LIMIT 0,10000');
        $data['params'] = self::$params;
        $data['lng'] = self::$lng;
        $data['postData'] = ['guest_id'=>'', 'tenant_id'=>''];
        View::renderPartner(self::$params['name'].'/'.__FUNCTION__,$data);
    }

    public function bulksend(){

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = SmsModel::bulkSend();
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('SMS successfully sent'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }

        $data['bulk_options'] = SmsModel::getBulkOptions();
        $data['params'] = self::$params;
        $data['lng'] = self::$lng;
        $data['postData'] = ['guest_id'=>'', 'tenant_id'=>''];
        View::renderPartner(self::$params['name'].'/'.__FUNCTION__,$data);
    }


}