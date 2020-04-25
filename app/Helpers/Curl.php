<?php

namespace Helpers;


class Curl
{
    public static $timeout = 1000;
    public static $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

    public static function postRequest($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT,self::$timeout);
        $server_output = curl_exec ($ch);
        curl_close ($ch);

        return $server_output;
    }

    public static function getRequest($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT,self::$timeout);
        $server_output = curl_exec ($ch);
        curl_close($ch);

        return $server_output;
    }

    public static function saveFile($url, $local_url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);

        $fp = fopen($local_url, 'wb');
        curl_setopt($curl, CURLOPT_FILE, $fp);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_exec($curl);
        curl_close($curl);
        fclose($fp);
    }
    public static function saveFileFgc($url, $local_url)
    {
        $image = file_get_contents($url);
        $file = fopen($local_url,"w");
        fwrite($file, $image);
        fclose($file);
    }
}
