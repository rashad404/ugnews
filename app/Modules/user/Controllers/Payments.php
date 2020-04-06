<?php
namespace Modules\user\Controllers;

use Core\Language;
use Helpers\Csrf;
use Helpers\Pagination;
use Helpers\Session;
use Helpers\Url;
use Models\PartnerModel;
use Modules\user\Models\BalanceModel;
use Core\View;
use Modules\user\Models\PaymentsModel;
use Modules\user\Models\UserModel;

class Payments extends MyController{

    public static $params = [
        'name' => 'payments',
        'searchFields' => ['id','text'],
        'title' => 'Payment',
        'position' => true,
        'status' => true,
        'actions' => true,
    ];


    public static $lng;
    public static $user_id;
    public static $model;
    public static $partner_id;

    public function __construct(){
        self::$lng = new Language();
        self::$lng->load('user');
        self::$user_id = Session::get('user_session_id');
        self::$model = new PaymentsModel(self::$params);

        $user_info = UserModel::getItem(self::$user_id);
        self::$partner_id = $user_info['partner_id'];

        parent::__construct();
        new BalanceModel();
    }

    public function index(){
        $data['lng'] = self::$lng;
        $data['params'] = self::$params;
        $data['partner_info'] = PartnerModel::getInfo(self::$partner_id);
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model::pay();
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Payment has been made successfully'));
                Url::redirect("user/balance");
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }

        View::renderUser(self::$params['name'].'/'.__FUNCTION__, $data);
    }


}