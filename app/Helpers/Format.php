<?php
namespace Helpers;
use Core\Model;
use function GuzzleHttp\Psr7\str;
use Helpers\Curl;

class Format{

    public static function urlText($text){
        $text = mb_strtolower($text);
        $text = self::removeAccents($text);
        $text = self::slugify($text);

        return $text;
    }

    public static function urlTextChannel($text){
        $text = preg_replace('/%/','',$text);
        $text = preg_replace('/\//','',$text);
        $text = preg_replace('/ /','-',$text);
        $text = mb_strtolower($text);
        $text = self::removeAccents($text);
        $text = urlencode($text);

        return $text;
    }
    public static function urlTextTag($text){
        $text = preg_replace('/%/','',$text);
        $text = preg_replace('/\//','',$text);
        $text = mb_strtolower($text);
        $text = urlencode($text);
        return $text;
    }


    public static function removeAccents($str){
        $a = array('Ə', 'ə', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
        $b = array('E', 'e', 'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
        return str_replace($a, $b, $str);
    }

    public static function slugify($text){
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');

        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public static function deUrlText($text){
        $text = urldecode($text);
        $text = ucwords($text);
        return $text;
    }
    public static function shortText($text,$length=100){
        if(strlen($text)>$length){
            return mb_substr($text,0,$length).'.';
        }else {
            return $text;
        }
    }
    public static function listTitle($text,$length=100){
//        $text = ucfirst(mb_strtolower($text));
        if(strlen($text)>$length)$text = mb_substr($text,0,$length).'...';
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