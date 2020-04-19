<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Console;
use Helpers\Url;
use Helpers\XLSXReader;

class SeoModel extends Model{

    public static $add_text = ' | UG.news';
    public static $add_prefix = 'UG.news | User Generated News ';
    public $lng;

    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }

    public static function general(){
        $array['title'] = self::$add_prefix.' ';
        $array['keywords'] = self::$add_prefix.' ';
        $array['description'] = self::$add_prefix.' ';
        return $array;
    }
    public static function index(){
        $array['title'] = self::$add_prefix.' ';
        $array['keywords'] = self::$add_prefix.' ';
        $array['description'] = self::$add_prefix.' ';
        return $array;
    }



















}
