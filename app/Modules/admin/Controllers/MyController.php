<?php
namespace Modules\admin\Controllers;

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
        $getSessionId = intval(Session::get('auth_session_id'));
        $getSessionPass = Security::safe(Session::get('auth_session_pass'));
         $getAuthInfo = Database::get()->selectOne("SELECT * FROM `admins` WHERE id=:id",["id" => $getSessionId]);
        if($getSessionPass != Security::session_password($getAuthInfo["password"])){
            return Url::redirect("admin/auth");
        } else {
            Session::set("auth_session_role",intval($getAuthInfo["role"]));
        }

    }

    public function adminRole()
    {
        return Session::get('auth_session_role');
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