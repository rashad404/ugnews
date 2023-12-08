<?php

namespace Helpers;


class Security
{

    public function __construct()
    {

    }

    public static function safeTextNew($value)
    {

        $value = addslashes($value);
        $from = ['”','“',"’","‘",'&amp;lsquo;','"'];
        $to =   ['"','"',"'","'","'","'"];
        $value = str_replace($from, $to, $value);

        return $value;
    }
    public static function safeText($value)
    {

        $value = self::safe($value);
        $value = addslashes($value);
        $value = htmlspecialchars($value);
        return $value;
    }

    public static function safe($value,$strip=false)
    {
        $value=htmlentities( $value, ENT_QUOTES, 'utf-8' );
        if($strip) $value=strip_tags($value);
//        $from=['&Uuml;','&Ouml;','&Ccedil;','&uuml;','&ouml;','&ccedil;',"\0","\x1a", "\x00","\x0B","&#039;",'&quot;','”','“',"’","‘",'&amp;lsquo;'];
//        $to=['Ü','Ö','Ç','ü','ö','ç','\\0','\\Z',"\\0","","'",'"','"','"',"'","'","'"];
//        $value=str_replace($from, $to, $value);
        return $value;
    }

    public static function filterPhone($var,$country='az')
    {
        if($country=='az'){
            if(preg_match("/^[0-9]{12}$/", $var)){
                return $var;
            }else{
                return false;
            }
        }else{
            return filter_var($var,FILTER_SANITIZE_NUMBER_FLOAT);
        }

    }

    public static function filterEmail($var)
    {
        return filter_var($var,FILTER_VALIDATE_EMAIL);
    }

    public function SqlInjectFilter($str) {
        $str = str_replace(" ",'',$str);
        $str = str_replace("\n",'',$str);
        $str = str_replace("\t",'',$str);
        $str = str_replace("\r",'',$str);
        $str = str_replace("\0",'',$str);
        $str = str_replace("\x0B",'',$str);
        $str = str_replace("'",'',$str);
        $str = str_replace('"','',$str);
        $str = str_replace('\\','',$str);
        $str = str_replace('/','',$str);
        $str = str_ireplace (" and ","",$str);
        $str = str_ireplace ("execute ","",$str);
        $str = str_ireplace ("update ","",$str);
        $str = str_ireplace ("count ","",$str);
        $str = str_ireplace ("chr ","",$str);
        $str = str_ireplace ("mid ","",$str);
        $str = str_ireplace ("master ","",$str);
        $str = str_ireplace ("truncate ","",$str);
        $str = str_ireplace ("char ","",$str);
        $str = str_ireplace ("declare ","",$str);
        $str = str_replace ("select ","",$str);
        $str = str_ireplace ("create ","",$str);
        $str = str_ireplace ("delete ","",$str);
        $str = str_ireplace ("insert ","",$str);
        $str = str_ireplace ("union ","",$str);
        $str = str_replace ("\"","",$str);
        $str = str_replace ('"',"",$str);
        //$str = str_replace (" ","",$str);
        $str = str_replace ("$","",$str);
        $str = str_ireplace ("or ","",$str);
        //$str = str_replace ("=","",$str);
        $str = str_replace ("% 20 ","",$str);
        $str = addslashes($str);
        return $str;
    }


    public static function generatePassword($length = '')
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $max = strlen($str);
        $length = @round($length);
        if (empty($length)) {
            $length = rand(8, 12);
        }
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $str[rand(0, $max - 1)];
        }
        return $password;
    }

    public static function generateConfirmationCode($length = 4)
    {
        $str = '0123456789';
        $max = strlen($str);
        $length = @round($length);
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $str[rand(0, $max - 1)];
        }
        return $code;
    }
    public static function generateConfirmationCodeEmail($length = 20)
    {
        $str = '0123456789abcdefghijklmnopqrstuvwxyz';
        $max = strlen($str);
        $length = @round($length);
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $str[rand(0, $max - 1)];
        }
        return $code;
    }

    public static function generateHash($length = 20)
    {
        $str = '0123456789abcdefghijklmnopqrstuvwxyz';
        $max = strlen($str);
        $length = @round($length);
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $str[rand(0, $max - 1)];
        }
        return $code;
    }


    public static function session_password($password)
    {
        return md5(md5($password).date("d.m.Y"));
    }

    public static function password_hash($password)
    {
        return md5(md5($password).CMS_KEY);
    }

    public static function filterFileMimeTypes($file){
        $status = false;
        $valid_mime_types = array(
            "image/gif",
            "image/png",
            "image/jpeg",
            "image/pjpeg",
        );
        if(!empty($file)){
            if (in_array($file, $valid_mime_types)) {
                $status = true;
            }else{
                $status = false;
            }
        }else{
            $status = false;
        }
        return $status;
    }

    public static function filterUrl($url)
    {
        if(filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getIp(){

        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $user_ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $user_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $user_ip = $_SERVER["REMOTE_ADDR"];
        }
        if (strlen($user_ip) > 15) {
            $explode_1 = explode(",", $user_ip);
            $user_ip = $explode_1[0];
        }
        return $user_ip;
    }

    public static function getBrowser(){
        return $_SERVER['HTTP_USER_AGENT'];
    }
}
