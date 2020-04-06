<?php
namespace Helpers;
use \DateTime;

class Date{

    public static function getMonths(){
        $array = ['','January','February','March','April','May','June','July','August','September','October','November','December'];
        unset($array[0]);
        return $array;
    }
    public static function getMonths3Code(){
        $array = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        unset($array[0]);
        return $array;
    }
    public static function getMonthsInt(){
        $array = range(1,12);
        return $array;
    }
    public static function getYears($min=1950, $max=0){
        if($max==0)$max = date("Y");
        $array = range($min, $max);
        return $array;
    }
    public static function getDays(){
        $array = range(1,31);
        return $array;
    }
    public static function getHours(){
        $array = ['12:00 AM','12:30 AM','1:00 AM','1:30 AM','2:00 AM','2:30 AM','3:00 AM','3:30 AM','4:00 AM','4:30 AM','5:00 AM','5:30 AM','6:00 AM',
            '6:30 AM','7:00 AM','7:30 AM','8:00 AM','8:30 AM','9:00 AM','9:30 AM','10:00 AM','10:30 AM','11:00 AM','11:30 AM',
            '12:00 PM','12:30 PM','1:00 PM','1:30 PM','2:00 PM','2:30 PM','3:00 PM','3:30 PM','4:00 PM','4:30 PM','5:00 PM','5:30 PM','6:00 PM',
            '6:30 PM','7:00 PM','7:30 PM','8:00 PM','8:30 PM','9:00 PM','9:30 PM','10:00 PM','10:30 PM','11:00 PM','11:30 PM'
        ];
        return $array;
    }


    public static function toMysqlFormat($date)
    {
        $str = strtotime($date);
        $date = date('Y-m-d', $str);
        return $date;
    }

    public static function toInputFormat($date)
    {
        $str = strtotime($date);
        $date = date('m/d/Y', $str);
        return $date;
    }

    public static function validateDateOld($date, $format = 'Y-m-d H:i A')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    public static function validateDate($date)
    {
        //2019-01-05T01:00
        $regEx = '/(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})/';
        return preg_match($regEx,$date);
    }

    public static function yearToAge($year){
        $age = date('Y')- $year;
        return $age;
    }

    public static function dateToAge($text){
        if(!empty($text) && preg_match('/-/', $text)) {
            $birthDate = explode("-", $text);
            //get age from date or birthdate
            $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
                ? ((date("Y") - $birthDate[0]) - 1)
                : (date("Y") - $birthDate[0]));
        }else{
            $age = 'N/A';
        }
        return $age;
    }
    public static function dateToDayMonth($text){
        return date('M d', strtotime($text));
    }
    public static function timeToDate($time){
        if(date('d', $time) == date('d')){
            $date = date('M d g:i A',$time);
        }else{
            $date = date('g:i A',$time);
        }
        return $date;
    }

    public static function timeElapsedString($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;


        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'min',
//            's' => 'sec',
        );
        foreach ($string as $key => $value) {
            if ($diff->$key) {
                $array[] = $diff->$key . ' ' . $value . ($diff->$key > 1 ? 's' : '');
            }
        }
        if(!empty($array)) {
            $array = array_slice($array, 0, 1);
            $text = implode(', ', $array) . ' ago';
        }else{
            $text = 'Just now';
        }
        return $text;
    }

    public static function getOnline($time){
        $date_time = date("Y-m-d H:i:s", $time);
        if($time>time()-ONLINE_TIME){
            $online_text = 'Just now';
        }else{
            $online_text = self::timeElapsedString($date_time);
        }
        return $online_text;
    }

}