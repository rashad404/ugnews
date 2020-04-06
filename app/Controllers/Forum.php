<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Helpers\Url;
use Models\ForumModel;
use Helpers\Csrf;
use Helpers\Session;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Forum extends Controller
{

    public $lng;
    public $model;
    public $userId;
    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        $this->model = new ForumModel();
        $this->userId = intval(Session::get("user_session_id"));
    }

    public function index()
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        $data['list'] = ForumModel::getList();
        $data['category_list'] = ForumModel::getCategoryList();
        $data['selected_cat'] = 0;
        View::render('forum/'.__FUNCTION__, $data);
    }

    public function cat($id)
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        $data['list'] = ForumModel::getList($id);
        $data['selected_cat'] = $id;
        $data['category_list'] = ForumModel::getCategoryList();

        View::render('forum/'.__FUNCTION__, $data);
    }

    public function ask()
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['extra_js'] = ['summernote'];
        $data['extra_css'] = ['summernote'];

        $data['def_language'] = self::$def_language;
        $data['userId'] = $this->userId;
        $data['selected_cat'] = 0;

        $data['category_list'] = ForumModel::getCategoryList();

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){

            $modelArray = $this->model->ask();
            $data['postData'] = $modelArray['postData'];
            if(empty($modelArray['errors'])){
                Session::setFlash('success',$this->lng->get('Your question has been added successfully'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }

        View::render('forum/'.__FUNCTION__, $data);
    }


    // News inner page
    public function inner($id)
    {
        $data['item'] = ForumModel::getItem($id);
        $data['title'] = $data['item']['title'].' '.$data['item']['tags'].' - '.SITE_TITLE;
        $data['keywords'] = $data['item']['title'].' '.$data['item']['tags'];
        $data['description'] = $data['item']['title'].' '.$data['item']['tags'];

        $data['def_language'] = self::$def_language;
        $data['userId'] = $this->userId;


        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $this->model->answer($id);
            $data['postData'] = $modelArray['postData'];
            if(empty($modelArray['errors'])){
                Session::setFlash('success',$this->lng->get('Your Answer has been added successfully'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }

        $data['answer_list'] = ForumModel::getAnswerList($id);

        View::render('forum/'.__FUNCTION__, $data);
    }

    public function search($text)
    {
        if(isset($_POST['search'])){
            Url::redirect('forum/search/'.urlencode($_POST['search']));
            exit;
        }
        $text = urldecode($text);
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        $data['list'] = ForumModel::getSearchList($text);

        View::render('forum/index', $data);
    }

}
