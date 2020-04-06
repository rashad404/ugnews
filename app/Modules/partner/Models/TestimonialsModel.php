<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class TestimonialsModel extends Model{

    public static $tableName = 'testimonials';
    public static $safeMode = false;  // silinmemeli olan rowlarin mudafiesi
    public static $positionOrderBy = 'DESC'; // Siralama ucun order
    public static $positionEnable = true;     // Siralama aktiv, deaktiv
    public static $positionCondition = false;    // siralanma zamani nezere alinacaq fieldlerin olub olmamasi
    public static $fields = [

    ];


    public function __construct(){
        parent::__construct();
    }

    public static function rules()
    {
        return [
            'text_'.self::$def_language => ['required',],
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