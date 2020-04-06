<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class HistoryModel extends Model{

    public function __construct(){
        parent::__construct();
    }
    public static $tableName = 'history';

    public static $fields = [

    ];

    public static function rules()
    {
        return [
            'adi_az' => ['required'],
        ];
    }

    public static function naming()
    {
        return [

        ];
    }


}

?>