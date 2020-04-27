<?php
namespace Models;
use Core\Model;
use Core\Language;

class InfoModel extends Model{

    private static $tableNameCorona = 'coronavirus';
    private static $tableNameNamaz = 'namaz';
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }



    public static function coronavirusList(){
        $array = self::$db->select("SELECT * FROM `".self::$tableNameCorona."` WHERE `id`!=2 ORDER BY `total_cases` DESC");
        return $array;
    }

    public static function coronavirusSelected(){
        $array = self::$db->select("SELECT * FROM `".self::$tableNameCorona."` WHERE `id`=2 OR `id`=70");
        return $array;
    }

    public static function getMostCorona(){
        $array = self::$db->selectOne("SELECT `total_cases` FROM `".self::$tableNameCorona."` WHERE `id`=2");
        return $array['total_cases'];
    }



    //Namaz

    public static function namazList(){
        $begin_date = date("Y-m")."-01";
        $end_date = date("Y-m-t");
        $array = self::$db->select("SELECT * FROM `".self::$tableNameNamaz."` WHERE `date`>='".$begin_date."' AND  `date`<='".$end_date."'");
        return $array;
    }

    public static function getNamazTime($date=''){
        if(empty($date)){
            $date = date('Y-m-d');
        }
        $array = self::$db->selectOne("SELECT * FROM `".self::$tableNameNamaz."` WHERE `date`='".$date."'");
        return $array;
    }


    public static function getRamazanText(){
        $namaz_time = self::getNamazTime();

        $megrib_time = strtotime($namaz_time['date'].' '.$namaz_time['maghrib']);
        $imsak_time = strtotime($namaz_time['date'].' '.$namaz_time['imsak']);

        if(time()<=$imsak_time){
            return 'İmsak '.$namaz_time['imsak'];
        }elseif(time()>$imsak_time && time()<=$megrib_time){
            return 'Məğrib '.$namaz_time['maghrib'];
        }else{
            $namaz_time_tomorrow = self::getNamazTime(date('Y-m-d', strtotime(' +1 day')));
            return 'İmsak '.$namaz_time_tomorrow['imsak'];
        }
    }


    public static function getNamazText(){
        $namaz_time = self::getNamazTime();

        $fajr = strtotime($namaz_time['date'].' '.$namaz_time['fajr']);
        $dhuhr = strtotime($namaz_time['date'].' '.$namaz_time['dhuhr']);
        $asr = strtotime($namaz_time['date'].' '.$namaz_time['asr']);
        $maghrib = strtotime($namaz_time['date'].' '.$namaz_time['maghrib']);
        $isha = strtotime($namaz_time['date'].' '.$namaz_time['isha']);


        if(time()<=$fajr){
            return 'Sübh '.$namaz_time['fajr'];
        }elseif(time()>$fajr && time()<=$zohr){
            return 'Zöhr '.$namaz_time['dhuhr'];
        }elseif(time()>$dhuhr && time()<=$asr){
            return 'Əsr '.$namaz_time['asr'];
        }elseif(time()>$asr && time()<=$maghrib){
            return 'Məğrib '.$namaz_time['maghrib'];
        }elseif(time()>$maghrib && time()<=$isha){
            return 'İşa '.$namaz_time['isha'];
        }else{
            $namaz_time_tomorrow = self::getNamazTime(date('Y-m-d', strtotime(' +1 day')));
            return 'Sübh: '.$namaz_time_tomorrow['fajr'];
        }
    }

}
