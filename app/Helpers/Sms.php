<?php
namespace Helpers;

class Sms{

    public static function getOperatorPrefixes(){
        $array = ['99450','99451','99455','99470','99477'];
        return $array;
    }
    public static function getCountryList(){
        $array = [1=>'United States'];
        $array = [994=>'Azerbaijan'];
        return $array;
    }
    public static function get3DigitPrefix($prefix){
        $prefix3Digit = '0'.substr($prefix,3,2);
        return $prefix3Digit;
    }
}