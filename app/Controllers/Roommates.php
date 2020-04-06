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
use Models\RoommatesModel;
use Models\BedsModel;
use Models\SeoModel;
use Models\UserModel;

/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Roommates extends Controller
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


    public function index($location='')
    {
        $data = SeoModel::roommates();

        $data['def_language'] = self::$def_language;
        $data['filter_max'] = 4000;

        $data['feature_list'] = RoommatesModel::getFeatureList();
        $data['category_list'] = RoommatesModel::getCategoryList();
        $data['location_list'] = RoommatesModel::getlocationList();
        $data['star_list'] = RoommatesModel::getStarList();

        $data['gender_list'] = Features::genderList();
        $data['profession_list'] = Features::professionList();
        $data['languageList'] = Features::getLanguages();
        $data['nationalityList'] = Features::getNationalities();

        if(isset($_POST['products_order'])){
            Cookie::set('products_order', $_POST['products_order']);
            $data['products_order'] = $_POST['products_order'];
        }elseif(Cookie::has('products_order')){
            $data['products_order'] = Cookie::get('products_order');
        }else{
            $data['products_order'] = 'recent';
        }


        if(isset($_GET['reset_filter'])) {
            FilterModel::resetFilters('roommates');
        }

        if(isset($_POST['filter']) && isset($_POST['csrf_token']) && Csrf::isTokenValid()) {
            FilterModel::setFilters('roommates');
        }

        $data['list'] = RoommatesModel::getList($data['products_order']);

        $data['postData'] = FilterModel::getFilters('roommates');


        if(!empty($data['postData'])){
        }else{
            $data['postData']['budget_min']= 0;
            $data['postData']['budget_max']= $data['filter_max'];
            $data['postData']['budget_period']= 3;
            $data['postData']['movein_month']= date('m');
            $data['postData']['movein_day']= date('d');
            $data['postData']['movein_year']= date('Y');
            $data['postData']['stay_min']= '';
            $data['postData']['stay_max']= '';
            $data['postData']['smoking']= '';
            $data['postData']['animals']= '';
            $data['postData']['location']= '';
        }


        if(isset($data['postData']['countries'])){$data['postData']['countries'] = explode(',',$data['postData']['countries']);}else{$data['postData']['countries']=[];}
        if(isset($data['postData']['gender'])){$data['postData']['gender'] = explode(',',$data['postData']['gender']);}else{$data['postData']['gender']=[];}
        if(isset($data['postData']['stars'])){$data['postData']['stars'] = explode(',',$data['postData']['stars']);}else{$data['postData']['stars']=[];}
        if(isset($data['postData']['features'])){$data['postData']['features'] = array_map('trim', explode(',', $data['postData']['features']));}else{$data['postData']['features']=[];}
        if(isset($data['postData']['profession'])){$data['postData']['profession'] = explode(',',$data['postData']['profession']);}else{$data['postData']['profession']=[];}

//        Console::varDump($data['postData']);


        View::render('roommates/'.__FUNCTION__, $data);
    }



    //Inner page
    public function inner($id)
    {
        $data = SeoModel::roommate_inner();
        $data['item'] = RoommatesModel::getItem($id);

        $data['title'] = $data['item']['first_name'].' '.$data['title'];
        $data['keywords'] = $data['item']['first_name'].' '.$data['keywords'];
        $data['description'] = strip_tags(html_entity_decode($data['item']['description'])).' '.$data['description'];

        $data['meta_img'] = $data['item']['image'];

        $data['def_language'] = self::$def_language;


        $data['popular_list'] = RoommatesModel::getPopularList(10);

        View::render('roommates/'.__FUNCTION__, $data);
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

        View::render('roommates/index', $data);
    }


    public function add(){

        AuthModel::checkLogin();
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;


        $data['state_list'] = RoommatesModel::getStateList();
        $data['countryList'] = Sms::getCountryList();
        $data['languageList'] = Features::getLanguages();
        $data['nationalityList'] = Features::getNationalities();
        $data['features_list'] = ApartmentsModel::getFeatureList(13);
        $data['userId'] = $this->userId;
        $data['def_language'] = self::$def_language;

        $user_info = $this->userInfo;

        $user_info['phone'] = substr($user_info['phone'], strlen($user_info['country_code']));
        $birthday_exp = explode('-', $user_info['birthday']);
        $birth_year = $birthday_exp[0];

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $model = new RoommatesModel();
            $modelArray = $model->add();
            $data['postData'] = $modelArray['postData'];
            $data['postData']['email'] = $user_info['email'];
            if(empty($modelArray['errors'])){
                Session::setFlash('success',$this->lng->get('You ad has been successfully created'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }else{
            $data['postData'] = [
                'movein_month'=> date('m'),
                'movein_day'=>date('d'),
                'movein_year'=> date('Y'),
                'stay_min'=>'',
                'stay_max'=>'',
                'profession'=>'',
                'smoking'=>'',
                'animals'=>'',
                'language'=>'',
                'nationality'=>'',
                'budget'=>'',
                'budget_period'=>'',
                'pr_gender'=>'',
                'pr_age_min'=>'18',
                'pr_age_max'=>'45',
                'pr_profession'=>'',
                'pr_smoking'=>'',
                'pr_animals'=>'',
                'description'=>'',
                'state'=>'',
            ];
        }

        $data['postData']['first_name'] = $user_info['first_name'];
        $data['postData']['country_code'] = $user_info['country_code'];
        $data['postData']['phone'] = $user_info['phone'];
        $data['postData']['email'] = $user_info['email'];
        $data['postData']['gender'] = $user_info['gender'];
        $data['postData']['birth_year'] = $birth_year;

        if(!isset($data['postData']['features'])){
            $data['postData']['features'] = [];
        }
        View::render('roommates/'.__FUNCTION__, $data);
    }

}
