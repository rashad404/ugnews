<?php
namespace Modules\Admin\Controllers;

use Core\Controller;
use Core\View;
use Helpers\Url;
use Helpers\Security;
use Helpers\Csrf;
use Helpers\Database;
use Helpers\Session;

class Auth extends Controller
{
    public function __construct()
    { 
        parent::__construct();
    }

    public function index()
    {
         if($_POST && Csrf::isTokenValid() ){
            $login=Security::safe($_POST["login"]);
            $password_hash=Security::password_hash(Security::safe($_POST["password"]));
            $auth=Database::get()->selectOne("SELECT `id`,`role` from `admins` where login=:login and password=:password", [':login'=>$login,':password'=>$password_hash] );
            if(intval($auth["id"])>0){
                Session::set("auth_session_id",intval($auth["id"]));
                Session::set("auth_session_pass",Security::session_password($password_hash) );
                Session::set("auth_session_role",intval($auth["role"]) );

                 return Url::redirect("admin/main");
            }
            else Session::setFlash("error","Login və ya şifrə yanlışdır.");
        }
        View::renderModule('auth/index','',"admin","admin_login");
    }
}








