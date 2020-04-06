<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class ReviewsModel extends Model{

    public function __construct(){
        parent::__construct();
    }
    public static $tableName = 'reviews';

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


    public static function getAllReviews(){
        return Database::get()->select("SELECT * FROM ".self::$tableName." ");
    }


}

?>