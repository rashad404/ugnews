<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Facebook\Facebook;
use Google_Client;
use Google_Service_Oauth2;
use Helpers\Parse;
use Helpers\Sms;
use Helpers\Url;
use Helpers\Csrf;
use Helpers\Session;
use Models\AuthModel;
use Models\BoutiquesModel;
use Models\FaqsModel;
use Models\FilterModel;
use Models\MediaModel;
use Models\ProductsModel;
use Models\RegistrationModel;
use Models\SeoModel;
use Models\SiteModel;
use Models\ContactsModel;
use Models\LoginModel;
use Models\NewsModel;
use Models\AboutModel;
use Models\SliderModel;
use Helpers\Pagination;
use Helpers\Cookie;
use Models\BlogModel;
use Models\ApartmentsModel;
use Models\TestimonialsModel;


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

    }

    // Index page
    public function index()
    {
        $data = SeoModel::index();
        $data['def_language'] = self::$def_language;
        $data['slider'] = SliderModel::getList(10);

        $pagination = new Pagination();
        $pagination->limit = 70;
        $data['pagination'] = $pagination;

        $data['list'] = NewsModel::getList();

        Session::set('cat',0);

        View::render('site/'.__FUNCTION__, $data);
    }

    public function news()
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        $data['list'] = NewsModel::getList();

        Session::set('cat',0);
        View::render('site/'.__FUNCTION__, $data);
    }


    // News inner page
    public function news_inner($id)
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;

        $data['def_language'] = self::$def_language;

        $data['item'] = NewsModel::getItem($id);
        $data['next_item'] = NewsModel::navigate($id,'next');
        $data['previous_item'] = NewsModel::navigate($id,'previous');
//        $data['latest'] = ProductsModel::getProductListBySimilar($id,4);

        View::render('site/'.__FUNCTION__, $data);
    }


    public function blog()
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        $data['list'] = BlogModel::getList();

        Session::set('cat',0);
        View::render('site/'.__FUNCTION__, $data);
    }


    // News inner page
    public function blog_inner($id)
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;

        $data['def_language'] = self::$def_language;

        $data['item'] = BlogModel::getItem($id);

        $data['popular_list'] = BlogModel::getPopularList(10);

        $data['next_item'] = BlogModel::navigate($id,'next');
        $data['previous_item'] = BlogModel::navigate($id,'previous');

        View::render('site/'.__FUNCTION__, $data);
    }


    public function media()
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        $pagination = new Pagination();
        $pagination->limit = 12;
        $data['pagination'] = $pagination;
        $limitSql = $pagination->getLimitSql(MediaModel::countList());
        $data['list'] = MediaModel::getList($limitSql);

        View::render('site/'.__FUNCTION__, $data);
    }

    // Index page
    public function cat($id=0, $name=''){
        $boutique_info = BoutiquesModel::getItem($id);
        $data['title'] = $boutique_info['name'];
        $data['keywords'] = $boutique_info['name'];
        $data['description'] = $boutique_info['name'];

        $data['def_language'] = self::$def_language;

        if(isset($_POST['products_order'])){
            Cookie::set('products_order', $_POST['products_order']);
            $data['products_order'] = $_POST['products_order'];
        }elseif(Cookie::has('products_order')){
            $data['products_order'] = Cookie::get('products_order');
        }else{
            $data['products_order'] = 'recent';
        }

        $pagination = new Pagination();
        $pagination->limit = 72;
        $data['pagination'] = $pagination;
        $limitSql = $pagination->getLimitSql(ProductsModel::countListByCat($id));
        $data['products'] = ProductsModel::getProductListByCat($id, $limitSql, $data['products_order']);
        $data['pageTitle'] = $boutique_info['name'];
        View::render('site/'.__FUNCTION__, $data);
    }

    // Product inner page
    public function product_inner($id)
    {

        $product_info = ProductsModel::getProduct($id);
        $data['title'] = $product_info['title_'.self::$def_language];
        $data['keywords'] = $product_info['title_'.self::$def_language];
        $data['description'] = $product_info['title_'.self::$def_language];

        $data['def_language'] = self::$def_language;

        $data['productInfo'] = $product_info;
        $data['products_similar'] = ProductsModel::getProductListBySimilar($id,4);
//        $data['product_photos'] = ProductPhotosModel::getAll($id);

        View::render('site/'.__FUNCTION__, $data);
    }

    // Boutique inner page
    public function boutique($id)
    {
        $boutique_info = BoutiquesModel::getItem($id);
        $data['title'] = $boutique_info['name'];
        $data['keywords'] = $boutique_info['name'];
        $data['description'] = $boutique_info['name'];

        $data['def_language'] = self::$def_language;

        if(isset($_POST['products_order'])){
            Cookie::set('products_order', $_POST['products_order']);
            $data['products_order'] = $_POST['products_order'];
        }elseif(Cookie::has('products_order')){
            $data['products_order'] = Cookie::get('products_order');
        }else{
            $data['products_order'] = 'recent';
        }

        $pagination = new Pagination();
        $pagination->limit = 72;
        $data['pagination'] = $pagination;
        $limitSql = $pagination->getLimitSql(ProductsModel::countListByBoutique($id));
        $data['products'] = ProductsModel::getListByBoutique($id, $limitSql, $data['products_order']);
        $data['pageTitle'] = $boutique_info['name'];
        View::render('site/'.__FUNCTION__, $data);
    }


//    public function sign_in(){
//        $data['title'] = SITE_TITLE;
//        $data['keywords'] = SITE_TITLE;
//        $data['description'] = SITE_TITLE;
//        $data['def_language'] = self::$def_language;
//
//        $link = 'https://lordhousing.appfolio.com/connect/users/sign_in';
//        $html = Parse::sign_in($link);
//        $data['html'] = $html;
//        View::render('site/'.__FUNCTION__, $data);
//    }

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
            'app_id' => '602514563823433', // Replace {app-id} with your app id
            'app_secret' => 'dfa689f62219f6ae4111a2591a4a3dc3',
            'default_graph_version' => 'v3.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl('https://ureb.com/auth/facebook/callback', $permissions);

        $data['postData']['facebook_url'] = htmlspecialchars($loginUrl);

        View::render('site/'.__FUNCTION__, $data);
    }

    public function register($return=''){
        if($this->userId>0){
            Url::redirect("user/dashboard");exit;
        }
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['countryList'] = Sms::getCountryList();
        $data['def_language'] = self::$def_language;

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $model = new RegistrationModel();
            $modelArray = $model->registration();
            $data['postData'] = $modelArray['postData'];
            if(empty($modelArray['errors'])){
//                $return = preg_replace('/\+/','/',$return);
//                Url::redirect($return);
                Url::redirect('');
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
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
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

    public function how_it_works(){
        $data['title'] = $this->lng->get("How It Works").' '.PROJECT_NAME;
        $data['keywords'] = $this->lng->get("How It Works").' '.SITE_TITLE;
        $data['description'] = $this->lng->get("How It Works").' '.SITE_TITLE;
        $data['def_language'] = self::$def_language;
        $data['item'] = AboutModel::getItem();

        View::render('site/'.__FUNCTION__, $data);
    }
    public function about(){
        $data['title'] = $this->lng->get("About").' '.PROJECT_NAME;
        $data['keywords'] = $this->lng->get("About").' '.SITE_TITLE;
        $data['description'] = $this->lng->get("About").' '.SITE_TITLE;
        $data['def_language'] = self::$def_language;
        $data['item'] = AboutModel::getItem();

        View::render('site/'.__FUNCTION__, $data);
    }
    public function privacy(){
        $data['title'] = $this->lng->get("Privacy").' '.PROJECT_NAME;
        $data['keywords'] = $this->lng->get("Privacy").' '.SITE_TITLE;
        $data['description'] = $this->lng->get("Privacy").' '.SITE_TITLE;
        $data['def_language'] = self::$def_language;

        View::render('site/'.__FUNCTION__, $data);
    }
    public function refund(){
        $data['title'] = $this->lng->get("Refund").' '.PROJECT_NAME;
        $data['keywords'] = $this->lng->get("Refund").' '.SITE_TITLE;
        $data['description'] = $this->lng->get("Refund").' '.SITE_TITLE;
        $data['def_language'] = self::$def_language;

        View::render('site/'.__FUNCTION__, $data);
    }
    public function what_we_do(){
        $data['title'] = $this->lng->get("About").' '.PROJECT_NAME;
        $data['keywords'] = $this->lng->get("About").' '.SITE_TITLE;
        $data['description'] = $this->lng->get("About").' '.SITE_TITLE;
        $data['def_language'] = self::$def_language;

        View::render('site/'.__FUNCTION__, $data);
    }
    public function airport_service(){
        $data = SeoModel::airport_service();
        $data['def_language'] = self::$def_language;

        View::render('site/'.__FUNCTION__, $data);
    }
    public function faqs(){
        $data['title'] = $this->lng->get("FAQs").' '.PROJECT_NAME;
        $data['keywords'] = $this->lng->get("Frequently asked questions").' '.SITE_TITLE;
        $data['description'] = $this->lng->get("Frequently asked questions").' '.SITE_TITLE;
        $data['def_language'] = self::$def_language;
        $data['list'] = FaqsModel::getList();
        View::render('site/'.__FUNCTION__, $data);
    }
    public function testimonials(){
        $data['title'] = $this->lng->get("About").' '.PROJECT_NAME;
        $data['keywords'] = $this->lng->get("About").' '.SITE_TITLE;
        $data['description'] = $this->lng->get("About").' '.SITE_TITLE;
        $data['def_language'] = self::$def_language;
        View::render('site/'.__FUNCTION__, $data);
    }
    public function schedule(){
        $data = [];
        View::render('site/'.__FUNCTION__, $data);
    }
    public function locations(){
        $data = SeoModel::locations();
        $data['def_language'] = self::$def_language;
        View::render('site/'.__FUNCTION__, $data);
    }
    public function locations_iframe(){
        Session::set('header_off',true);
        $this->locations();
    }
    public function locations_app(){
        Session::set('header_off',true);
        $this->locations();
    }
    public function contacts_app(){
        Session::set('header_off',true);
        $this->contacts();
    }
    public function about_app(){
        Session::set('header_off',true);
        $this->about();
    }

}
