<?php
namespace Controllers;

use Core\Controller;
use Core\Language;
use Helpers\Session;
use Models\AjaxModel;

/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */

class Ajax extends Controller
{

    public static $lng;
    public static $userId;
    public static $userInfo;
    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        self::$lng = new Language();
        self::$lng->load('app');
        self::$userId = intval(Session::get("user_session_id"));
        new AjaxModel();
    }


    public function subscribe($id){
        echo AjaxModel::subscribe($id);
    }
    public function un_subscribe($id){
        echo AjaxModel::unSubscribe($id);

    }

    public function like($id){
        echo AjaxModel::like($id);
    }
    public function remove_like($id){
        echo AjaxModel::removeLike($id);
    }

    public function dislike($id){
        echo AjaxModel::dislike($id);
    }

    public function remove_dislike($id){
        echo AjaxModel::removeDislike($id);
    }


//    public function countyListByState($id){
//        echo AjaxModel::countyListByState($id);
//    }
//
//    public function cityListByCounty($id){
//        echo AjaxModel::cityListByCounty($id);
//    }
//
//    public function locationSearchList($text){
//        echo AjaxModel::locationSearchList($text);
//    }


}
