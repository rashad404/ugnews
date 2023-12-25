<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Google_Client;
use Google_Service_Oauth2;
use Helpers\Format;
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
use Models\SettingsModel;
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
class Settings extends Controller
{

    public static $userId;
    public static $model;
    public static $lng;
    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        self::$lng = new Language();
        self::$lng->load('app');

        self::$userId = intval(Session::get("user_session_id"));
	    self::$model = new SettingsModel();

    }

    public function region($region){
        $model = self::$model;
        $model::setRegion($region);
        echo Cookie::get('set_region');
        Url::redirect('');
    }


}
