<?php
namespace Controllers;

use Core\Controller;
use Core\Language;
use Helpers\Session;
use Models\ChargesModel;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */

class Charges extends Controller
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
        new ChargesModel();
    }



    public function addCharges(){
        echo ChargesModel::addCharges();
    }


}
