<?php
namespace Modules\user\Controllers;

use Core\Language;
use Dompdf\Dompdf;
use Helpers\Csrf;
use Helpers\Pagination;
use Helpers\Session;
use Modules\user\Models\LeasesModel;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;
use Modules\user\Models\UserModel;

class Leases extends MyController{

    public static $params = [
        'name' => 'leases',
        'title' => 'Leases',
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
        self::$user_id = Session::get('user_session_id');
        self::$model = new LeasesModel(self::$params);
        parent::__construct();
    }

    protected static function lease_html($id){
        $model = self::$model;
        $data['lease_pages'] = $model::getPages($id);
        $data['item'] = $model::getItem($id);

        if($data['item']['user_sign']==0)exit;
        if($data['item']['user_id']!=self::$user_id)exit;

        $data['lng'] = self::$lng;
        $data['params'] = self::$params;

        ob_start();
        View::renderPdf(self::$params['name'].'/html_lease',$data);
        $html = ob_get_contents();
        ob_end_clean();

        $css =  file_get_contents('app/Modules/user/templates/main/css/lease.css');

        $html .= '<style>body{font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
                    font-size: 14px;
                    line-height: 1.42857143;
                    color: #333;
                    background-color: #fff;} '.$css.'</style>';

        return $html;
    }

    public function download_lease($id){
        $html = self::lease_html($id);
        if(strlen($html)<100)exit;

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('lease.pdf');

//        $output = $dompdf->output();
//        file_put_contents('filename.pdf', $output);
    }

    public function view_lease($id){
        $html = self::lease_html($id);
        echo $html;
    }

    public function index(){
        $model = self::$model;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $data['list'] = $model::search();
            $pagination = new Pagination();
            $data['pagination'] = $pagination;
        }else {

            $pagination = new Pagination();
            $pagination->limit = 30;
            $data['pagination'] = $pagination;
            $limitSql = $pagination->getLimitSql($model::countList());
            $data['list'] = $model::getList($limitSql);
        }
        $data['lng'] = self::$lng;
        $data['params'] = self::$params;
        View::renderUser(self::$params['name'].'/'.__FUNCTION__,$data);
    }

    public function view($id, $page_id=0){

        $model = self::$model;

        $data['lease_pages'] = $model::getPages($id);
        $data['item'] = $model::getItem($id);
        if($data['item']['user_id']!=self::$user_id)exit;
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