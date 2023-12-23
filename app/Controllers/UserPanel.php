<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Helpers\Url;
use Helpers\Session;
use Models\CartModel;
use Models\UserModel;
use Helpers\Sms;
use Helpers\Csrf;
use Models\ProfileModel;
use Models\AddressModel;

/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class UserPanel extends Controller
{

    public $userId;
    public $userInfo;
    public $lng;

    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');

        $this->userId = intval(Session::get("user_session_id"));
        $model = new UserModel();
	    $this->userInfo = $model->getInfo($this->userId);
    }

    private function checkLogin($return=''){
        if($this->userId<1){
            Url::redirect("login/".$return);exit;
        }
    }

    public function logout(){
        Session::destroy('',true);
        Url::redirect("");
    }

    public function dashboard()
    {
        if($this->userId<1){
            Url::redirect("login");exit;
        }
	    $data['title'] = SITE_TITLE;
	    $data['keywords'] = SITE_TITLE;
	    $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        View::render('user_panel/'.__FUNCTION__, $data);
    }

    public function checkout(){
        $this->checkLogin('user+checkout');
	    $data['title'] = SITE_TITLE;
	    $data['keywords'] = SITE_TITLE;
	    $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        $model = new AddressModel();
        $data['address_info'] = $model->getInfo($this->userId);
        $data['cart_list'] = CartModel::getList();
        $data['cart_total'] = CartModel::getTotalPrice();

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $model = new ProfileModel();
            $modelArray = $model->update();
            $data['postData'] = $modelArray['postData'];
            if(empty($modelArray['errors'])){
                Session::setFlash('success',$this->lng->get('Your profile information has been changed successfully'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }else{
            $data['postData'] = [
                'card_name'=>'',
                'card_number'=>'',
                'card_exp'=>'',
                'card_cvv'=>'',
            ];
        }

        View::render('user_panel/'.__FUNCTION__, $data);
    }

    public function profile(){
        $this->checkLogin();
	    $data['title'] = SITE_TITLE;
	    $data['keywords'] = SITE_TITLE;
	    $data['description'] = SITE_TITLE;
        $data['countryList'] = Sms::getCountryList();
        $data['userId'] = $this->userId;
        $data['def_language'] = self::$def_language;

        $user_info = $this->userInfo;
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $model = new ProfileModel();
            $modelArray = $model->update();
            $data['postData'] = $modelArray['postData'];
            $data['postData']['email'] = $user_info['email'];
            if(empty($modelArray['errors'])){
                Session::setFlash('success',$this->lng->get('Your profile information has been changed successfully'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }else{
            $user_info['phone'] = substr($user_info['phone'], strlen($user_info['country_code']));
            if(strlen($user_info['birthday'])>5) {
                $birthday_exp = explode('-', $user_info['birthday']);
                $birth_year = $birthday_exp[0];
                $birth_month = $birthday_exp[1];
                $birth_day = $birthday_exp[2];
            }else{
                $birth_year = $birth_month = $birth_day ='';
            }
            $data['postData'] = [
                'first_name'=>$user_info['first_name'],
                'last_name'=>$user_info['last_name'],
                'phone'=>$user_info['phone'],
                'email'=>$user_info['email'],
                'gender'=>$user_info['gender'],
                'birth_year'=>$birth_year,
                'birth_month'=>$birth_month,
                'birth_day'=>$birth_day,
                'country_code'=>$user_info['country_code']
            ];
        }
        View::render('user_panel/'.__FUNCTION__, $data);
    }

    public function address(){
        $this->checkLogin();
	    $data['title'] = SITE_TITLE;
	    $data['keywords'] = SITE_TITLE;
	    $data['description'] = SITE_TITLE;
        $data['countryList'] = Sms::getCountryList();
        $data['def_language'] = self::$def_language;

        $model = new AddressModel();
        $address_info = $model->getInfo($this->userId);
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model->update();
            $data['postData'] = $modelArray['postData'];
            if(empty($modelArray['errors'])){
                Session::setFlash('success',$this->lng->get('Your Address information has been changed successfully'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }else{
            $data['postData'] = $address_info;

        }
        View::render('user_panel/'.__FUNCTION__, $data);
    }

}
