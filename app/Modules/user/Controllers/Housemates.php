<?php
namespace Modules\user\Controllers;

use Core\Language;
use Dompdf\Dompdf;
use Helpers\Csrf;
use Helpers\Pagination;
use Helpers\Session;
use Modules\user\Models\HousematesModel;
use Modules\user\Models\LeasesModel;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;
use Modules\user\Models\UserModel;

class Housemates extends MyController{

    public static $params = [
        'name' => 'housemates',
        'title' => 'Housemates',
    ];


    public static $model;
    public static $lng;
    public static $def_language;
    public static $rules;
    public static $user_id;

    public function __construct(){
        self::$def_language = LanguagesModel::getDefaultLanguage('partner');
        self::$lng = new Language();
        self::$lng->load('partner');
        self::$rules = ['first_name' => ['required']];
        self::$model = new HousematesModel(self::$params);
        self::$user_id = Session::get('user_session_id');
        parent::__construct();
    }


    public function index(){
        $model = self::$model;
        $data['list'] = $model::getList();
        $data['user_id'] = self::$user_id;

        $data['lng'] = self::$lng;
        $data['params'] = self::$params;
        View::renderUser(self::$params['name'].'/'.__FUNCTION__,$data);
    }

    public function view($id, $page_id=0){

        $model = self::$model;

        $data['lease_pages'] = $model::getPages($id);
        $data['item'] = $model::getItem($id);
        $data['page'] = $model::getPage($id, $page_id);
        $page_id = $data['page']['id'];
        $data['page_id'] = $page_id;
        $next_page = intval(LeasesModel::getNextPage($page_id));
        $previous_page = intval(LeasesModel::getPreviousPage($page_id));
        $data['next_page'] = $next_page;
        $data['previous_page'] = $previous_page;

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = LeasesModel::sign($page_id);
            if(empty($modelArray['errors'])){
//                Session::setFlash('success',self::$lng->get('Successfully updated'));

                if($next_page>0){
                    Url::redirect('user/leases/view/' . $id . '/' . $next_page);
                }
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        if(isset($_POST['csrf_tokenfinal']) && Csrf::isTokenValid('final')){
            LeasesModel::sign($page_id);
            $modelArray = LeasesModel::signFinal($id);
            if(empty($modelArray['errors'])){
//                Session::setFlash('success',self::$lng->get('You have successfully signed the lease'));
                $data['item'] = $model::getItem($id);
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }

        $data['lng'] = self::$lng;
        $data['params'] = self::$params;


        View::renderUser(self::$params['name'].'/'.__FUNCTION__,$data);
    }

}