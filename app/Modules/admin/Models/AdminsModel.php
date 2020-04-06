<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Database;

class AdminsModel extends Model{

    public function __construct(){
        parent::__construct();
    }
    public static $tableName = 'admins';

    public static $levels = [1 => "Super admin", 2 => "Admin", 3 => "Editor"];

    public static $fields = [

    ];

    public static function rules()
    {
        return [
            'login' => [
                'required',
                'between(3,25)',
                'check_login'
            ],
            'email' => [
                'required',
                'max_length(150)',
                'email'
            ],
            'role' => ['required']
        ];
    }

    public static function naming()
    {
        return [
            'login' => "Login",
            'password' => "Şifrə",
            'role' => 'Admin level'
        ];
    }

    public static function getPhotos($table,$row_id)
    {
        $photos = [];
        $photos = Database::get()->select("SELECT * FROM `photos` WHERE `table_name`=:table and `row_id`=:row_id ORDER BY `position` DESC",
            [
                ":table" => $table,
                ":row_id" => $row_id
            ]);
        return $photos ;
    }


}

?>