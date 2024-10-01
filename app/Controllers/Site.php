<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Facebook\Facebook;
use Google_Client;
use Helpers\Format;
use Helpers\Sms;
use Helpers\Url;
use Helpers\Csrf;
use Helpers\Session;
use Models\AdsModel;
use Models\ChannelsModel;
use Models\CityModel;
use Models\RegistrationModel;
use Models\SeoModel;
use Models\SiteModel;
use Models\ContactsModel;
use Models\LoginModel;
use Models\NewsModel;
use Models\AboutModel;
use Helpers\Pagination;
use Helpers\Cookie;
use Models\CurrencyModel;
use Models\NamazTimesModel;
use Models\WeatherModel;
use Modules\partner\Controllers\News;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Site extends Controller
{

    public $userId;
    public $siteModel;
    public $lng;
    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');

        $this->userId = intval(Session::get("user_session_id"));
	    $this->siteModel = new SiteModel();
        new NewsModel();

    }

    // Index page
    public function index()
    {
        $data = SeoModel::index();
        $data['def_language'] = self::$def_language;

        $pagination = new Pagination();
        $pagination->limit = 24;
        $data['pagination'] = $pagination;
        $limitSql = $pagination->getLimitSql(NewsModel::countList());
        $data['current_page'] = $pagination->currentPage;
        $data['list'] = NewsModel::getList($limitSql);


        $data['channel_list'] = ChannelsModel::getList(7);
        new CityModel();
        $data['city_list_1'] = CityModel::getList('LIMIT 0,18');
        $data['city_list_2'] = CityModel::getList('LIMIT 18,100');

        $data['region'] = Cookie::get('set_region');
        if($data['region']==0)$data['region']=DEFAULT_COUNTRY;
        Session::set('cat',0);


        $data['usdRate'] = CurrencyModel::getUSDRate();
        $data['todayNamaz'] = NamazTimesModel::getTodayNamazTimes();
        $data['bakuWeatherInfo'] = round(WeatherModel::getWeatherBySlug('Baki')['temp']);

        View::render('site/'.__FUNCTION__, $data);
    }


    // Index page
    public function cat($name = '') {
        $categoryIdArray = NewsModel::getCategoryIdByName($name);
    
        if (isset($categoryIdArray[0]['id'])) {
            $categoryId = intval($categoryIdArray[0]['id']);
    
            $data = SeoModel::general();
            $data['def_language'] = self::$def_language;
    
            $pagination = new Pagination();
            $pagination->limit = 24;
            $data['pagination'] = $pagination;
            $limitSql = $pagination->getLimitSql(NewsModel::countListByCat($categoryId));
            $data['list'] = NewsModel::getListByCat($categoryId, $limitSql);
    
            $data['cat_name'] = NewsModel::getCatName($categoryId);

            $data['title'] = $this->lng->get($data['cat_name']) . ' Xəbərləri, ' . $this->lng->get($data['cat_name']) . ' xeberleri';
            $data['keywords'] = $this->lng->get($data['cat_name']) . ' Xəbərləri, ' . $this->lng->get($data['cat_name']) . ' xeberleri';
            $data['description'] = $this->lng->get($data['cat_name']) . ' Xəbərləri, ' . $this->lng->get($data['cat_name']) . ' xeberleri';

            View::render('site/' . __FUNCTION__, $data);
        } else {
            header("HTTP/1.0 404 Not Found");
            View::render('errors/404');
        }
    }


    // Tag cat page
    public function tag_cat($id=0, $name=''){
        $data = SeoModel::general();
        $data['def_language'] = self::$def_language;

        $pagination = new Pagination();
        $pagination->limit = 24;
        $data['pagination'] = $pagination;
        $limitSql = $pagination->getLimitSql(NewsModel::countListByTagCat($id));
        $data['list'] = NewsModel::getListByTagCat($id, $limitSql);

        $data['cat_name'] = NewsModel::getTagName($id);
        View::render('site/'.__FUNCTION__, $data);
    }

    // Tag page
    public function tags($name=''){
        $name = Format::deUrlText($name);
        $data = [];

        $data['title'] = $name . ' Xəbərləri, ' . $name . ' xeberleri';
        $data['keywords'] = $name . ' Xəbərləri, ' . $name . ' xeberleri';
        $data['description'] = $name . ' Xəbərləri, ' . $name . ' xeberleri';

        $data['def_language'] = self::$def_language;

        $pagination = new Pagination();
        $pagination->limit = 24;
        $data['pagination'] = $pagination;
        $limitSql = $pagination->getLimitSql(NewsModel::countListByTag($name));
        $data['list'] = NewsModel::getListByTag($name, $limitSql);
        $data['cat_name'] = $name;
        View::render('site/'.__FUNCTION__, $data);
    }
    // cities
    public function city($id){

        $data = SeoModel::city(CityModel::getName($id));

        $data['def_language'] = self::$def_language;
        $pagination = new Pagination();
        $pagination->limit = 24;
        $data['pagination'] = $pagination;
        $limitSql = $pagination->getLimitSql(NewsModel::countListByCity($id));
        $data['list'] = NewsModel::getListByCity($id, $limitSql);
        $data['name'] = CityModel::getName($id);



        View::render('site/'.__FUNCTION__, $data);
    }


    // News inner page
    public function news_inner($slug_part_1, $slug_part_2)
    {
        $slug = $slug_part_1 . '/'. $slug_part_2;
        
        $data['def_language'] = self::$def_language;
        $data['userId'] = $this->userId;

        $id = NewsModel::getItemName($slug);

        $data['item'] = NewsModel::getItem($id);
        $data['meta_img'] = $data['item']['image'];

        $data['title'] = $data['item']['title'];
        $data['keywords'] = $data['item']['title'];
        $data['description'] = $data['item']['title'];

        $data['next_item'] = NewsModel::navigate($id,'next');
        $data['previous_item'] = NewsModel::navigate($id,'previous');
        $data['list'] = NewsModel::getSimilarNews($id, 5);
        $data['ad'] = AdsModel::getItem();

        $return = '';
        $data['countryList'] = Sms::getCountryList();
        if(isset($_POST['csrf_token_register']) or isset($_POST['csrf_token_login'])){
            if(Csrf::isTokenValid('_register')){
                $model = new RegistrationModel();
                $modelArray = $model->registration();
            }else if(Csrf::isTokenValid('_login')){
                $model = new LoginModel();
                $modelArray = $model->login();
            }

            $data['postData'] = $modelArray['postData'];
            if(empty($modelArray['errors'])){
                if(isset($_POST['redirect_url'])) {
                    $redirect_url = $_POST['redirect_url'];
                    Url::redirect($redirect_url);
                    exit;
                }else {
                    Url::redirect('');
                    exit;
                }
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }else{
            if(!empty($return))$return = '/'.$return;
            $data['postData'] = ['return'=>$return,'first_name'=>'','last_name'=>'','phone'=>'','email'=>'','gender'=>'','birth_month'=>'','birth_day'=>'','birth_year'=>'','country_code'=>''];
        }
        $data['modal_url'] = SMVC."app/views/modals/login.php";



        $clientID = '358271044733-dkovkbpii2rt8ocr9ednfm9q9qmerqe4.apps.googleusercontent.com';
        $clientSecret = 'WeMXhqBBxwbBOF65qw0thv8T';
        $redirectUri = 'https://ureb.com/auth/google';

        // create Client Request to access Google API
        $client = new Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope("email");
        $client->addScope("profile");

        $data['postData']['google_client'] = $client;


        // $fb = new Facebook([
        //     'app_id' => '977943965970059', // Replace {app-id} with your app id
        //     'app_secret' => '83a9b6499f72d0a344e2b2fa2e27a65e',
        //     'default_graph_version' => 'v3.2',
        // ]);

        // $helper = $fb->getRedirectLoginHelper();

        // $permissions = ['email']; // Optional permissions
        // $loginUrl = $helper->getLoginUrl('https://ug.news/auth/facebook/callback', $permissions);

        // $data['postData']['facebook_url'] = htmlspecialchars($loginUrl);


        View::render('site/'.__FUNCTION__, $data);
    }


    public function logout(){
        Session::destroy('',true);
        Url::redirect("");
    }

    public function login($return='')
    {
        $return = preg_replace('/\+/','/',$return);
        if($this->userId>0){
            Url::redirect($return);exit;
        }


        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $model = new LoginModel();
            $modelArray = $model->login();
            $data['postData'] = $modelArray['postData'];
            if(empty($modelArray['errors'])){
                Url::redirect($return);
                exit;
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }else{
            if(!empty($return))$return = '/'.$return;
            $data['postData'] = ['email'=>'','return'=>$return];
        }

        $clientID = '358271044733-dkovkbpii2rt8ocr9ednfm9q9qmerqe4.apps.googleusercontent.com';
        $clientSecret = 'WeMXhqBBxwbBOF65qw0thv8T';
        $redirectUri = 'https://ureb.com/auth/google';

        // create Client Request to access Google API
        $client = new Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope("email");
        $client->addScope("profile");

        $data['postData']['google_client'] = $client;



        $fb = new Facebook([
            'app_id' => '977943965970059', // Replace {app-id} with your app id
            'app_secret' => '83a9b6499f72d0a344e2b2fa2e27a65e',
            'default_graph_version' => 'v3.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl('https://ug.news/auth/facebook/callback', $permissions);

        $data['postData']['facebook_url'] = htmlspecialchars($loginUrl);

        View::render('site/'.__FUNCTION__, $data);
    }

    public function register($return=''){
        if($this->userId>0){
            Url::redirect("partner/news/index");exit;
        }
        $data = SeoModel::general();
        $data['countryList'] = Sms::getCountryList();
        $data['def_language'] = self::$def_language;

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $model = new RegistrationModel();
            $modelArray = $model->registration();
            $data['postData'] = $modelArray['postData'];
            if(empty($modelArray['errors'])){
//                $return = preg_replace('/\+/','/',$return);
//                Url::redirect($return);
                Url::redirect('partner/news/index');
                exit;
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }else{
            if(!empty($return))$return = '/'.$return;
            $data['postData'] = ['return'=>$return,'first_name'=>'','last_name'=>'','phone'=>'','email'=>'','gender'=>'','birth_month'=>'','birth_day'=>'','birth_year'=>'','country_code'=>''];
        }

        View::render('site/'.__FUNCTION__, $data);
    }

    public function contacts(){
        $data = SeoModel::general();
        $data['contacts'] = $this->siteModel->getContacts();
        $data['def_language'] = self::$def_language;

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $model = new ContactsModel();
            $modelArray = $model->sendMessage();
            $data['postData'] = $modelArray['postData'];

            if(empty($modelArray['errors'])){
                Session::setFlash('success',$this->lng->get('Message has been sent'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }else{
            $data['postData'] = ['name'=>'','phone'=>'','email'=>'','message'=>''];
        }

        View::render('site/'.__FUNCTION__, $data);
    }

    public function about(){
        $data = SeoModel::general();
        $data['def_language'] = self::$def_language;

        View::render('site/'.__FUNCTION__, $data);
    }
    public function privacy(){
        $data = SeoModel::general();
        $data['def_language'] = self::$def_language;

        View::render('site/'.__FUNCTION__, $data);
    }
    
    public function data_deletion(){
        $data = SeoModel::general();
        $data['def_language'] = self::$def_language;

        View::render('site/'.__FUNCTION__, $data);
    }
    public function refund(){
        $data = SeoModel::general();
        $data['def_language'] = self::$def_language;

        View::render('site/'.__FUNCTION__, $data);
    }
    public function create_channel(){
        $data = SeoModel::create_channel();
        $data['def_language'] = self::$def_language;
        View::render('help/'.__FUNCTION__, $data);
    }

}
