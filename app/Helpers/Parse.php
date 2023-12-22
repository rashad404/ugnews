<?php
namespace Helpers;
use Helpers\Curl;

class Parse{

    public static function get($link){
        $html = Curl::getRequest($link);
        return $html;
    }

    public static function sign_in($link){
        $html = self::get($link);
//        $exp = explode('<form',$html);
//        $data = '<form'.$exp[1];
        $html = preg_replace('/\/connect\/users\/sign_in/','',$html);

        return $html;
    }
}