<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class LicenseesModel extends Model{

    public function __construct(){
        parent::__construct();
    }
    public static $tableName = 'licensees';

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


    public static function getAllLicensees(){
        return Database::get()->select("SELECT * FROM ".self::$tableName." ");
    }


}

?>