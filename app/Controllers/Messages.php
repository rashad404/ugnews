<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Helpers\Console;
use Helpers\Cookie;
use Helpers\Csrf;
use Helpers\Features;
use Helpers\Session;
use Helpers\Sms;
use Helpers\Url;
use Models\ApartmentsModel;
use Models\AuthModel;
use Models\FeaturesModel;
use Models\FilterModel;
use Models\MessagesModel;
use Models\RoommatesModel;
use Models\BedsModel;
use Models\SeoModel;
use Models\UserModel;

/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Messages extends Controller
{

    public $lng;
    public $userId;
    public $userInfo;
    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        $this->userId = intval(Session::get("user_session_id"));
        new RoommatesModel();

        if($this->userId>0) {
            $model = new UserModel();
            $this->userInfo = $model->getInfo($this->userId);
        }
    }


    public function index()
    {
        $data = SeoModel::messages();

        $data['def_language'] = self::$def_language;
        $data['user_info'] = $this->userInfo;

        if(isset($_POST['list_order'])){
            Cookie::set('list_order', $_POST['list_order']);
            $data['list_order'] = $_POST['list_order'];
        }elseif(Cookie::has('list_order')){
            $data['list_order'] = Cookie::get('list_order');
        }else{
            $data['list_order'] = 'recent';
        }

        $data['list'] = MessagesModel::getAllMessages($data['list_order']);

        View::render('messages/'.__FUNCTION__, $data);
    }

    //Inner page
    public function inner($id)
    {
        AuthModel::checkLogin();
        $data = SeoModel::message_inner();
        $data['list'] = MessagesModel::getList($id);
        $data['user_info'] = $this->userInfo;

        $model = new UserModel();
        $data['to_info'] = $model->getInfo($id);

        $data['title'] = $data['title'].' '.$data['to_info']['first_name'];
        $data['def_language'] = self::$def_language;

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $model = new MessagesModel();
            $modelArray = $model->send($id);
            $data['postData'] = $modelArray['postData'];
            if(empty($modelArray['errors'])){
                Url::redirect('message/'.$id);exit;
                Session::setFlash('success',$this->lng->get('You message has been successfully sent'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }else{
            $data['postData'] = [
                'text'=>'',
            ];
        }


        View::render('messages/'.__FUNCTION__, $data);
    }

    public function search($text)
    {
        if(isset($_POST['search'])){
            Url::redirect('roommates/search/'.urlencode($_POST['search']));
            exit;
        }
        $text = urldecode($text);
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        $data['list'] = RoommatesModel::getSearchList($text);

        View::render('messages/index', $data);
    }

}
