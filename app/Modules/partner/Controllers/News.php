<?php
namespace Modules\partner\Controllers;

use Core\Language;
use Helpers\Csrf;
use Helpers\Database;
use Helpers\Pagination;
use Helpers\Security;
use Helpers\Session;
use Modules\partner\Models\BalanceModel;
use Modules\partner\Models\ChannelsModel;
use Modules\partner\Models\SmsModel;
use Modules\partner\Models\NewsModel;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;

class News extends MyController{



    public static $model;
    public static $lng;
    public static $def_language;
    public static $rules;
    public static $params;

    public function __construct(){
        self::$def_language = LanguagesModel::getDefaultLanguage('partner');
        self::$lng = new Language();
        self::$lng->load('partner');
        self::$rules = ['first_name' => ['required']];


        self::$params = [
            'name' => 'news',
            'searchFields' => ['id','title','text'],
            'title' => self::$lng->get('News'),
            'position' => true,
            'status' => true,
            'actions' => true,
            'imageSizeX' => '730',
            'imageSizeY' => '450',
            'thumbSizeX' => '640',
            'thumbSizeY' => '340',
        ];

        parent::__construct();
        self::$model = new NewsModel(self::$params);
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
        new ChannelsModel();
        $data['channel_count'] = ChannelsModel::countList();
        $data['lng'] = self::$lng;
        $data['params'] = self::$params;
        View::renderPartner(self::$params['name'].'/index',$data);
    }


    public function upload_image(){
        $accepted_origins = array("http://ug.loc", "https://ug.news");

        $imageFolder = "Web/uploads/redactor/images/";

        reset ($_FILES);
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])){
            if (isset($_SERVER['HTTP_ORIGIN'])) {
                // same-origin requests won't set an origin. If the origin is set, it must be valid.
                if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
                    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
                } else {
                    header("HTTP/1.1 403 Origin Denied");
                    return;
                }
            }


            // Sanitize input
            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                header("HTTP/1.1 400 Invalid file name.");
                return;
            }

            // Verify extension
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.1 400 Invalid extension.");
                return;
            }

            // Accept upload if there was no origin, or if it is an accepted origin
            $filetowrite = $imageFolder . $temp['name'];
            move_uploaded_file($temp['tmp_name'], $filetowrite);

            $filetowrite = 'https://ug.news/Web/uploads/redactor/images/'.$temp['name'];
            echo json_encode(array('location' => $filetowrite));
        } else {
            // Notify editor that the upload failed
            header("HTTP/1.1 500 Server Error");
        }

    }



    public function view($id){

        $data['item'] = NewsModel::getItem($id);
        $data['lng'] = self::$lng;
        $data['params'] = self::$params;

        if(isset($_POST['log_id'])){$log_id=intval($_POST['log_id']);}else{$log_id=0;}

        if(isset($_POST['csrf_token'.$log_id]) && Csrf::isTokenValid($log_id)){
            $modelArray = BalanceModel::sendReceipt(intval($_POST['log_id']));
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Receipt successfully sent'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }

        if(isset($_POST['csrf_tokensms']) && Csrf::isTokenValid('sms')){
            $modelArray = SmsModel::send($id);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('SMS successfully sent'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }

        View::renderPartner(self::$params['name'].'/'.__FUNCTION__,$data);
    }

    public function view_portal($id){

        Session::set('user_session_id', $id);
        $pass = NewsModel::getPass($id);
        Session::set("user_session_pass", Security::session_password($pass));
        Url::redirect('user');
    }

    public function add(){

        $model = self::$model;
//        echo $_POST['csrf_token'].' ||| ';
//        echo Session::get('csrf_token');

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model::add();
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Data has been saved successfully'));
                Url::redirect(MODULE_PARTNER."/".self::$params["name"]);
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        $data['input_list'] = $model::getInputs();
        $data['params'] = self::$params;
        $data['item'] = '';
        $data['lng'] = self::$lng;
        View::renderPartner(self::$params["name"].'/'.__FUNCTION__,$data);
    }

    public function update($id){
        $model = self::$model;

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model::update($id);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Data has been saved successfully'));
                Url::redirect(MODULE_PARTNER."/".self::$params["name"]);
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        $data['input_list'] = $model::getInputs();
        $data['params'] = self::$params;
        $data['item'] = $model::getItem($id);
        $data['lng'] = self::$lng;
        View::renderPartner(self::$params["name"].'/'.__FUNCTION__,$data);
    }


    public function searchLikeFor($table, $values, $search_word){

        $sql_s = ''; // Stop errors when $words is empty
        if($values<=1){
            $sql_s = "`".$values."` LIKE '%".$search_word."%' ";
        } else {
            foreach($values as $value){
                $sql_s .= "`".$value."` LIKE '%".$search_word."%' OR ";
            }
            $sql_s = substr($sql_s,0,-3);
        }
        $sql = Database::get()->select("SELECT * FROM `".$table."` WHERE ".$sql_s);
        return $sql;

    }

    public function up($id){
        $model = self::$model;
        $model::move($id,'up');
        Url::previous(MODULE_PARTNER."/".self::$params['name']);
    }
    public function down($id){
        $model = self::$model;
        $model::move($id,'down');
        Url::previous(MODULE_PARTNER."/".self::$params['name']);
    }
    public function status($id){
        $model = self::$model;
        $model::statusToggle($id);
        Url::previous(MODULE_PARTNER."/".self::$params['name']);
    }
    public function delete($id){
        $model = self::$model;
        $model::delete([$id]);
        Url::previous(MODULE_PARTNER."/".self::$params['name']);
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
        return Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"].'/album');
    }


}