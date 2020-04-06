<?php
namespace Modules\user\Controllers;

use Core\Controller;
use Helpers\Data;
use Helpers\Database;
use Helpers\Security;
use Helpers\Session;
use Helpers\Url;
use Models\LanguagesModel;

class MyController extends Controller
{



    public function __construct()
    {
        parent::__construct();
        $this->checkAuth();

    }

    public function checkAuth(){
        $getSessionId = intval(Session::get('user_session_id'));
        $getSessionPass = Security::safe(Session::get('user_session_pass'));
         $getAuthInfo = Database::get()->selectOne("SELECT * FROM `users` WHERE id=:id",["id" => $getSessionId]);
        if($getSessionPass != Security::session_password($getAuthInfo["password_hash"])){
            Url::redirect("login");
            exit;
        }
        if($getAuthInfo['tenant_portal']!=1){
            Url::redirect("login");
            exit;
        }
    }

    public function adminRole()
    {
        return Session::get('user_session_role');
    }

    public function accessControl($methods = array(), $tableName)
    {
        $current_method = Url::getMethod();
        if(in_array($current_method, $methods)) {
            $role = $this->adminRole();
            $get_role_cat = Database::get()->selectOne("Select * FROM `admin_roles` Where `table_name` = :table_name Limit 1", [':table_name' => $tableName]);
            if($role == 0 OR ($role == 1 && ($get_role_cat['super_admin'] == 0)) OR ($role == 2 && ($get_role_cat['admin'] == 0)) OR ($role == 3 && ($get_role_cat['editor'] == 0))) {
                return true; // access denied
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function defaultLanguage()
    {
        if(Session::get("defaultLanguage")==null){
            $defaultLanguage  = LanguagesModel::getDefaultLanguage();
            Session::set("defaultLanguage",$defaultLanguage);
            return $defaultLanguage;
        }else{
            $defaultLanguage = Session::get("defaultLanguage");
            return $defaultLanguage;
        }
    }


}