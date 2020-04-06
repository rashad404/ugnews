<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Database;
use Models\LanguagesModel;

class AboutModel extends Model{

    public function __construct(){
        parent::__construct();
    }
    public static $tableName = 'about';

    public static $fields = [

    ];


}

?>