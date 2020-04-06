<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Facebook\Facebook;
use Google_Client;
use Helpers\Console;
use Helpers\Cookie;
use Helpers\Csrf;
use Helpers\Features;
use Helpers\Session;
use Helpers\Sms;
use Helpers\Url;
use Models\AuthModel;
use Models\FilterModel;
use Models\ApartmentsModel;
use Models\BedsModel;
use Models\LoginModel;
use Models\PartnerModel;
use Models\RegistrationModel;
use Models\RoommatesModel;
use Models\SeoModel;
use Models\UserModel;
use Modules\admin\Controllers\Beds;

/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Apartments extends Controller
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
        new ApartmentsModel();

        $this->userId = intval(Session::get("user_session_id"));
        if($this->userId>0) {
            $model = new UserModel();
            $this->userInfo = $model->getInfo($this->userId);
        }
    }


    public function index($location='')
    {

        $data = SeoModel::apartments();
        $data['def_language'] = self::$def_language;
        $data['filter_max'] = 4000;

        $data['feature_list'] = ApartmentsModel::getFeatureList();
        $data['category_list'] = ApartmentsModel::getCategoryList();
        $data['room_types'] = ApartmentsModel::getRoomTypes();
        $data['location_list'] = ApartmentsModel::getlocationList();
        $data['star_list'] = ApartmentsModel::getStarList();

        if(isset($_POST['products_order'])){
            Cookie::set('products_order', $_POST['products_order']);
            $data['products_order'] = $_POST['products_order'];
        }elseif(Cookie::has('products_order')){
            $data['products_order'] = Cookie::get('products_order');
        }else{
            $data['products_order'] = 'recent';
        }


        if(isset($_GET['reset_filter']) or !isset($_GET['filter'])) {
            FilterModel::resetFilters();
        }

        if(isset($_POST['filter'])) {
            FilterModel::setFilters();
        }

        if($location=='downtown_la') {
            $data['postData'] = FilterModel::getFilters();
//            Console::varDump( $data['postData']);
            if(isset($data['postData']['countries'])) {
                if (count(explode(',', $data['postData']['countries'])) > 1 && isset($_POST['filter'])) {
                    Url::redirect('apartments');
                    exit;
                }
            }
            $data['postData']['countries'] = '2';
            FilterModel::setFilters($data['postData']);
        }elseif($location=='koreatown') {
            $data['postData'] = FilterModel::getFilters();
            if(isset($data['postData']['countries'])) {
                if (count(explode(',', $data['postData']['countries'])) > 1 && isset($_POST['filter'])) {
                    Url::redirect('apartments');
                    exit;
                }
            }
            $data['postData']['countries'] = '3';
            FilterModel::setFilters($data['postData']);
        }elseif($location=='santa_monica') {
            $data['postData'] = FilterModel::getFilters();
            if(isset($data['postData']['countries'])) {
                if (count(explode(',', $data['postData']['countries'])) > 1 && isset($_POST['filter'])) {
                    Url::redirect('apartments');
                    exit;
                }
            }
            $data['postData']['countries'] = '1';
            FilterModel::setFilters($data['postData']);
        }

        $_PARTNER = PartnerModel::getInfo();
        $data['list'] = ApartmentsModel::getListByBeds(50, $data['products_order'], $_PARTNER['id']);

        $data['postData'] = FilterModel::getFilters();


        if(isset($data['postData']['countries'])){$data['postData']['countries'] = explode(',',$data['postData']['countries']);}else{$data['postData']['countries']=[];}
        if(isset($data['postData']['categories'])){$data['postData']['categories'] = explode(',',$data['postData']['categories']);}else{$data['postData']['categories']=[];}
        if(isset($data['postData']['room_types'])){$data['postData']['room_types'] = explode(',',$data['postData']['room_types']);}else{$data['postData']['room_types']=[];}
        if(isset($data['postData']['stars'])){$data['postData']['stars'] = explode(',',$data['postData']['stars']);}else{$data['postData']['stars']=[];}
        if(isset($data['postData']['features'])){$data['postData']['features'] = explode(',',$data['postData']['features']);}else{$data['postData']['features']=[];}
        if(isset($data['postData']['price_min'])){}else{$data['postData']['price_min']=0;}
        if(isset($data['postData']['price_max'])){}else{$data['postData']['price_max']=$data['filter_max'];}

//        Console::varDump($data['postData']);
        View::render('apartments/'.__FUNCTION__, $data);
    }


    //Inner page
    public function inner($bed_id)
    {
        $data = SeoModel::apartment_inner();
        $data['userId'] = $this->userId;

        $data['bed'] = BedsModel::getItem($bed_id);
        $apt_id = $data['bed']['apt_id'];
        $data['item'] = ApartmentsModel::getItem($apt_id);
        $data['album'] = ApartmentsModel::getAlbum($apt_id);

        $data['title'] = $data['item']['title_'.self::$def_language].' '.$data['title'];
        $data['keywords'] = $data['item']['title_'.self::$def_language].' '.$data['keywords'];
        $data['description'] = strip_tags(html_entity_decode($data['item']['text_'.self::$def_language])).' '.$data['description'];

        $data['meta_img'] = $data['item']['image'];

        $data['def_language'] = self::$def_language;


        $data['rooms'] = ApartmentsModel::getRooms($apt_id);
        $data['popular_list'] = ApartmentsModel::getPopularList(10);


        $data['countryList'] = Sms::getCountryList();
        $data['def_language'] = self::$def_language;

        $return = '';
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


        $fb = new Facebook([
            'app_id' => '602514563823433', // Replace {app-id} with your app id
            'app_secret' => 'dfa689f62219f6ae4111a2591a4a3dc3',
            'default_graph_version' => 'v3.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl('https://ureb.com/auth/facebook/callback', $permissions);

        $data['postData']['facebook_url'] = htmlspecialchars($loginUrl);

        View::render('apartments/'.__FUNCTION__, $data);
    }

    public function search($text)
    {
        if(isset($_POST['search'])){
            Url::redirect('apartments/search/'.urlencode($_POST['search']));
            exit;
        }
        $text = urldecode($text);
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        $data['list'] = ApartmentsModel::getSearchList($text);

        View::render('apartments/index', $data);
    }



    public function apply($id){

        AuthModel::checkLogin();
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;


        $data['bed_info'] = BedsModel::getItem($id);
        $apt_id = $data['bed_info']['apt_id'];
        $room_id = $data['bed_info']['room_id'];
        $data['apt_info'] = ApartmentsModel::getItem($apt_id);

        $data['state_list'] = ApartmentsModel::getStateList();
        $data['countryList'] = Features::getCountries();
        $data['features_list'] = ApartmentsModel::getFeatureList(13);
        $data['userId'] = $this->userId;
        $data['def_language'] = self::$def_language;

        $user_info = $this->userInfo;

        $user_info['phone'] = substr($user_info['phone'], strlen($user_info['country_code']));
        $birthday_exp = explode('-', $user_info['birthday']);
        $birth_year = $birthday_exp[0];

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $model = new ApartmentsModel();
            $modelArray = $model->apply();
            $data['postData'] = $modelArray['postData'];
            $data['postData']['email'] = $user_info['email'];
            if(empty($modelArray['errors'])){
                Session::setFlash('success',$this->lng->get('You have successfully applied'));
                Url::redirect('');exit;
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }else{
            $data['postData'] = [
                'movein_month'=> date('m'),
                'movein_day'=>date('d'),
                'movein_year'=> date('Y'),
                'ssn'=>'',
                'dl'=>'',
                'dl_state'=>'',

                'current_address'=>'',
                'current_city'=>'',
                'current_zip'=>'',
                'current_state'=>'',
                'current_country'=>'187',
                'current_rent'=>'',
                'current_month_from'=>'',
                'current_year_from'=>'',
                'current_month_to'=>'',
                'current_year_to'=>'',
                'current_landlord_name'=>'',
                'current_landlord_phone'=>'',
                'current_landlord_email'=>'',
                'current_reason'=>'',

                'previous_address'=>'',
                'previous_city'=>'',
                'previous_zip'=>'',
                'previous_state'=>'',
                'previous_country'=>'187',
                'previous_rent'=>'',
                'previous_month_from'=>'',
                'previous_year_from'=>'',
                'previous_month_to'=>'',
                'previous_year_to'=>'',
                'previous_landlord_name'=>'',
                'previous_landlord_phone'=>'',
                'previous_landlord_email'=>'',
                'previous_reason'=>'',

                'employer_address'=>'',
                'employer_city'=>'',
                'employer_zip'=>'',
                'employer_state'=>'',
                'employer_country'=>'',
                'salary'=>'',
                'position'=>'',
                'worked_month_from'=> 1,
                'worked_year_from'=> date('Y'),
                'worked_month_to'=> date('m'),
                'worked_year_to'=> date('Y'),
                'employer_name'=> '',
                'employer_phone'=> '',
                'employer_email'=> '',
                'extra_income'=> '',

                'smoking'=>'',
                'animals'=>'',
                'note'=>'',
            ];
        }

        $data['postData']['first_name'] = $user_info['first_name'];
        $data['postData']['last_name'] = $user_info['last_name'];
        $data['postData']['country_code'] = $user_info['country_code'];
        $data['postData']['phone'] = $user_info['phone'];
        $data['postData']['email'] = $user_info['email'];
        $data['postData']['gender'] = $user_info['gender'];
        $data['postData']['birth_year'] = $birth_year;

        $data['postData']['apt_id'] = $apt_id;
        $data['postData']['room_id'] = $room_id;
        $data['postData']['bed_id'] = $id;

        View::render('apartments/'.__FUNCTION__, $data);
    }



    public function showing($id){

        AuthModel::checkLogin();
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;


        $data['bed_info'] = BedsModel::getItem($id);
        $apt_id = $data['bed_info']['apt_id'];
        $room_id = $data['bed_info']['room_id'];
        $data['apt_info'] = ApartmentsModel::getItem($apt_id);

        $data['userId'] = $this->userId;
        $data['def_language'] = self::$def_language;

        $user_info = $this->userInfo;

        $user_info['phone'] = substr($user_info['phone'], strlen($user_info['country_code']));
        $birthday_exp = explode('-', $user_info['birthday']);
        $birth_year = $birthday_exp[0];

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $model = new ApartmentsModel();
            $modelArray = $model->showing();
            $data['postData'] = $modelArray['postData'];
            $data['postData']['email'] = $user_info['email'];
            if(empty($modelArray['errors'])){
                Session::setFlash('success',$this->lng->get('Your showing successfully submitted, we will contact you ASAP'));
                Url::redirect('');exit;
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }else{
            $data['postData'] = [
                'showing_date'=> date('m/d/Y H:i A'),
                'movein_date'=> date('m/d/Y H:i A'),
                'animals'=>'',
                'note'=>'',
            ];
        }

        $data['postData']['first_name'] = $user_info['first_name'];
        $data['postData']['last_name'] = $user_info['last_name'];
        $data['postData']['phone'] = $user_info['phone'];
        $data['postData']['email'] = $user_info['email'];
        $data['postData']['gender'] = $user_info['gender'];
        $data['postData']['birth_year'] = $birth_year;

        $data['postData']['apt_id'] = $apt_id;
        $data['postData']['room_id'] = $room_id;
        $data['postData']['bed_id'] = $id;

        View::render('apartments/'.__FUNCTION__, $data);
    }

}
