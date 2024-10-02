<?php
namespace Modules\partner\Controllers;
use Core\Controller;
use Modules\partner\Traits\BaseControllerTrait;
use Helpers\Security;
use Helpers\Session;
use Helpers\Url;
use Helpers\Database;

class MyController extends Controller
{
    use BaseControllerTrait;

    public function __construct()
    {
        parent::__construct();
        $this->checkAuth();
    }

    protected function checkAuth()
    {
        $getSessionId = intval(Session::get('user_session_id'));
        $getSessionPass = Security::safe(Session::get('user_session_pass'));
        $getAuthInfo = Database::get()->selectOne("SELECT * FROM `users` WHERE id=:id", ["id" => $getSessionId]);
        if ($getSessionPass != Security::session_password($getAuthInfo["password_hash"])) {
            return Url::redirect("login");
        } else {
            Session::set("partner_session_role", intval($getAuthInfo["role"]));
        }
    }

    protected function accessControl($tableName, $methods = array())
    {
        $current_method = Url::getMethod();
        if (in_array($current_method, $methods)) {
            $role = $this->adminRole();
            $get_role_cat = Database::get()->selectOne("Select * FROM `partner_roles` Where `table_name` = :table_name Limit 1", [':table_name' => $tableName]);
            if ($role == 0 || ($role == 1 && ($get_role_cat['super_admin'] == 0)) || ($role == 2 && ($get_role_cat['admin'] == 0)) || ($role == 3 && ($get_role_cat['editor'] == 0))) {
                return true; // access denied
            }
        }
        return false;
    }

    protected function adminRole()
    {
        return Session::get('partner_session_role');
    }
}