<?php
namespace Models;
use Core\Model;
use Helpers\Cookie;

class SettingsModel extends Model{

    private $defLang;
    public function __construct(){
        parent::__construct();
    }

    public static function setRegion($region){
        Cookie::set('set_region', $region);
    }


}
