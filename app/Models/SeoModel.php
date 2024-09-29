<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Console;
use Helpers\Url;
use Helpers\XLSXReader;

class SeoModel extends Model{

    public static $add_text = ' | ' . SITE_TITLE;
    public static $add_prefix = SITE_TITLE . ' | ';
    public $lng;

    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }

    public static function general(){
        $array['title'] = self::$add_prefix.' Xəbər Sosial Şəbəkəsi';
        $array['keywords'] = self::$add_prefix.' xəbərlər, xeberler, en son xeber, bugun xeber, son deqiqe xeberleri, namaz, valyuta';
        $array['description'] = SITE_TITLE . ' Xəbər Sosial Şəbəkəsidir. Ən son xəbərlər fərqli formatda';
        $array['meta_img'] = 'logo/logo-fb.png';
        return $array;
    }
    public static function index(){
        $array['title'] = self::$add_prefix.' Xəbər Sosial Şəbəkəsi';
        $array['keywords'] = self::$add_prefix.' xəbərlər, xeberler, en son xeber, bugun xeber, son deqiqe xeberleri, namaz, valyuta';
        $array['description'] = SITE_TITLE . ' Xəbər Sosial Şəbəkəsidir. Ən son xəbərlər fərqli formatda';
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


    public static function coronavirus(){
        $array['title'] = self::$add_prefix.' Koronavirus statistikası, koronavirus xəbərləri';
        $array['keywords'] = self::$add_prefix.' en son koronavirus statistikaları, koronavirus xeberleri, son koronavirus yenilikleri, koronavirus baki, koronavirus azerbaycan';
        $array['description'] = self::$add_prefix.'Ən son koronavirus statistikaları, canlı statistika';
        $array['meta_img'] = 'logo/logo-fb.png';
        return $array;
    }
    public static function namaz(){
        $array['title'] = self::$add_prefix.' Namaz vaxtı, Bakı Namaz vaxtı';
        $array['keywords'] = self::$add_prefix.' namaz vaxti, namaz vaxtlari, bugun namaz, subh, zohr,esr,şam,xuften namazi vaxti, namaz teqvimi';
        $array['description'] = self::$add_prefix.'Namaz vaxtları, Aylıq namaz təqvimi';
        $array['meta_img'] = 'logo/logo-fb.png';
        return $array;
    }
    public static function city($name=''){
        $array['title'] = $name.' xəbərləri, '.$name.' son xəbərlər';
        $array['keywords'] = $name.' xəbərləri, '.$name.' son xəbərlər';
        $array['description'] = $name.' xəbərləri, '.$name.' son xəbərlər';
        $array['meta_img'] = 'logo/logo-fb.png';
        return $array;
    }

    public static function currencies() {
        $array['title'] = ' Valyuta məzənnələri, Dollar məzənnəsi, Avro məzənnəsi';
        $array['keywords'] = ' valyuta məzənnələri, dolların məzənnəsi, avronun məzənnəsi, xarici valyutalar, manat qarşısında valyutalar, bugünkü valyuta məzənnələri';
        $array['description'] = ' Valyuta məzənnələri, bugünkü valyuta dəyişmələri, Azərbaycan manatına qarşı xarici valyutaların məzənnələri.';
        $array['meta_img'] = 'logo/logo-fb.png';
        return $array;
    }
    


















}
