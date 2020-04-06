<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Console;
use Helpers\Date;
use Helpers\Security;
use Helpers\FileUploader;
use Helpers\Url;
use Helpers\File;
use Helpers\Validator;
use Helpers\Slim;
use Helpers\SimpleImage;
use \DateTime;

class PricesModel extends Model{

    private static $params;

    public function __construct($params){
        parent::__construct();
        self::$params = $params;
    }

    public static function getLeaseTerms(){
        $array[] = ['id'=>1, 'name'=>'Month to Month', 'increase'=>15];
        $array[] = ['id'=>2, 'name'=>'3 Month', 'increase'=>11];
        $array[] = ['id'=>3, 'name'=>'6 Month', 'increase'=>7];
        $array[] = ['id'=>4, 'name'=>'9 Month', 'increase'=>4];
        $array[] = ['id'=>5, 'name'=>'12 Month', 'increase'=>0];
        return $array;
    }


}

?>