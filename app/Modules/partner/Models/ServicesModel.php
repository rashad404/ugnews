<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class ServicesModel extends Model{

    public function __construct(){
        parent::__construct();
    }
    public static $tableName = 'services';

    public static $fields = [

    ];

    public static function rules()
    {
        return [
            'adi_az' => ['required',],
        ];
    }

    public static function naming()
    {
        return [

        ];
    }


    public static function getAllServices(){
        return Database::get()->select("SELECT * FROM ".self::$tableName." ");
    }


}

?>