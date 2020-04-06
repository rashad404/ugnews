<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class ToursModel extends Model{

    public static $tableName = 'tours';
    public static $tableNameFeatures = 'tour_features';
    public static $tableNameCountries = 'tour_countries';
    public static $tableNameCategories = 'tour_categories';
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
            'title_'.self::$def_language => ['required',],
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

    public static function getFeatures(){
        return Database::get()->select("SELECT `id`,`title_".self::$def_language."` FROM ".self::$tableNameFeatures." WHERE `status`=1 ORDER BY `position` DESC ");
    }

    public static function getCountries(){
        return Database::get()->select("SELECT `id`,`title_".self::$def_language."` FROM ".self::$tableNameCountries." WHERE `status`=1 ORDER BY `position` DESC ");
    }

    public static function getCategories(){
        return Database::get()->select("SELECT `id`,`title_".self::$def_language."` FROM ".self::$tableNameCategories." WHERE `status`=1 ORDER BY `position` DESC ");
    }


}

?>