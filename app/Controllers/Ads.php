<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Helpers\Session;
use Helpers\Url;
use Models\AdsModel;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Ads extends Controller
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
        new AdsModel();
    }


    public function click($id)
    {

        $data['def_language'] = self::$def_language;

        $data['userId'] = self::$userId;
        $data['item'] = AdsModel::getItem(false);
        $update = AdsModel::updateClick($id);
        if($update){
            Url::redirect($data['item']['link'], true);
        }
    }



}
