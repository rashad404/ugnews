<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Console;
use Helpers\Url;
use Helpers\XLSXReader;

class SeoModel extends Model{

    public static $add_text = ' | UG.news';
    public static $add_prefix = 'UG.news | ';
    public $lng;

    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }

    public static function general(){
        $array['title'] = self::$add_prefix.' Xəbər Sosial Şəbəkəsi';
        $array['keywords'] = self::$add_prefix.' xəbərlər, xeberler, en son xeber, bugun xeber, son deqiqe xeberleri, namaz, valyuta';
        $array['description'] = 'UG.news Xəbər Sosial Şəbəkəsidir. Ən son xəbərlər fərqli formatda';
        $array['meta_img'] = 'logo/logo-fb.png';
        return $array;
    }
    public static function index(){
        $array['title'] = self::$add_prefix.' Xəbər Sosial Şəbəkəsi';
        $array['keywords'] = self::$add_prefix.' xəbərlər, xeberler, en son xeber, bugun xeber, son deqiqe xeberleri, namaz, valyuta';
        $array['description'] = 'UG.news Xəbər Sosial Şəbəkəsidir. Ən son xəbərlər fərqli formatda';
        $array['meta_img'] = 'logo/logo-fb.png';
        return $array;
    }
    public static function create_channel(){
        $array['title'] = self::$add_prefix.'Xəbər Kanalı yarat, xəbər saytı, internetden pul qazan';
        $array['keywords'] = self::$add_prefix.'Xəbər Kanalı yarat, xəbər saytı, internetden pul qazan';
        $array['description'] = self::$add_prefix.'Xəbər Kanalı yarat, xəbər saytı, internetden pul qazan';
        $array['meta_img'] = 'logo/logo-fb.png';
        return $array;
    }



















}
