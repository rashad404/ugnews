<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class SliderModel extends Model{


    public static $safeMode = false;  // silinmemeli olan rowlarin mudafieso
    public static $safeModeFields = ["safe_mode"]; // siline bilmeyen rowlarin nezere alinmali fieldi

    public function __construct(){
        parent::__construct();
    }
    public static $tableName = 'slider';

    public static $fields = [

    ];

    public static function rules()
    {
        return [
            'title_'.self::$def_language => ['required',],
        ];
    }

    public static function naming()
    {
        return [

        ];
    }


    public static function getAll(){
        return Database::get()->select("SELECT * FROM ".self::$tableName." ");
    }


}

?>