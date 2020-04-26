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

    public static function getMost(){
        $array = self::$db->selectOne("SELECT `total_cases` FROM `".self::$tableNameCorona."` WHERE `id`=2");
        return $array['total_cases'];
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

        $megrib_time = strtotime($namaz_time['date'].' '.$namaz_time['megrib']);
        $imsak_time = strtotime($namaz_time['date'].' '.$namaz_time['imsak']);

        if(time()<=$imsak_time){
            return 'İmsak: '.$namaz_time['imsak'];
        }elseif(time()>$imsak_time && time()<=$megrib_time){
            return 'Məğrib : '.$namaz_time['megrib'];
        }else{
            $namaz_time_tomorrow = self::getNamazTime(date('Y-m-d', strtotime(' +1 day')));
            return 'İmsak: '.$namaz_time_tomorrow['imsak'];
        }
    }


    public static function getNamazText(){
        $namaz_time = self::getNamazTime();

        $subh = strtotime($namaz_time['date'].' '.$namaz_time['subh']);
        $zohr = strtotime($namaz_time['date'].' '.$namaz_time['zohr']);
        $esr = strtotime($namaz_time['date'].' '.$namaz_time['esr']);
        $megrib = strtotime($namaz_time['date'].' '.$namaz_time['megrib']);
        $isha = strtotime($namaz_time['date'].' '.$namaz_time['isha']);


        if(time()<=$subh){
            return 'Sübh: '.$namaz_time['subh'];
        }elseif(time()>$subh && time()<=$zohr){
            return 'Zöhr : '.$namaz_time['zohr'];
        }elseif(time()>$zohr && time()<=$esr){
            return 'Əsr : '.$namaz_time['esr'];
        }elseif(time()>$esr && time()<=$megrib){
            return 'Məğrib : '.$namaz_time['megrib'];
        }elseif(time()>$megrib && time()<=$isha){
            return 'İşa : '.$namaz_time['isha'];
        }else{
            $namaz_time_tomorrow = self::getNamazTime(date('Y-m-d', strtotime(' +1 day')));
            return 'Sübh: '.$namaz_time_tomorrow['subh'];
        }
    }

}
