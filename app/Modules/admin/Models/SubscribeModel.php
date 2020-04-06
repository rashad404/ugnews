<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class SubscribeModel extends Model{

    public function __construct(){
        parent::__construct();
    }
    public static $tableName = 'subscribe';

    public static $fields = [

    ];

    public static function rules()
    {
        return [
            'mail' => ['required',],
        ];
    }

    public static function naming()
    {
        return [

        ];
    }


    public static function getAllNews(){
        return Database::get()->select("SELECT * FROM ".self::$tableName." ");
    }


}

?>