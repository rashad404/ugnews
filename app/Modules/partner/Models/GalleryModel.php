<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class GalleryModel extends Model{

    public function __construct(){
        parent::__construct();
    }
    public static $tableName = 'gallery';

    public static $fields = [

    ];

    public static function rules()
    {
        return [

        ];
    }

    public static function naming()
    {
        return [

        ];
    }

    public static function getPhotos($table,$row_id)
    {
        $photos = [];
        $photos = Database::get()->select("SELECT * FROM `photos` WHERE `table_name`=:table and `row_id`=:row_id ORDER BY `position` DESC",
            [
                ":table" => $table,
                ":row_id" => $row_id
            ]);
        return $photos ;
    }


}

?>