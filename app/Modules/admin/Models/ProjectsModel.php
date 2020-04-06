<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class ProjectsModel extends Model{

    public function __construct(){
        parent::__construct();
    }
    public static $tableName = 'projects';

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


    public static function getAllProjects(){
        return Database::get()->select("SELECT * FROM ".self::$tableName." ");
    }


}

?>