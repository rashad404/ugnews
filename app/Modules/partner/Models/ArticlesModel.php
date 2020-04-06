<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class ArticlesModel extends Model{

    public function __construct(){
        parent::__construct();
    }
    public static $tableName = 'articles';

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


    public static function getAllArticles(){
        return Database::get()->select("SELECT * FROM ".self::$tableName." ");
    }


}

?>