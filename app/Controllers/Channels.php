<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Facebook\Facebook;
use Google_Client;
use Helpers\Cookie;
use Helpers\Csrf;
use Helpers\Pagination;
use Helpers\Session;
use Helpers\Sms;
use Helpers\Url;
use Models\ChannelsModel;
use Models\LoginModel;
use Models\NewsModel;
use Models\RegistrationModel;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Channels extends Controller
{

    public $lng;
    public static $userId;
    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        self::$userId = intval(Session::get("user_session_id"));;
        new ChannelsModel();
        new NewsModel();
    }

    // News inner page
    public function inner($url)
    {
        $data['def_language'] = self::$def_language;

        $data['userId'] = self::$userId;

        $data['item'] = ChannelsModel::getItemByUrl(urldecode($url));

        if($data['item']['id']<1){
            echo 'Wrong Channel';
            exit;
        }

        $data['meta_img'] = $data['item']['image'];
        $data['title'] = $data['item']['name'];

        $data['keywords'] = $data['item']['name'];
        $data['description'] = $data['item']['name'];

        $pagination = new Pagination();
        $pagination->limit = 24;
        $data['pagination'] = $pagination;
        $limitSql = $pagination->getLimitSql(NewsModel::countListByChannel($data['item']['id']));
        $data['list'] = NewsModel::getListByChannel($data['item']['id'], $limitSql);

        $data['region'] = Cookie::get('set_region');
        if($data['region']==0)$data['region']=DEFAULT_COUNTRY;



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


        $fb = new Facebook([
            'app_id' => '977943965970059', // Replace {app-id} with your app id
            'app_secret' => '83a9b6499f72d0a344e2b2fa2e27a65e',
            'default_graph_version' => 'v3.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl('https://ug.news/auth/facebook/callback', $permissions);

        $data['postData']['facebook_url'] = htmlspecialchars($loginUrl);

        View::render('channels/'.__FUNCTION__, $data);
    }

    public function search($text)
    {
        if(isset($_POST['search'])){
            Url::redirect('blog/search/'.urlencode($_POST['search']));
            exit;
        }
        $text = urldecode($text);
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        $data['list'] = ChannelsModel::getSearchList($text);

        View::render('channels/index', $data);
    }

}
