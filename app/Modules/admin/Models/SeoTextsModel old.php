<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class old extends Model{


    public static $tableName = 'seo_texts';

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
            'text' => ['required']
        ];
    }

    public static function naming()
    {
        return [
            'text' => "Yazı"
        ];
    }
    public function __construct(){
        parent::__construct();
    }

    public static function getTexts()
    {
        $data = [0 => " - "];
        $rows = Database::get()->select('SELECT `id`,`text` FROM '.self::$tableName);
        foreach($rows as $row){
            $data[$row["id"]] = $row["text"];
        }
        return $data;
    }

}

?>