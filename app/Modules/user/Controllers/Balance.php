<?php
namespace Modules\user\Controllers;

use Core\Language;
use Helpers\Session;
use Modules\user\Models\BalanceModel;
use Core\View;

class Balance extends MyController{

    public static $params = [
        'name' => 'balance',
        'searchFields' => ['id','text'],
        'title' => 'Payment History',
        'position' => true,
        'status' => true,
        'actions' => true,
    ];


    public static $lng;
    public static $user_id;

    public function __construct(){
        self::$lng = new Language();
        self::$lng->load('user');
        self::$user_id = Session::get('user_session_id');
        parent::__construct();
        new BalanceModel();
    }

    public function index(){
        $data['lng'] = self::$lng;
        $data['params'] = self::$params;

        $data['balance_logs'] = BalanceModel::getLogs();
        View::renderUser(self::$params['name'].'/'.__FUNCTION__, $data);
    }


}