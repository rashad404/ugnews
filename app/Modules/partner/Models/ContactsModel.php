<?php

namespace Modules\partner\Models;

use Core\Model;

class ContactsModel extends Model{


    public static $tableName = 'contacts';

    public static $fields = [
        [
            "field_name" => "working_days",
            "field_type" => "VARCHAR (255)"

        ],[
            "field_name" => "weekend_days",
            "field_type" => "VARCHAR (255)"

        ],[
            "field_name" => "address",
            "field_type" => "VARCHAR (255)"

        ],[
            "field_name" => "diff",
            "field_type" => "TEXT"

        ]
    ];

    public static function rules()
    {
        return [
            'email' => ['required', 'email']
        ];
    }

    public static function naming()
    {
        return [
            'working_days_'.LANGUAGE_CODE => "İş günləri (".LANGUAGE_CODE.")",
            'weekend_days_'.LANGUAGE_CODE => "Qeyri iş günləri (".LANGUAGE_CODE.")",
            'address_'.LANGUAGE_CODE => "Ünvan  (".LANGUAGE_CODE.")",
            'diff_'.LANGUAGE_CODE => "Prezentasiya videosunun mətni (".LANGUAGE_CODE.")",
            'image' => 'Logo',
            'image2' => 'Logo 2',
            'video' => 'Video',

        ];
    }

    public function __construct(){
        parent::__construct();
    }
    
}

?>