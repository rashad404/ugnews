<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class ProductsModel extends Model{

    public function __construct(){
        parent::__construct();
    }
    public static $tableName = 'products';
    public static $safeMode = false;  // silinmemeli olan rowlarin mudafiesi
    public static $positionOrderBy = 'DESC'; // Siralama ucun order
    public static $positionEnable = true;     // Siralama aktiv, deaktiv
    public static $positionCondition = false;    // siralanma zamani nezere alinacaq fieldlerin olub olmamasi
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
        $photos = Database::get()->select("SELECT * FROM `products` WHERE `table_name`=:table and `row_id`=:row_id ORDER BY `position` DESC",
            [
                ":table" => $table,
                ":row_id" => $row_id
            ]);
        return $photos ;
    }


}

?>