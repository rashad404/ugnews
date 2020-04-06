<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class BranchesModel extends Model{

    public function __construct(){
        parent::__construct();
    }
    public static $tableName = 'branches';

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


    public static function getAllBranches(){
        return Database::get()->select("SELECT * FROM ".self::$tableName." ");
    }


}

?>