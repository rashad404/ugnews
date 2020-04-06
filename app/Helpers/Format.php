<?php
namespace Helpers;
use Core\Model;
use function GuzzleHttp\Psr7\str;
use Helpers\Curl;

class Format{
    public static function urlText($text){
        $text = preg_replace('/%/','',$text);
        $text = preg_replace('/\//','',$text);
        $text = mb_strtolower($text);
        $text = urlencode($text);
        return $text;
    }
    public static function shortText($text,$length=100){
        if(strlen($text)>$length)$text = substr($text,0,$length);
        return $text;
    }
    public static function listTitle($text,$length=100){
        $text = ucfirst(mb_strtolower($text));
        if(strlen($text)>$length)$text = substr($text,0,$length).'...';
        return $text;
    }

    public static function listText($text, $length=25){
        $old_text = $text;

        if(strlen($old_text) > strlen($text)){
            $text = $text.'...';
        }
        $text = mb_substr(strip_tags(html_entity_decode($text)),0,$length);
        return $text;
    }

    public static function getText($text, $length=5000){
        $old_text = $text;
        $text = html_entity_decode($text);
        $text = stripslashes($text);
        $text = stripslashes($text);
        $text = stripslashes($text);
        $text = preg_replace('/\\n/','<br/>', $text);
        $text = mb_substr($text,0,$length);
        if(strlen($old_text) > $length){
            $text = $text.'...';
        }
        return $text;
    }

    public static function phoneNumber($phone, $country_code=1){
        $phone = preg_replace('/-/','',$phone);
        $phone = preg_replace('/\(/','',$phone);
        $phone = preg_replace('/\)/','',$phone);
        $phone = preg_replace('/ /','',$phone);
        if(strlen($phone)==11){
            $country_code = substr($phone, 0,1);
            $phone = substr($phone, 1,10);
        }

        if($country_code==1 && strlen($phone)==10){
            $part1 = substr($phone, 0,3);
            $part2 = substr($phone, 3,3);
            $part3 = substr($phone, 6,4);
            return '('.$part1.') '.$part2.'-'.$part3;
        }else{
            return $phone;
        }
    }

    public static function full_digits($text){
        $text = floor($text);
        return $text;
    }
}