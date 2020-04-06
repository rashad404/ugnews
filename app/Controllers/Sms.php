<?php
namespace Controllers;

use Core\Controller;
use Core\Language;
use Helpers\Session;
use Models\SmsModel;
use Twilio\Rest\Client;

/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */

class Sms extends Controller
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
        new SmsModel();
    }


    public static function send($to, $text){
        SmsModel::send($to, $text);
    }

    public static function receive($to){
        SmsModel::receive($to);
        header('Content-Type: text/xml');
        echo '<Response></Response>';
        exit;
    }



}
