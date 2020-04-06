<?php
namespace Helpers;
use Core\Model;
use Helpers\Curl;

class Time{

    public static function todayStartUnixTime(){
        return strtotime("midnight", time());
    }
    public static function tomorrowStartUnixTime(){
        return strtotime("midnight", time())+86400;
    }

}