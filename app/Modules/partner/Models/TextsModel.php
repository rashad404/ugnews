<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class TextsModel extends Model{


    public static $tableName = 'texts';

    public static $positionOrderBy  = 'ASC'; // Siralama ucun order
    public static $positionCondition = true;    // siralanma zamani nezere alinacaq fieldlerin olub olmamasi
    public static $positionConditionField = []; // siralanma zamani nezere alinacaq fieldler
    public static $fields = [
        [
            "field_name" => "text",
            "field_type" => "TEXT"

        ],[
            "field_name" => "title",
            "field_type" => "VARCHAR (255)"

        ],[
            "field_name" => "subtitle",
            "field_type" => "VARCHAR (255)"

        ]
    ];

    // Rules
    
    public static function rules()
    {
        return [
            'text_'.LANGUAGE_CODE => ['required']
        ];
    }

    public static function naming()
    {
        return [
            'text_'.LANGUAGE_CODE => "Yazı (".LANGUAGE_CODE.")"
        ];
    }
    public function __construct(){
        parent::__construct();
    }

    public static function getTexts()
    {
        $data = [0 => " - "];
        $defaultLang = LanguagesModel::getDefaultLanguage();
        $rows = Database::get()->select('SELECT `id`,`text_'.$defaultLang.'` FROM '.self::$tableName);
        foreach($rows as $row){
            $data[$row["id"]] = $row["text_".$defaultLang];
        }
        return $data;
    }

}

?>